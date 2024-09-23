<?php

namespace App\Services;

class AuthService
{
    private $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function authenticate($credentials)
    {
        return $this->authService->authenticate($credentials);
    }
}
