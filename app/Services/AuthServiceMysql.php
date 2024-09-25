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
        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->token;
        $token->save();

        $customClaims = [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role->statu ?? null,
        ];

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
            'custom_claims' => $customClaims,
        ];
    }
}
