<?php
namespace App\Services;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;

class FirebaseService implements DatabaseServiceInterface {
    protected $database;

    public function __construct() {

        // dd(config('firebase.credentials'));
        $firebaseCredentials = config('firebase.credentials');
        $firebaseDatabaseUrl = config('firebase.database_url');

        try {
            $factory = (new Factory)
                ->withServiceAccount($firebaseCredentials)
                ->withDatabaseUri($firebaseDatabaseUrl);
            $this->database = $factory->createDatabase();
        } catch (\Exception $e) {
            Log::error('Error initializing Firebase: ' . $e->getMessage());
            throw $e;
        }
    }

    public function Connect() {
        return $this->database;
    }
}