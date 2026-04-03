<?php

namespace App\Http\Controllers\Chat;

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

        // return response()->json([
        //     'message' => $message->message,
        //     'sender' => $message->sender,
        //     'receiver_id' => $message->receiver_id,
        //     'created_at' => $message->created_at->format('h:i A')
        // ]);

        return response()->json([
            'message' => $message->message,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            // 'created_at' => $message->created_at->toIso8601String(),
            //    'created_at' => $message->created_at,
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
        $messages = Message::with(['sender', 'receiver'])->where(function ($q) use ($id) {
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
                    'receiver_id' => $msg->receiver_id,
                    // 'created_at' => $msg->created_at,
                    // 'created_at' => $msg->created_at->toIso8601String(),
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
        Message::where('sender_id', $id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'ok']);
    }
}
