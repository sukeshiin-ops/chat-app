<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $message;
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('chat-channel.' . $this->message->receiver_id),
        ];
    }



    public function broadcastAs()
    {
        return 'message.sent';
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'message' => $this->message->message,
    //         'sender_id' => $this->message->sender_id,
    //     ];
    // }


    public function broadcastWith()
    {
        return [
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->name,
                'profile_img' => $this->message->sender->profile_img,
            ]
        ];
    }
}
