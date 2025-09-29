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
        Schema::create('master_simpanan_sukarela', function (Blueprint $table) {
            $table->id();
             $table->integer('nilai');
            $table->integer('tahun');
            $table->integer('bulan');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_simpanan_sukarela');
    }
};
