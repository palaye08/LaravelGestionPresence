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
        return $this->model->update($id, $data);
    }

    public function softDeleteReferentiel($id)
    {
        return $this->model->softDelete($id);
    }

    public function restoreReferentiel($id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }
    
    public function getReferentielsByEtat($etat)
    {
        return $this->model->where('etat', $etat)->get();
    }

    // ReferentielRepository.php
    public function getDeletedReferentiels()
    {
        return $this->model->where('etat', 'inactif')->get();
    }



}
