<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthentificationPassport implements AuthServiceInterface
{
    public function authenticate($credentials)
    {
        if (Auth::attempt($credentials)) {
            return Auth::user(); 
        }

        return null; 
    }

    public function logout()
    {
        Auth::logout();
    }

    public function generateToken($user)
    {
        // Créer un token d'accès personnel avec les claims personnalisés
        $tokenResult = $user->createToken('API Token');

        $token = $tokenResult->token;
        $token->user_id = $user->id; // Ajout de l'ID utilisateur au token
        $token->save();

        // Ajout de claims personnalisés
        $customClaims = [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role->statu,
        ];

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
            'custom_claims' => $customClaims,
        ];
    }
}
