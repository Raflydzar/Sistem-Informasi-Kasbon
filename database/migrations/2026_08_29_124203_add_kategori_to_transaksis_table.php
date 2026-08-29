<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->string('kategori')->default('kasbon')->after('jenis'); // Pilihan: kasbon, reimburse, rtk, operasional, dll
    });
}

public function down()
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropColumn('kategori');
    });
}
};
