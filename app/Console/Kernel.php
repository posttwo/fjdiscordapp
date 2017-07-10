<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CAHBot::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $fj = new \Posttwo\FunnyJunk\FunnyJunk;
            $r = $fj->getByUrl("/askamod");
            $comments = $r->comments;

            $lastProcessedId = \Cache::get("Cron-ASKAMOD", 0);

            foreach($comments as $com) {
                if($com->is_sticky == 1)
                    continue;
                if($com->reply_level != 0)
                    continue;
                if($lastProcessedId >= $com->id)
                    continue;
                
                $slack = new \App\Slack;
                $slack->username =   $com->username;
                $slack->text     =   $com->text;
                $slack->avatar   =   $com->original_avatar_url;
                $slack->number   =   $com->number;
                $slack->id       =   $com->id;
                $slack->date     =   $com->date;
                $slack->title = 'https://funnyjunk.com/askamod/' . $com->number;
                $slack->description = 'I am confused on Ask A Mod again, please help!';
                \Notification::send($slack, new \App\Notifications\ModNotify(null));
            }
            \Cache::forever("Cron-ASKAMOD", $comments[1]->id);
            
        })->everyFiveMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
