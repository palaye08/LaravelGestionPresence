<?php

namespace App\Services;

interface AuthServiceInterface
{
    public function authenticate($credentials);
}