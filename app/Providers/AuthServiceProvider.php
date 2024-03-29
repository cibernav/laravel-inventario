<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        //'App\User' => 'App\Policies\UserPolicy', //Forma 1
        User::class => UserPolicy::class, //Forma 2 - incluir use namespace
        'App\Client' => 'App\Policies\ClientPolicy',
        'App\DocumentoCabecera' => 'App\Policies\DocumentoCabeceraPolicy',
        'App\Category' => 'App\Policies\CategoryPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
