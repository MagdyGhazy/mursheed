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
    public function __construct($users , $subject , $body)
    {
        $this->users = $users;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        foreach ($this->users as $user )
        {
            $user->notify(new SendReminder($this->subject, $this->body));
        }

    }
}
