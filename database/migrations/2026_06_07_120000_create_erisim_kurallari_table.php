<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erisim_kurallari', function (Blueprint $table) {
            $table->id();
            $table->string('bolum_slug')->unique();
            $table->string('bolum_adi');
            $table->string('aciklama')->nullable();
            $table->json('izin_tipler');
            $table->timestamps();
        });

        DB::table('erisim_kurallari')->insert([
            [
                'bolum_slug'  => 'haberler',
                'bolum_adi'   => 'Haberler',
                'aciklama'    => 'Aile haberleri ve duyurular',
                'izin_tipler' => json_encode(['standart', 'sucek', 'teknik']),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'bolum_slug'  => 'soy-agaci',
                'bolum_adi'   => 'Soy Ağacı',
                'aciklama'    => 'Aile soy ağacı ve şecere',
                'izin_tipler' => json_encode(['standart', 'sucek', 'teknik']),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('erisim_kurallari');
    }
};
