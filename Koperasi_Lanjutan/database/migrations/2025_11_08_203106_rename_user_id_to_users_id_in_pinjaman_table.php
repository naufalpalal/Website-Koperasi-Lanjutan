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
        Schema::table('pinjaman', function (Blueprint $table) {
            // Jika foreign key lama ada, hapus dulu
            $sm = DB::select("SELECT CONSTRAINT_NAME 
                              FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                              WHERE TABLE_NAME = 'pinjaman' AND COLUMN_NAME = 'user_id' AND REFERENCED_TABLE_NAME = 'users'");
            if (!empty($sm)) {
                $table->dropForeign([$sm[0]->CONSTRAINT_NAME]);
            }

            // Rename kolom
            $table->renameColumn('user_id', 'users_id');

            // Tambahkan foreign key baru
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropForeign(['users_id']);
            $table->renameColumn('users_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
