<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\Admin\SimpananController;
use App\Http\Controllers\SimpananSukarelaController;
use App\Http\Controllers\SimpananWajibController;

Route::get('/', function () {
    return view('auth.login');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Dashboard umum
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile user (auth wajib)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Login
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login_submit');

// Dashboard Admin
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/admin/index', [UserController::class, 'dashboard'])->name('admin.index');
    Route::get('/admin/dashboard', [UserController::class, 'dashboardView'])->name('admin.dashboard.index');
});
// Kelola Anggota (Pengurus)
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/anggota', [KelolaAnggotController::class, 'index'])->name('admin.anggota.index');
    Route::get('/anggota/create', [KelolaAnggotController::class, 'create'])->name('admin.anggota.create');
    Route::post('/anggota', [KelolaAnggotController::class, 'store'])->name('admin.anggota.store');
    Route::get('/anggota/{id}/edit', [KelolaAnggotController::class, 'edit'])->name('admin.anggota.edit');
    Route::put('/anggota/{id}', [KelolaAnggotController::class, 'update'])->name('admin.anggota.update');
    Route::delete('/anggota/{id}', [KelolaAnggotController::class, 'destroy'])->name('admin.anggota.destroy');
});

// Laporan (Pengurus)
Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');

// Pinjaman (Pengurus)
Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('admin.pinjaman.index');

// Simpanan (Admin)
Route::prefix('admin/simpanan')->middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('transactions', [SimpananController::class, 'index'])->name('admin.simpanan.index');
    Route::get('transactions/{transaction}/edit', [SimpananController::class, 'edit'])->name('admin.simpanan.edit');
    Route::put('transactions/{transaction}', [SimpananController::class, 'update'])->name('admin.simpanan.update');
    Route::post('generate', [SimpananController::class, 'generate'])->name('admin.simpanan.generate');
});

// Simpanan Sukarela - Pengurus
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/admin/simpanan/sukarela/pending', [SimpananSukarelaController::class, 'indexPending'])->name('admin.simpanan.kelola.pending');
    Route::post('/admin/simpanan/sukarela/{simpanan}/process', [SimpananSukarelaController::class, 'process'])->name('admin.simpanan.sukarela.process');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/dasboard', [UserController::class, 'dashboardUserView'])->name('user.dashboard.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/simpanan/sukarela', [SimpananSukarelaController::class, 'index'])
        ->name('user.simpanan.sukarela.index');
    Route::put('/simpanan/sukarela/{id}', [SimpananSukarelaController::class, 'update'])
        ->name('simpanan.sukarela.update');
});

// Simpanan Wajib - Pengurus
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/admin/nominal_wajib', [SimpananWajibController::class, 'index'])->name('admin.nominal_wajib.index');
    Route::get('/admin/nominal_wajib/edit', [SimpananWajibController::class, 'editNominalWajib'])->name('admin.nominal_wajib.edit');
    Route::post('/admin/nominal_wajib/update', [SimpananWajibController::class, 'updateNominalWajib'])->name('admin.nominal_wajib.update');
});