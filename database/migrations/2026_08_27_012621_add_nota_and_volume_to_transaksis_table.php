<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->string('no_nota')->nullable()->after('deskripsi');
        $table->decimal('volume', 8, 2)->nullable()->after('no_nota');
    });
}

public function down(): void
{
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropColumn(['no_nota', 'volume']);
    });
}
};
