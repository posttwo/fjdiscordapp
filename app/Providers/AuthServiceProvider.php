<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Carbon\Carbon;

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

        //API SHIT
        Passport::routes(null, ['middleware' => ['web']]);
        Passport::tokensExpireIn(Carbon::now()->addYears(15));
        Passport::tokensCan([
            'fjapi-userinfo-basic' => 'Get basic info for an FJ User',
            'fjapi-userinfo-mod' => 'Get Mod Info for an FJ User',
            'discord-post-modhelp' => 'Post Mod-Help queries',
            'fjmod-token' => 'Create or Get a Notes Token'
        ]);
        Passport::enableImplicitGrant();
    }
}
