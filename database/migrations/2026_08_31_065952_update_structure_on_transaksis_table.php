<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->string('kategori_utama')->default('kasbon')->after('jenis'); // kasbon, petty_cash, invoice_payment
        $table->string('sub_kategori')->nullable()->after('kategori_utama'); // building_material, fuel, spare_part_vehicle, electrical, water, office_equipment, mess_equipment
    });
}

public function down()
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropColumn(['kategori_utama', 'sub_kategori']);
    });
}
};
