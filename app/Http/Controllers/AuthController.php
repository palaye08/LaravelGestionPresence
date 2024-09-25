<?php

namespace App\Http\Controllers;

use App\Services\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $result = $this->authService->authenticate($credentials);

        if ($result) {
            return response()->json($result, 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
