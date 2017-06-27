<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Posttwo\FunnyJunk\Board;
use Posttwo\FunnyJunk\FunnyJunk;

class ReplaceDJ implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $board;
    protected $fj;
    protected $commentTree;
    protected $replaceDJNumber;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Board $board, FunnyJunk $fj, $number, $url = '/testcss2/84#84')
    {
        $this->board = $board;
        $this->fj = $fj;
        $this->commentTree = $this->board->getCommentTree($url);
        $this->replaceDJNumber = $number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //check if root comment has enough thumbs to continue
        if(!$this->commentTree[0]->thumb_val > 0){
            return true;
        }
        
        //pick random comment
        unset($this->commentTree[0]);
        $x = array_pluck($this->commentTree, 'username');
        $x = array_unique($x);
        $eligibleDJ = array_where($x, function ($value, $key) {
            return !in_array($value, $this->board->dj);
        });
        
        if(count($eligibleDJ) == 0){
            $this->board->postMessage("No eligible DJ was found.", false);
            return true;
        }
        $luckyCunt = $eligibleDJ[array_rand($eligibleDJ)];

        $this->board->setDJ($this->replaceDJNumber, $luckyCunt);
        echo "OK";
    }
}
