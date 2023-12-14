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
    protected $attachment;
    public function __construct($subject,$body,$attachment)
    {
        $this->body =$body;
        $this->subject  =$subject;
        $this->attachment  =$attachment;
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
        $mailMessage  = (new MailMessage)
            ->from('mursheed@visualinnvate.com')
            ->subject($this->subject)
            ->greeting('Hello ' . $notifiable->name)
            ->view('Email.ReminderEmail', ['body' => $this->body]);
        if ($this->attachment != null) {
            // Add attachment to the email
            $mailMessage->attach($this->attachment);
        }
        return $mailMessage;
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
