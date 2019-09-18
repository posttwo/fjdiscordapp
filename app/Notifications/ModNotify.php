<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class ModNotify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
    * Get the Slack representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return SlackMessage
    */
    public function toSlack($notifiable)
    {
        $n = $notifiable;
        if(!isset($n->footer))
            $n->footer = '';
        if(!isset($n->image_url))
            $n->image_url = '';
        $slack = (new SlackMessage)
                    ->from($n->username)
                    ->content($n->text)
                    ->image($n->avatar)
                    ->attachment(function ($attachment) use ($n) {
                        $attachment->title($n->title)
                            ->fields($n->embedFields)
                            ->footer($n->footer)
                            ->image($n->image_url);
                    });
        $slack->level = $n->color;
        return $slack;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
