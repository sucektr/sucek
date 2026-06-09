<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UrunSeeder extends Seeder
{
    public function run(): void
    {
        $urunler = [
            // ─── SPOR ─────────────────────────────────────────────────────
            [
                'ad'          => 'Profesyonel Futbol Topu',
                'kategori'    => 'spor',
                'alt_kategori'=> 'futbol',
                'fiyat'       => 649.90,
                'eski_fiyat'  => 849.90,
                'stok'        => 25,
                'one_cikan'   => true,
                'aciklama'    => 'FIFA onaylı, 5 numara profesyonel maç topu. Dayanıklı PU deri, su geçirmez kaplama.',
                'ozellikler'  => ['boyut' => '5 Numara', 'malzeme' => 'PU Deri', 'onay' => 'FIFA Onaylı'],
            ],
            [
                'ad'          => 'Koşu Bandı 2200W',
                'kategori'    => 'spor',
                'alt_kategori'=> 'fitness',
                'fiyat'       => 12500.00,
                'eski_fiyat'  => 15000.00,
                'stok'        => 8,
                'one_cikan'   => true,
                'aciklama'    => '2200W motor gücü, 0–18 km/s hız aralığı, 12 eğim kademesi, LCD ekran.',
                'ozellikler'  => ['motor' => '2200W', 'hiz' => '0-18 km/s', 'egim' => '12 kademe', 'maksimum_kullanici_kg' => '120'],
            ],
            [
                'ad'          => 'Dambıl Seti 20 kg',
                'kategori'    => 'spor',
                'alt_kategori'=> 'fitness',
                'fiyat'       => 1890.00,
                'eski_fiyat'  => null,
                'stok'        => 15,
                'one_cikan'   => false,
                'aciklama'    => 'Kauçuk kaplı dökme demir dambıl seti. 2 × 10 kg. Ergonomik grip.',
                'ozellikler'  => ['agirlik' => '2 × 10 kg', 'malzeme' => 'Kauçuk kaplı dökme demir'],
            ],
            [
                'ad'          => 'Profesyonel Tenis Raketi',
                'kategori'    => 'spor',
                'alt_kategori'=> 'tenis',
                'fiyat'       => 2200.00,
                'eski_fiyat'  => 2800.00,
                'stok'        => 12,
                'one_cikan'   => false,
                'aciklama'    => 'Grafit kompozit gövde, 300g, 100 in² kafa. Orta-ileri seviye oyuncular için ideal.',
                'ozellikler'  => ['agirlik' => '300g', 'kafa_yüzeyi' => '100 in²', 'malzeme' => 'Grafit Kompozit'],
            ],
            [
                'ad'          => 'Yoga Matı Ekstra Kalın 10mm',
                'kategori'    => 'spor',
                'alt_kategori'=> 'fitness',
                'fiyat'       => 420.00,
                'eski_fiyat'  => null,
                'stok'        => 40,
                'one_cikan'   => false,
                'aciklama'    => 'TPE malzeme, 183 × 61 cm, 10mm kalınlık. Kaymaz yüzey, taşıma askısı dahil.',
                'ozellikler'  => ['olcü' => '183 × 61 cm', 'kalinlik' => '10mm', 'malzeme' => 'TPE'],
            ],
            [
                'ad'          => 'Basketbol Potası Taşınabilir',
                'kategori'    => 'spor',
                'alt_kategori'=> 'basketbol',
                'fiyat'       => 5499.00,
                'eski_fiyat'  => 6200.00,
                'stok'        => 5,
                'one_cikan'   => true,
                'aciklama'    => 'Yükseklik ayarlı (2.28m – 3.05m), 40L su/kum bazası, kırılmaz cam panya.',
                'ozellikler'  => ['yükseklik' => '2.28 – 3.05m', 'panya' => 'Kırılmaz cam', 'baza' => '40L'],
            ],

            // ─── DEKORASYON ───────────────────────────────────────────────
            [
                'ad'          => 'Seramik Vazo El Yapımı',
                'kategori'    => 'dekorasyon',
                'alt_kategori'=> 'vazo',
                'fiyat'       => 380.00,
                'eski_fiyat'  => null,
                'stok'        => 20,
                'one_cikan'   => true,
                'aciklama'    => 'Çini desenli el yapımı Kütahya seramiği. 35cm yükseklik, mat glazür.',
                'ozellikler'  => ['yükseklik' => '35cm', 'malzeme' => 'Kütahya Seramiği', 'finish' => 'Mat Glazür'],
            ],
            [
                'ad'          => 'Ahşap Duvar Saati',
                'kategori'    => 'dekorasyon',
                'alt_kategori'=> 'saat',
                'fiyat'       => 750.00,
                'eski_fiyat'  => 950.00,
                'stok'        => 18,
                'one_cikan'   => false,
                'aciklama'    => 'Ceviz ağacı, çap 45cm, sessiz titreşimsiz mekanizma. Modern Türk tasarımı.',
                'ozellikler'  => ['cap' => '45cm', 'malzeme' => 'Ceviz ağacı', 'mekanizma' => 'Sessiz titreşimsiz'],
            ],
            [
                'ad'          => 'Pamuk Makrome Halı 120×180',
                'kategori'    => 'dekorasyon',
                'alt_kategori'=> 'hali-kilim',
                'fiyat'       => 1250.00,
                'eski_fiyat'  => null,
                'stok'        => 10,
                'one_cikan'   => false,
                'aciklama'    => '%100 doğal pamuk, el dokuması makrome halı. 120 × 180 cm.',
                'ozellikler'  => ['olcü' => '120 × 180 cm', 'malzeme' => '%100 Pamuk', 'uretim' => 'El dokuması'],
            ],
            [
                'ad'          => 'Endüstriyel Loft Lambası',
                'kategori'    => 'dekorasyon',
                'alt_kategori'=> 'aydinlatma',
                'fiyat'       => 890.00,
                'eski_fiyat'  => 1100.00,
                'stok'        => 22,
                'one_cikan'   => true,
                'aciklama'    => 'Mat siyah metal gövde, E27 duy, 150cm ayarlanabilir askı kablosu. Ampul dahil değil.',
                'ozellikler'  => ['duy' => 'E27', 'kablo' => '150cm ayarlı', 'renk' => 'Mat Siyah'],
            ],
            [
                'ad'          => 'Mozaik Ayna Oval 50×70',
                'kategori'    => 'dekorasyon',
                'alt_kategori'=> 'ayna',
                'fiyat'       => 620.00,
                'eski_fiyat'  => null,
                'stok'        => 14,
                'one_cikan'   => false,
                'aciklama'    => 'El yapımı Türk mozaiği çerçeve, 50 × 70 cm oval ayna. Asma aparatı dahil.',
                'ozellikler'  => ['olcü' => '50 × 70 cm', 'cerceve' => 'El yapımı Türk mozaiği'],
            ],

            // ─── İNŞAAT ───────────────────────────────────────────────────
            [
                'ad'          => 'Granit Mutfak Tezgahı (m²)',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'yüzey-kaplamalari',
                'fiyat'       => 1800.00,
                'eski_fiyat'  => null,
                'stok'        => 100,
                'one_cikan'   => true,
                'aciklama'    => 'Siyah Galaxy granit, 3cm kalınlık, parlak yüzey. Montaj hariç m² fiyatıdır.',
                'ozellikler'  => ['malzeme' => 'Siyah Galaxy Granit', 'kalinlik' => '3cm', 'finish' => 'Parlak'],
            ],
            [
                'ad'          => 'Isı Yalıtım Levhası EPS 5cm (m²)',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'yalitim',
                'fiyat'       => 85.00,
                'eski_fiyat'  => null,
                'stok'        => 500,
                'one_cikan'   => false,
                'aciklama'    => 'EPS 70 polistiren köpük, 5cm × 100 × 50 cm panel. Dış cephe ve tavan yalıtımı.',
                'ozellikler'  => ['kalinlik' => '5cm', 'boyut' => '100 × 50 cm', 'tip' => 'EPS 70'],
            ],
            [
                'ad'          => 'Porselan Karo 60×60 (m²)',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'seramik-karo',
                'fiyat'       => 320.00,
                'eski_fiyat'  => 380.00,
                'stok'        => 200,
                'one_cikan'   => true,
                'aciklama'    => 'Mat gri beton efekt, 60 × 60 cm, rektifiye kenar. İç/dış zemin kullanıma uygun.',
                'ozellikler'  => ['boyut' => '60 × 60 cm', 'renk' => 'Mat Gri Beton Efekt', 'kenar' => 'Rektifiye'],
            ],
            [
                'ad'          => 'Alüminyum Sürme Doğrama (m²)',
                'kategori'    => 'insaat',
                'alt_kategori'=> 'dograma',
                'fiyat'       => 2400.00,
                'eski_fiyat'  => null,
                'stok'        => 50,
                'one_cikan'   => false,
                'aciklama'    => 'Isıcamlı çift sürme alüminyum doğrama. 4+16+4 ısı cam, anthrazit renk. Montaj dahil fiyat.',
                'ozellikler'  => ['cam' => '4+16+4 Isı Cam', 'renk' => 'Anthrazit', 'montaj' => 'Dahil'],
            ],
        ];

        foreach ($urunler as $veri) {
            $slug = Str::slug($veri['ad'], '-');

            DB::table('urunler')->updateOrInsert(
                ['slug' => $slug],
                [
                    'ad'           => $veri['ad'],
                    'slug'         => $slug,
                    'aciklama'     => $veri['aciklama'],
                    'fiyat'        => $veri['fiyat'],
                    'eski_fiyat'   => $veri['eski_fiyat'],
                    'kdv_orani'    => 20,
                    'kdv_dahil'    => true,
                    'kargo_bedeli' => 0,
                    'kargo_kim_oder' => 'magaza',
                    'kategori'     => $veri['kategori'],
                    'alt_kategori' => $veri['alt_kategori'] ?? null,
                    'gorsel'       => null,
                    'gorseller'    => null,
                    'dosyalar'     => null,
                    'ozellikler'   => json_encode($veri['ozellikler'] ?? []),
                    'stok_kodu'    => strtoupper(Str::random(6)),
                    'stok'         => $veri['stok'],
                    'aktif'        => true,
                    'one_cikan'    => $veri['one_cikan'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );
        }
    }
}
