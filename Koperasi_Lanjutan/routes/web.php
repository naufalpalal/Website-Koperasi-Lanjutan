<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\Admin\SimpananController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

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


Route::get('/user/index', [UserController::class, 'dashboardUserView'])->name('user.dashboard.index');
//Route::get('/user/dashboard', [UserController::class, 'dashboardUser'])->name('user.index');

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

Route::prefix('admin/simpanan')->middleware('auth')->group(function () {
    Route::get('transactions', [SimpananController::class, 'index'])->name('admin.simpanan.index');
    Route::get('transactions/{transaction}/edit', [SimpananController::class, 'edit'])->name('admin.simpanan.edit');
    Route::put('transactions/{transaction}', [SimpananController::class, 'update'])->name('admin.simpanan.update');
    Route::post('generate', [SimpananController::class, 'generate'])->name('admin.simpanan.generate');
});

