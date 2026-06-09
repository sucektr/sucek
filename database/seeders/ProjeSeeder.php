<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjeSeeder extends Seeder
{
    public function run(): void
    {
        $projeler = [
            // ─── MİMARLIK ─────────────────────────────────────────────────
            [
                'baslik'      => 'Etimesgut Villa Projesi',
                'kategori'    => 'mimarlik',
                'alt_kategori'=> 'konut-tasarimi',
                'konum'       => 'Etimesgut, Ankara',
                'yil'         => 2023,
                'one_cikan'   => true,
                'sira'        => 1,
                'aciklama'    => '350 m² müstakil villa. Modern Akdeniz mimarisi, açık avlu konsepti, kuzey bahçe ve güney teras tasarımı. Ruhsat sürecinden anahtar teslimine kadar tam hizmet.',
                'detaylar'    => ['alan_m2' => 350, 'arsa_m2' => 600, 'kat_sayisi' => 2, 'süre_ay' => 14],
            ],
            [
                'baslik'      => 'Çankaya Ofis Dönüşümü',
                'kategori'    => 'mimarlik',
                'alt_kategori'=> 'ic-mimari',
                'konum'       => 'Çankaya, Ankara',
                'yil'         => 2024,
                'one_cikan'   => true,
                'sira'        => 2,
                'aciklama'    => '220 m² kurumsal ofis iç mekân tasarımı ve uygulaması. Açık ofis planı, toplantı odaları, lounge alanı. Mevcut bina sınırları içinde işlevsellik ve estetik bütünlüğü.',
                'detaylar'    => ['alan_m2' => 220, 'kişi_kapasitesi' => 35, 'süre_ay' => 4],
            ],
            [
                'baslik'      => 'Sincan Akaryakıt İstasyonu',
                'kategori'    => 'mimarlik',
                'alt_kategori'=> 'ticari-yapi',
                'konum'       => 'Sincan, Ankara',
                'yil'         => 2022,
                'one_cikan'   => false,
                'sira'        => 3,
                'aciklama'    => 'Akaryakıt istasyonu mimari proje ve ruhsat süreci. Kanopi, ofis binası, araç yıkama ünitesi. Belediye ve EPDK ruhsatları tam teslim.',
                'detaylar'    => ['arsa_m2' => 1200, 'süre_ay' => 8],
            ],
            [
                'baslik'      => 'Pursaklar Ruhsat Danışmanlığı',
                'kategori'    => 'mimarlik',
                'alt_kategori'=> 'ruhsat-sureci',
                'konum'       => 'Pursaklar, Ankara',
                'yil'         => 2024,
                'one_cikan'   => false,
                'sira'        => 4,
                'aciklama'    => 'Kat karşılığı inşaat anlaşmazlığı sonrası yeniden ruhsat edinme danışmanlığı. İmar durumu analizi, mimari proje revizyonu, belediye süreç takibi.',
                'detaylar'    => ['parsel_m2' => 420, 'süre_ay' => 6],
            ],
            [
                'baslik'      => 'Mamak Kafe İç Mimari',
                'kategori'    => 'mimarlik',
                'alt_kategori'=> 'ic-mimari',
                'konum'       => 'Mamak, Ankara',
                'yil'         => 2023,
                'one_cikan'   => false,
                'sira'        => 5,
                'aciklama'    => '90 m² Endüstriyel + Bohem konsept kafe tasarımı ve uygulaması. Özel mobilya, aydınlatma ve zemin çözümleri.',
                'detaylar'    => ['alan_m2' => 90, 'koltuk_kapasitesi' => 42, 'süre_ay' => 3],
            ],

            // ─── İNŞAAT ───────────────────────────────────────────────────
            [
                'baslik'      => 'Altındağ Müstakil Ev İnşaatı',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'anahtar-teslim',
                'konum'       => 'Altındağ, Ankara',
                'yil'         => 2023,
                'one_cikan'   => true,
                'sira'        => 1,
                'aciklama'    => '280 m² betonarme müstakil konut anahtar teslim inşaatı. Temel, kaba inşaat, ince işçilik ve dış cephe dahil. Tapu ve iskân teslimi.',
                'detaylar'    => ['alan_m2' => 280, 'kat_sayisi' => 2, 'süre_ay' => 18, 'bütçe_tl' => 4500000],
            ],
            [
                'baslik'      => 'Keçiören Daire Tadilat ve Dekorasyon',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'tadilat-dekorasyon',
                'konum'       => 'Keçiören, Ankara',
                'yil'         => 2024,
                'one_cikan'   => true,
                'sira'        => 2,
                'aciklama'    => '130 m² 3+1 daire komple tadilat. Zemin değişimi, mutfak ve banyo yenileme, alçıpan tavan, duvar kaplama. 6 haftada teslim.',
                'detaylar'    => ['alan_m2' => 130, 'süre_hafta' => 6],
            ],
            [
                'baslik'      => 'Yenimahalle İşyeri Çelik Çatı',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'cati-sistemleri',
                'konum'       => 'Yenimahalle, Ankara',
                'yil'         => 2022,
                'one_cikan'   => false,
                'sira'        => 3,
                'aciklama'    => '800 m² çelik konstrüksiyon çatı sistemi. Sandviç panel örtü, yağmur oluğu ve drenaj. Mevcut yapı üzeri uygulama.',
                'detaylar'    => ['alan_m2' => 800, 'süre_ay' => 3],
            ],
            [
                'baslik'      => 'Gölbaşı Çevre Düzenleme',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'cevre-duzenleme',
                'konum'       => 'Gölbaşı, Ankara',
                'yil'         => 2024,
                'one_cikan'   => false,
                'sira'        => 4,
                'aciklama'    => '2400 m² villa bahçe ve çevre düzenleme projesi. Peyzaj tasarımı, beton bordür, sulama sistemi, dış aydınlatma ve parke zemin.',
                'detaylar'    => ['alan_m2' => 2400, 'süre_ay' => 5],
            ],
        ];

        foreach ($projeler as $veri) {
            $slug = Str::slug($veri['baslik'], '-');

            DB::table('projeler')->updateOrInsert(
                ['slug' => $slug],
                [
                    'baslik'       => $veri['baslik'],
                    'slug'         => $slug,
                    'aciklama'     => $veri['aciklama'],
                    'kategori'     => $veri['kategori'],
                    'alt_kategori' => $veri['alt_kategori'] ?? null,
                    'konum'        => $veri['konum'],
                    'yil'          => $veri['yil'],
                    'kapak_gorsel' => null,
                    'gorseller'    => null,
                    'detaylar'     => json_encode($veri['detaylar'] ?? []),
                    'aktif'        => true,
                    'one_cikan'    => $veri['one_cikan'],
                    'sira'         => $veri['sira'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
