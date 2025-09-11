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
        Schema::create('nominal_wajib', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nominal');   // jumlah nominal wajib
            $table->year('tahun')->nullable(); // tahun berlaku (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominal_wajib');
    }
};
