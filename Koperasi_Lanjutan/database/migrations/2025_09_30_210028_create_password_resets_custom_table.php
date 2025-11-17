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
            $table->string('email');               // Email tujuan OTP
            $table->string('otp_hash');                        // Hash OTP untuk verifikasi
            $table->string('reset_token')->nullable();         // Token reset password setelah OTP valid
            $table->enum('status', [                           // Status proses
                'pending',
                'approved',
                'rejected',
                'otp_sent',
                'otp_verified',
                'completed'
            ])->default('pending');
            $table->timestamp('expires_at')->nullable();       // Waktu kadaluarsa OTP/token
            $table->timestamp('used_at')->nullable();          // Kapan OTP/token digunakan
            $table->string('ip')->nullable();                  // IP pengaju reset
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');
    }
};
