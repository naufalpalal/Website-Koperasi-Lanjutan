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
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis', ['pokok', 'wajib', 'sukarela_rutin']);
            $table->bigInteger('nominal');
            $table->enum('status', ['aktif', 'ditangguhkan', 'berhenti'])->default('aktif');
            $table->date('mulai_efektif')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->unique(['anggota_id', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};
