<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_icerik', function (Blueprint $table) {
            $table->string('dil', 5)->default('tr')->after('alan');
            $table->dropUnique('site_icerik_sayfa_alan_unique');
            $table->unique(['sayfa', 'alan', 'dil']);
        });
    }

    public function down(): void
    {
        Schema::table('site_icerik', function (Blueprint $table) {
            $table->dropUnique('site_icerik_sayfa_alan_dil_unique');
            $table->dropColumn('dil');
            $table->unique(['sayfa', 'alan']);
        });
    }
};
