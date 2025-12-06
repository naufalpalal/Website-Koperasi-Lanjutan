<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ==================================
// Halaman Utama & Logout
// ==================================

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ==================================
// Include Routes dari File Terpisah
// ==================================

// Auth Routes (login, register, forgot-password) - Guest Only
require __DIR__ . '/auth.php';

// User/Anggota Routes - Authenticated Users Only
Route::middleware(['auth:web'])->group(function () {
    require __DIR__ . '/user.php';
});

// Pengurus Routes - Authenticated Pengurus Only
Route::middleware(['auth:pengurus'])->group(function () {
    require __DIR__ . '/pengurus.php';
});