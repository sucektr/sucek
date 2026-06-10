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
        Schema::table('user_urunler', function (Blueprint $table) {
            $table->json('gorseller')->nullable()->after('gorsel');
        });
    }

    public function down(): void
    {
        Schema::table('user_urunler', function (Blueprint $table) {
            $table->dropColumn('gorseller');
        });
    }
};
