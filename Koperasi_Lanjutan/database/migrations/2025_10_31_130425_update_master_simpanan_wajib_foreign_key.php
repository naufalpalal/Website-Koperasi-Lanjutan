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
        Schema::table('master_simpanan_wajib', function (Blueprint $table) {
            // 1️⃣ Hapus foreign key lama
            $table->dropForeign(['users_id']);

            // 2️⃣ (Opsional) Ubah nama kolom biar lebih jelas
            $table->renameColumn('users_id', 'pengurus_id');

            // 3️⃣ Tambahkan foreign key baru ke tabel pengurus
            $table->foreign('pengurus_id')
                  ->references('id')
                  ->on('pengurus')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_simpanan_wajib', function (Blueprint $table) {
            // rollback ke relasi awal
            $table->dropForeign(['pengurus_id']);
            $table->renameColumn('pengurus_id', 'users_id');
            $table->foreign('users_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
