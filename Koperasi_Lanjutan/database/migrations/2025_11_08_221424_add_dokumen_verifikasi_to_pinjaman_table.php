<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('pinjaman', 'dokumen_verifikasi')) {
                $table->string('dokumen_verifikasi')->nullable()->after('dokumen_pinjaman');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            if (Schema::hasColumn('pinjaman', 'dokumen_verifikasi')) {
                $table->dropColumn('dokumen_verifikasi');
            }
        });
    }
};

