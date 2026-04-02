<?php

namespace App\Http\Controllers\AuthController;

use App\Http\Controllers\Controller;
use App\Service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'profile_img' => 'required|image|mimes:jpeg,png,jpg,gif,avif,webp'
        ]);


        $data = $this->authService->register($request->all());

        if (!$data) return redirect()->route('sign-up.page')->with('error', 'User Register Fail!!');
        return redirect()->route('sign-in.page')->with('success', 'User Register Successfully!!');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required  ',
        ]);

        $data = $this->authService->login($request->all());

        if (!$data) return redirect()->route('sign-in.page')->with('error', 'User Login Fail!!');
        return redirect()->route('chat.home.page')->with('success', 'User Login Successfully!!');
    }

    public function logout()
    {
        Auth::logout();

        session()->regenerateToken();
        session()->invalidate();


        return redirect()->route('sign-in.page')->with('success', ' Logout Successfully!!');
    }
}
