<?php

namespace App\Observers;

use App\ModCase;
use App\Slack;
Use App\Jobs\SendNotificationToMods;
use Illuminate\Support\Str;

class ModCaseObserver
{
    /**
     * Handle the mod case "creating" event.
     *
     * @param  \App\ModCase  $modCase
     * @return void
     */
    public function creating(ModCase $modCase)
    {
        $modCase->access_key = Str::random(40);
    }

    /**
     * Handle the mod case "saved" event.
     *
     * @param  \App\ModCase  $modCase
     * @return void
     */
    public function saved(ModCase $modCase)
    {
        if($modCase->severity != $modCase->getOriginal('severity')){
            //Severity has been changed
            if($modCase->severity != null && $modCase->severity <= 3)
            {
                //Severity is 3 or "higher"
                $slack = new Slack;
                $slack->target = 'mod-notify';
                $slack->username = 'Mod Complaint High Severity';
                $slack->avatar = 'https://i.imgur.com/RoZ6aLY.jpg';
                $slack->title = "Title Test";
                $slack->text = 'SEV3 Case has been openned <' . route( 'moderator.case', $modCase) . '>';
                $slack->color = "error";
                \Notification::send($slack, new \App\Notifications\ModNotifyNew(null));

                $modCase->addInternalAnnotation('notificationSent', "Sent notification due to SEV{$modCase->severity}");
            }
        }
    }

    /**
     * Handle the mod case "updated" event.
     *
     * @param  \App\ModCase  $modCase
     * @return void
     */
    public function updated(ModCase $modCase)
    {
        //
    }

    /**
     * Handle the mod case "deleted" event.
     *
     * @param  \App\ModCase  $modCase
     * @return void
     */
    public function deleted(ModCase $modCase)
    {
        //
    }

    /**
     * Handle the mod case "restored" event.
     *
     * @param  \App\ModCase  $modCase
     * @return void
     */
    public function restored(ModCase $modCase)
    {
        //
    }

    /**
     * Handle the mod case "force deleted" event.
     *
     * @param  \App\ModCase  $modCase
     * @return void
     */
    public function forceDeleted(ModCase $modCase)
    {
        //
    }
}
