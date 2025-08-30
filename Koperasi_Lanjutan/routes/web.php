<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\KelolaAnggotController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//require _DIR_.'/auth.php';

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login_submit');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// routes/web.php
// Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/anggota', [App\Http\Controllers\KelolaAnggotController::class, 'index'])->name('admin.layouts.anggota.index');
//     Route::get('/anggota/create', [App\Http\Controllers\AnggotaController::class, 'create'])->name('anggota.create');
//     Route::post('/anggota', [App\Http\Controllers\AnggotaController::class, 'store'])->name('anggota.store');
//     Route::get('/anggota/{id}/edit', [App\Http\Controllers\AnggotaController::class, 'edit'])->name('anggota.edit');
//     Route::put('/anggota/{id}', [App\Http\Controllers\AnggotaController::class, 'update'])->name('anggota.update');
//     Route::delete('/anggota/{id}', [App\Http\Controllers\AnggotaController::class, 'destroy'])->name('anggota.destroy');
// });
// });