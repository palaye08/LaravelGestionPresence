<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Client;

use App\Models\Article;
use App\Policies\UserPolicy;
use App\Policies\PromoPolicy;
use Laravel\Passport\Passport;
use App\Policies\ReferentielPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Promotion::class => PromoPolicy::class,
        Referentiel::class => ReferentielPolicy::class,
        User::class => UserPolicy::class,
    ];

     /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
