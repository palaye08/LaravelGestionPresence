<?php
namespace App\Repositories;


interface ReferentielRepositoryInterface{

    public function getAllReferentiels();
    public function getReferentielById($id);
    public function createReferentiel($data);
    public function updateReferentiel($id, $data);
    public function softDeleteReferentiel($id);
    public function restoreReferentiel($id);
    public function getActiveReferentiels();
    public function getReferentielsByEtat($etat);
    public function getDeletedReferentiels();
    public function save($referentiel); 

}