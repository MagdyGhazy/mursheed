<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $body;
    protected $subject;
    public function __construct($subject,$body)
    {
        $this->body =$body;
        $this->subject  =$subject;
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
            ->from('mursheed@visualinnvate.com')
            ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->name)
//            ->line($this->body)
            ->html($this->body);

//        $email = (new MailMessage)
//            ->from('mursheed@visualinnovate.com')
//            ->subject($this->subject)
//            ->greeting('Hello ' . $notifiable->name);
//
//        // Add HTML content with images
//        $email->line(function ($message) {
//            $message->html($this->body, ['mime' => 'text/html']);
//        });
//
//        // You can also add a plain text version of your email
////        $email->line(strip_tags($this->body));
//
//        return $email;
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
