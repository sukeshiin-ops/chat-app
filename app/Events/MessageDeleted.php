<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcastNow
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
            new PresenceChannel('chat-channel.' . $this->message->receiver_id),
             new PresenceChannel('chat-channel.' . $this->message->sender_id),
        ];
    }


    public function broadcastAs()
    {
        return 'message.deleted';
    }

public function broadcastWith()
    {
        //  get LAST message INCLUDING deleted ones
        $lastMessage = Message::withTrashed()
            ->where(function ($q) {
                $q->where('sender_id', $this->message->sender_id)
                  ->where('receiver_id', $this->message->receiver_id);
            })
            ->orWhere(function ($q) {
                $q->where('sender_id', $this->message->receiver_id)
                  ->where('receiver_id', $this->message->sender_id);
            })
            ->latest()
            ->first();

        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'message' => $this->message->message,

            //  CRITICAL FIX
            'is_last_message' => $lastMessage && $lastMessage->id == $this->message->id,
        ];
    }
}
