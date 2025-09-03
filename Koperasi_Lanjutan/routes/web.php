<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SimpananController;

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

// Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login_submit');
Route::get('/admin/index', [UserController::class, 'dashboard'])->name('admin.index');
Route::get('/admin/dashboard', [UserController::class, 'dashboardView'])->name('admin.dashboard.index');

// routes/web.php
// Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/anggota', [KelolaAnggotController::class, 'index'])->name('admin.anggota.index');
    Route::get('/anggota/create', [KelolaAnggotController::class, 'create'])->name('admin.anggota.create');
    Route::post('/anggota', [KelolaAnggotController::class, 'store'])->name('admin.anggota.store');
    Route::get('/anggota/{id}/edit', [KelolaAnggotController::class, 'edit'])->name('admin.anggota.edit');
    Route::put('/anggota/{id}', [KelolaAnggotController::class, 'update'])->name('admin.anggota.update');
    Route::delete('/anggota/{id}', [KelolaAnggotController::class, 'destroy'])->name('admin.anggota.destroy');
//});

//route laporan
//Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
//     Route::get('/laporan/simpanan', [LaporanController::class, 'simpanan'])->name('laporan.simpanan');
//     Route::get('/laporan/pinjaman', [LaporanController::class, 'pinjaman'])->name('laporan.pinjaman');
//     Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
//});

//route pinjaman
// Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('admin.pinjaman.index');
    // Route::get('/pinjaman/create', [App\Http\Controllers\PinjamanController::class, 'create'])->name('pinjaman.create');
    // Route::post('/pinjaman', [App\Http\Controllers\PinjamanController::class, 'store'])->name('pinjaman.store');
    // Route::get('/pinjaman/{id}/edit', [App\Http\Controllers\PinjamanController::class, 'edit'])->name('pinjaman.edit');
    // Route::put('/pinjaman/{id}', [App\Http\Controllers\PinjamanController::class, 'update'])->name('pinjaman.update');
    // Route::delete('/pinjaman/{id}', [App\Http\Controllers\PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
// });

//Route simpanan
//Route::middleware(['auth', 'role:pengurus'])->group(function () {
    // Route::get('/simpanan/sukarela', [App\Http\Controllers\SimpananController::class, 'indexSukarela'])->name('admin.layouts.simpanan.sukarela.index');
    // Route::get('/simpanan/wajib', [App\Http\Controllers\SimpananController::class, 'indexWajib'])->name('admin.layouts.simpanan.wajib.index');
//});

Route::get('/simpanan/sukarela', [SimpananController::class, 'indexSukarela'])->name('admin.simpanan.sukarela.index');
Route::get('/simpanan/wajib', [SimpananController::class, 'indexWajib'])->name('admin.simpanan.wajib.index');