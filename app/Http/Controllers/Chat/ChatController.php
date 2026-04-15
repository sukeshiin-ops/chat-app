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
        $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:51200',
            'receiver_id' => 'required|exists:users,id'
        ]);

        // IMPORTANT validation (dono empty nahi hone chahiye)
        if (!$request->message && !$request->hasFile('file')) {
            return response()->json(['error' => 'Message or file required'], 422);
        }

        $filePath = null;
        $type = 'text';
        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $filePath = $file->storeAs('chat_files', $filename, 'public');

            $mime = $file->getMimeType();

            $mime = $file->getMimeType();

            if (str_starts_with($mime, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $type = 'video';
            } else {
                $type = 'file';
            }
        }
        $message = Message::create([
            'message' => $request->message,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'file' => $filePath,
            'type' => $type
        ]);

        $message->load('sender');

        broadcast(new SendMessage($message))->toOthers();

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'file' => $message->file,
            'type' => $message->type,
            'receiver_id' => $message->receiver_id,
            'created_at' => now()->format('h:i A')
        ]);
    }
    // public function getMessages($id)
    // {
    //     $messages = Message::withTrashed()
    //         ->with(['sender', 'receiver'])->where(function ($q) use ($id) {
    //             $q->where('sender_id', Auth::id())
    //                 ->where('receiver_id', $id);
    //         })
    //         ->orWhere(function ($q) use ($id) {
    //             $q->where('sender_id', $id)
    //                 ->where('receiver_id', Auth::id());
    //         })
    //         ->orderBy('created_at', 'asc')
    //         ->get();

    //     return response()->json(
    //         $messages->map(function ($msg) {
    //             return [
    //                 'message' => $msg->message,
    //                 'sender_id' => $msg->sender_id,
    //                 'deleted_at' => $msg->deleted_at,
    //                 'receiver_id' => $msg->receiver_id,
    //                 'id' => $msg->id,
    //                 'delivered_at' => $msg->delivered_at,
    //                 'read_at' => $msg->read_at,
    //                 'file' => $msg->file,
    //                 'type' => $msg->type,
    //                 'created_at' => $msg->created_at->format('h:i A'),
    //                 'sender' => [
    //                     'id' => $msg->sender->id,
    //                     'name' => $msg->sender->name,
    //                     'profile_img' => $msg->sender->profile_img,
    //                 ]
    //             ];
    //         })
    //     );

    //     // return response()->json($messages);
    // }

    public function getMessages($id, Request $request)
    {
        $perPage = 20;

        $messages = Message::withTrashed()
            ->with(['sender', 'receiver'])
            ->where(function ($q) use ($id) {
                $q->where('sender_id', Auth::id())
                    ->where('receiver_id', $id);
            })
            ->orWhere(function ($q) use ($id) {
                $q->where('sender_id', $id)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $messages->getCollection()->map(function ($msg) {
                return [
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'receiver_id' => $msg->receiver_id,
                    'id' => $msg->id,
                    'delivered_at' => $msg->delivered_at,
                    'read_at' => $msg->read_at,
                    'deleted_at' => $msg->deleted_at,
                    'file' => $msg->file,
                    'type' => $msg->type,
                    'created_at' => $msg->created_at->format('h:i A'),

                    'sender' => [
                        'id' => $msg->sender->id,
                        'name' => $msg->sender->name,
                        'profile_img' => $msg->sender->profile_img,
                    ]
                ];
            }),
            'has_more' => $messages->hasMorePages(),
            'next_page' => $messages->currentPage() + 1
        ]);
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
