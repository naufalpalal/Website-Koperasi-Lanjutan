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
        Schema::create('pinjamans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id'); // relasi ke tabel anggota
            $table->decimal('jumlah', 15, 2); // jumlah pinjaman
            $table->integer('tenor'); // tenor dalam bulan
            $table->decimal('cicilan_per_bulan', 15, 2); // cicilan tiap bulan
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'lunas'])->default('pending');
            $table->timestamps();

            // Foreign key ke tabel anggota
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamans');
    }
};