<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::post('user-register', [AuthController::class, 'register']);
    Route::post('user-login', [AuthController::class, 'login']);

    Route::post('user-logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


    Route::middleware('auth:sanctum')->post('user-changepassword', [AuthController::class, 'changePassword']);
    Route::post('user-forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::post('user-verifyotp', [AuthController::class, 'verifyOtp']);
    Route::post('user-getuser', [AuthController::class, 'getUserList'])->middleware('auth:sanctum');

    Route::post('user-allmessage', [AuthController::class, 'messageFetch'])->middleware('auth:sanctum');



    Route::middleware('auth:sanctum')->get('/check-user', function (Request $request) {
        return $request->user();
    });
});
