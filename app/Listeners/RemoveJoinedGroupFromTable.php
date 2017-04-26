<?php

namespace App\Listeners;

use App\Events\UserLeftGroup;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveJoinedGroupFromTable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLeftGroup  $event
     * @return void
     */
    public function handle(UserLeftGroup $event)
    {
        $event->user->roles()->detach($event->role->id);
    }
}
