<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinjaman_settings', function (Blueprint $table) {
            $table->id();

            $table->string('nama_paket');   // Paket A, Paket B, dll
            $table->integer('nominal');     // Nominal pinjaman
            $table->integer('tenor');       // Tenor dalam bulan
            $table->decimal('bunga', 5, 2); // Bunga persen
            $table->boolean('status')->default(true); // aktif / nonaktif

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_settings');
    }
};
