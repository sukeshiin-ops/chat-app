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
        ]);


           $message->load('sender');

        broadcast(new SendMessage($message))->toOthers();

        return response()->json($message);
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

        return response()->json($messages);
    }
}
