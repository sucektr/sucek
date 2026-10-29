<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->enum('onay_durumu', ['bekliyor', 'onaylandi', 'reddedildi'])->default('onaylandi')->after('aktif');
            $table->foreignId('eklenen_kullanici_id')->nullable()->after('onay_durumu')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->dropConstrainedForeignId('eklenen_kullanici_id');
            $table->dropColumn('onay_durumu');
        });
    }
};
