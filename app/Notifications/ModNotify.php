<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class ModNotify extends Notification
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
        return (new SlackMessage)
                    ->warning()
                    ->from($n->username)
                    ->image($n->avatar)
                    ->content($n->description)
                    ->attachment(function ($attachment) use ($n) {
                        $attachment->title($n->title)
                            ->fields([
                                    'Username' => $n->username,
                                    'Text' => $n->text,
                                    'ID' => $n->id,
                                    'Date' => $n->date,
                                ]);
                    });
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
