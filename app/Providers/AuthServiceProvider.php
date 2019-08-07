<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Passport::routes();

        Passport::tokensExpireIn(now()->addDays(config('auth.days_tokens_expire_in')));

        Passport::refreshTokensExpireIn(now()->addDays(config('auth.days_refresh__tokens_expire_in')));

        Passport::personalAccessTokensExpireIn(now()->addDays((int)config('auth.days_tokens_expire_in')));

        Passport::personalAccessClientId('1');
    }
}
