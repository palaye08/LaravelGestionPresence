<?php

namespace App\Models;

class FirebaseModel extends FirebaseBaseModel
{
    protected $fillable = ['nom', 'prenom', 'telephone', 'adresse', 'photo', 'email','password', 'role', 'statut'];
      


    public function getTable()
    {
        return 'users';
    }
}