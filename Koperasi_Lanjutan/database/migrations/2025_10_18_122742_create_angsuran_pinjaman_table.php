<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('angsuran_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pinjaman_id');
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->integer('bulan_ke');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['belum_lunas','lunas'])->default('belum_lunas');
            $table->enum('jenis_pembayaran', ['angsuran','pelunasan'])->nullable();
            $table->timestamps();
            // Relasi ke tabel pinjaman dan petugas (user)
            $table->foreign('pinjaman_id')->references('id')->on('pinjaman')->onDelete('cascade');
            $table->foreign('petugas_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran_pinjaman');
    }
};
