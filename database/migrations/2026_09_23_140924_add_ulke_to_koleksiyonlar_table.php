<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->string('ulke')->nullable()->after('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->dropColumn('ulke');
        });
    }
};
