<?php

namespace App\Repositories;

use App\Models\Referentiel;

class ReferentielRepository implements ReferentielRepositoryInterface
{
    protected $model;

    public function __construct(Referentiel $model)
    {
        $this->model = $model;
    }

    public function getAllReferentiels()
    {
        return $this->model->all();
    }

    public function getReferentielById($id)
    {
        return $this->model->find($id);
        
    }
    public function getActiveReferentiels()
    {
        $referentiels = $this->model->all(); // Récupère tous les référentiels
        $activeReferentiels = [];

        foreach ($referentiels as $referentiel) {
            if (isset($referentiel['etat']) && $referentiel['etat'] === 'actif') {
                $activeReferentiels[] = $referentiel;
            }
        }

        return $activeReferentiels;
    }
    public function createReferentiel($data)
    {
        return $this->model->create($data);
    }

    public function updateReferentiel($id, $data)
    {  
        // dd($id, $data);
        return $this->model->update($id, $data);
    }
    public function save($referentiel)
    {
        $this->model->update($referentiel['id'], $referentiel);
    }
    
    
    public function restoreReferentiel($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }
    
    public function getReferentielsByEtat($etat)
    {
        $allReferentiels = $this->database->getReference($this->tableName)->getValue();
        
        $filteredReferentiels = [];
        foreach ($allReferentiels as $key => $referentiel) {
            if (isset($referentiel['etat']) && $referentiel['etat'] === $etat) {
                $referentiel['id'] = $key; 
                $filteredReferentiels[] = $referentiel;
            }
        }
        
        return $filteredReferentiels;
    }
    public function softDeleteReferentiel($id)
    {
        return $this->model->softDelete($id);
    }

    public function getDeletedReferentiels()
    {
        // Récupérer tous les référentiels et filtrer ceux qui sont inactifs
        $referentiels = $this->model->all();
        
        if ($referentiels) {
            return array_filter($referentiels, function($referentiel) {
                return isset($referentiel['etat']) && $referentiel['etat'] === 'inactif';
            });
        }

        return []; // Retourne un tableau vide si aucun référentiel n'est trouvé
    }
    



}
