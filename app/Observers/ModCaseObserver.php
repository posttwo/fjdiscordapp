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
            if($modCase->severity != null && $modCase->severity <= 3 && $modCase->queue == 'user-complaint-sfw')
            {
                //Severity is 3 or "higher"
                $slack = new Slack;
                $slack->target = 'mod-notify';
                $slack->username = 'Mod Complaint High Severity';
                $slack->avatar = 'https://i.imgur.com/RoZ6aLY.jpg';
                $slack->title = "Title Test";
                $slack->text = 'Currently banned user submitted a complaint <@&151904333703675904> <' . route( 'moderator.case', $modCase) . '>';
                $slack->color = "error";
                \Notification::send($slack, new \App\Notifications\ModNotifyNew(null));

                $modCase->addInternalAnnotation('notificationSent', "Sent notification due to SEV{$modCase->severity}");
            }
            
            if($modCase->severity != null && $modCase->severity == 4 && $modCase->queue == 'user-complaint-sfw')
            {
                //Normal case
                $slack = new Slack;
                $slack->target = 'mod-notify';
                $slack->username = 'Mod Complaint Spammer';
                $slack->avatar = 'https://i.imgur.com/SRa0wCj.png';
                $slack->title = "Title Test";
                $slack->text = 'Normal user is butthurt <' . route( 'moderator.case', $modCase) . '>';
                $slack->color = "warning";
                \Notification::send($slack, new \App\Notifications\ModNotifyNew(null));

                $modCase->addInternalAnnotation('notificationSent', "Sent notification due to SEV{$modCase->severity}");
            }
        }
        //548199053943373969

        if($modCase->queue != $modCase->getOriginal('queue')){
            //Queue has been has been changed
            if($modCase->queue == 'user-complaint-nsfw')
            {
                //is NSFW queue
                $slack = new Slack;
                $slack->target = 'mod-notify';
                $slack->username = 'user-complaint-nsfw';
                $slack->avatar = 'https://i.imgur.com/RoZ6aLY.jpg';
                $slack->title = "Title Test";
                $slack->text = 'NSFWrinkly Case has been openned <' . route( 'moderator.case', $modCase) . '> <@&548199053943373969>';
                $slack->color = "error";
                \Notification::send($slack, new \App\Notifications\ModNotifyNew(null));

                $modCase->addInternalAnnotation('notificationSent', "Sent notification due to queue {$modCase->queue}");
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
