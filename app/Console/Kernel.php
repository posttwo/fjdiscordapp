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
        Commands\CAHBot::class,
        Commands\UnverifyUser::class,
        Commands\ClearBotCookie::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Check new askamod comments
        $schedule->call(function () {
            $fj = new \Posttwo\FunnyJunk\FunnyJunk;
            $r = $fj->getByUrl("/askamod");
            $comments = $r->comments;

            $lastProcessedId = \Cache::get("Cron-ASKAMOD", 0);

            foreach($comments as $com) {
                if($com->id == "empty")
                    continue;
                if($com->is_sticky == 1)
                    continue;
                if($com->reply_level != 0)
                    continue;
                if($lastProcessedId >= $com->id)
                    continue;
                
                $slack = new \App\Slack;
                $slack->target = 'mod-notify';
                $slack->username =   $com->username;
                $slack->text     =   null;
                $slack->avatar   =   $com->original_avatar_url;
                $slack->title    = '';
                $slack->text     = 'I am confused on Ask A Mod again, please help!';
                if($com->username == 'crixuz')
                {
                    $slack->text = 'Im a special snowflake trying to post porn and not get banned, please assist oh my dearest modfriends!';
                }


                $slack->embedFields = ['Username' => $com->username,
                                       'Text' => str_limit($com->text, 80),
                                       'ID'   => $com->id,
                                       'Date' => $com->date,
                                       'Link' => 'https://funnyjunk.com/askamod/' . $com->number ];

                \Notification::send($slack, new \App\Notifications\ModNotify(null));
            }
            \Cache::forever("Cron-ASKAMOD", collect($comments)->max('id'));
            
        })->everyFiveMinutes();

        
        //Check if anyone has been demodded :)
        /*$schedule->call(function (){
            echo "Running";
            $fj = new \Posttwo\FunnyJunk\FunnyJunk;
            $fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
            $mods = $fj->getMods();
            
            $oldMods = \Cache::get('Cron-Mod-Array', []);
            if($mods == $oldMods){
                echo "No Changes";
            }
            else {
                echo "Changes in mods detected!";
                logger()->alert("Cron detected change in mods!");
                foreach($oldMods as $key => $value) {
                    if(!isset($mods[$key])) {
                        echo $value->username . " has been demodded";
                        logger()->alert($value->username . " has been demodded");
                        $slack = new \App\Slack;
                        $slack->target = 'mod-social';
                        $slack->username =   "FunnyJunk Alert System";
                        $slack->text     =   ':warning: USER HAS BEEN DEMODDED: ' . $value->username . ' :warning:';     
                        $slack->embedFields = [];  
                        $slack->title = null;
                        $slack->avatar = null;
                        \Notification::send($slack, new \App\Notifications\ModNotify(null));
                    }
                }

                \Cache::forever('Cron-Mod-Array', $mods);
            }
                
        })->everyFiveMinutes();*/
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
