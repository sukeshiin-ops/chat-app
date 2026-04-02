<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function register($data)
    {

        if (isset($data['profile_img'])) {
            $file = $data['profile_img'];

            $path = $file->store('profile_images', 'public');
            $data['profile_img'] = $path;
        }
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'profile_img' => $data['profile_img']
        ]);

        $token = $user->createToken('token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }


    public function login($data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $token = Auth::user()->createToken('token')->plainTextToken;
        }
        return [
            'token' => $token,
        ];
    }
}
