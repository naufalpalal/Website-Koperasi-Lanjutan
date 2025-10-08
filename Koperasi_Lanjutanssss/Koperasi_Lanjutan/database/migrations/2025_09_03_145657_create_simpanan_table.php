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
        // Tabel aturan simpanan
        Schema::create('simpanan_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['pokok', 'wajib']); // aturan hanya untuk pokok & wajib
            $table->decimal('amount', 15, 2);         // nominal simpanan
            $table->date('start_date');               // mulai berlaku
            $table->date('end_date')->nullable();     // selesai berlaku (null = masih aktif)
            $table->timestamps();
        });

        // Tabel simpanan anggota
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['pokok', 'wajib', 'sukarela']);
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('note')->nullable(); 
            $table->date('month')->nullable(); 
            $table->foreignId('rule_id')->nullable()
                  ->constrained('simpanan_rules')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
        Schema::dropIfExists('simpanan_rules');
    }
};
