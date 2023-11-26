<?php

namespace App\Events;

use App\Models\Message;
use App\Models\Replay;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var\App\Models\Message
     */
    public $message;
    public $replay;
    /**
     * Create a new event instance.
     * 
     * @param  \App\Models\Message $message
     * @param  \App\Models\Replay $replay
     * 
     * @return  void
     */
    public function __construct(Message $message , Replay $replay)
    {
        $this->message = $message;
        $this->replay = $replay;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        if ($this->replay) {
            return new Channel('Replay');
        }
    
        return new Channel('Message');
    }
    
}
