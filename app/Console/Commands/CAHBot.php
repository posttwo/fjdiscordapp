<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Discord\DiscordCommandClient;
use App\Cah;

class CAHBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cahbot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the CAH suggestions bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->discord = new DiscordCommandClient([
            'token' => env('DISCORD_TOKEN'),
            'description' => "CAH Bot"
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->discord->registerCommand('white', function ($message) {
            return $this->addCard('white', $message);
        });
        $this->discord->registerCommand('black', function ($message) {
            return $this->addCard('black', $message);
        });
        $this->discord->run();
    }

    protected function addCard($color, $message)
    {
        if($message->channel_id == 307268778310238208){
            $explode = explode('|', $message->content);
            $text = $explode[1];
            $cah = new Cah;
            $cah->color = $color;
            $cah->text = $text;
            $cah->discord_id = $message->author->id;
            $cah->save();
            $this->info("Added " . $color . " card ID: " . $cah->id);
            return "Added " . $color . " card:```" . $text . "```";
        }
    }
}
