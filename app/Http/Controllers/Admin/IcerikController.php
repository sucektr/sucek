<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Icerik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IcerikController extends Controller
{
    private function sayfaTanimlari(): array
    {
        return [
            'site' => [
                'baslik'   => 'Site Geneli',
                'ikon'     => 'ti-settings',
                'aciklama' => 'Şirket adı, iletişim bilgileri, çalışma saatleri, logo',
                'alanlar'  => [
                    ['alan' => 'sirket_adi',       'baslik' => 'Şirket Adı',                'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'slogan',            'baslik' => 'Slogan',                    'tip' => 'metin',    'sira' => 2],
                    ['alan' => 'footer_aciklama',   'baslik' => 'Footer Açıklaması',         'tip' => 'textarea', 'sira' => 3],
                    ['alan' => 'telefon',           'baslik' => 'Telefon',                   'tip' => 'metin',    'sira' => 4],
                    ['alan' => 'email',             'baslik' => 'E-posta',                   'tip' => 'metin',    'sira' => 5],
                    ['alan' => 'adres',             'baslik' => 'Adres',                     'tip' => 'textarea', 'sira' => 6],
                    ['alan' => 'calisma_hafta',     'baslik' => 'Çalışma Saati (Hafta içi)', 'tip' => 'metin',    'sira' => 7],
                    ['alan' => 'calisma_cumartesi', 'baslik' => 'Çalışma Saati (Cumartesi)', 'tip' => 'metin',    'sira' => 8],
                    ['alan' => 'calisma_pazar',     'baslik' => 'Çalışma Saati (Pazar)',     'tip' => 'metin',    'sira' => 9],
                    ['alan' => 'logo',              'baslik' => 'Logo',                      'tip' => 'gorsel',   'sira' => 10],
                    ['alan' => 'seo_baslik',        'baslik' => 'SEO — Varsayılan Sayfa Başlığı (boşsa: SUÇEK — Mimarlık · ...)', 'tip' => 'metin',    'sira' => 11],
                    ['alan' => 'seo_aciklama',      'baslik' => 'SEO — Varsayılan Meta Açıklaması',                              'tip' => 'textarea', 'sira' => 12],
                    ['alan' => 'sosyal_instagram',  'baslik' => 'Sosyal Medya — Instagram URL',  'tip' => 'url', 'sira' => 13],
                    ['alan' => 'sosyal_facebook',   'baslik' => 'Sosyal Medya — Facebook URL',   'tip' => 'url', 'sira' => 14],
                    ['alan' => 'sosyal_pinterest',  'baslik' => 'Sosyal Medya — Pinterest URL',  'tip' => 'url', 'sira' => 15],
                    ['alan' => 'sosyal_whatsapp',   'baslik' => 'Sosyal Medya — WhatsApp URL (örn: https://wa.me/905551234567)', 'tip' => 'url', 'sira' => 16],
                    ['alan' => 'sosyal_youtube',    'baslik' => 'Sosyal Medya — YouTube URL (boş = gizle)',    'tip' => 'url', 'sira' => 17],
                    ['alan' => 'sosyal_linkedin',   'baslik' => 'Sosyal Medya — LinkedIn URL (boş = gizle)',   'tip' => 'url', 'sira' => 18],
                    ['alan' => 'sosyal_tiktok',     'baslik' => 'Sosyal Medya — TikTok URL (boş = gizle)',     'tip' => 'url', 'sira' => 19],
                ],
            ],
            'anasayfa' => [
                'baslik'   => 'Ana Sayfa',
                'ikon'     => 'ti-home',
                'aciklama' => 'Banner, hero kart görselleri, yaklaşım, referanslar + SEO',
                'alanlar'  => [
                    ['alan' => 'banner_metni',      'baslik' => 'Banner Metni',                                  'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'banner_link',        'baslik' => 'Banner Linki (boş = otomatik mağaza sayfası)', 'tip' => 'url',      'sira' => 2],
                    ['alan' => 'mimarlik_gorsel',   'baslik' => 'Mimarlık Kart Görseli',   'tip' => 'gorsel',   'sira' => 3],
                    ['alan' => 'koleksiyon_gorsel', 'baslik' => 'Koleksiyon Kart Görseli', 'tip' => 'gorsel',   'sira' => 4],
                    ['alan' => 'insaat_gorsel',     'baslik' => 'İnşaat Kart Görseli',     'tip' => 'gorsel',   'sira' => 5],
                    ['alan' => 'magaza_gorsel',     'baslik' => 'Mağaza Kart Görseli',     'tip' => 'gorsel',   'sira' => 6],
                    ['alan' => 'yaklasim_baslik',   'baslik' => 'Yaklaşım Başlığı',        'tip' => 'metin',    'sira' => 7],
                    ['alan' => 'yaklasim_metin',    'baslik' => 'Yaklaşım Metni',          'tip' => 'textarea', 'sira' => 8],
                    ['alan' => 'yaklasim_gorsel',   'baslik' => 'Yaklaşım Görseli',        'tip' => 'gorsel',   'sira' => 9],
                    ['alan' => 'ref_1_metin',       'baslik' => 'Referans 1 — Metin',      'tip' => 'textarea', 'sira' => 10],
                    ['alan' => 'ref_1_ad',          'baslik' => 'Referans 1 — Ad',         'tip' => 'metin',    'sira' => 11],
                    ['alan' => 'ref_1_unvan',       'baslik' => 'Referans 1 — Unvan',      'tip' => 'metin',    'sira' => 12],
                    ['alan' => 'ref_2_metin',       'baslik' => 'Referans 2 — Metin',      'tip' => 'textarea', 'sira' => 13],
                    ['alan' => 'ref_2_ad',          'baslik' => 'Referans 2 — Ad',         'tip' => 'metin',    'sira' => 14],
                    ['alan' => 'ref_2_unvan',       'baslik' => 'Referans 2 — Unvan',      'tip' => 'metin',    'sira' => 15],
                    ['alan' => 'ref_3_metin',       'baslik' => 'Referans 3 — Metin',      'tip' => 'textarea', 'sira' => 16],
                    ['alan' => 'ref_3_ad',          'baslik' => 'Referans 3 — Ad',         'tip' => 'metin',    'sira' => 17],
                    ['alan' => 'ref_3_unvan',       'baslik' => 'Referans 3 — Unvan',      'tip' => 'metin',    'sira' => 18],
                    ['alan' => 'seo_baslik',        'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 19],
                    ['alan' => 'seo_aciklama',      'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 20],
                ],
            ],
            'mimarlik' => [
                'baslik'   => 'Mimarlık',
                'ikon'     => 'ti-building',
                'aciklama' => 'Hero, hizmet kartları, süreç adımları, SSS, CTA',
                'alanlar'  => [
                    ['alan' => 'banner_metni',      'baslik' => 'Banner Metni',             'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'hero_gorsel',       'baslik' => 'Hero Görseli',             'tip' => 'gorsel',   'sira' => 2],
                    ['alan' => 'hero_baslik',       'baslik' => 'Hero Başlığı',             'tip' => 'metin',    'sira' => 3],
                    ['alan' => 'hero_alt_baslik',   'baslik' => 'Hero Alt Yazısı',          'tip' => 'textarea', 'sira' => 4],
                    ['alan' => 'hizmet_1_baslik',   'baslik' => 'Hizmet 1 — Başlık',        'tip' => 'metin',    'sira' => 5],
                    ['alan' => 'hizmet_1_aciklama', 'baslik' => 'Hizmet 1 — Açıklama',     'tip' => 'textarea', 'sira' => 6],
                    ['alan' => 'hizmet_2_baslik',   'baslik' => 'Hizmet 2 — Başlık',        'tip' => 'metin',    'sira' => 7],
                    ['alan' => 'hizmet_2_aciklama', 'baslik' => 'Hizmet 2 — Açıklama',     'tip' => 'textarea', 'sira' => 8],
                    ['alan' => 'hizmet_3_baslik',   'baslik' => 'Hizmet 3 — Başlık',        'tip' => 'metin',    'sira' => 9],
                    ['alan' => 'hizmet_3_aciklama', 'baslik' => 'Hizmet 3 — Açıklama',     'tip' => 'textarea', 'sira' => 10],
                    ['alan' => 'hizmet_4_baslik',   'baslik' => 'Hizmet 4 — Başlık',        'tip' => 'metin',    'sira' => 11],
                    ['alan' => 'hizmet_4_aciklama', 'baslik' => 'Hizmet 4 — Açıklama',     'tip' => 'textarea', 'sira' => 12],
                    ['alan' => 'hizmet_5_baslik',   'baslik' => 'Hizmet 5 — Başlık',        'tip' => 'metin',    'sira' => 13],
                    ['alan' => 'hizmet_5_aciklama', 'baslik' => 'Hizmet 5 — Açıklama',     'tip' => 'textarea', 'sira' => 14],
                    ['alan' => 'hizmet_6_baslik',   'baslik' => 'Hizmet 6 — Başlık',        'tip' => 'metin',    'sira' => 15],
                    ['alan' => 'hizmet_6_aciklama', 'baslik' => 'Hizmet 6 — Açıklama',     'tip' => 'textarea', 'sira' => 16],
                    ['alan' => 'adim_1_baslik',     'baslik' => 'Süreç Adım 1 — Başlık',   'tip' => 'metin',    'sira' => 17],
                    ['alan' => 'adim_1_aciklama',   'baslik' => 'Süreç Adım 1 — Açıklama', 'tip' => 'textarea', 'sira' => 18],
                    ['alan' => 'adim_2_baslik',     'baslik' => 'Süreç Adım 2 — Başlık',   'tip' => 'metin',    'sira' => 19],
                    ['alan' => 'adim_2_aciklama',   'baslik' => 'Süreç Adım 2 — Açıklama', 'tip' => 'textarea', 'sira' => 20],
                    ['alan' => 'adim_3_baslik',     'baslik' => 'Süreç Adım 3 — Başlık',   'tip' => 'metin',    'sira' => 21],
                    ['alan' => 'adim_3_aciklama',   'baslik' => 'Süreç Adım 3 — Açıklama', 'tip' => 'textarea', 'sira' => 22],
                    ['alan' => 'adim_4_baslik',     'baslik' => 'Süreç Adım 4 — Başlık',   'tip' => 'metin',    'sira' => 23],
                    ['alan' => 'adim_4_aciklama',   'baslik' => 'Süreç Adım 4 — Açıklama', 'tip' => 'textarea', 'sira' => 24],
                    ['alan' => 'sss_1_soru',        'baslik' => 'SSS 1 — Soru',             'tip' => 'metin',    'sira' => 25],
                    ['alan' => 'sss_1_cevap',       'baslik' => 'SSS 1 — Cevap',            'tip' => 'textarea', 'sira' => 26],
                    ['alan' => 'sss_2_soru',        'baslik' => 'SSS 2 — Soru',             'tip' => 'metin',    'sira' => 27],
                    ['alan' => 'sss_2_cevap',       'baslik' => 'SSS 2 — Cevap',            'tip' => 'textarea', 'sira' => 28],
                    ['alan' => 'sss_3_soru',        'baslik' => 'SSS 3 — Soru',             'tip' => 'metin',    'sira' => 29],
                    ['alan' => 'sss_3_cevap',       'baslik' => 'SSS 3 — Cevap',            'tip' => 'textarea', 'sira' => 30],
                    ['alan' => 'sss_4_soru',        'baslik' => 'SSS 4 — Soru',             'tip' => 'metin',    'sira' => 31],
                    ['alan' => 'sss_4_cevap',       'baslik' => 'SSS 4 — Cevap',            'tip' => 'textarea', 'sira' => 32],
                    ['alan' => 'cta_baslik',          'baslik' => 'CTA Başlığı',                   'tip' => 'metin',    'sira' => 33],
                    ['alan' => 'cta_metin',           'baslik' => 'CTA Alt Metni',                 'tip' => 'metin',    'sira' => 34],
                    ['alan' => 'referanslar_baslik',  'baslik' => 'Referanslar — Bölüm Başlığı',   'tip' => 'metin',    'sira' => 35],
                    ['alan' => 'ref_kurum_1_ad',      'baslik' => 'Referans 1 — Kurum Adı',        'tip' => 'metin',    'sira' => 36],
                    ['alan' => 'ref_kurum_1_logo',    'baslik' => 'Referans 1 — Logo',             'tip' => 'gorsel',   'sira' => 37],
                    ['alan' => 'ref_kurum_2_ad',      'baslik' => 'Referans 2 — Kurum Adı',        'tip' => 'metin',    'sira' => 38],
                    ['alan' => 'ref_kurum_2_logo',    'baslik' => 'Referans 2 — Logo',             'tip' => 'gorsel',   'sira' => 39],
                    ['alan' => 'ref_kurum_3_ad',      'baslik' => 'Referans 3 — Kurum Adı',        'tip' => 'metin',    'sira' => 40],
                    ['alan' => 'ref_kurum_3_logo',    'baslik' => 'Referans 3 — Logo',             'tip' => 'gorsel',   'sira' => 41],
                    ['alan' => 'ref_kurum_4_ad',      'baslik' => 'Referans 4 — Kurum Adı',        'tip' => 'metin',    'sira' => 42],
                    ['alan' => 'ref_kurum_4_logo',    'baslik' => 'Referans 4 — Logo',             'tip' => 'gorsel',   'sira' => 43],
                    ['alan' => 'ref_kurum_5_ad',      'baslik' => 'Referans 5 — Kurum Adı',        'tip' => 'metin',    'sira' => 44],
                    ['alan' => 'ref_kurum_5_logo',    'baslik' => 'Referans 5 — Logo',             'tip' => 'gorsel',   'sira' => 45],
                    ['alan' => 'ref_kurum_6_ad',      'baslik' => 'Referans 6 — Kurum Adı',        'tip' => 'metin',    'sira' => 46],
                    ['alan' => 'ref_kurum_6_logo',    'baslik' => 'Referans 6 — Logo',             'tip' => 'gorsel',   'sira' => 47],
                    ['alan' => 'ref_kurum_7_ad',      'baslik' => 'Referans 7 — Kurum Adı',        'tip' => 'metin',    'sira' => 48],
                    ['alan' => 'ref_kurum_7_logo',    'baslik' => 'Referans 7 — Logo',             'tip' => 'gorsel',   'sira' => 49],
                    ['alan' => 'ref_kurum_8_ad',      'baslik' => 'Referans 8 — Kurum Adı',        'tip' => 'metin',    'sira' => 50],
                    ['alan' => 'ref_kurum_8_logo',    'baslik' => 'Referans 8 — Logo',             'tip' => 'gorsel',   'sira' => 51],
                    ['alan' => 'seo_baslik',          'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 52],
                    ['alan' => 'seo_aciklama',        'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 53],
                ],
            ],
            'ruhsat' => [
                'baslik'   => 'Ruhsat Süreci',
                'ikon'     => 'ti-file-certificate',
                'aciklama' => 'Ruhsat Süreci sayfası hero, içerik',
                'alanlar'  => [
                    ['alan' => 'banner_metni',    'baslik' => 'Banner Metni',              'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'hero_gorsel',     'baslik' => 'Hero Görseli',              'tip' => 'gorsel',   'sira' => 2],
                    ['alan' => 'hero_baslik',     'baslik' => 'Hero Başlığı',              'tip' => 'metin',    'sira' => 3],
                    ['alan' => 'hero_alt_baslik', 'baslik' => 'Hero Alt Yazısı',           'tip' => 'textarea', 'sira' => 4],
                    ['alan' => 'icerik',          'baslik' => 'Sayfa İçeriği (HTML desteklenir)', 'tip' => 'html', 'sira' => 5],
                    ['alan' => 'seo_baslik',      'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 6],
                    ['alan' => 'seo_aciklama',    'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 7],
                ],
            ],
            'icmimari' => [
                'baslik'   => 'İç Mimari',
                'ikon'     => 'ti-sofa',
                'aciklama' => 'İç Mimari sayfası hero, içerik',
                'alanlar'  => [
                    ['alan' => 'banner_metni',    'baslik' => 'Banner Metni',              'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'hero_gorsel',     'baslik' => 'Hero Görseli',              'tip' => 'gorsel',   'sira' => 2],
                    ['alan' => 'hero_baslik',     'baslik' => 'Hero Başlığı',              'tip' => 'metin',    'sira' => 3],
                    ['alan' => 'hero_alt_baslik', 'baslik' => 'Hero Alt Yazısı',           'tip' => 'textarea', 'sira' => 4],
                    ['alan' => 'icerik',          'baslik' => 'Sayfa İçeriği (HTML desteklenir)', 'tip' => 'html', 'sira' => 5],
                    ['alan' => 'seo_baslik',      'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 6],
                    ['alan' => 'seo_aciklama',    'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 7],
                ],
            ],
            'insaat' => [
                'baslik'   => 'İnşaat',
                'ikon'     => 'ti-crane',
                'aciklama' => 'Hero, hizmet kartları, CTA',
                'alanlar'  => [
                    ['alan' => 'banner_metni',      'baslik' => 'Banner Metni',         'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'hero_gorsel',       'baslik' => 'Hero Görseli',         'tip' => 'gorsel',   'sira' => 2],
                    ['alan' => 'hero_baslik',       'baslik' => 'Hero Başlığı',         'tip' => 'metin',    'sira' => 3],
                    ['alan' => 'hero_alt_baslik',   'baslik' => 'Hero Alt Yazısı',      'tip' => 'textarea', 'sira' => 4],
                    ['alan' => 'hizmet_1_baslik',   'baslik' => 'Hizmet 1 — Başlık',   'tip' => 'metin',    'sira' => 5],
                    ['alan' => 'hizmet_1_aciklama', 'baslik' => 'Hizmet 1 — Açıklama', 'tip' => 'textarea', 'sira' => 6],
                    ['alan' => 'hizmet_2_baslik',   'baslik' => 'Hizmet 2 — Başlık',   'tip' => 'metin',    'sira' => 7],
                    ['alan' => 'hizmet_2_aciklama', 'baslik' => 'Hizmet 2 — Açıklama', 'tip' => 'textarea', 'sira' => 8],
                    ['alan' => 'hizmet_3_baslik',   'baslik' => 'Hizmet 3 — Başlık',   'tip' => 'metin',    'sira' => 9],
                    ['alan' => 'hizmet_3_aciklama', 'baslik' => 'Hizmet 3 — Açıklama', 'tip' => 'textarea', 'sira' => 10],
                    ['alan' => 'hizmet_4_baslik',   'baslik' => 'Hizmet 4 — Başlık',   'tip' => 'metin',    'sira' => 11],
                    ['alan' => 'hizmet_4_aciklama', 'baslik' => 'Hizmet 4 — Açıklama', 'tip' => 'textarea', 'sira' => 12],
                    ['alan' => 'hizmet_5_baslik',   'baslik' => 'Hizmet 5 — Başlık',   'tip' => 'metin',    'sira' => 13],
                    ['alan' => 'hizmet_5_aciklama', 'baslik' => 'Hizmet 5 — Açıklama', 'tip' => 'textarea', 'sira' => 14],
                    ['alan' => 'hizmet_6_baslik',   'baslik' => 'Hizmet 6 — Başlık',   'tip' => 'metin',    'sira' => 15],
                    ['alan' => 'hizmet_6_aciklama', 'baslik' => 'Hizmet 6 — Açıklama', 'tip' => 'textarea', 'sira' => 16],
                    ['alan' => 'cta_baslik',        'baslik' => 'CTA Başlığı',          'tip' => 'metin',    'sira' => 17],
                    ['alan' => 'cta_metin',         'baslik' => 'CTA Alt Metni',        'tip' => 'metin',    'sira' => 18],
                    ['alan' => 'seo_baslik',        'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 19],
                    ['alan' => 'seo_aciklama',      'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 20],
                ],
            ],
            'koleksiyon' => [
                'baslik'   => 'Koleksiyon',
                'ikon'     => 'ti-clock',
                'aciklama' => 'Banner metni, hero görsel, başlık ve alt yazısı',
                'alanlar'  => [
                    ['alan' => 'banner_metni',    'baslik' => 'Banner Metni',    'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'hero_gorsel',     'baslik' => 'Hero Görseli',    'tip' => 'gorsel',   'sira' => 2],
                    ['alan' => 'hero_baslik',     'baslik' => 'Hero Başlığı',    'tip' => 'metin',    'sira' => 3],
                    ['alan' => 'hero_alt_baslik', 'baslik' => 'Hero Alt Yazısı', 'tip' => 'textarea', 'sira' => 4],
                    ['alan' => 'seo_baslik',      'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 5],
                    ['alan' => 'seo_aciklama',    'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 6],
                ],
            ],
            'magaza' => [
                'baslik'   => 'Mağaza',
                'ikon'     => 'ti-shopping-bag',
                'aciklama' => 'Banner metni, hero görsel ve başlık',
                'alanlar'  => [
                    ['alan' => 'banner_metni',    'baslik' => 'Banner Metni',    'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'hero_gorsel',     'baslik' => 'Hero Görseli',    'tip' => 'gorsel',   'sira' => 2],
                    ['alan' => 'hero_baslik',     'baslik' => 'Hero Başlığı',    'tip' => 'metin',    'sira' => 3],
                    ['alan' => 'hero_alt_baslik', 'baslik' => 'Hero Alt Yazısı', 'tip' => 'textarea', 'sira' => 4],
                    ['alan' => 'seo_baslik',      'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 5],
                    ['alan' => 'seo_aciklama',    'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 6],
                ],
            ],
            'yasal' => [
                'baslik'   => 'Yasal Sayfalar',
                'ikon'     => 'ti-file-description',
                'aciklama' => 'KVKK, Aydınlatma Metni, Gizlilik, SSS, Mesafeli Satış, İade & Değişim',
                'alanlar'  => [
                    ['alan' => 'kvkk_tarih',  'baslik' => 'Kişisel Verilerin Korunması — Son Güncelleme Tarihi', 'tip' => 'metin', 'sira' => 1],
                    ['alan' => 'kvkk_icerik', 'baslik' => 'Kişisel Verilerin Korunması — İçerik (HTML desteklenir)', 'tip' => 'html', 'sira' => 2],
                    ['alan' => 'gizlilik_tarih',  'baslik' => 'Gizlilik Politikası — Son Güncelleme Tarihi',    'tip' => 'metin',    'sira' => 5],
                    ['alan' => 'gizlilik_icerik', 'baslik' => 'Gizlilik Politikası — İçerik (HTML desteklenir)','tip' => 'html',     'sira' => 6],
                    ['alan' => 'sss_tarih',       'baslik' => 'SSS — Son Güncelleme Tarihi',                    'tip' => 'metin',    'sira' => 7],
                    ['alan' => 'sss_icerik',      'baslik' => 'SSS — İçerik (HTML desteklenir)',                'tip' => 'html',     'sira' => 8],
                    ['alan' => 'mesafeli_tarih',  'baslik' => 'Mesafeli Satış Sözleşmesi — Son Güncelleme Tarihi','tip' => 'metin',  'sira' => 9],
                    ['alan' => 'mesafeli_icerik', 'baslik' => 'Mesafeli Satış Sözleşmesi — İçerik (HTML desteklenir)','tip' => 'html','sira' => 10],
                    ['alan' => 'iade_tarih',      'baslik' => 'İade & Değişim — Son Güncelleme Tarihi',         'tip' => 'metin',    'sira' => 11],
                    ['alan' => 'iade_icerik',     'baslik' => 'İade & Değişim — İçerik (HTML desteklenir)',     'tip' => 'html',     'sira' => 12],
                ],
            ],
            'hesaplama' => [
                'baslik'   => 'Hesaplama Araçları',
                'ikon'     => 'ti-calculator',
                'aciklama' => 'Maliyet hesaplama birim fiyatları ve emsal yapı sınıfı fiyatları (₺/m²)',
                'alanlar'  => [
                    // ─── Maliyet Hesaplama Birim Fiyatları ───────────────────
                    ['alan' => 'kaba_ekonomik',        'baslik' => 'Kaba İnşaat — Ekonomik (₺/m²)',       'tip' => 'metin', 'sira' => 1],
                    ['alan' => 'kaba_standart',        'baslik' => 'Kaba İnşaat — Standart (₺/m²)',       'tip' => 'metin', 'sira' => 2],
                    ['alan' => 'kaba_luks',            'baslik' => 'Kaba İnşaat — Lüks (₺/m²)',           'tip' => 'metin', 'sira' => 3],
                    ['alan' => 'kaba_ultra',           'baslik' => 'Kaba İnşaat — Ultra Lüks (₺/m²)',     'tip' => 'metin', 'sira' => 4],
                    ['alan' => 'ince_ekonomik',        'baslik' => 'İnce İnşaat — Ekonomik (₺/m²)',       'tip' => 'metin', 'sira' => 5],
                    ['alan' => 'ince_standart',        'baslik' => 'İnce İnşaat — Standart (₺/m²)',       'tip' => 'metin', 'sira' => 6],
                    ['alan' => 'ince_luks',            'baslik' => 'İnce İnşaat — Lüks (₺/m²)',           'tip' => 'metin', 'sira' => 7],
                    ['alan' => 'ince_ultra',           'baslik' => 'İnce İnşaat — Ultra Lüks (₺/m²)',     'tip' => 'metin', 'sira' => 8],
                    ['alan' => 'dekorasyon_ekonomik',  'baslik' => 'Dekorasyon — Ekonomik (₺/m²)',        'tip' => 'metin', 'sira' => 9],
                    ['alan' => 'dekorasyon_standart',  'baslik' => 'Dekorasyon — Standart (₺/m²)',        'tip' => 'metin', 'sira' => 10],
                    ['alan' => 'dekorasyon_luks',      'baslik' => 'Dekorasyon — Lüks (₺/m²)',            'tip' => 'metin', 'sira' => 11],
                    ['alan' => 'dekorasyon_ultra',     'baslik' => 'Dekorasyon — Ultra Lüks (₺/m²)',      'tip' => 'metin', 'sira' => 12],
                    ['alan' => 'anahtar_ekonomik',     'baslik' => 'Anahtar Teslim — Ekonomik (₺/m²)',    'tip' => 'metin', 'sira' => 13],
                    ['alan' => 'anahtar_standart',     'baslik' => 'Anahtar Teslim — Standart (₺/m²)',    'tip' => 'metin', 'sira' => 14],
                    ['alan' => 'anahtar_luks',         'baslik' => 'Anahtar Teslim — Lüks (₺/m²)',        'tip' => 'metin', 'sira' => 15],
                    ['alan' => 'anahtar_ultra',        'baslik' => 'Anahtar Teslim — Ultra Lüks (₺/m²)', 'tip' => 'metin', 'sira' => 16],
                    // ─── Emsal Hesaplama — Bakanlık Yapı Sınıfı Fiyatları ──
                    ['alan' => 'teblig_not',   'baslik' => 'Emsal — Tebliğ Kaynağı Notu',       'tip' => 'metin', 'sira' => 17],
                    ['alan' => 'sinif_IA',     'baslik' => 'Emsal I-A — Basit Tarım (₺/m²)',     'tip' => 'metin', 'sira' => 18],
                    ['alan' => 'sinif_IB',     'baslik' => 'Emsal I-B — Sera/Yardımcı (₺/m²)',   'tip' => 'metin', 'sira' => 19],
                    ['alan' => 'sinif_IC',     'baslik' => 'Emsal I-C — Su Deposu/Ahır (₺/m²)',  'tip' => 'metin', 'sira' => 20],
                    ['alan' => 'sinif_ID',     'baslik' => 'Emsal I-D — GES (₺/m²)',             'tip' => 'metin', 'sira' => 21],
                    ['alan' => 'sinif_IIA',    'baslik' => 'Emsal II-A — Genel Depo (₺/m²)',     'tip' => 'metin', 'sira' => 22],
                    ['alan' => 'sinif_IIB',    'baslik' => 'Emsal II-B — Hangar/Halı Saha (₺/m²)','tip' => 'metin', 'sira' => 23],
                    ['alan' => 'sinif_IIC',    'baslik' => 'Emsal II-C — Sanayi/Bağ Evi (₺/m²)', 'tip' => 'metin', 'sira' => 24],
                    ['alan' => 'sinif_IIIA',   'baslik' => 'Emsal III-A — Konut ≤3 kat (₺/m²)',  'tip' => 'metin', 'sira' => 25],
                    ['alan' => 'sinif_IIIB',   'baslik' => 'Emsal III-B — Konut h≤21.50m (₺/m²)','tip' => 'metin', 'sira' => 26],
                    ['alan' => 'sinif_IIIC',   'baslik' => 'Emsal III-C — Konut h 21.50–30.50m (₺/m²)', 'tip' => 'metin', 'sira' => 27],
                    ['alan' => 'sinif_IVA',    'baslik' => 'Emsal IV-A — Konut h 30.50–51.50m (₺/m²)', 'tip' => 'metin', 'sira' => 28],
                    ['alan' => 'sinif_IVB',    'baslik' => 'Emsal IV-B — Konut h>51.50m (₺/m²)', 'tip' => 'metin', 'sira' => 29],
                    ['alan' => 'sinif_IVC',    'baslik' => 'Emsal IV-C — AVM≥25K / Hastane (₺/m²)', 'tip' => 'metin', 'sira' => 30],
                    ['alan' => 'sinif_VA',     'baslik' => 'Emsal V-A — İş Mer. h>51.50m / Karma (₺/m²)', 'tip' => 'metin', 'sira' => 31],
                    ['alan' => 'sinif_VB',     'baslik' => 'Emsal V-B — Otel 4 yıldız (₺/m²)',   'tip' => 'metin', 'sira' => 32],
                    ['alan' => 'sinif_VC',     'baslik' => 'Emsal V-C — Müze/Opera/Kongre (₺/m²)','tip' => 'metin', 'sira' => 33],
                    ['alan' => 'sinif_VD',     'baslik' => 'Emsal V-D — Otel 5★ / Havalimanı (₺/m²)', 'tip' => 'metin', 'sira' => 34],
                    ['alan' => 'sinif_VE',     'baslik' => 'Emsal V-E — RES (₺/m²)',              'tip' => 'metin', 'sira' => 35],
                ],
            ],
            'kargo' => [
                'baslik'   => 'Kargo Ayarları',
                'ikon'     => 'ti-truck-delivery',
                'aciklama' => 'Ücretsiz kargo eşiği — bu tutarın üzerindeki siparişlerde tüm ürün kargoları sıfırlanır',
                'alanlar'  => [
                    ['alan' => 'ucretsiz_esik', 'baslik' => 'Ücretsiz Kargo Eşiği (₺, 0 = devre dışı)', 'tip' => 'metin', 'sira' => 1],
                ],
            ],
            'odeme' => [
                'baslik'   => 'Ödeme Bilgileri',
                'ikon'     => 'ti-building-bank',
                'aciklama' => 'Havale/EFT için banka hesapları — birden fazla banka ekleyebilirsiniz',
                'alanlar'  => [
                    ['alan' => 'bankalar', 'baslik' => 'Banka Hesapları', 'tip' => 'bankaListesi', 'sira' => 1],
                ],
            ],
            'sistem' => [
                'baslik'   => 'Sistem Ayarları',
                'ikon'     => 'ti-shield-lock',
                'aciklama' => 'reCAPTCHA ve SMS (İletimerkezi) entegrasyon anahtarları',
                'alanlar'  => [
                    ['alan' => 'recaptcha_site_key',      'baslik' => 'reCAPTCHA Site Anahtarı (public key)',  'tip' => 'metin', 'sira' => 1],
                    ['alan' => 'recaptcha_secret_key',    'baslik' => 'reCAPTCHA Gizli Anahtar (secret key)', 'tip' => 'metin', 'sira' => 2],
                    ['alan' => 'iletimerkezi_key',        'baslik' => 'İletimerkezi API Key',                 'tip' => 'metin', 'sira' => 3],
                    ['alan' => 'iletimerkezi_secret',     'baslik' => 'İletimerkezi Hash Secret',             'tip' => 'metin', 'sira' => 4],
                    ['alan' => 'iletimerkezi_originator', 'baslik' => 'İletimerkezi Gönderici Adı (max 11 karakter)', 'tip' => 'metin', 'sira' => 5],
                    ['alan' => 'google_maps_key',         'baslik' => 'Google Maps API Anahtarı',                  'tip' => 'metin', 'sira' => 6],
                ],
            ],
            'iletisim' => [
                'baslik'   => 'İletişim Sayfası',
                'ikon'     => 'ti-mail',
                'aciklama' => 'Hero, harita koordinatları, form ve yönlendirme metinleri',
                'alanlar'  => [
                    ['alan' => 'hero_baslik',     'baslik' => 'Hero Başlığı',              'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'hero_alt_baslik', 'baslik' => 'Hero Alt Yazısı',           'tip' => 'textarea', 'sira' => 2],
                    ['alan' => 'harita_embed',    'baslik' => 'Google Maps Embed URL (Google Maps\'ten "Paylaş → Haritayı yerleştir" ile alın — sadece src="..." içindeki URL)',  'tip' => 'metin', 'sira' => 3],
                    ['alan' => 'harita_lat',      'baslik' => 'Harita Enlemi (latitude) — embed varsa kullanılmaz',   'tip' => 'metin', 'sira' => 4],
                    ['alan' => 'harita_lng',      'baslik' => 'Harita Boylamı (longitude) — embed varsa kullanılmaz', 'tip' => 'metin', 'sira' => 5],
                    ['alan' => 'harita_zoom',     'baslik' => 'Harita Yakınlaştırma (1–18) — embed varsa kullanılmaz','tip' => 'metin', 'sira' => 6],
                    ['alan' => 'form_baslik',     'baslik' => 'Form Bölümü Başlığı',       'tip' => 'metin',    'sira' => 6],
                    ['alan' => 'yon_baslik',      'baslik' => 'Yan Panel Başlığı',         'tip' => 'metin',    'sira' => 7],
                    ['alan' => 'yon_metin',       'baslik' => 'Yan Panel Metni',           'tip' => 'textarea', 'sira' => 8],
                    ['alan' => 'seo_baslik',      'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 9],
                    ['alan' => 'seo_aciklama',    'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 10],
                ],
            ],
            'projeler' => [
                'baslik'   => 'Projelerimiz',
                'ikon'     => 'ti-layout-grid',
                'aciklama' => 'Projelerimiz sayfası SEO ayarları',
                'alanlar'  => [
                    ['alan' => 'seo_baslik',   'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'seo_aciklama', 'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 2],
                ],
            ],
            'haberler' => [
                'baslik'   => 'Haberler',
                'ikon'     => 'ti-news',
                'aciklama' => 'Haberler sayfası SEO ayarları',
                'alanlar'  => [
                    ['alan' => 'seo_baslik',   'baslik' => 'SEO — Sayfa Başlığı (Google\'da görünen)',    'tip' => 'metin',    'sira' => 1],
                    ['alan' => 'seo_aciklama', 'baslik' => 'SEO — Meta Açıklaması (Google\'da görünen)', 'tip' => 'textarea', 'sira' => 2],
                ],
            ],
        ];
    }

    public function index()
    {
        $tanimlar = $this->sayfaTanimlari();
        return view('admin.icerik.index', compact('tanimlar'));
    }

    public function sayfa(string $sayfa)
    {
        $tanimlar = $this->sayfaTanimlari();
        abort_unless(isset($tanimlar[$sayfa]), 404);

        $tanim  = $tanimlar[$sayfa];
        $mevcut = Icerik::where('sayfa', $sayfa)->get()->keyBy('alan');

        return view('admin.icerik.sayfa', compact('sayfa', 'tanim', 'mevcut'));
    }

    public function guncelle(Request $request, string $sayfa)
    {
        $tanimlar = $this->sayfaTanimlari();
        abort_unless(isset($tanimlar[$sayfa]), 404);

        foreach ($tanimlar[$sayfa]['alanlar'] as $tanim) {
            $alan = $tanim['alan'];
            $tip  = $tanim['tip'];

            $row          = Icerik::firstOrNew(['sayfa' => $sayfa, 'alan' => $alan]);
            $row->baslik  = $tanim['baslik'];
            $row->tip     = $tip;
            $row->sira    = $tanim['sira'];

            if ($tip === 'gorsel') {
                if ($request->hasFile("f_{$alan}")) {
                    $dosya = $request->file("f_{$alan}");
                    if (!$dosya->isValid()) {
                        return back()
                            ->withErrors(["f_{$alan}" => "\"{$tanim['baslik']}\" yüklenemedi. Dosyayı kontrol edip tekrar deneyin."])
                            ->withInput();
                    }
                    if ($dosya->getSize() > 5 * 1024 * 1024) {
                        return back()
                            ->withErrors(["f_{$alan}" => "\"{$tanim['baslik']}\" en fazla 5 MB olabilir."])
                            ->withInput();
                    }
                    if (!in_array($dosya->getMimeType(), ['image/jpeg','image/png','image/gif','image/webp','image/bmp','image/avif'])) {
                        return back()
                            ->withErrors(["f_{$alan}" => "\"{$tanim['baslik']}\" yalnızca JPG, PNG, WEBP veya GIF olabilir."])
                            ->withInput();
                    }
                    $boyutlar = @getimagesize($dosya->getRealPath());
                    if ($boyutlar && ($boyutlar[0] > 4000 || $boyutlar[1] > 4000)) {
                        return back()
                            ->withErrors(["f_{$alan}" => "\"{$tanim['baslik']}\" en fazla 4000×4000 piksel olabilir. Görseli küçülterek tekrar deneyin."])
                            ->withInput();
                    }
                    if ($row->gorsel) {
                        Storage::disk('uploads')->delete($row->gorsel);
                    }
                    $yol = $dosya->store("icerik/{$sayfa}", 'uploads');
                    if (!$yol) {
                        return back()
                            ->withErrors(["f_{$alan}" => "\"{$tanim['baslik']}\" kaydedilemedi. Sunucu klasör izinlerini kontrol edin."])
                            ->withInput();
                    }
                    @chmod(public_path('uploads/' . $yol), 0644);
                    $row->gorsel = $yol;
                } elseif ($request->boolean("f_{$alan}_sil")) {
                    if ($row->gorsel) {
                        Storage::disk('uploads')->delete($row->gorsel);
                    }
                    $row->gorsel = null;
                }
            } else {
                $row->deger = $request->input("f_{$alan}", '');
            }

            $row->save();
        }

        return redirect()->route('admin.icerik.sayfa', $sayfa)
                         ->with('basari', 'İçerik başarıyla güncellendi.');
    }
}
