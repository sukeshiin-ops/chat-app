<?php

namespace App\Http\Controllers\Chat;

use App\Events\MessageDeleted;
use App\Events\MessageStatusUpdated;
use App\Events\SendMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function send(Request $request)
    {
        $message = Message::create([
            'message' => $request->message,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'read_at' => null
        ]);

        $message->load('sender');

        broadcast(new SendMessage($message));

        return response()->json([
            'message' => $message->message,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'id' => $message->id,
            'delivered_at' => $message->delivered_at,
            'read_at' => $message->read_at,
            'created_at' => $message->created_at->format('h:i A'),
            'sender' => [
                'id' => $message->sender->id,
                'name' => $message->sender->name,
                'profile_img' => $message->sender->profile_img,
            ]
        ]);

        // return response()->json($message);
    }


    public function getMessages($id)
    {
        $messages = Message::withTrashed()
            ->with(['sender', 'receiver'])->where(function ($q) use ($id) {
                $q->where('sender_id', Auth::id())
                    ->where('receiver_id', $id);
            })
            ->orWhere(function ($q) use ($id) {
                $q->where('sender_id', $id)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(
            $messages->map(function ($msg) {
                return [
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'deleted_at' => $msg->deleted_at,
                    'receiver_id' => $msg->receiver_id,
                    'id' => $msg->id,
                    'delivered_at' => $msg->delivered_at,
                    'read_at' => $msg->read_at,
                    'created_at' => $msg->created_at->format('h:i A'),
                    'sender' => [
                        'id' => $msg->sender->id,
                        'name' => $msg->sender->name,
                        'profile_img' => $msg->sender->profile_img,
                    ]
                ];
            })
        );

        // return response()->json($messages);
    }


    public function markAsRead($id)
    {
        $messages = Message::where('sender_id', $id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->get();

        foreach ($messages as $msg) {
            $msg->update(['read_at' => now()]);

            broadcast(new MessageStatusUpdated($msg));
        }

        return response()->json(['status' => 'ok']);
    }


    public function markAsDelivered($senderId)
    {
        $messages = Message::where('sender_id', $senderId)
            ->where('receiver_id', Auth::id())
            ->whereNull('delivered_at')
            ->get();

        foreach ($messages as $msg) {
            $msg->update(['delivered_at' => now()]);

            broadcast(new MessageStatusUpdated($msg));
        }

        return response()->json(['status' => 'delivered']);
    }


    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);

        //  Only sender can delete
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->delete(); // soft delete

        broadcast(new MessageDeleted($message))->toOthers();

        return response()->json([
            'status' => 'deleted',
            'id' => $message->id
        ]);
    }
}
