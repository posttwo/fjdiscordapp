<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Slack extends Model
{
    use Notifiable;

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return "https://discordapp.com/api/webhooks/334097861081825282/Fu6sm10Ry4NLM-u2YJ72UMxjlwCTQY4A7ORbI9mAPoF1BnxWEDLSIcsDi3hibdpfHCw2/slack";
    }
}
