<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthServiceInterface;
use App\Services\AuthServiceMysql;
use App\Services\AuthServiceFirebase;

class LoginServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthServiceInterface::class, function ($app) {
            $loginType = config('auth.login_type'); 

            if ($loginType === 'firebase') {
                return new AuthServiceFirebase();
            }

            return new AuthServiceMysql();
        });
    }
}
