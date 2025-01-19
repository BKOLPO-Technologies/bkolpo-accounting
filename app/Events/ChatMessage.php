<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessage implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $user;
    public $message;
    public $time;

    public function __construct($user, $message)
    {
        $this->user = $user;
        $this->message = $message;
        $this->time = now()->format('h:i A');
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }
}
