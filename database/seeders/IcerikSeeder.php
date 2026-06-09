<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IcerikSeeder extends Seeder
{
    public function run(): void
    {
        $kayitlar = [
            // ─── Site Geneli ──────────────────────────────────────────────
            ['sayfa' => 'site', 'alan' => 'sirket_adi',           'baslik' => 'Şirket Adı',                  'tip' => 'metin',    'deger' => 'SUÇEK',                                                                    'sira' => 1],
            ['sayfa' => 'site', 'alan' => 'slogan',               'baslik' => 'Slogan',                      'tip' => 'metin',    'deger' => 'Mimarlık · İnşaat · Koleksiyon',                                           'sira' => 2],
            ['sayfa' => 'site', 'alan' => 'footer_aciklama',      'baslik' => 'Footer Açıklaması',           'tip' => 'textarea', 'deger' => 'Mimarlık, inşaat, antika koleksiyon ve mağaza hizmetlerinde güvenilir adresiniz.', 'sira' => 3],
            ['sayfa' => 'site', 'alan' => 'telefon',              'baslik' => 'Telefon',                     'tip' => 'metin',    'deger' => '+90 (544) 294 84 02',                                                      'sira' => 4],
            ['sayfa' => 'site', 'alan' => 'email',                'baslik' => 'E-posta',                     'tip' => 'metin',    'deger' => 'info@sucek.com.tr',                                                        'sira' => 5],
            ['sayfa' => 'site', 'alan' => 'adres',                'baslik' => 'Adres',                       'tip' => 'textarea', 'deger' => 'Kazım Karabekir Mah. Misaki-i Milli Cad. No: 6/A Etimesgut, Ankara',      'sira' => 6],
            ['sayfa' => 'site', 'alan' => 'calisma_hafta',        'baslik' => 'Çalışma Saati (Hafta içi)',   'tip' => 'metin',    'deger' => 'Pzt–Cum 09:00–18:00',                                                      'sira' => 7],
            ['sayfa' => 'site', 'alan' => 'calisma_cumartesi',    'baslik' => 'Çalışma Saati (Cumartesi)',   'tip' => 'metin',    'deger' => 'Cts 10:00–15:00',                                                          'sira' => 8],
            ['sayfa' => 'site', 'alan' => 'calisma_pazar',        'baslik' => 'Çalışma Saati (Pazar)',       'tip' => 'metin',    'deger' => 'Pazar: Kapalı',                                                            'sira' => 9],
            ['sayfa' => 'site', 'alan' => 'logo',                 'baslik' => 'Logo',                        'tip' => 'gorsel',   'deger' => null,                                                                       'sira' => 10],

            // ─── Ana Sayfa ────────────────────────────────────────────────
            ['sayfa' => 'anasayfa', 'alan' => 'banner_metni',      'baslik' => 'Banner Metni',                'tip' => 'metin',    'deger' => 'Seçili ürünlerde %30 indirim ve ücretsiz kargo fırsatını kaçırma!',       'sira' => 1],
            ['sayfa' => 'anasayfa', 'alan' => 'mimarlik_gorsel',   'baslik' => 'Mimarlık Kart Görseli',       'tip' => 'gorsel',   'deger' => null,                                                                       'sira' => 2],
            ['sayfa' => 'anasayfa', 'alan' => 'koleksiyon_gorsel', 'baslik' => 'Koleksiyon Kart Görseli',     'tip' => 'gorsel',   'deger' => null,                                                                       'sira' => 3],
            ['sayfa' => 'anasayfa', 'alan' => 'insaat_gorsel',     'baslik' => 'İnşaat Kart Görseli',         'tip' => 'gorsel',   'deger' => null,                                                                       'sira' => 4],
            ['sayfa' => 'anasayfa', 'alan' => 'magaza_gorsel',     'baslik' => 'Mağaza Kart Görseli',         'tip' => 'gorsel',   'deger' => null,                                                                       'sira' => 5],
            ['sayfa' => 'anasayfa', 'alan' => 'yaklasim_baslik',   'baslik' => 'Yaklaşım Başlığı',            'tip' => 'metin',    'deger' => 'Kalite, Güven ve Estetik',                                                 'sira' => 6],
            ['sayfa' => 'anasayfa', 'alan' => 'yaklasim_metin',    'baslik' => 'Yaklaşım Metni',              'tip' => 'textarea', 'deger' => 'Her projede müşterilerimizin hayalini gerçeğe dönüştürüyoruz. Mimarlıktan inşaata, antika koleksiyondan mağazacılığa kadar uzanan geniş hizmet yelpazesiyle yanınızdayız.', 'sira' => 7],
            ['sayfa' => 'anasayfa', 'alan' => 'yaklasim_gorsel',   'baslik' => 'Yaklaşım Görseli',            'tip' => 'gorsel',   'deger' => null,                                                                       'sira' => 8],

            // ─── Mimarlık ─────────────────────────────────────────────────
            ['sayfa' => 'mimarlik', 'alan' => 'banner_metni',   'baslik' => 'Banner Metni',   'tip' => 'metin',    'deger' => 'Ücretsiz ön görüşme için hemen randevu alın!',                                  'sira' => 1],
            ['sayfa' => 'mimarlik', 'alan' => 'hero_gorsel',    'baslik' => 'Hero Görseli',   'tip' => 'gorsel',   'deger' => null,                                                                            'sira' => 2],
            ['sayfa' => 'mimarlik', 'alan' => 'hero_baslik',    'baslik' => 'Hero Başlığı',   'tip' => 'metin',    'deger' => 'Vizyonunuzu Gerçeğe Taşıyoruz',                                                'sira' => 3],
            ['sayfa' => 'mimarlik', 'alan' => 'hero_alt_baslik','baslik' => 'Hero Alt Yazısı','tip' => 'textarea', 'deger' => 'Ruhsat takibinden iç mimari tasarıma kadar kapsamlı mimarlık hizmetleri.',      'sira' => 4],

            // ─── İnşaat ───────────────────────────────────────────────────
            ['sayfa' => 'insaat', 'alan' => 'banner_metni',   'baslik' => 'Banner Metni',   'tip' => 'metin',    'deger' => 'Maliyet hesaplama aracımızı kullanmak için tıklayın →',                          'sira' => 1],
            ['sayfa' => 'insaat', 'alan' => 'hero_gorsel',    'baslik' => 'Hero Görseli',   'tip' => 'gorsel',   'deger' => null,                                                                              'sira' => 2],
            ['sayfa' => 'insaat', 'alan' => 'hero_baslik',    'baslik' => 'Hero Başlığı',   'tip' => 'metin',    'deger' => 'Sağlam Temeller, Mükemmel Sonuçlar',                                             'sira' => 3],
            ['sayfa' => 'insaat', 'alan' => 'hero_alt_baslik','baslik' => 'Hero Alt Yazısı','tip' => 'textarea', 'deger' => 'Anahtar teslim projelerden dekorasyona, maliyet optimizasyonundan denetim hizmetine.', 'sira' => 4],

            // ─── Koleksiyon ───────────────────────────────────────────────
            ['sayfa' => 'koleksiyon', 'alan' => 'banner_metni', 'baslik' => 'Banner Metni', 'tip' => 'metin',  'deger' => 'Koleksiyonunuzu değerletmek için randevu alın!',                                   'sira' => 1],
            ['sayfa' => 'koleksiyon', 'alan' => 'hero_gorsel',  'baslik' => 'Hero Görseli', 'tip' => 'gorsel', 'deger' => null,                                                                               'sira' => 2],
            ['sayfa' => 'koleksiyon', 'alan' => 'hero_baslik',  'baslik' => 'Hero Başlığı', 'tip' => 'metin',  'deger' => 'Nadir Eserler, Eşsiz Değerler',                                                   'sira' => 3],

            // ─── Mağaza ───────────────────────────────────────────────────
            ['sayfa' => 'magaza', 'alan' => 'banner_metni',   'baslik' => 'Banner Metni',   'tip' => 'metin',    'deger' => 'Seçili ürünlerde %30 indirim ve ücretsiz kargo fırsatını kaçırma!',             'sira' => 1],
            ['sayfa' => 'magaza', 'alan' => 'hero_gorsel',    'baslik' => 'Hero Görseli',   'tip' => 'gorsel',   'deger' => null,                                                                             'sira' => 2],
            ['sayfa' => 'magaza', 'alan' => 'hero_baslik',    'baslik' => 'Hero Başlığı',   'tip' => 'metin',    'deger' => 'Kaliteli Ürünler, Uygun Fiyatlar',                                              'sira' => 3],
            ['sayfa' => 'magaza', 'alan' => 'hero_alt_baslik','baslik' => 'Hero Alt Yazısı','tip' => 'textarea', 'deger' => 'Spor malzemeleri, dekorasyon ve inşaat ürünleri.',                              'sira' => 4],

            // ─── İletişim ─────────────────────────────────────────────────
            ['sayfa' => 'iletisim', 'alan' => 'harita_embed', 'baslik' => 'Google Maps Embed URL', 'tip' => 'url', 'deger' => 'https://maps.google.com/maps?q=Etimesgut+Ankara&output=embed', 'sira' => 1],

            // ─── Sistem Ayarları ──────────────────────────────────────────
            ['sayfa' => 'sistem', 'alan' => 'recaptcha_site_key',   'baslik' => 'reCAPTCHA Site Anahtarı',  'tip' => 'metin', 'deger' => '', 'sira' => 1],
            ['sayfa' => 'sistem', 'alan' => 'recaptcha_secret_key', 'baslik' => 'reCAPTCHA Gizli Anahtar', 'tip' => 'metin', 'deger' => '', 'sira' => 2],
            ['sayfa' => 'sistem', 'alan' => 'recaptcha_min_score',  'baslik' => 'reCAPTCHA Min. Skor',      'tip' => 'metin', 'deger' => '0.5', 'sira' => 3],
        ];

        foreach ($kayitlar as $k) {
            DB::table('site_icerik')->updateOrInsert(
                ['sayfa' => $k['sayfa'], 'alan' => $k['alan']],
                array_merge($k, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
