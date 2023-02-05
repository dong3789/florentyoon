<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');

        // 토큰 유효기간
        Passport::tokensExpireIn(now()->addMinutes(30));

        // refresh token
        Passport::refreshTokensExpireIn(now()->addMinutes(30));

        // personalAccessToken
        Passport::personalAccessTokensExpireIn(now()->addMinutes(30));
    }
}
