<?php

use App\Http\Controllers\Pengurus\PengajuanAngsuranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RolePengurusMiddleware;
use App\Models\PengajuanAngsuran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// Controllers
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\User\SimpananWajibController;
use App\Http\Controllers\Pengurus\SimpananSukarelaController;
use App\Http\Controllers\Pengurus\PengurusSimpananWajibController;
use App\Http\Controllers\Pengurus\MasterSimpananWajibController;
use App\Http\Controllers\Pengurus\Tabungan2Controller;
use App\Http\Controllers\User\SimpananSukarelaAnggotaController;
use App\Http\Controllers\User\PengajuanSukarelaAnggotaController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\filedokumen;
use App\Http\Controllers\Pengurus\PinjamanController;
use App\Http\Controllers\Pengurus\AngsuranController;
use App\Http\Controllers\User\PinjamanAnggotaController;
use App\Http\Controllers\IdentitasKoperasiController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\PinjamanDokumenController;
use App\Http\Controllers\Pengurus\PinjamanSettingController;
use App\Http\Controllers\User\AngsuranAnggotaController;


// Registrasi Middleware
Route::aliasMiddleware('role.pengurus', RolePengurusMiddleware::class);

// Halaman Utama & Logout
Route::get('/', function () {
    return view('auth.login');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');





// Profile User (Auth wajib)
Route::middleware(['auth:pengurus,web'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::put('/profile-combined', [ProfileController::class, 'updateCombined'])->name('profile.update-combined');
});


// Laporan (Pengurus)
Route::get('/laporan', [LaporanController::class, 'index'])->name('pengurus.laporan.index');


// Pinjaman (Anggota)
Route::middleware(['auth:web'])->prefix('anggota')->group(function () {
    Route::get('/pinjaman/create', [PinjamanAnggotaController::class, 'create'])->name('user.pinjaman.create');
    Route::post('/pinjaman/store', [PinjamanAnggotaController::class, 'store'])->name('user.pinjaman.store');
    Route::post('/pinjaman/{id}/upload', [PinjamanAnggotaController::class, 'upload'])->name('user.pinjaman.upload');
    // Pilih bulan angsuran
    // Route::get(
    //     '/angsuran/{pinjaman}',
    //     [AngsuranAnggotaController::class, 'index']
    // )->name('anggota.angsuran.index');


    Route::get(
        '/angsuran/pilih/{pinjaman}',
        [AngsuranAnggotaController::class, 'pilihBulan']
    )->name('anggota.angsuran.pilih');

    Route::post(
        '/angsuran/bayar/{pinjaman}',
        [AngsuranAnggotaController::class, 'bayar']
    )->name('anggota.angsuran.bayar');
    // Route::get('/pinjaman/download/{id}', [PinjamanAnggotaController::class, 'download'])->name('user.pinjaman.download');
    // Route::get('/pinjaman/upload/{id}', [PinjamanAnggotaController::class, 'uploadForm'])->name('user.pinjaman.uploadForm');
    // Route::post('/pinjaman/upload/{id}', [PinjamanAnggotaController::class, 'upload'])->name('user.pinjaman.upload');
    // Route::delete('/pinjaman/dokumen/{id}', [PinjamanAnggotaController::class, 'hapusDokumen'])->name('user.pinjaman.hapusDokumen');
});


// Tabungan (Anggota)
Route::middleware(['auth:web'])->prefix('user/simpanan')->as('user.simpanan.')->group(function () {
    Route::resource('tabungan', TabunganController::class);
    Route::post('/user/tabungan/store', [TabunganController::class, 'store'])->name('user.simpanan.tabungan.store');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboardUserView'])->name('user.dashboard.index');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/anggota/simpanan', [SimpananWajibController::class, 'index'])->name('user.simpanan.wajib.index');
});


// Login & Register (Guest Area)
Route::get('/pengurus/login', [PengurusController::class, 'showLoginForm'])->name('pengurus.login');
Route::post('/pengurus/login', [PengurusController::class, 'login']);
Route::post('/pengurus/logout', [PengurusController::class, 'logout'])->name('pengurus.logout');


// Pengurus Area (auth:pengurus)
Route::middleware(['auth:pengurus'])->prefix('pengurus')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PengurusController::class, 'dashboard'])
        ->name('pengurus.dashboard.index');

    // ============================
    // SIMPANAN WAJIB
    // ============================
    Route::prefix('simpanan-wajib')->group(function () {
        Route::get('/', [PengurusSimpananWajibController::class, 'dashboard'])
            ->name('pengurus.simpanan.wajib.dashboard');

        Route::get('/edit', [PengurusSimpananWajibController::class, 'index'])
            ->name('pengurus.simpanan.wajib.index');

        Route::post('/generate', [PengurusSimpananWajibController::class, 'generate'])
            ->name('pengurus.simpanan.wajib.generate');

        Route::post('/update-status', [PengurusSimpananWajibController::class, 'updateStatus'])
            ->name('pengurus.simpanan.wajib.updateStatus');
    });

    // ============================
    // TABUNGAN (Bendahara, Superadmin, Ketua)
    // ============================
    Route::middleware('role.pengurus:bendahara,superadmin,ketua')->prefix('tabungan')->group(function () {

        Route::get('/', [Tabungan2Controller::class, 'index'])
            ->name('pengurus.tabungan.index');
        Route::get('/pengurus/tabungan/download-excel', [Tabungan2Controller::class, 'downloadExcel'])
            ->name('pengurus.tabungan.download.excel');

        Route::get('/{id}', [Tabungan2Controller::class, 'detail'])
            ->name('pengurus.tabungan.detail');

        // Kredit
        Route::get('/kredit/{id}', [Tabungan2Controller::class, 'kredit'])
            ->name('pengurus.tabungan.kredit');

        Route::post('/kredit/store', [Tabungan2Controller::class, 'storeKredit'])
            ->name('pengurus.tabungan.kredit.store');

        // Tambah Saldo
        Route::get('/{id}/tambah', [Tabungan2Controller::class, 'create'])
            ->name('pengurus.tabungan.create');

        Route::post('/store', [Tabungan2Controller::class, 'store'])
            ->name('pengurus.tabungan.store');

        // Debit
        Route::get('/debit/{id}', [Tabungan2Controller::class, 'debit'])
            ->name('pengurus.tabungan.debit');

        Route::post('/debit/store', [Tabungan2Controller::class, 'storeDebit'])
            ->name('pengurus.tabungan.debit.store');

        // Approve / Reject
        Route::put('/{id}/terima', [Tabungan2Controller::class, 'approve'])
            ->name('pengurus.tabungan.terima');

        Route::put('/{id}/tolak', [Tabungan2Controller::class, 'reject'])
            ->name('pengurus.tabungan.tolak');
    });

    // ============================
    // PINJAMAN
    // ============================
    Route::prefix('pinjaman')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])
            ->name('pengurus.pinjaman.index');
        Route::get('/pengajuan', [PinjamanController::class, 'pengajuan'])
            ->name('pengurus.pinjaman.pengajuan');
        Route::get('/pemotongan', [AngsuranController::class, 'periodePotongan'])
            ->name('pengurus.pinjaman.pemotongan');
         Route::get('/download', [PinjamanController::class, 'downloadExcel'])
         ->name('pengurus.pinjaman.download');
        Route::post('/{id}/approve', [PinjamanController::class, 'approve'])
            ->name('pengurus.pinjaman.approve');
        Route::post('/{id}/reject', [PinjamanController::class, 'reject'])
            ->name('pengurus.pinjaman.reject');
        Route::get('/angsuran/{id}', [AngsuranController::class, 'index'])
            ->name('pengurus.angsuran.index');
        Route::post(
            '/pengurus/pinjaman/{id}/status',
            [AngsuranController::class, 'updateStatus']
        )->name('pengurus.pinjaman.updateStatus');
        // Setting pinjaman
        Route::get('/settings', [PinjamanSettingController::class, 'index'])
            ->name('pengurus.settings.index');
        Route::post('/settings', [PinjamanSettingController::class, 'store'])
            ->name('pengurus.settings.store');
        // Detail pinjaman → letakkan paling bawah biar tidak bentrok
        Route::get('/{id}', [PinjamanController::class, 'show'])
            ->name('pengurus.pinjaman.show');

        Route::prefix('pengurus/pinjaman')->group(function () {
            Route::get('/pengajuan', [PengajuanAngsuranController::class, 'pengajuanList'])
                ->name('pengurus.angsuran.pengajuan');

            Route::post('/pengajuan/{id}/acc', [PengajuanAngsuranController::class, 'accPengajuan'])
                ->name('pengurus.angsuran.acc');

            Route::post('/pengajuan/{id}/tolak', [PengajuanAngsuranController::class, 'tolakPengajuan'])
                ->name('pengurus.angsuran.tolak');
        });

    });
    // ============================
    // KELOLA ANGGOTA
    // ============================
    Route::middleware('role.pengurus:sekretaris,superadmin,ketua')
        ->prefix('anggota')
        ->group(function () {

            Route::get('/', [KelolaAnggotController::class, 'index'])
                ->name('pengurus.anggota.index');

            Route::get('/create', [KelolaAnggotController::class, 'create'])
                ->name('pengurus.anggota.create');

            Route::post('/', [KelolaAnggotController::class, 'store'])
                ->name('pengurus.anggota.store');

            Route::get('/download-excel', [KelolaAnggotController::class, 'downloadExcel'])
                ->name('pengurus.anggota.download');

            Route::get('/verifikasi', [KelolaAnggotController::class, 'verifikasi'])
                ->name('pengurus.anggota.verifikasi');

            Route::post('/{id}/approve', [KelolaAnggotController::class, 'approve'])
                ->name('pengurus.anggota.approve');

            Route::post('/{id}/reject', [KelolaAnggotController::class, 'reject'])
                ->name('pengurus.anggota.reject');

            Route::get('/tidak-aktif', [KelolaAnggotController::class, 'nonaktif'])
                ->name('pengurus.anggota.nonaktif');

            Route::get('/{id}/edit', [KelolaAnggotController::class, 'edit'])
                ->name('pengurus.anggota.edit');

            Route::put('/{id}', [KelolaAnggotController::class, 'update'])
                ->name('pengurus.anggota.update');

            Route::delete('/{id}', [KelolaAnggotController::class, 'destroy'])
                ->name('pengurus.anggota.destroy');

            Route::patch('/{id}/toggle-status', [KelolaAnggotController::class, 'toggleStatus'])
                ->name('pengurus.anggota.toggleStatus');
        });
});


// Tabungan Anggota
Route::middleware(['auth:web'])->group(function () {
    Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan.index');
    Route::post('/tabungan/store', [TabunganController::class, 'store'])->name('tabungan.store');
    Route::get('/tabungan/history', [TabunganController::class, 'historyFull'])->name('tabungan.history');
});


// ROLE: superadmin
Route::middleware('role.pengurus:superadmin')->group(function () {
    Route::get('/identitas-koperasi', [IdentitasKoperasiController::class, 'edit'])->name('settings.edit');
    Route::put('/identitas-koperasi', [IdentitasKoperasiController::class, 'update'])->name('settings.update');
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
    Route::get('/master/edit', [MasterSimpananWajibController::class, 'editNominal'])->name('pengurus.simpanan.wajib_2.edit');
    Route::post('/master/update-nominal', [MasterSimpananWajibController::class, 'updateNominal'])->name('pengurus.simpanan.wajib_2.updateNominal');
});


// ROLE: bendahara, superadmin, ketua
Route::middleware('role.pengurus:bendahara,superadmin,ketua')->group(function () {
    Route::prefix('simpanan-sukarela')->group(function () {
        Route::get('/', [SimpananSukarelaController::class, 'index'])->name('pengurus.simpanan.sukarela.index');
        Route::post('/store', [SimpananSukarelaController::class, 'store'])->name('pengurus.simpanan.sukarela.store');
        Route::post('/generate', [SimpananSukarelaController::class, 'generate'])->name('pengurus.simpanan.sukarela.generate');
        Route::post('/update', [SimpananSukarelaController::class, 'update'])->name('pengurus.simpanan.sukarela.update');
        Route::get('/riwayat', [SimpananSukarelaController::class, 'riwayat'])->name('pengurus.simpanan.sukarela.riwayat');
        Route::get('/pengajuan', [SimpananSukarelaController::class, 'create'])->name('pengurus.simpanan.sukarela.pengajuan');
        Route::post('/approve/{id}', [SimpananSukarelaController::class, 'approve'])->name('pengurus.simpanan.sukarela.approve');
        Route::post('/reject/{id}', [SimpananSukarelaController::class, 'reject'])->name('pengurus.simpanan.sukarela.reject');
    });
});


// Simpanan Sukarela - Anggota
Route::middleware(['auth:web'])->prefix('simpanan-sukarela-anggota')->group(function () {
    Route::get('/', [SimpananSukarelaAnggotaController::class, 'index'])->name('user.simpanan.sukarela.index');
    Route::post('/ajukan', [PengajuanSukarelaAnggotaController::class, 'store'])->name('user.simpanan.sukarela.store');
    Route::get('/pengajuan', [PengajuanSukarelaAnggotaController::class, 'create'])->name('user.simpanan.sukarela.pengajuan');
    Route::get('/riwayat', [SimpananSukarelaAnggotaController::class, 'riwayat'])->name('user.simpanan.sukarela.riwayat');
    Route::post('/toggle', [SimpananSukarelaAnggotaController::class, 'toggle'])->name('simpanan.sukarela.toggle');
});


// Auth: Logout If Authenticated (Guest Access Only)
Route::middleware(['logout.if.authenticated'])->group(function () {
    // Login
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login_submit');
    // Register
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    // Forgot password + OTP
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/forgot-password/send-otp', [PasswordResetRequestController::class, 'sendOtp'])->name('password.sendOtp');
    Route::post('/forgot-password/verify-otp', [PasswordResetRequestController::class, 'verifyOtp'])->name('password.verifyOtp');
});


// Dashboard Anggota
Route::middleware(['auth:web'])->group(function () {
    Route::get('/anggota/dashboard', [UserController::class, 'dashboardnotverifikasi'])->name('guest.dashboard');
    Route::get('/download', [filedokumen::class, 'dokumenverifikasi'])->name('dokumen.download');
    Route::get('/upload', [filedokumen::class, 'dashboardUpload'])->name('dokumen.upload');
    Route::post('/upload', [filedokumen::class, 'uploadDokumen'])->name('dokumen.upload.store');
    // routes/web.php
    Route::get('/anggota/pinjaman/dokumen/{tipe}', [PinjamanDokumenController::class, 'generate'])
        ->name('anggota.pinjaman.download');

});

Route::middleware(['auth:web'])->get('/dokumen/lihat/{userId}/{jenis}', [filedokumen::class, 'lihatDokumen'])
    ->name('dokumen.lihat.user');

Route::middleware(['auth:pengurus'])->get('/pengurus/dokumen/lihat/{userId}/{jenis}', [filedokumen::class, 'lihatDokumen'])
    ->name('dokumen.lihat.pengurus');
