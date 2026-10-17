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
        Schema::table('cari_hareketler', function (Blueprint $table) {
            $table->boolean('iptal')->default(false)->after('odendi_mi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cari_hareketler', function (Blueprint $table) {
            $table->dropColumn('iptal');
        });
    }
};
