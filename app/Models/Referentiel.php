<?php

namespace App\Models;


class Referentiel extends FirebaseBaseModel
{
    

    protected $fillable = [
        'code', 'description', 'libelle', 'photo'
    ];

    public function getTable(){

        return 'referentiels';
    }
}
