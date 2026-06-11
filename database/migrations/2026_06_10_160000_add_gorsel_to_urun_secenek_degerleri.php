<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urun_secenek_degerleri', function (Blueprint $table) {
            $table->string('gorsel')->nullable()->after('deger');
        });
    }

    public function down(): void
    {
        Schema::table('urun_secenek_degerleri', function (Blueprint $table) {
            $table->dropColumn('gorsel');
        });
    }
};
