<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koperasi')->nullable();
            $table->string('nama_ketua_koperasi')->nullable();
            $table->string('nama_bendahara_koperasi')->nullable();
            $table->string('nama_bendahara_pengeluaran')->nullable();
            $table->string('nama_wadir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Kembalikan perubahan (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
