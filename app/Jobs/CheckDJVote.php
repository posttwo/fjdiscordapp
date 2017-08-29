<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\User as FJUser;
use Posttwo\FunnyJunk\Board;

class CheckDJVote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $fj;
    protected $board;
    protected $commentUrl;
    protected $djPosition;
    public function __construct(Board $board, $commentUrl, $djPosition)
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
        $this->board = $board;
        $this->commentUrl = $commentUrl;
        $this->djPosition = $djPosition;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Get Comment
        $coms = $this->board->getCommentTree($this->commentUrl);
        if($coms[0]->thumb_val < 0){
            $this->board->postMessage("DJ Vote Failed: Negative Thumbs On Root");
            return true;
        }
        //Sort replies by thumbs
        $coms = collect($coms);
        //remove 0
        $coms->forget(0);
        $coms = $coms->sortByDesc('thumb_val');

        //foreach comment until eligible DJ is found
        foreach($coms as $com)
        {
            $test = $this->board->setDJ($this->djPosition, $com->username);
            if($test == true)
                break;
        }
        return true;
    }
}
