<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            // Tambah kolom email
            $table->string('email')->after('user_id')->nullable();
            
            // Tambah kolom reset_token untuk reset password setelah OTP verified
            $table->string('reset_token')->after('otp_hash')->nullable();
            
            // Update enum status untuk tambah status baru
            DB::statement("ALTER TABLE password_reset_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'otp_sent', 'otp_verified', 'completed') DEFAULT 'pending'");
        });
    }

    public function down(): void
    {
        Schema::table('password_reset_requests', function (Blueprint $table) {
            $table->dropColumn(['email', 'reset_token']);
            
            // Kembalikan enum status
            DB::statement("ALTER TABLE password_reset_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        });
    }
};