<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthentificationPassport;

class AuthController extends Controller
{
    protected AuthentificationPassport $authService;

    public function __construct(AuthentificationPassport $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->authService->authenticate($request->only('email', 'password'));
            // dd($user);
        if ($user) {
            $token = $this->authService->generateToken($user);
    
            return response()->json([
             
                'token' => $token,
            ], 200);
        } 

        return response()->json([
            'error' => 'Unauthorized',
            'message' => 'Les informations d\'identification ne correspondent pas.',
        ], 401);
    }
}
