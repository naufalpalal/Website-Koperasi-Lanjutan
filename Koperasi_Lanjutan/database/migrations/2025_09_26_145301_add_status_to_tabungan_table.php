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
        Schema::table('tabungan', function (Blueprint $table) {
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabungan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
