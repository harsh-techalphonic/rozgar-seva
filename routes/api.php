<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/verify-otp', 'verify_otp')->name('verify_otp');
});
Route::prefix('profile')->middleware([AuthMiddleware::class])->controller(ProfileController::class)->group(function () {
    Route::post('/add', 'add_profile')->name('add_profile');
});
