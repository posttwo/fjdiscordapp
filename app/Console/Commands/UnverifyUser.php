<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class UnverifyUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:unverify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unverified User';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $discordId = $this->ask("Please enter the users Discord ID");
        $user = User::where('discord_id', $discordId)->firstOrFail();
        $fjusername = $user->fjuser->username;
        $this->line("Nickname: " . $user->nickname);
        $this->line("FJ Username: " . $fjusername);

        if(!$this->confirm("Are you sure you want to UNVERIFY this user?"))
        {
            $this->error("Unverification Command Cancelled");
            return false;
        }
        logger("Administrative user has deverified user ", ["id" => $user->id]);
        $user->fjuser()->delete();
        $this->info("Deleted assosciated FJUser");
        //syncPermissions
        $user->syncPermissions([]);
        $this->info("Removed all Permissions assosciated with user");

        if($this->confirm("Would you like to revoke note tokens for this user?"))
        {
            $this->info("Trying to delete note token from " . $fjusername);
            $json = json_decode(file_get_contents('http://fjmod.posttwo.pt/token/no' . env("NOTE_API") . "?mod=" . $fjusername), true);
            $this->info($json);
            $this->info("Deleted!");
        }
    }
}
