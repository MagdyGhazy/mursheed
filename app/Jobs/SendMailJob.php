<?php

namespace App\Jobs;

use App\Notifications\SendReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $users;
    protected $subject;
    protected $body;
    protected $attachment;
    public function __construct($users , $subject , $body,$attachment)
    {
        $this->users = $users;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        foreach ($this->users as $user )
        {
            $user->notify(new SendReminder($this->subject, $this->body, $this->attachment));
        }

    }
}
