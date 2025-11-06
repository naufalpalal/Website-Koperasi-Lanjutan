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
        Schema::table('tabungans', function (Blueprint $table) {
            $table->unsignedBigInteger('pengurus_id')->nullable()->after('users_id');
            $table->foreign('pengurus_id')->references('id')->on('pengurus')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabungans', function (Blueprint $table) {
            $table->dropForeign(['pengurus_id']);
            $table->dropColumn('pengurus_id');
        });
    }
};
