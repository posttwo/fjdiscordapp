<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use RestCord\DiscordClient;
use App\User;

class VerifyUserDiscord implements ShouldQueue
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
        //dd($user);
        //$this->discord = new DiscordClient(['token' => env('DISCORD_SECRET'), 'throwOnRatelimit' => true]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $discord = new DiscordClient(['token' => env('DISCORD_TOKEN'), 'throwOnRatelimit' => true]);
        $discord->guild->addGuildMemberRole(['guild.id' => '137320242652119040', 'role.id' => '252455598338211842', 'user.id' => $this->user->discord_id]);
    }
}
