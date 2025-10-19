<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\pengurus\SimpananController;
use App\Http\Controllers\User\SimpananWajibController;
use App\Http\Controllers\Pengurus\SimpananSukarelaController;
use App\Http\Controllers\Pengurus\PengurusSimpananWajibController;
use App\Http\Controllers\Pengurus\MasterSimpananWajibController;
use App\Http\Controllers\Pengurus\Tabungan2Controller;
use App\Http\Controllers\User\SimpananSukarelaAnggotaController;
use App\Http\Controllers\User\PengajuanSukarelaAnggotaController;
use App\Http\Controllers\TabunganController;
use app\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\filedokumen;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\IdentitasKoperasiController;




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
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::put('/profile-combined', [ProfileController::class, 'updateCombined'])->name('profile.update-combined');
   Route::get('/identitas-koperasi', [IdentitasKoperasiController::class, 'edit'])->name('settings.edit');
    Route::put('/identitas-koperasi', [IdentitasKoperasiController::class, 'update'])->name('settings.update');
});
// Login
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login_submit');
// Dashboard pengurus
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/pengurus/index', [UserController::class, 'dashboard'])->name('pengurus.index');
    Route::get('/pengurus/dashboard', [UserController::class, 'dashboardView'])->name('pengurus.dashboard.index');
});
// Kelola Anggota (Pengurus)=-
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/anggota', [KelolaAnggotController::class, 'index'])->name('pengurus.KelolaAnggota.index');
    Route::get('/anggota/create', [KelolaAnggotController::class, 'create'])->name('pengurus.KelolaAnggota.create');
    Route::get('/anggota/verifikasi', [KelolaAnggotController::class, 'verifikasi'])->name('pengurus.KelolaAnggota.verifikasi');
    Route::post('/anggota/{id}/approve', [KelolaAnggotController::class, 'approve'])->name('pengurus.KelolaAnggota.approve');
    Route::post('/anggota/{id}/reject', [KelolaAnggotController::class, 'reject'])->name('pengurus.KelolaAnggota.reject');
    Route::post('/anggota', [KelolaAnggotController::class, 'store'])->name('pengurus.KelolaAnggota.store');
    Route::get('/anggota/{id}/edit', [KelolaAnggotController::class, 'edit'])->name('pengurus.KelolaAnggota.edit');
    Route::put('/anggota/{id}', [KelolaAnggotController::class, 'update'])->name('pengurus.KelolaAnggota.update');
    Route::delete('/anggota/{id}', [KelolaAnggotController::class, 'destroy'])->name('pengurus.KelolaAnggota.destroy');
    Route::get('/dokumen/lihat/{userId}/{jenis}', [App\Http\Controllers\filedokumen::class, 'lihatDokumen'])
        ->name('dokumen.lihat');

});

// Laporan (Pengurus)
Route::get('/laporan', [LaporanController::class, 'index'])->name('pengurus.laporan.index');

// Pinjaman (Pengurus)
Route::middleware(['auth', 'role:pengurus'])->prefix('pengurus/pinjaman')->name('pengurus.pinjaman.')->group(function () {
    Route::get('/', [PinjamanController::class, 'index'])->name('index');
    Route::put('/{id}', [PinjamanController::class, 'update'])->name('update');
});

// Pinjaman (Anggota)
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->group(function () {
    Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('user.pinjaman.create');
    Route::post('/pinjaman/store', [PinjamanController::class, 'store'])->name('user.pinjaman.store');
    Route::get('/pinjaman/download/{id}', [PinjamanController::class, 'download'])->name('user.pinjaman.download');
    Route::get('/pinjaman/upload/{id}', [PinjamanController::class, 'uploadForm'])->name('user.pinjaman.uploadForm');

    Route::post('/pinjaman/upload/{id}', [PinjamanController::class, 'upload'])->name('user.pinjaman.upload');

    Route::delete('/pinjaman/dokumen/{id}', [PinjamanController::class, 'hapusDokumen'])->name('user.pinjaman.hapusDokumen');
});

// Tabungan Pengurus
Route::middleware(['auth', 'role:pengurus'])->prefix('pengurus/tabungan')->as('pengurus.tabungan.')->group(function () {
    Route::get('/', [Tabungan2Controller::class, 'index'])->name('index');
    Route::post('/{id}/approve', [Tabungan2Controller::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [Tabungan2Controller::class, 'reject'])->name('reject');
});

// Tabungan (anggota)
Route::middleware(['auth', 'role:anggota'])->prefix('user/simpanan')->as('user.simpanan.')->group(function () {
    Route::resource('tabungan', TabunganController::class);
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
    Route::get('/pengajuan', [SimpananSukarelaController::class, 'create'])->name('pengurus.simpanan.sukarela.pengajuan');
    Route::post('/generate', [SimpananSukarelaController::class, 'generate'])->name('pengurus.simpanan.sukarela.generate');
    Route::post('/store', [SimpananSukarelaController::class, 'store'])->name('pengurus.simpanan.sukarela.store');
    Route::post('/edit', [SimpananSukarelaController::class, 'update'])->name('pengurus.simpanan.sukarela.update');
    Route::post('/approve/{id}', [SimpananSukarelaController::class, 'approve'])->name('pengurus.simpanan.sukarela.approve');
    Route::post('/reject/{id}', [SimpananSukarelaController::class, 'reject'])->name('pengurus.simpanan.sukarela.reject');
    Route::get('/riwayat', [SimpananSukarelaController::class, 'riwayat'])->name('pengurus.simpanan.sukarela.riwayat');
});

// Simpanan Wajib - Pengurus
Route::prefix('pengurus/simpanan-wajib')->group(function () {
    Route::get('/', [PengurusSimpananWajibController::class, 'dashboard'])->name('pengurus.simpanan.wajib_2.dashboard');
    Route::get('/edit', [PengurusSimpananWajibController::class, 'index'])->name('pengurus.simpanan.wajib_2.index');
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
    Route::post('/ajukan', [PengajuanSukarelaAnggotaController::class, 'store'])->name('user.simpanan.sukarela.store');
    Route::get('/pengajuan', [PengajuanSukarelaAnggotaController::class, 'create'])->name('user.simpanan.sukarela.pengajuan');
    Route::get('/riwayat', [SimpananSukarelaAnggotaController::class, 'riwayat'])->name('user.simpanan.sukarela.riwayat');


});

// Form forgot password (untuk anggota)
Route::get('/forgot-password', function () {
    return view('user.auth.reset-password');
})->middleware('guest')->name('password.request');

// Proses permintaan reset password
Route::post('/forgot-password', [PasswordResetRequestController::class, 'requestReset'])
    ->middleware('guest')
    ->name('password.email');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});
Route::middleware(['auth', 'role:anggota'])->group(function () {
    Route::get('/anggota/dashboard', [UserController::class, 'dashboardnotverifikasi'])->name('guest.dashboard');
    Route::get('/download', [filedokumen::class, 'dokumenverifikasi'])->name('dokumen.download');
    Route::get('/upload', [filedokumen::class, 'dashboardUpload'])->name('dokumen.upload');
    Route::post('/upload', [filedokumen::class, 'uploadDokumen'])->name('dokumen.upload.store');
});



