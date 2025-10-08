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

// Routes untuk Pengurus (semua dengan middleware role:pengurus)
Route::middleware(['auth', 'role:pengurus'])->prefix('pengurus')->name('pengurus.')->group(function () {
    // Dashboard pengurus
    Route::get('/index', [UserController::class, 'dashboard'])->name('index');
    Route::get('/dashboard', [UserController::class, 'dashboardView'])->name('dashboard.index');

    // Kelola Anggota
    Route::get('/anggota', [KelolaAnggotController::class, 'index'])->name('KelolaAnggota.index');
    Route::get('/anggota/create', [KelolaAnggotController::class, 'create'])->name('KelolaAnggota.create');
    Route::post('/anggota', [KelolaAnggotController::class, 'store'])->name('KelolaAnggota.store');
    Route::get('/anggota/{id}/edit', [KelolaAnggotController::class, 'edit'])->name('KelolaAnggota.edit');
    Route::put('/anggota/{id}', [KelolaAnggotController::class, 'update'])->name('KelolaAnggota.update');
    Route::delete('/anggota/{id}', [KelolaAnggotController::class, 'destroy'])->name('KelolaAnggota.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Pinjaman
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index');
        Route::put('/{id}', [PinjamanController::class, 'update'])->name('update');
    });

    // Tabungan pengurus
    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        Route::get('/', [Tabungan2Controller::class, 'index'])->name('index');
        Route::post('/{id}/approve', [Tabungan2Controller::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [Tabungan2Controller::class, 'reject'])->name('reject');
    });

    // Simpanan Sukarela (pengurus)
    Route::prefix('simpanan-sukarela')->name('simpanan.sukarela.')->group(function () {
        Route::get('/', [SimpananSukarelaController::class, 'index'])->name('index');
        Route::get('/pengajuan', [SimpananSukarelaController::class, 'create'])->name('pengajuan');
        Route::post('/generate', [SimpananSukarelaController::class, 'generate'])->name('generate');
        Route::post('/store', [SimpananSukarelaController::class, 'store'])->name('store');
        Route::post('/edit', [SimpananSukarelaController::class, 'update'])->name('update');
        Route::post('/approve/{id}', [SimpananSukarelaController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [SimpananSukarelaController::class, 'reject'])->name('reject');
        Route::get('/riwayat', [SimpananSukarelaController::class, 'riwayat'])->name('riwayat');

        // pending/process khusus
        Route::get('/pending', [SimpananSukarelaController::class, 'indexPending'])->name('kelola.pending');
        Route::post('/{simpanan}/process', [SimpananSukarelaController::class, 'process'])->name('process');
    });

    // Simpanan Wajib pengurus
    Route::prefix('simpanan-wajib')->name('simpanan.wajib_2.')->group(function () {
        Route::get('/', [PengurusSimpananWajibController::class, 'dashboard'])->name('dashboard');
        Route::get('/edit', [PengurusSimpananWajibController::class, 'index'])->name('index');
        Route::post('/generate', [PengurusSimpananWajibController::class, 'generate'])->name('generate');
        Route::post('/update-status', [PengurusSimpananWajibController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/riwayat/{id}', [PengurusSimpananWajibController::class, 'riwayat'])->name('riwayat');
        Route::get('/download', [PengurusSimpananWajibController::class, 'downloadExcel'])->name('download');
        Route::post('/kunci', [PengurusSimpananWajibController::class, 'lockPeriode'])->name('lock');

        Route::get('/master/edit', [MasterSimpananWajibController::class, 'editNominal'])->name('editMasterNominal');
        Route::post('/master/update-nominal', [MasterSimpananWajibController::class, 'updateNominal'])->name('updateNominal');
    });
});

// Routes untuk anggota (user)
Route::middleware(['auth', 'role:anggota'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboardUserView'])->name('dashboard.index');

    // Tabungan anggota (resource)
    Route::prefix('simpanan')->name('simpanan.')->group(function () {
        Route::resource('tabungan', TabunganController::class);
    });

    // Simpanan sukarela anggota
    Route::prefix('simpanan-sukarela-anggota')->name('simpanan.sukarela.')->group(function () {
        Route::get('/', [SimpananSukarelaAnggotaController::class, 'index'])->name('index');
        Route::post('/ajukan', [PengajuanSukarelaAnggotaController::class, 'store'])->name('store');
        Route::get('/pengajuan', [PengajuanSukarelaAnggotaController::class, 'create'])->name('pengajuan');
        Route::get('/riwayat', [SimpananSukarelaAnggotaController::class, 'riwayat'])->name('riwayat');
    });

    // Simpanan wajib anggota (view)
    Route::get('/anggota/simpanan', [SimpananWajibController::class, 'index'])->name('simpanan.wajib.index');
}

// Form forgot password (untuk anggota)
Route::get('/forgot-password', function () {
    return view('user.auth.reset-password');
})->middleware('guest')->name('password.request');

// Proses permintaan reset password
Route::post('/forgot-password', [PasswordResetRequestController::class, 'requestReset'])
    ->middleware('guest')
    ->name('password.email');

