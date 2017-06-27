<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\PM;
use Posttwo\FunnyJunk\User as FJUser;
use Posttwo\FunnyJunk\Board;
use App\FunnyjunkUser;
use RestCord\DiscordClient;
use App\Jobs\ReplaceDJ;

class DJController extends Controller
{

    public function __construct()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }
    
    public function get($dj)
    {
        $board = new Board();
        $board->name = 'testcss2';
        $board->id = 6163000;

        $board->getDom()->getDJs();
        $djName = $board->dj[$dj];
        $message  = "[big][bold]DJ SWITCHER[bold][big] \n\n";
        $message .= "Replacing: @$djName \n";
        $message .= "[greenish-gray]---------------------------------------------------------[greenish-gray] \n";
        $message .= ":thumb-up: Vote UP to approve \n";
        $message .= ":thumb-down: Vote DOWN to deny \n\n";
        $message .= "Reply for a chance to be picked as DJ \n\n";
        $message .= "[small]I am a bot, this action was taken automatically.[small]";
        //$messageUrl = $board->postMessage($message);
        //schedule a job in one minute to check it
        dispatch(new ReplaceDJ($board, $this->fj, $dj));
        return "ok";
    }
    
}
