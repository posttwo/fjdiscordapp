<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\User as FJUser;
use Posttwo\FunnyJunk\Board;
use App\Jobs\CheckDJVote;
use Carbon\Carbon;

class DJController extends Controller
{
    public function __construct()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }

    public function index($boardName)
    {
        $board = new Board();
        $board->name = $boardName;
        $board->getId()->getDom()->getDJs();
        return view('moderator.djs')->with('board', $board);
    }

    public function initiateReplacement($boardName, $djPosition)
    {
        $board = new Board();
        $board->name = $boardName;
        $board->getId()->getDom()->getDJs();
        
        //Who am I replacing?
        $toReplace = $board->dj[$djPosition];
        
        //Who initiated this?
        $initiatedBy = \Auth::user()->fjuser->username;
        //Lets inform the board of my decision

        //TODO: Make this into a job
        $message =  "[big][bold]DJ SWITCHER[bold][big]\n\n";
        $message .= "Replacing: @" . $toReplace . "\n";
        $message .= "---------------------------------------------------------\n";
        $message .= ":thumb-up: Vote UP to approve\n";
        $message .= ":thumb-down: Vote DOWN to disagree\n";
        $message .= "Reply with most thumbs will get DJ\n";
        $message .= "[small]I am a bot, this action was performed by @" . $initiatedBy . "[small]";

        $url = $board->postMessage($message);
        if($url == false)
            return false;
        //Dispatch a job to check in 5 minutes
        $job = (new CheckDJVote($board, $url, $djPosition))
                    ->delay(Carbon::now()->addMinutes(2));

        dispatch($job);

        return collect(['To Replace' => $toReplace,
                        'Initiated By' => $initiatedBy,
                        'URL Of Notification' => $url
                        ]);
    }

}
