<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\MessageFetchRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\verifyOptRequest;
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
                'user' => $user,
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

    public function getUserList()
    {
        $user = User::get();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "User data fails to fetch!!",
            ]);
        }

        return response()->json([
            'status' => true,
            'all-user' => $user ? $user : "No User Found!!",
            'message' => "User verify Successfully!!",
        ]);
    }


    public function messageFetch(MessageFetchRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "User not found!!",
            ]);
        }

        $userMessage = Message::where('sender_id', $user->id)
            ->select('id', 'message', 'created_at')
            ->get();

        if ($userMessage->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => "No messages found!!",
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $userMessage,
            'message' => "User Message Fetch Successfully!!",
        ]);
    }
}
