<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPasswordRessetToken extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private $otp)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Hello,' . $notifiable->email)
            ->line('you have request to reset your password')
            ->line('use this token to confirm and get to reseting your password : ' . $this->otp)
            ->line('do not share your token or password with others')
            ->line('Thank you for using our application!')
            ->line('Greetings from Mursheed Support Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
