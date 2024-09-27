<?php
namespace App\Services;

use Illuminate\Support\Str;
use App\Jobs\InscriptionMailJob;
use App\Services\UserServiceMysql;
use App\Repositories\ApprenantRepository;
use App\Repositories\PromotionRepository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ReferentielRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ApprenantsImport;
use App\Exports\FailedApprenantsExport;

class ApprenantService
{
    protected $userService;
    protected $promotionRepository;
    protected $referentielRepository;

    public function __construct(UserService $userService, generateQRCode $generateQRcode,ApprenantRepository $apprenantRepository, 
     UserServiceMysql $serviceMysql, ReferentielRepository $referentielRepository)
    {
        
        $this->apprenantRepository = $apprenantRepository;
        $this->userService = $userService;
        $this->serviceMysql = $serviceMysql;
        $this->referentielRepository = $referentielRepository;
        $this-generateQRCode(); 

    }

    public function createApprenant($data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'adresse' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'referentiel_id' => 'required|exists:referentiels,id',
            'tuteur_nom' => 'required|string|max:255',
            'tuteur_prenom' => 'required|string|max:255',
            'tuteur_contact' => 'required|string|max:255',
            'cni' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'sexe' => 'nullable|srting|max:10',
            'diplome' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'extrait_naissance' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'visite_medicale' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'casier_judiciaire' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Créer un compte utilisateur
        $userData = [
            'nom' => $data['nom'],
            'adresse' => $data['adresse'],
            'telephone' => $data['telephone'],
            'prenom' => $data['prenom'],
            'email' => $data['contact'],
            'password' => '', 
            'statu'=>$data['statut'], 
            'role' => 'Apprenant'
        ];
        $userData['password'] = Str::random(8);
       
        // enregistrer sur Mysql
        $mysqlUser = $this->serviceMysql->store($userData);
            
            
         // enregistrer dans Firebase
        $userId = $this->userService->store($userData);

        $matricule = Str::random(10);

        $qrCode = $this->generateQRCode($matricule);



        // Créer l'apprenant
        $apprenantData = [
            'referentiel_id' => $data['referentiel_id'],
            'matricule' => $matricule,
            'qr_code' => $qrCode,
            'user_id' => $userId,
            'tuteur_nom' => $data['tuteur_nom'],
            'tuteur_prenom' => $data['tuteur_prenom'],
            'tuteur_contact' => $data['tuteur_contact'],
            'cni' => $data['cni'] ?? null,
            'diplome' => $data['diplome'] ?? null,
            'sexe'=>$data['sexe'] ?? null,
            'extrait_naissance' => $data['extrait_naissance'] ?? null,
            'visite_medicale' => $data['visite_medicale'] ?? null,
            'casier_judiciaire' => $data['casier_judiciaire'] ?? null,
        ];

       
        InscriptionMailJob::dispatch($this->serviceMysql->find($mysqlUser));

        return $apprenantData;
    }
    
    public function generateQRCode($matricule){
        
        
        $qrCode = 'https://qrcode.link/api?text='. urlencode($matricule);
        return $qrCode;
    }
    public function importApprenants($file)
    {
        $import = new ApprenantsImport($this);
        $failedRows = [];

        Excel::import($import, $file);

        $failedRows = $import->getFailedRows();

        if (!empty($failedRows)) {
            $export = new FailedApprenantsExport($failedRows);
            $exportPath = 'exports/failed_apprenants_' . time() . '.xlsx';
            Excel::store($export, $exportPath, 'public');

            return [
                'success' => true,
                'message' => 'Import terminé avec des erreurs.',
                'failed_file' => $exportPath
            ];
        }

        return [
            'success' => true,
            'message' => 'Import terminé avec succès.'
        ];
    }

    public function listApprenants($filters = [])
    {
        $query = $this->apprenantsRepository->query();

        if (isset($filters['referentiel_id'])) {
            $query->where('referentiel_id', $filters['referentiel_id']);
        }

        if (isset($filters['statut'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('statut', $filters['statut']);
            });
        }

        $currentPromotion = $this->promotionRepository->getCurrentPromotion();
        $query->whereHas('referentiel', function ($q) use ($currentPromotion) {
            $q->where('promotion_id', $currentPromotion->id);
        });

        return $query->get();
    }
   
    public function getApprenantWithReferentiel($id)
    {
        $apprenant = $this->apprenantRepository->find($id);

        if (!$apprenant) {
            throw new \Exception("Apprenant non trouvé");
        }

        $referentiel = $this->referentielRepository->find($apprenant['referentiel_id']);

        if (!$referentiel) {
            throw new \Exception("Référentiel non trouvé pour cet apprenant");
        }

        return [
            'apprenant' => $apprenant,
            'referentiel' => $referentiel
        ];
    }

}
