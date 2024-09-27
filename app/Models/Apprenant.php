<?php
namespace App\Models;

class Apprenant extends FirebaseBaseModel{

    protected $fillable = ['referentiel_id','matricule','qr_code','user_id','tuteur_nom','tuteur_prenom','tuteur_contact','cni','diplome','sexe','extrait_naissance','visite_medicale','casier_judiciaire'];

    public function getTable(){
        return 'apprenants';
    }
}
