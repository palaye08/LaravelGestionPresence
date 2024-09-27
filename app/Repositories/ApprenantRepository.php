<?php

namespace App\Repositories;

use App\Models\Apprenant;

class ApprenantRepository 
{
    protected $model;

    public function __construct(Apprenant $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function softDelete($id)
    {
        return $this->model->softDelete($id);
    }

    public function restore($id)
    {
        return $this->model->restore($id);
    }

    public function query()
    {
        return $this->model;
    }

    public function getByReferentiel($referentielId)
    {
        $all = $this->all();
        return array_filter($all, function($apprenant) use ($referentielId) {
            return $apprenant['referentiel_id'] == $referentielId;
        });
    }

    public function getByStatut($statut)
    {
        $all = $this->all();
        return array_filter($all, function($apprenant) use ($statut) {
            return $apprenant['statut'] == $statut;
        });
    }

    public function getByReferentielAndStatut($referentielId, $statut)
    {
        $all = $this->all();
        return array_filter($all, function($apprenant) use ($referentielId, $statut) {
            return $apprenant['referentiel_id'] == $referentielId && $apprenant['statut'] == $statut;
        });
    }
}