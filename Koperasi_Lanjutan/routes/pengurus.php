<?php

use App\Http\Controllers\PengurusController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\IdentitasKoperasiController;
use App\Http\Controllers\Pengurus\PengurusSimpananWajibController;
use App\Http\Controllers\Pengurus\MasterSimpananWajibController;
use App\Http\Controllers\Pengurus\Tabungan2Controller;
use App\Http\Controllers\Pengurus\PinjamanController;
use App\Http\Controllers\Pengurus\AngsuranController;
use App\Http\Controllers\Pengurus\PengajuanAngsuranController;
use App\Http\Controllers\Pengurus\SimpananSukarelaController;
use App\Http\Controllers\Pengurus\PinjamanSettingController;
use App\Http\Controllers\KelolaAnggotController;
use App\Http\Controllers\FileDokumenController;
use Illuminate\Support\Facades\Route;

Route::prefix('pengurus')->name('pengurus.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PengurusController::class, 'dashboard'])->name('dashboard.index');

    // ============================
    // SIMPANAN WAJIB
    // ============================
    Route::prefix('simpanan-wajib')->name('simpanan.wajib.')->group(function () {
        Route::get('/', [PengurusSimpananWajibController::class, 'dashboard'])->name('dashboard');
        Route::get('/edit', [PengurusSimpananWajibController::class, 'index'])->name('index');
        Route::post('/generate', [PengurusSimpananWajibController::class, 'generate'])->name('generate');
        Route::post('/update-status', [PengurusSimpananWajibController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/riwayat/{id}', [PengurusSimpananWajibController::class, 'riwayat'])->name('riwayat');
        Route::get('/download', [PengurusSimpananWajibController::class, 'downloadExcel'])->name('download');
        Route::post('/kunci', [PengurusSimpananWajibController::class, 'lockPeriode'])->name('lock');
        Route::get('/master/edit', [MasterSimpananWajibController::class, 'editNominal'])->name('edit');
        Route::post('/master/update-nominal', [MasterSimpananWajibController::class, 'updateNominal'])->name('updateNominal');
    });

    // ============================
    // TABUNGAN (Bendahara, Superadmin, Ketua)
    // ============================
    Route::middleware('role.pengurus:bendahara,superadmin,ketua')->prefix('tabungan')->name('tabungan.')->group(function () {
        Route::get('/', [Tabungan2Controller::class, 'index'])->name('index');
        Route::get('/download-excel', [Tabungan2Controller::class, 'downloadExcel'])->name('download.excel');
        Route::get('/{id}', [Tabungan2Controller::class, 'detail'])->name('detail');
        Route::get('/kredit/{id}', [Tabungan2Controller::class, 'kredit'])->name('kredit');
        Route::post('/kredit/store', [Tabungan2Controller::class, 'storeKredit'])->name('kredit.store');
        Route::get('/{id}/tambah', [Tabungan2Controller::class, 'create'])->name('create');
        Route::post('/store', [Tabungan2Controller::class, 'store'])->name('store');
        Route::get('/debit/{id}', [Tabungan2Controller::class, 'debit'])->name('debit');
        Route::post('/debit/store', [Tabungan2Controller::class, 'storeDebit'])->name('debit.store');
        Route::put('/{id}/terima', [Tabungan2Controller::class, 'approve'])->name('terima');
        Route::put('/{id}/tolak', [Tabungan2Controller::class, 'reject'])->name('tolak');
    });

    // ============================
    // PINJAMAN
    // ============================
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [PinjamanController::class, 'index'])->name('index');
        Route::get('/pengajuan', [PinjamanController::class, 'pengajuan'])->name('pengajuan');
        Route::get('/pemotongan', [AngsuranController::class, 'periodePotongan'])->name('pemotongan');
        Route::get('/download', [PinjamanController::class, 'downloadExcel'])->name('download');
        Route::post('/{id}/approve', [PinjamanController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PinjamanController::class, 'reject'])->name('reject');
        Route::get('/angsuran/{id}', [AngsuranController::class, 'index'])->name('angsuran.index');
        Route::post('/{id}/status', [AngsuranController::class, 'updateStatus'])->name('updateStatus');

        // Settings
        Route::get('/settings', [PinjamanSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [PinjamanSettingController::class, 'store'])->name('settings.store');

        // Pengajuan Angsuran
        Route::prefix('angsuran-pengajuan')->name('angsuran.')->group(function () {
            Route::get('/', [PengajuanAngsuranController::class, 'pengajuanList'])->name('pengajuan');
            Route::post('/{id}/acc', [PengajuanAngsuranController::class, 'accPengajuan'])->name('acc');
            Route::post('/{id}/tolak', [PengajuanAngsuranController::class, 'tolakPengajuan'])->name('tolak');
        });

        // Detail pinjaman (harus di paling bawah)
        Route::get('/{id}', [PinjamanController::class, 'show'])->name('show');
    });

    // ============================
    // KELOLA ANGGOTA
    // ============================
    Route::middleware('role.pengurus:sekretaris,superadmin,ketua')->prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/', [KelolaAnggotController::class, 'index'])->name('index');
        Route::get('/create', [KelolaAnggotController::class, 'create'])->name('create');
        Route::post('/', [KelolaAnggotController::class, 'store'])->name('store');
        Route::get('/download-excel', [KelolaAnggotController::class, 'downloadExcel'])->name('download');
        Route::get('/verifikasi', [KelolaAnggotController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/{id}/approve', [KelolaAnggotController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [KelolaAnggotController::class, 'reject'])->name('reject');
        Route::get('/tidak-aktif', [KelolaAnggotController::class, 'nonaktif'])->name('nonaktif');
        Route::get('/{id}/edit', [KelolaAnggotController::class, 'edit'])->name('edit');
        Route::put('/{id}', [KelolaAnggotController::class, 'update'])->name('update');
        Route::delete('/{id}', [KelolaAnggotController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [KelolaAnggotController::class, 'toggleStatus'])->name('toggleStatus');
        Route::put('/{id}/restore', [KelolaAnggotController::class, 'restore'])->name('restore');
    });

    // ============================
    // SIMPANAN SUKARELA
    // ============================
    Route::middleware('role.pengurus:bendahara,superadmin,ketua')->prefix('simpanan-sukarela')->name('simpanan.sukarela.')->group(function () {
        Route::get('/', [SimpananSukarelaController::class, 'index'])->name('index');
        Route::post('/store', [SimpananSukarelaController::class, 'store'])->name('store');
        Route::post('/generate', [SimpananSukarelaController::class, 'generate'])->name('generate');
        Route::post('/update', [SimpananSukarelaController::class, 'update'])->name('update');
        Route::get('/riwayat', [SimpananSukarelaController::class, 'riwayat'])->name('riwayat');
        Route::get('/pengajuan', [SimpananSukarelaController::class, 'create'])->name('pengajuan');
        Route::post('/approve/{id}', [SimpananSukarelaController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [SimpananSukarelaController::class, 'reject'])->name('reject');
        Route::get('/download', [SimpananSukarelaController::class, 'downloadExcel'])->name('download');
    });

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Settings
    Route::middleware('role.pengurus:superadmin')->group(function () {
        Route::get('/identitas-koperasi', [IdentitasKoperasiController::class, 'edit'])->name('settings.edit');
        Route::put('/identitas-koperasi', [IdentitasKoperasiController::class, 'update'])->name('settings.update');
    });

    // Dokumen
    Route::get('/dokumen/lihat/{userId}/{jenis}', [FileDokumenController::class, 'lihatDokumen'])->name('dokumen.lihat.pengurus');
});
