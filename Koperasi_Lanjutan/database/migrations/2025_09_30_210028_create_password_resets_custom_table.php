<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop tabel jika sudah ada (untuk recreate)
        Schema::dropIfExists('password_reset_requests');
        
        Schema::create('password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->string('token_hash'); // Required untuk reset password
            $table->string('otp_hash')->nullable(); // Tambahkan otp_hash nullable (untuk kompatibilitas)
            $table->string('reset_token')->nullable(); // Tambahkan reset_token nullable (untuk kompatibilitas)
            $table->enum('status', ['pending', 'token_sent', 'used', 'expired', 'approved', 'rejected', 'otp_sent', 'otp_verified', 'completed'])
                ->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');
    }
};
