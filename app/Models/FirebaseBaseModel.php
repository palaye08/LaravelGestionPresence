<?php

namespace App\Models;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Exception\DatabaseException;

abstract class FirebaseBaseModel
{
    protected $database;
    protected $tableName;

    public function __construct()
    {
        try {
            $factory = (new Factory)
            ->withServiceAccount(env('FIREBASE_CONFIG_PATH'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
            
            $this->database = $factory->createDatabase();
            $this->tableName = $this->getTable();
            
            $this->database->getReference($this->tableName)->getValue();
        } catch (DatabaseException $e) {
            throw new \Exception("Impossible de se connecter à Firebase: " . $e->getMessage());
        }
    }

    abstract public function getTable();

    public function create(array $data)
    {
        $reference = $this->database->getReference($this->tableName)->push($data);
        return $reference->getKey();
    }

    public function find($id)
    {
                
        return $this->database->getReference($this->tableName)->getChild($id)->getValue();
    }

    public function all()
    {    
        $result = $this->database->getReference($this->tableName)->getValue();
        return $result ?: [];
    }

    public function update($id, array $data)
    {
        $this->database->getReference($this->tableName)->getChild($id)->update($data);
        return $this->find($id);
    }

    public function delete($id)
    {
        return $this->database->getReference($this->tableName)->getChild($id)->remove();
    }

    public function restore($id){
        $this->database->getReference($this->tableName)->getChild($id)->update(['statut' => 'actif']);
        return $this->find($id);
    }
    public function softDelete($id)
    {
        $this->database->getReference($this->tableName)->getChild($id)->update(['statut' => 'inactif']);
        return $this->find($id);
    }
}