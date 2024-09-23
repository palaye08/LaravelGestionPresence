<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServiceMysql implements AuthServiceInterface
{
    public function authenticate($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return $this->generateToken($user);
        }
        return null;
    }

    public function generateToken($user)
    {
        // Créer un token pour l'utilisateur
        $tokenResult = $user->createToken('API Token');

        // Les claims personnalisés
        $customClaims = [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role->statu ?? null, // Assurez-vous que la relation 'role' existe
        ];

        return [
            'access_token' => $tokenResult->accessToken, // Utiliser accessToken pour Passport
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at, // Cette propriété devrait exister
            'custom_claims' => $customClaims,
        ];
    }
}
