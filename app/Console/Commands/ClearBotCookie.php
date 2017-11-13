<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearBotCookie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears cache for FJ cookies';

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
        $current = \Cache::get('fjmodbot' . "-cookie");        
        $this->line("Cache Value: " . $current);

        if(!$this->confirm("Are you sure you want to clear cache?"))
        {
            $this->error("Clear Cookie Cache Command Cancelled");
            return false;
        }
        logger("Administrative user has cleared cookie cache ");
        $r1 = \Cache::forget('fjmodbot' . "-cookie");
        $r2 = \Cache::forget('activecookie-cookie-bot');        
        $this->info("Cleared cache " . $r1 . $r2);
    }
}
