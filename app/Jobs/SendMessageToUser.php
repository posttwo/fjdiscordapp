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

class SendMessageToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    protected $message;
    protected $topic;
    public function __construct(FJUser $user, $message, $topic)
    {
        $this->user = $user;
        $this->message = $message;
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fj = new FunnyJunk();
        $fj = $fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));

        $pm = new PM();
        $pm->sendToUser($this->user->id, $this->user->username, $this->message, $this->topic);

    }
}
