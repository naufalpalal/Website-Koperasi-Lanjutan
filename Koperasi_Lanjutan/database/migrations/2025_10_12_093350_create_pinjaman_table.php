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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('nominal', 15, 2); // nominal pinjaman
            $table->decimal('bunga', 5, 2)->nullable(); // persen bunga
            $table->integer('tenor')->nullable(); // bulan
            $table->decimal('angsuran', 15, 2)->nullable(); // per bulan
            $table->string('dokumen_pinjaman')->nullable(); // file surat awal
            $table->enum('status', ['draft', 'pending', 'disetujui', 'ditolak'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
