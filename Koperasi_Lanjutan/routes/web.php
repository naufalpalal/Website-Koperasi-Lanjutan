<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\Pengurus\SimpananController;
use App\Http\Controllers\User\SimpananWajibController;
use App\Http\Controllers\Pengurus\SimpananSukarelaController;
use App\Http\Controllers\Pengurus\PengurusSimpananWajibController;
use App\Http\Controllers\Pengurus\MasterSimpananWajibController;
use App\Http\Controllers\Pengurus\Tabungan2Controller;
use App\Http\Controllers\User\SimpananSukarelaAnggotaController;
use App\Http\Controllers\User\PengajuanSukarelaAnggotaController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\PasswordResetRequestController;

/* --- Auth --- */
Route::get('/', fn() => view('auth.login'));
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login_submit');
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* --- Pengurus --- */
Route::middleware(['auth','role:pengurus'])->prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/index', [UserController::class, 'dashboard'])->name('index');
    Route::get('/dashboard', [UserController::class, 'dashboardView'])->name('dashboard.index');

    Route::resource('anggota', KelolaAnggotController::class)->except(['show']);

    Route::prefix('reset-requests')->name('password.')->group(function () {
        Route::get('/', [PasswordResetRequestController::class,'index'])->name('index');
        Route::post('/{id}/approve', [PasswordResetRequestController::class,'approve'])->name('approve');
        Route::post('/{id}/reject', [PasswordResetRequestController::class,'reject'])->name('reject');
    });

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index');
        Route::put('/{id}', [PinjamanController::class, 'update'])->name('update');
    });

    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        Route::get('/', [Tabungan2Controller::class, 'index'])->name('index');
        Route::post('/{id}/approve', [Tabungan2Controller::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [Tabungan2Controller::class, 'reject'])->name('reject');
    });

    Route::prefix('simpanan-sukarela')->name('simpanan.sukarela.')->group(function () {
        Route::get('/', [SimpananSukarelaController::class, 'index'])->name('index');
        Route::get('/pending', [SimpananSukarelaController::class, 'indexPending'])->name('pending');
        Route::post('/{simpanan}/process', [SimpananSukarelaController::class, 'process'])->name('process');
        Route::post('/store', [SimpananSukarelaController::class, 'store'])->name('store');
        Route::post('/approve/{id}', [SimpananSukarelaController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [SimpananSukarelaController::class, 'reject'])->name('reject');
    });

    Route::prefix('simpanan-wajib')->name('simpanan.wajib.')->group(function () {
        Route::get('/', [PengurusSimpananWajibController::class, 'dashboard'])->name('dashboard');
        Route::get('/edit', [PengurusSimpananWajibController::class, 'index'])->name('index');
        Route::post('/generate', [PengurusSimpananWajibController::class, 'generate'])->name('generate');
        Route::post('/update-status', [PengurusSimpananWajibController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/riwayat/{id}', [PengurusSimpananWajibController::class, 'riwayat'])->name('riwayat');
        Route::get('/download', [PengurusSimpananWajibController::class, 'downloadExcel'])->name('download');
        Route::post('/kunci', [PengurusSimpananWajibController::class, 'lockPeriode'])->name('lock');

        Route::get('/master/edit', [MasterSimpananWajibController::class, 'editNominal'])->name('master.edit');
        Route::post('/master/update-nominal', [MasterSimpananWajibController::class, 'updateNominal'])->name('master.updateNominal');
    });
});

/* --- Anggota --- */
Route::middleware(['auth','role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboardUserView'])->name('dashboard.index');

    Route::get('/reset-password', [PasswordResetRequestController::class,'create'])->name('password.reset.form');
    Route::post('/reset-password', [PasswordResetRequestController::class,'requestReset'])->name('password.reset.request');

    Route::get('/simpanan-wajib', [SimpananWajibController::class, 'index'])->name('simpanan.wajib.index');

    Route::prefix('simpanan-sukarela')->name('simpanan.sukarela.')->group(function () {
        Route::get('/', [SimpananSukarelaAnggotaController::class, 'index'])->name('index');
        Route::get('/pengajuan', [PengajuanSukarelaAnggotaController::class, 'create'])->name('pengajuan');
        Route::post('/ajukan', [PengajuanSukarelaAnggotaController::class, 'store'])->name('store');
        Route::get('/riwayat', [SimpananSukarelaAnggotaController::class, 'riwayat'])->name('riwayat');
    });

    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        Route::resource('/', TabunganController::class)->parameters(['' => 'tabungan']);
    });
});
