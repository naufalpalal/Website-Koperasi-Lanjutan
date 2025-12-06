<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PengurusController;
use Illuminate\Support\Facades\Route;

// Guest Access Only (Logout If Authenticated)
Route::middleware(['logout.if.authenticated'])->group(function () {
    // User Login
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login_submit');

    // Pengurus Login
    Route::get('/pengurus/login', [PengurusController::class, 'showLoginForm'])->name('pengurus.login');
    Route::post('/pengurus/login', [PengurusController::class, 'login']);

    // Register
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot-password');
    Route::post('/forgot-password/send', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password.send');
    Route::get('/forgot-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('forgot-password.form');
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset');
});

// Pengurus Logout (authenticated only)
Route::post('/pengurus/logout', [PengurusController::class, 'logout'])->name('pengurus.logout');

