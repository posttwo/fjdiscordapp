<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Slack extends Model
{
    use Notifiable;
    
    protected $fillable = ['target', 'username', 'avatar', 'title', 'text', 'color'];
    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        if($this->target == 'dev')
            return env("DISCORD_DEV");
        if($this->target == 'mod-notify')
            return env("DISCORD_MODNOTIFY");
        if($this->target == 'mod-social')
            return env("DISCORD_MODSOCIAL");
        if($this->target == 'mod-help')
            return env("DISCORD_MODHELP");
    }
}
