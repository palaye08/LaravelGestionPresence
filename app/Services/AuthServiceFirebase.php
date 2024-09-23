<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Auth;

class AuthServiceFirebase implements AuthServiceInterface
{

    protected $auth;

    public function __construct()
    {
        try {
            // dd(env('FIREBASE_DATABASE_URL')); 
            $factory = (new Factory)
                        ->withServiceAccount(env('FIREBASE_CONFIG_PATH'))
                        ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
    
            $this->auth = $factory->createAuth();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la configuration Firebase: ' . $e->getMessage());
            throw new \Exception('Erreur lors de la configuration Firebase');
        }
    }
    


    public function authenticate($credentials)
    {
        try {
            // Récupérer l'utilisateur par email

            $user = $this->auth->getUserByEmail($credentials['email']);
            // Vérifier le mot de passe
            // Note: Firebase Admin SDK ne fournit pas de méthode directe pour vérifier le mot de passe
            // Vous devrez implémenter votre propre logique de vérification ou utiliser une autre approche
    
            // Si l'authentification réussit, créer un token personnalisé
            $customToken = $this->auth->createCustomToken($user->uid);
    
            return [
                'access_token' => $customToken->toString(),
                'token_type' => 'Bearer',
                'expires_at' => time() + 3600, // Expire dans 1 heure
                'custom_claims' => [
                    'user_id' => $user->uid,
                    'email' => $user->email,
                    'role' => $user->customClaims['role'] ?? 'user',
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Erreur d\'authentification Firebase: ' . $e->getMessage());
            return null;
        }
    }
}