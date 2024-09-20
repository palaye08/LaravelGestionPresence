<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\DatabaseManager;
use App\Database\Connections\FirebaseConnection;

class AppServiceProvider extends ServiceProvider
{
   
    public function register()
    {
        //
    }
    public function boot()
    {
        $this->app->resolving('db', function (DatabaseManager $db) {
            $db->extend('firebase', function ($config) {
                return new FirebaseConnection($config);
            });
        });
    }

}
