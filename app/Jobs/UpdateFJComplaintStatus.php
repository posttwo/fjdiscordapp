<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\PM;
use Posttwo\FunnyJunk\User as FJUser;
use App\FunnyjunkUser;

class UpdateFJComplaintStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $id;
    protected $status;
    protected $text;

    public function __construct($id, $status, $text)
    {
        $this->id = $id;
        $this->status = $status;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fj = new FunnyJunk();
        $fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
        $res = $fj->setComplaintStatus($this->id, $this->status, $this->text);
    }
}
