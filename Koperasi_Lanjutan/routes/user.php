<?php

use App\Http\Controllers\TabunganController;
use App\Http\Controllers\User\SimpananWajibController;
use App\Http\Controllers\User\SimpananSukarelaAnggotaController;
use App\Http\Controllers\User\PengajuanSukarelaAnggotaController;
use App\Http\Controllers\User\PinjamanAnggotaController;
use App\Http\Controllers\User\AngsuranAnggotaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileDokumenController;
use App\Http\Controllers\PinjamanDokumenController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/dashboard', [UserController::class, 'dashboardUserView'])->name('user.dashboard.index');
Route::get('/anggota/dashboard', [UserController::class, 'dashboardnotverifikasi'])->name('guest.dashboard');

// Profile
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::put('/profile/combined', [ProfileController::class, 'updateCombined'])->name('profile.update-combined');

// Simpanan Wajib
Route::get('/anggota/simpanan', [SimpananWajibController::class, 'index'])->name('user.simpanan.wajib.index');

// Simpanan Sukarela
Route::prefix('simpanan-sukarela-anggota')->name('user.simpanan.sukarela.')->group(function () {
    Route::get('/', [SimpananSukarelaAnggotaController::class, 'index'])->name('index');
    Route::post('/ajukan', [PengajuanSukarelaAnggotaController::class, 'store'])->name('store');
    Route::get('/pengajuan', [PengajuanSukarelaAnggotaController::class, 'create'])->name('pengajuan');
    Route::get('/riwayat', [SimpananSukarelaAnggotaController::class, 'riwayat'])->name('riwayat');
    Route::post('/toggle', [SimpananSukarelaAnggotaController::class, 'toggle'])->name('toggle');
});

// Tabungan
Route::prefix('tabungan')->name('tabungan.')->group(function () {
    Route::get('/', [TabunganController::class, 'index'])->name('index');
    Route::post('/store', [TabunganController::class, 'store'])->name('store');
    Route::get('/history', [TabunganController::class, 'historyFull'])->name('history');
});

// Tabungan Resource
Route::prefix('user/simpanan')->name('user.simpanan.')->group(function () {
    Route::resource('tabungan', TabunganController::class);
    Route::post('/user/tabungan/store', [TabunganController::class, 'store'])->name('user.tabungan.store');
});

// Pinjaman
Route::prefix('anggota')->name('user.pinjaman.')->group(function () {
    Route::get('/pinjaman/create', [PinjamanAnggotaController::class, 'create'])->name('create');
    Route::post('/pinjaman/store', [PinjamanAnggotaController::class, 'store'])->name('store');
    Route::post('/pinjaman/{id}/upload', [PinjamanAnggotaController::class, 'upload'])->name('upload');
    Route::get('/angsuran/pilih/{pinjaman}', [AngsuranAnggotaController::class, 'pilihBulan'])->name('angsuran.pilih');
    Route::post('/angsuran/bayar/{pinjaman}', [AngsuranAnggotaController::class, 'bayar'])->name('angsuran.bayar');
});

// Dokumen
Route::get('/download', [FileDokumenController::class, 'dokumenverifikasi'])->name('dokumen.download');
Route::get('/upload', [FileDokumenController::class, 'dashboardUpload'])->name('dokumen.upload');
Route::post('/upload', [FileDokumenController::class, 'uploadDokumen'])->name('dokumen.upload.store');
Route::get('/anggota/pinjaman/dokumen/{tipe}', [PinjamanDokumenController::class, 'generate'])->name('anggota.pinjaman.download');
Route::get('/dokumen/lihat/{userId}/{jenis}', [FileDokumenController::class, 'lihatDokumen'])->name('dokumen.lihat.user');

