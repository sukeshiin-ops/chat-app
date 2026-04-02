<?php

use App\Http\Controllers\AuthController\AuthController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\SendMessageController;
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\NotAccessIfLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::view('signin', 'auth.signIn')->name('sign-in.page');
Route::view('signup', 'auth.signUp')->name('sign-up.page');
Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout.user');

Route::middleware('auth')->group(function () {
    Route::view('chat-app', 'components.layouts.master')->name('chat.home.page');
    Route::get('user/profile', [ProfileController::class, 'view'])->name('user.profile.page');
    Route::post('user/profile/store/{id}', [ProfileController::class, 'store'])->name('user.profile.store');


    //search bar
    Route::get('/search-users', function (Request $request) {

        $query = $request->search;

        $users = User::where('id', '!=', Auth::id())
            ->where('name', 'LIKE', "%$query%")
            ->get();

        return response()->json($users);
    });


    // //send message
    // Route::post('send-message', [SendMessageController::class,'storeSendMessage'])

    Route::post('/send-message', [ChatController::class, 'send']);


    Route::get('/get-messages/{id}', [ChatController::class, 'getMessages']);
});
