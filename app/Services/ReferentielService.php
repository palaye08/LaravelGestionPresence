<?php
namespace App\Services;

use App\Repositories\ReferentielRepository;
use Illuminate\Support\Facades\Validator;


class ReferentielService implements ReferentielServiceInterface
{
    protected $referentielRepository;

    public function __construct(ReferentielRepository $referentielRepository)
    {
        $this->referentielRepository = $referentielRepository;
    }

    public function createReferentiel($data)
        {
            $validator = Validator::make($data, [
                'etat' => 'required|in:actif,inactif,archive',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Taille maximale de 2MB, types d'images acceptés
                'libelle' => 'required|string|max:255',
                'description' => 'nullable|array',
                'description.*.type' => 'required|in:Back-end,Front-end',
                'description.*.modules' => 'array',
                'description.*.modules.*.nom' => 'required|string|max:255',
                'description.*.modules.*.description' => 'required|string|max:255',
                'description.*.modules.*.duree' => 'required|integer|min:1', // Durée en heures, au moins 1 heure
            ]);
        
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
        
            // Traitez les données si nécessaire (par exemple, le téléchargement de la photo)
            if (isset($data['photo'])) {
                $photoPath = $data['photo']->store('referentiels', 'public');
                $data['photo'] = $photoPath;
            }
        
            $randomNumber = mt_rand(100, 999); // Génère un nombre aléatoire entre 100 et 999
            $randomLetter = chr(mt_rand(65, 90)); // Génère une lettre majuscule aléatoire
            $referentielCode = "REF" . str_pad($randomNumber, 3, "0", STR_PAD_LEFT) . $randomLetter;
        
            $data['code'] = $referentielCode;
        
            // Traiter les descriptions Back-end
            if (isset($data['description']['back'])) {
                $backDescription = [
                    'type' => 'Back-end',
                    'modules' => [],
                ];
        
                foreach ($data['description']['back'] as $backModule) {
                    $backDescription['modules'][] = [
                        'nom' => $backModule['nom'],
                        'description' => $backModule['description'],
                        'duree' => $backModule['duree'],
                    ];
                }
        
                $data['description'][] = $backDescription;
            }
        
            // Traiter les descriptions Front-end
            if (isset($data['description']['front'])) {
                $frontDescription = [
                    'type' => 'Front-end',
                    'modules' => [],
                ];
        
                foreach ($data['description']['front'] as $frontModule) {
                    $frontDescription['modules'][] = [
                        'nom' => $frontModule['nom'],
                        'description' => $frontModule['description'],
                        'duree' => $frontModule['duree'],
                    ];
                }
        
                $data['description'][] = $frontDescription;
            }
        
            // Créez le référentiel en utilisant le repository
            $referentielId = $this->referentielRepository->createReferentiel($data);
        
            return $referentielId;
        }
    
        public function getActiveReferentiels()
        {
            return $this->referentielRepository->getActiveReferentiels();
        }
        public function getReferentielsByEtat($etat)
        {
            return $this->referentielRepository->getReferentielsByEtat($etat);
        }
       
        public function getReferentielCompetences($id)
        {
            $referentiel = $this->referentielRepository->getReferentielById($id);
        
            if (!$referentiel) {
                throw new \Exception('Référentiel non trouvé');
            }
        
            $competences = [];
        
            // Si $referentiel est un tableau, on utilise l'accès par clé
            if (isset($referentiel['description']) && is_array($referentiel['description'])) {
                foreach ($referentiel['description'] as $description) {
                    if (isset($description['modules']) && is_array($description['modules'])) {
                        foreach ($description['modules'] as $module) {
                            $competences[] = [
                                'competence' => $description['type'] ?? null,
                                'module' => $module['nom'] ?? null,
                                'description' => $module['description'] ?? null,
                                'duree' => $module['duree'] ?? null,
                            ];
                        }
                    }
                }
            } else {
                throw new \Exception('Description non trouvée ou invalide');
            }
        
            return $competences;
        }
        
        public function getReferentielModules($id)
        {
            $referentiel = $this->referentielRepository->getReferentielById($id);
        
            if (!$referentiel) {
                throw new \Exception('Référentiel non trouvé');
            }
        
            $modules = [];
        
            // Si $referentiel est un tableau, on vérifie l'existence de 'description'
            if (isset($referentiel['description']) && is_array($referentiel['description'])) {
                foreach ($referentiel['description'] as $description) {
                    if (isset($description['modules']) && is_array($description['modules'])) {
                        foreach ($description['modules'] as $module) {
                            $modules[] = [
                                'competence' => $description['type'] ?? null,
                                'module' => $module['nom'] ?? null,
                                'description' => $module['description'] ?? null,
                                'duree' => $module['duree'] ?? null,
                            ];
                        }
                    }
                }
            } else {
                throw new \Exception('Description non trouvée ou invalide');
            }
        
            return $modules;
        }
        


        public function updateReferentiel($id, $data)
        {
            $referentiel = $this->referentielRepository->getReferentielById($id);
            
            if (!$referentiel) {
                throw new \Exception('Référentiel non trouvé');
            }
        
            if (isset($data['nom'])) {
                $referentiel['nom'] = $data['nom'];
            }
        
            if (isset($data['new_competence'])) {
                $newCompetence = $data['new_competence'];
                $referentiel['description'][] = [
                    'type' => $newCompetence['type'],
                    'modules' => [],
                ];
            }
        
            if (isset($data['add_modules'])) {
                $addModules = $data['add_modules'];
                foreach ($referentiel['description'] as $key => $description) {
                    if ($description['type'] === $addModules['type']) {
                        $referentiel['description'][$key]['modules'][] = $addModules['module'];
                    }
                }
            }
        
            if (isset($data['delete_competence'])) {
                $deleteCompetence = $data['delete_competence'];
                foreach ($referentiel['description'] as $key => $description) {
                    if ($description['type'] === $deleteCompetence['type']) {
                        unset($referentiel['description'][$key]);
                    }
                }
            }
        
            if (isset($data['delete_module'])) {
                $deleteModule = $data['delete_module'];
                foreach ($referentiel['description'] as $key => $description) {
                    if ($description['type'] === $deleteModule['type']) {
                        foreach ($description['modules'] as $moduleKey => $module) {
                            if ($module['nom'] === $deleteModule['module']) {
                                unset($referentiel['description'][$key]['modules'][$moduleKey]);
                            }
                        }
                    }
                }
            }
        
            $this->referentielRepository->save($referentiel);
        
            return $referentiel;
        }
        

        public function softDeleteReferentiel($id)
        {
            $referentiel = $this->referentielRepository->getReferentielById($id);

            if (!$referentiel) {
                throw new \Exception('Référentiel non trouvé');
            }
            if( $referentiel['etat']=='inactif'){  
            throw new \Exception('Référentiel déjà supprimé');
            }
            $referentiel['etat'] = 'inactif';

            $this->referentielRepository->updateReferentiel($id, $referentiel);

            return [
                'message' => 'Référentiel mis à jour avec succès', 
                'referentiel' => $referentiel 
            ];
        }

        

                    
        public function getDeletedReferentiels()
        {
            return $this->referentielRepository->getDeletedReferentiels();
        }



}
