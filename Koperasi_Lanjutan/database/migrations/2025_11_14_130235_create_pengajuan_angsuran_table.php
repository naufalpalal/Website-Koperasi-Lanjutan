<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Menjalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('pengajuan_angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // PERBAIKAN
            $table->foreignId('pinjaman_id')
                ->constrained('pinjaman')
                ->onDelete('cascade');

            $table->json('angsuran_ids');
            $table->string('bukti_transfer');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_angsuran');
    }
};
