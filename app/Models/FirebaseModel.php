<?php

namespace App\Models;

class FirebaseModel extends FirebaseBaseModel
{
    protected $fillable = ['nom', 'prenom', 'telephone', 'adresse', 'photo', 'email','password', 'role', 'statut'];
    protected $hidden = ['passsword'];
      


    public function getTable()
    {
        return 'users';
    }
}