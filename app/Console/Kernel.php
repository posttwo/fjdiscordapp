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
				$slack->title    = '';
				
                $slack->username =   "Sexy Flanders";
                $slack->avatar   =   'https://i.imgur.com/ILcjrEq.png';
                $slack->text     = '<:Voretwo:356922673395138571> Cummie cummie on my tummy <:Voretwo:356922673395138571> <@&427487027429244929>';
                
				if($r->sfw > 45){
					$slack->username =   "Helpfuls Ghost";
					$slack->avatar   =   'https://i.imgur.com/4g9cHT2.png';
					$slack->text     = 	 'One day I shall come back. Yes, I shall come back. <@&427487027429244929>';
				}
				
				if($r->sfw > 50){
                    $slack->text .= ' <:NotLikeThis:250445078647144449> <@&305827361767817216> <@&137342300723478528> <@&151904749984284672> **<@&464411259303886849> with a cap above 20 are allowed ignore their cap and rate until all content is rated**';
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
