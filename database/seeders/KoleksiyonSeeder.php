<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KoleksiyonSeeder extends Seeder
{
    public function run(): void
    {
        $koleksiyonlar = [
            // ─── SAAT ─────────────────────────────────────────────────────
            [
                'ad'         => 'Osmanlı Köşk Saati 19. yy',
                'kategori'   => 'saat',
                'fiyat'      => 85000.00,
                'durum'      => 'satista',
                'one_cikan'  => true,
                'aciklama'   => 'Ceviz kasa, bronz varak detaylar, orijinal mekanik kurmalı hareket. Yükseklik 110cm. Ayak üstü köşk tipi. Tamamen çalışır durumda.',
                'ozellikler' => ['dönem' => '19. yüzyıl', 'menşei' => 'Osmanlı', 'malzeme' => 'Ceviz + Bronz', 'durum' => 'Mükemmel', 'yükseklik' => '110cm'],
                'stok_kodu'  => 'STS-001',
            ],
            [
                'ad'         => 'Art Deco Masa Saati Fransa',
                'kategori'   => 'saat',
                'fiyat'      => 22500.00,
                'durum'      => 'satista',
                'one_cikan'  => false,
                'aciklama'   => '1920\'ler Fransız Art Deco dönemi, mermer kaide, altın yaldız kadran. 30 günlük sarmalı mekanizma.',
                'ozellikler' => ['dönem' => '1920\'ler', 'menşei' => 'Fransa', 'stil' => 'Art Deco', 'malzeme' => 'Mermer + Yaldız Bronz'],
                'stok_kodu'  => 'STS-002',
            ],
            [
                'ad'         => 'İngiliz Deniz Kronometresi',
                'kategori'   => 'saat',
                'fiyat'      => 65000.00,
                'durum'      => 'rezerve',
                'one_cikan'  => false,
                'aciklama'   => 'Hamilton 1940\'lar ABD Deniz Kuvvetleri seri üretim kronometresi. Mahun kutu, gümüş kadran, çift kapak.',
                'ozellikler' => ['dönem' => '1940\'lar', 'menşei' => 'ABD', 'marka' => 'Hamilton', 'amaç' => 'Deniz navigasyonu'],
                'stok_kodu'  => 'STS-003',
            ],

            // ─── NÜMİZMATİK ───────────────────────────────────────────────
            [
                'ad'         => 'Osmanlı Altın Takımı (5 Adet)',
                'kategori'   => 'numizmatik',
                'fiyat'      => 42000.00,
                'durum'      => 'satista',
                'one_cikan'  => true,
                'aciklama'   => 'II. Abdülhamid dönemi çeşitli apointe altınlar. EF+ kondisyon. NGC sertifikalı birer adet.',
                'ozellikler' => ['dönem' => 'II. Abdülhamid', 'metal' => 'Altın', 'adet' => 5, 'kondisyon' => 'EF+', 'sertifika' => 'NGC'],
                'stok_kodu'  => 'NUM-001',
            ],
            [
                'ad'         => 'Selçuklu Gümüş Dirhem',
                'kategori'   => 'numizmatik',
                'fiyat'      => 8500.00,
                'durum'      => 'satista',
                'one_cikan'  => false,
                'aciklama'   => '12. yüzyıl Anadolu Selçuklu dönemi gümüş dirhem. Net tarih okunur. VF kondisyon.',
                'ozellikler' => ['dönem' => '12. yüzyıl', 'devlet' => 'Anadolu Selçukluları', 'metal' => 'Gümüş', 'kondisyon' => 'VF'],
                'stok_kodu'  => 'NUM-002',
            ],
            [
                'ad'         => 'Cumhuriyet Gümüş 50 Kuruş 1935',
                'kategori'   => 'numizmatik',
                'fiyat'      => 3200.00,
                'durum'      => 'satista',
                'one_cikan'  => false,
                'aciklama'   => 'Türkiye Cumhuriyeti 1935 tarihli gümüş 50 kuruş. Atatürk portöesi, XF kondisyon.',
                'ozellikler' => ['yıl' => 1935, 'değer' => '50 Kuruş', 'metal' => 'Gümüş', 'kondisyon' => 'XF'],
                'stok_kodu'  => 'NUM-003',
            ],

            // ─── ANTİKA ───────────────────────────────────────────────────
            [
                'ad'         => 'Osmanlı Sedef Kakma Sehpa',
                'kategori'   => 'antika',
                'fiyat'      => 38000.00,
                'durum'      => 'satista',
                'one_cikan'  => true,
                'aciklama'   => '19. yüzyıl Şam işi sedef ve bağa kakma ceviz ağacı sehpa. 70 × 50 × 55 cm. Orijinal, restorasyon yok.',
                'ozellikler' => ['dönem' => '19. yüzyıl', 'menşei' => 'Şam', 'malzeme' => 'Ceviz + Sedef + Bağa', 'ölçü' => '70×50×55cm'],
                'stok_kodu'  => 'ANT-001',
            ],
            [
                'ad'         => 'El Yazması Kur\'an-ı Kerim 18. yy',
                'kategori'   => 'antika',
                'fiyat'      => 120000.00,
                'durum'      => 'satista',
                'one_cikan'  => true,
                'aciklama'   => 'Nesih hatlı, tezhipli 18. yüzyıl el yazması. 350 varak, deri cilt. Eksiksiz, sağlam cilt.',
                'ozellikler' => ['dönem' => '18. yüzyıl', 'hat' => 'Nesih', 'varak' => 350, 'cilt' => 'Deri', 'durum' => 'Sağlam'],
                'stok_kodu'  => 'ANT-002',
            ],
            [
                'ad'         => 'Bakır Şamdanlar (Çift)',
                'kategori'   => 'antika',
                'fiyat'      => 12500.00,
                'durum'      => 'satista',
                'one_cikan'  => false,
                'aciklama'   => 'Geç Osmanlı dönemi, dövme bakır çift şamdan. Kabartma motifleri, 45cm yükseklik. Eş takım.',
                'ozellikler' => ['dönem' => 'Geç Osmanlı', 'malzeme' => 'Dövme Bakır', 'yükseklik' => '45cm', 'adet' => 2],
                'stok_kodu'  => 'ANT-003',
            ],
            [
                'ad'         => 'Çini Tabak İznik 17. yy',
                'kategori'   => 'antika',
                'fiyat'      => 55000.00,
                'durum'      => 'satildi',
                'one_cikan'  => false,
                'aciklama'   => '17. yüzyıl İznik çini tabak. Lale ve karanfil motifli, 32cm çap. Küçük kenar kıymığı dışında mükemmel.',
                'ozellikler' => ['dönem' => '17. yüzyıl', 'menşei' => 'İznik', 'çap' => '32cm', 'motif' => 'Lale ve Karanfil'],
                'stok_kodu'  => 'ANT-004',
            ],
        ];

        foreach ($koleksiyonlar as $veri) {
            $slug = Str::slug($veri['ad'], '-');

            DB::table('koleksiyonlar')->updateOrInsert(
                ['slug' => $slug],
                [
                    'ad'             => $veri['ad'],
                    'slug'           => $slug,
                    'aciklama'       => $veri['aciklama'],
                    'kategori'       => $veri['kategori'],
                    'fiyat'          => $veri['fiyat'],
                    'gorsel'         => null,
                    'gorseller'      => null,
                    'dosyalar'       => null,
                    'ozellikler'     => json_encode($veri['ozellikler'] ?? []),
                    'stok_kodu'      => $veri['stok_kodu'],
                    'durum'          => $veri['durum'],
                    'aktif'          => true,
                    'one_cikan'      => $veri['one_cikan'],
                    'kargo_bedeli'   => 0,
                    'kargo_kim_oder' => 'musteri',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }
    }
}
