<?php
namespace App\Services;

interface AuthServiceInterface{
    
    public function authenticate($credentials);
    public function logout();
}