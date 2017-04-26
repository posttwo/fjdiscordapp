<?php

namespace App\Listeners;

use App\Events\UserJoinedGroup;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InsertJoinedGroupToTable
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
     * @param  UserJoinedGroup  $event
     * @return void
     */
    public function handle(UserJoinedGroup $event)
    {
        //$event->user and $event->role are available
        //shove them into a tbale
    }
}
