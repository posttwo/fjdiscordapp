<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'SocialiteProviders\Discord\DiscordExtendSocialite@handle'
        ],
        'App\Events\UserJoinedGroup' => [
            'App\Listeners\InsertJoinedGroupToTable'
        ],
        'App\Events\UserLeftGroup' => [
            'App\Listeners\RemoveJoinedGroupFromTable'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
