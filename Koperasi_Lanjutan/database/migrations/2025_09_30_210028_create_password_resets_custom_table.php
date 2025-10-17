<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('otp_hash'); // hash OTP untuk verifikasi
            $table->string('password')->nullable(); // password baru (setelah diapprove)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('expires_at')->nullable(); // waktu kedaluwarsa OTP
            $table->timestamp('used_at')->nullable(); // kapan digunakan
            $table->string('ip')->nullable(); // IP yang mengajukan reset
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');
    }
};
