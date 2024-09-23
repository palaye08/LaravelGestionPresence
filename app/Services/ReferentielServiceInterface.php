<?php
namespace App\Services;
 
interface ReferentielServiceInterface {

public function createReferentiel($data);

public function getActiveReferentiels();

public function getReferentielsByEtat($etat);

public function getReferentielCompetences($id);

public function getReferentielModules($id);

public function updateReferentiel($id, $data);

public function softDeleteReferentiel($id);


}