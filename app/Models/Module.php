<?php
namespace App\Models;

class Module extends FirebaseModel{

    protected $fillable = ['appreciatin', 'note'];

    public function getTable(){
        return 'modules';   
     }
}
