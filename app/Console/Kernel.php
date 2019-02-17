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
        /*$schedule->call(function () {
            $fj = new \Posttwo\FunnyJunk\FunnyJunk;
			$fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
            $r = $fj->getByUrl("/mod-social");
            $comments = $r->comments;

            $lastProcessedId = \Cache::get("Cron-ModSocial", 0);

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
                $slack->avatar   =   $com->original_avatar_url;
                $slack->title    = '';
                $slack->text     = str_limit($com->text, 1500);


                $slack->embedFields = ['Username' => $com->username,
                                       'Text' => str_limit($com->text, 80),
                                       'ID'   => $com->id,
                                       'Date' => $com->date,
                                       'Link' => 'https://funnyjunk.com/mod-social/' . $com->number ];

                \Notification::send($slack, new \App\Notifications\ModNotify(null));
            }
            $collection = collect($comments);
            $collection->pop(); //admin is a retard
            \Cache::forever("Cron-ModSocial", $collection->max('id'));
            
        })->everyFiveMinutes();*/

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
				$slack->title    = '';
				
                $slack->username =   "Amazon Content Rating Prime";
                $slack->avatar   =   'https://i.imgur.com/VkIRAY4.png';
                $slack->text     = '<@&427487027429244929>  Please return to your Primestation for your daily Primerating, valued Primemod!';
                
				if($r->sfw > 45){
					$slack->username =   "Amazon Content Rating Prime";
					$slack->avatar   =   'https://i.imgur.com/VkIRAY4.png';
					$slack->text     = 	 ' <@&427487027429244929>   SEV2 incident detected. Please return to your Primestations immediately, valued Primemods! ';
				}
				
				if($r->sfw > 50){
					$slack->avatar   =   'https://i.imgur.com/VkIRAY4.png';
                    			$slack->text .= ' SEV0 incident detected and ticket opened. Paging Jeff. <@&305827361767817216> <@&137342300723478528> <@&151904749984284672> **Students with a cap above 20 are allowed ignore their cap and rate until all content is rated**';
				}
				
				if($r->sfw > 70){
					$slack->username = 'Helpfuls Ressurection';
					$slack->text .= '@everyone The needs of the many outweigh the needs of the few. Remodding <@156717038570700800> in progress';
				}
				
				if($r->sfw > 100){
					$slack->text .= 'Seriously tho';
				}
                
				$slack->embedFields = [ 'SFW' => $r->sfw,
                                        'NSFW' => $r->nsfw,
                                        'LINKS'   => $r->links ];

                \Notification::send($slack, new \App\Notifications\ModNotify(null));
            }
            \Cache::forever("Cron-SFW_COUNT", $r->sfw);
            
        })->everyFiveMinutes();
        
        $schedule->call(function () {
            $fj = new \Posttwo\FunnyJunk\FunnyJunk;
            $user = new \Posttwo\FunnyJunk\User;
            $fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
            $user->id = 1490859;
            $accounts = $user->getUsersSameId();
            $previous = \Cache::get("SAMEIP-1490859", []);
            $current = [];
            foreach($accounts as $ac){
                    $current[] = $ac->username;
            }
            $diff = array_diff($current, $previous);
            $total = array_merge($previous, $diff);
            \Cache::forever("SAMEIP-1490859", $total);      
            if($diff != [])
            {
                $text = '';
                foreach($diff as $d)
                {
                    $text .= ' ' . $d;
                }
                $slack = new \App\Slack;
                $slack->target = 'mod-social';
                $slack->username =   "Anitas Butthole";
                $slack->text     =   null;
                $slack->avatar   =   'https://i.imgur.com/rwFxHPc.png';
                $slack->title    = '';
                $slack->text     =  '<@&151904333703675904> I found illegal accounts! ' . $text;
                $slack->embedFields = ['Monitored User' => 1490859];

                \Notification::send($slack, new \App\Notifications\ModNotify(null));
            }
        })->everyTenMinutes();

        $schedule->call('App\Http\Controllers\ModActionController@parseJson')->hourly();
        //$schedule->call('App\Http\Controllers\ModComplaintController@checkComplaintsAndAlertMods')->everyTenMinutes();
        $schedule->call(function(){
            $x = new \App\ModCase;
            $x->bulkImport();
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
