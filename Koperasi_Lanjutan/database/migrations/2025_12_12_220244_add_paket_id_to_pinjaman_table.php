<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('pinjaman', function (Blueprint $table) {
        $table->foreignId('paket_id')
              ->after('user_id')
              ->constrained('pinjaman_settings')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('pinjaman', function (Blueprint $table) {
        $table->dropForeign(['paket_id']);
        $table->dropColumn('paket_id');
    });
}


};
