<?php

namespace App\Models;



class Promotion extends FirebaseBaseModel 
{
    
    protected $fillable = ['photo','libelle', 'date_debut', 'date_fin','duree','etat', 'referentiels'];

    public function getTable(){

        return 'promotions';
    }
}
