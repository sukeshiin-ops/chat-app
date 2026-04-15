<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\MessageFetchRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\verifyOptRequest;
use App\Http\Resources\LoginResource;
use App\Models\Message;
use App\Models\Opt;
use App\Models\Otp;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Reverb\Loggers\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        if ($request->hasFile('image')) {

            if ($user->profile_img && Storage::disk('public')->exists($user->profile_img)) {
                Storage::disk('public')->delete($user->profile_img);
            }

            $path = $request->file('image')->store('images', 'public');

            $user->profile_img = $path;

            $user->save();
        }

        return response()->json([
            'status' => 'true',
            'message' => "User Register Successfully!!",
            'user' => $user
        ]);
    }


    public function login(LoginRequest $register)
    {
        $data = $register->validated();

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {

            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'status' => 'true',
                'message' => "User Login Successfully!!",
                'user' => new LoginResource($user), // this file located in Resource/LoginResource.php
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => 'false',
            'message' => "User Login Fail!!",
        ]);
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => "User Logout Successfully!!",
        ]);
    }

    public function changePassword(Request $request)
    {

        $data = $request->validate([
            'password' =>  'required|min:3',
            'new_password' =>  'required|min:3',
        ]);

        $user = $request->user();


        if (!Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => "Change Password Fail!!",
            ]);
        } else {
            $user->update([
                'password' => Hash::make($data['new_password'])
            ]);

            return response()->json([
                'status' => true,
                'message' => "Change Password Successfully!!",
            ]);
        }
    }


    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);
        DB::beginTransaction();
        try {

            $user =  User::where('email', $data['email'])->first();

            if ($user) {

                $otp = random_int(100000, 999999);

                Otp::updateOrCreate(
                    ['user_id' => $user->id,],
                    ['otp' => $otp]
                );

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => "Otp sent  Successfully!!",
                    'otp' => $otp
                ]);
            }
        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => "Otp Sent Fails!!",
            ]);
        }
    }


    public function verifyOtp(verifyOptRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {


            $user =  User::where('email', $data['email'])->first();

            if ($user) {

                $userOtp =  Otp::where('user_id', $user->id)->first();
                if ($userOtp->otp == $data['otp']) {

                    $new_password = random_int(100000, 999999);

                    User::where('email', $data['email'])->update([
                        'password' => Hash::make($new_password)
                    ]);

                    DB::commit();

                    $userOtp->delete();

                    return response()->json([
                        'status' => true,
                        'new_password' => $new_password,
                        'message' => "User verify Successfully!!",
                    ]);
                }


                return response()->json([
                    'status' => false,
                    'aja' => $userOtp,
                    'message' => "User Opt verify Fail!!",
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => "Otp verify Fails Due to Internal issue !!",
            ]);
        }
    }


    public function getUserList(Request $request)
    {
        $activeUser = $request->user();

        $users = User::where('id', '!=', $activeUser->id)
            ->get()
            ->map(function ($user) use ($activeUser) {

                $lastMessage = Message::withTrashed()
                    ->where(function ($q) use ($user, $activeUser) {
                        $q->where('sender_id', $activeUser->id)
                            ->where('receiver_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($user, $activeUser) {
                        $q->where('sender_id', $user->id)
                            ->where('receiver_id', $activeUser->id);
                    })
                    ->latest()
                    ->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_img' => $user->profile_img,

                    'last_message' => $lastMessage
                        ? ($lastMessage->deleted_at
                            ? 'This message was deleted'
                            : $lastMessage->message)
                        : null,

                    'last_time' => $lastMessage?->created_at?->format('Y-m-d H:i:s'),

                    'unread_count' => Message::where('sender_id', $user->id)
                        ->where('receiver_id', $activeUser->id)
                        ->whereNull('read_at')
                        ->count(),
                ];
            })
            ->sortByDesc('last_time')
            ->values();

        return response()->json([
            'status' => true,
            'message' => 'User list fetched successfully',
            'data' => $users,
        ]);
    }
    public function messageFetch(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'page' => 'nullable|integer|min:1'
        ]);

        $authId = Auth::id();
        $receiverId = $request->receiver_id;

        $messages = Message::with(['sender:id,name,profile_img', 'receiver:id,name,profile_img'])
            ->where(function ($q) use ($authId, $receiverId) {
                $q->where('sender_id', $authId)
                    ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($authId, $receiverId) {
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'desc')   // IMPORTANT: latest first for pagination
            ->paginate(20);                   //  PAGINATION ADDED

        return response()->json([
            'status' => true,
            'message' => "Messages fetched successfully",
            'data' => collect($messages->items())->map(function ($msg) use ($authId) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'receiver_id' => $msg->receiver_id,

                    'is_seen' => !is_null($msg->read_at),
                    'is_delivered' => !is_null($msg->delivered_at),
                    'is_deleted' => !is_null($msg->deleted_at),

                    'read_at' => $msg->read_at,
                    'delivered_at' => $msg->delivered_at,

                    'file_url' => $msg->file ? asset('storage/' . $msg->file) : null,
                    'file_name' => $msg->file ? basename($msg->file) : null,

                    'created_at' => $msg->created_at->format('h:i A'),

                    'is_me' => $msg->sender_id == $authId,

                    'sender' => $msg->sender,
                    'receiver' => $msg->receiver,
                ];
            }),


            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
                'has_more' => $messages->hasMorePages(),
            ]
        ]);
    }
}
