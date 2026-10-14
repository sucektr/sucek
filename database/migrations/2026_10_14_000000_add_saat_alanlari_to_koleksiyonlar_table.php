<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->string('saat_turu')->nullable()->after('para_turu');
            $table->string('maden_turu')->nullable()->after('saat_turu');
            $table->string('ayar')->nullable()->after('maden_turu');
        });
    }

    public function down(): void
    {
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->dropColumn(['saat_turu', 'maden_turu', 'ayar']);
        });
    }
};
