<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\pengurus\SimpananController;
use App\Http\Controllers\User\SimpananWajibController;
use App\Http\Controllers\Pengurus\SimpananSukarelaController;
use App\Http\Controllers\Pengurus\PengurusSimpananWajibController;
use App\Http\Controllers\Pengurus\MasterSimpananWajibController;
use App\Http\Controllers\User\SimpananSukarelaAnggotaController;



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

// Dashboard pengurus
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/pengurus/index', [UserController::class, 'dashboard'])->name('pengurus.index');
    Route::get('/pengurus/dashboard', [UserController::class, 'dashboardView'])->name('pengurus.dashboard.index');
});
// Kelola Anggota (Pengurus)
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/anggota', [KelolaAnggotController::class, 'index'])->name('pengurus.KelolaAnggota.index');
    Route::get('/anggota/create', [KelolaAnggotController::class, 'create'])->name('pengurus.KelolaAnggota.create');
    Route::post('/anggota', [KelolaAnggotController::class, 'store'])->name('pengurus.KelolaAnggota.store');
    Route::get('/anggota/{id}/edit', [KelolaAnggotController::class, 'edit'])->name('pengurus.KelolaAnggota.edit');
    Route::put('/anggota/{id}', [KelolaAnggotController::class, 'update'])->name('pengurus.KelolaAnggota.update');
    Route::delete('/anggota/{id}', [KelolaAnggotController::class, 'destroy'])->name('pengurus.KelolaAnggota.destroy');
});

// Laporan (Pengurus)
Route::get('/laporan', [LaporanController::class, 'index'])->name('pengurus.laporan.index');

// Pinjaman (Pengurus)
Route::prefix('pengurus/pinjaman')->middleware(['auth', 'role:pengurus'])->name('pengurus.pinjaman.')->group(function () {
    Route::get('/', [PinjamanController::class, 'index'])->name('index');
    Route::put('/{id}', [PinjamanController::class, 'update'])->name('update');
});

// Simpanan (pengurus)
// Route::prefix('pengurus/simpanan')->middleware(['auth', 'role:pengurus'])->group(function () {
//     Route::get('transactions', [SimpananController::class, 'index'])->name('pengurus.simpanan.index');
//     Route::get('transactions/{transaction}/edit', [SimpananController::class, 'edit'])->name('pengurus.simpanan.edit');
//     Route::put('transactions/{transaction}', [SimpananController::class, 'update'])->name('pengurus.simpanan.update');
//     Route::post('generate', [SimpananController::class, 'generate'])->name('pengurus.simpanan.generate');
// });

// Simpanan Sukarela - Pengurus
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/pengurus/simpanan/sukarela/pending', [SimpananSukarelaController::class, 'indexPending'])->name('pengurus.simpanan.kelola.pending');
    Route::post('/pengurus/simpanan/sukarela/{simpanan}/process', [SimpananSukarelaController::class, 'process'])->name('pengurus.simpanan.sukarela.process');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/dasboard', [UserController::class, 'dashboardUserView'])->name('user.dashboard.index');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/simpanan/sukarela', [SimpananSukarelaController::class, 'index'])
//         ->name('user.simpanan.sukarela.index');
//     Route::put('/simpanan/sukarela/{id}', [SimpananSukarelaController::class, 'update'])
//         ->name('simpanan.sukarela.update');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/anggota/simpanan', [SimpananWajibController::class, 'index'])->name('user.simpanan.wajib.index');
});

// Simpanan Wajib - Pengurus
// Route::middleware(['auth', 'role:pengurus'])->group(function () {
//     Route::get('/pengurus/nominal_wajib', [SimpananWajibController::class, 'index'])->name('pengurus.nominal_wajib.index');
//     Route::get('/pengurus/nominal_wajib/edit', [SimpananWajibController::class, 'editNominalWajib'])->name('pengurus.nominal_wajib.edit');
//     Route::post('/pengurus/nominal_wajib/update', [SimpananWajibController::class, 'updateNominalWajib'])->name('pengurus.nominal_wajib.update');
// });



// Simpanan Sukarela - Pengurus;;

Route::prefix('simpanan-sukarela')->group(function () {
    Route::get('/', [SimpananSukarelaController::class, 'index'])->name('pengurus.simpanan.sukarela.index');
    Route::get('/create', [SimpananSukarelaController::class, 'create'])->name('pengurus.simpanan.sukarela.create');
    Route::post('/store', [SimpananSukarelaController::class, 'store'])->name('pengurus.simpanan.sukarela.store');
    Route::post('/edit', [SimpananSukarelaController::class, 'update'])->name('pengurus.simpanan.sukarela.update');
});

// Simpanan Wajib - Pengurus
Route::prefix('pengurus/simpanan-wajib')->group(function () {
    Route::get('/', [PengurusSimpananWajibController::class, 'index'])->name('pengurus.simpanan.wajib_2.index');
    Route::post('/generate', [PengurusSimpananWajibController::class, 'generate'])->name('pengurus.simpanan.wajib_2.generate');
    Route::post('/update-status', [PengurusSimpananWajibController::class, 'updateStatus'])->name('pengurus.simpanan.wajib_2.updateStatus');
    Route::get('/riwayat/{id}', [PengurusSimpananWajibController::class, 'riwayat'])->name('pengurus.simpanan.wajib_2.riwayat');
    Route::get('/download', [PengurusSimpananWajibController::class, 'downloadExcel'])->name('pengurus.simpanan.wajib_2.download');
    Route::post('/pengurus/simpanan-wajib/kunci', [PengurusSimpananWajibController::class, 'lockPeriode'])->name('pengurus.simpanan.wajib_2.lock');
    //Route::delete('/pengurus/simpanan-wajib/{id}', [PengurusSimpananWajibController::class, 'destroy'])->name('pengurus.simpanan.wajib_2.destroy');

    // Pindah ke controller baru
    Route::get('/master/edit', [MasterSimpananWajibController::class, 'editNominal'])->name('pengurus.simpanan.wajib_2.edit');
    Route::post('/master/update-nominal', [MasterSimpananWajibController::class, 'updateNominal'])->name('pengurus.simpanan.wajib_2.updateNominal');
});


// Simpanan Sukarela - Anggota
Route::prefix('simpanan-sukarela-anggota')->group(function () {
    Route::get('/', [SimpananSukarelaAnggotaController::class, 'index'])->name('user.simpanan.sukarela.index');
    Route::get('/riwayat', [SimpananSukarelaAnggotaController::class, 'riwayat'])->name('user.simpanan.sukarela.riwayat');
});