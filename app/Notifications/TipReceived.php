<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TipReceived extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($recipient, $tipper_location)
    {
        $this->username = $recipient->username;
        $this->location = $tipper_location;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $recipient_username = $this->username;
        $sender_location = $this->location;

        if($sender_location){
            return (new MailMessage)
                    ->greeting('Hello! '.$recipient_username)
                    ->line('You received a brand new donation from a user located in '.$sender_location)
                    ->action('View Tip', url('/'.$recipient_username));
        }else{
            return (new MailMessage)
                    ->greeting('Hello, '.$recipient_username)
                    ->line('You received a brand new donation!')
                    ->action('View Tip', url('/'.$recipient_username));
        }

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
