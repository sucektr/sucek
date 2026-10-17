<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('musteriler', function (Blueprint $table) {
            $table->enum('tip', ['musteri', 'tedarikci'])->default('musteri')->after('ad_soyad');
            $table->string('yetkili_kisi')->nullable()->after('ad_soyad');
        });

        if (Schema::hasTable('tedarikciler')) {
            $idEslesme = [];

            foreach (DB::table('tedarikciler')->get() as $tedarikci) {
                $yeniId = DB::table('musteriler')->insertGetId([
                    'ad_soyad'      => $tedarikci->unvan,
                    'tip'           => 'tedarikci',
                    'yetkili_kisi'  => $tedarikci->yetkili_kisi,
                    'telefon'       => $tedarikci->telefon,
                    'email'         => $tedarikci->email,
                    'adres'         => $tedarikci->adres,
                    'vergi_dairesi' => $tedarikci->vergi_dairesi,
                    'vergi_no'      => $tedarikci->vergi_no,
                    'notlar'        => $tedarikci->notlar,
                    'aktif'         => $tedarikci->aktif,
                    'created_at'    => $tedarikci->created_at,
                    'updated_at'    => $tedarikci->updated_at,
                ]);

                $idEslesme[$tedarikci->id] = $yeniId;
            }

            if (Schema::hasTable('tedarikci_hareketler')) {
                foreach (DB::table('tedarikci_hareketler')->get() as $hareket) {
                    if (!isset($idEslesme[$hareket->tedarikci_id])) {
                        continue;
                    }

                    DB::table('cari_hareketler')->insert([
                        'musteri_id'  => $idEslesme[$hareket->tedarikci_id],
                        // Tedarikci modelinde 'odeme' = yapilan odeme, musteri modelindeki
                        // 'tahsilat' ile ayni islevi gorur (bakiyeyi azaltir).
                        'tur'         => $hareket->tur === 'odeme' ? 'tahsilat' : 'borc',
                        'tutar'       => $hareket->tutar,
                        'aciklama'    => $hareket->aciklama,
                        'tarih'       => $hareket->tarih,
                        'vade_tarihi' => $hareket->vade_tarihi,
                        'odendi_mi'   => $hareket->odendi_mi,
                        'created_at'  => $hareket->created_at,
                        'updated_at'  => $hareket->updated_at,
                    ]);
                }
            }
        }

        Schema::dropIfExists('tedarikci_hareketler');
        Schema::dropIfExists('tedarikciler');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musteriler', function (Blueprint $table) {
            $table->dropColumn(['tip', 'yetkili_kisi']);
        });
    }
};
