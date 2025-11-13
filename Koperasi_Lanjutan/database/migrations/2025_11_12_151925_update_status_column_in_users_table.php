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
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom status menjadi ENUM baru dengan tambahan 'tidak aktif'
            $table->enum('status', ['pending', 'aktif', 'tidak aktif', 'ditolak'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke semula (jika rollback)
            $table->enum('status', ['pending', 'aktif', 'ditolak'])
                  ->default('pending')
                  ->change();
        });
    }
};
