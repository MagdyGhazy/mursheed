<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailForApprove extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    public function __construct()
    {


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

        if ($notifiable->status == 1) {
            $status = "Activated Successfully";
        } else {
            $status = "Cancelled please check your data you added before";
        }
        return (new MailMessage)
            ->subject('Account Status')
            ->line('Hello ' . $notifiable->name)
            ->line('Your acccount is ' . $status)
            ->line('Thank you for using Mursheed!');
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
