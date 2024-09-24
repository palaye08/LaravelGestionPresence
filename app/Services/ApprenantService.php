<?php
namespace App\Services;

use App\Repositories\PromotionRepository;
use App\Repositories\ReferentielRepository;
use App\Jobs\InscriptionMailJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApprenantService
{
    protected $userService;
    protected $promotionRepository;
    protected $referentielRepository;

    public function __construct(UserService $userService, PromotionRepository $promotionRepository, ReferentielRepository $referentielRepository)
    {
        $this->userService = $userService;
        $this->promotionRepository = $promotionRepository;
        $this->referentielRepository = $referentielRepository;
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
            'password' => '', // Le mot de passe sera généré par le service
            'statu'=>$data['statut'], // Le mot de passe
            'role' => 'Apprenant'
        ];
          // Générer un mot de passe aléatoire
        $userData['password'] = Str::random(8);
        $userId = $this->userService->store($userData);

        // Générer un matricule et un QR code
        $matricule = Str::random(10);
        $qrCode = 'QR code data'; // Vous pouvez générer un QR code ici


        // Créer l'apprenant
        $apprenantData = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'photo' => $data['photo'] ?? null,
            'referentiel_id' => $data['referentiel_id'],
            'matricule' => $matricule,
            'qr_code' => $qrCode,
            'user_id' => $userId,
            'tuteur_nom' => $data['tuteur_nom'],
            'tuteur_prenom' => $data['tuteur_prenom'],
            'tuteur_contact' => $data['tuteur_contact'],
            'cni' => $data['cni'] ?? null,
            'diplome' => $data['diplome'] ?? null,
            'extrait_naissance' => $data['extrait_naissance'] ?? null,
            'visite_medicale' => $data['visite_medicale'] ?? null,
            'casier_judiciaire' => $data['casier_judiciaire'] ?? null,
        ];

       
        InscriptionMailJob::dispatch($this->userService->find($userId));

        return $apprenantData;
    }
}
