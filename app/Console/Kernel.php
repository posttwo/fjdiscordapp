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
            $collection = collect($comments);
            $collection->pop(); //admin is a retard
            \Cache::forever("Cron-ASKAMOD", $collection->max('id'));
            
        })->everyFiveMinutes();

         //Check mod stats comments
         $schedule->call(function () {
            $fj = new \Posttwo\FunnyJunk\FunnyJunk;
            $fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
            $r = $fj->getModInfo();
            $lastCall = \Cache::get("Cron-SFW_COUNT", 0);
            $isIncreasing = $r->sfw > $lastCall;
            //$r -> sfw nsfw links
            if($r->sfw > 30 && $isIncreasing)
            {
                $slack = new \App\Slack;
                $slack->target = 'mod-social';
                $slack->username =   "Jettoms Peen";
                $slack->text     =   null;
                $slack->avatar   =   'https://i.imgur.com/6G1qaAT.png';
                $slack->title    = '';
                $slack->text     = '<:lul:374908740958158848> DING DONG YOU HAVE AIDS <:lul:374908740958158848> <@&427487027429244929>';
                if($isIncreasing)
                    $slack->text .= '\n :warning:  ';
                if($r->sfw > 50)
                    $slack->text .= '\n :red_circle:  <@&305827361767817216> <@&137342300723478528>  <@&151904749984284672>';
                $slack->embedFields = [ 'SFW' => $r->sfw,
                                        'NSFW' => $r->nsfw,
                                        'LINKS'   => $r->links ];

                \Notification::send($slack, new \App\Notifications\ModNotify(null));
            }
            \Cache::forever("Cron-SFW_COUNT", $r->sfw);
            
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
