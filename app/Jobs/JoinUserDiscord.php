<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use RestCord\DiscordClient;
use App\User;

class JoinUserDiscord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $discord = new DiscordClient(['token' => env('DISCORD_TOKEN'), 'throwOnRatelimit' => true]);
        $discord->guild->addGuildMember(['guild.id' => '137320242652119040', 'user.id' => $this->user->discord_id, 'access_token' => $this->user->token]);
    }
}
