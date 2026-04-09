<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return [
            new PresenceChannel('chat-channel.' . $this->message->sender_id),
        ];
    }

    public function broadcastAs()
    {
        return 'message.status';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'delivered_at' => $this->message->delivered_at,
            'read_at' => $this->message->read_at,
        ];
    }
}
