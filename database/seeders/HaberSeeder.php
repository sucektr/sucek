<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HaberSeeder extends Seeder
{
    public function run(): void
    {
        $haberler = [
            [
                'baslik'   => '2024 Yılı Aile Buluşması Etimesgut\'ta Gerçekleşti',
                'kategori' => 'etkinlik',
                'ozet'     => 'Her yıl geleneksel olarak düzenlenen SUÇEK aile buluşması bu yıl Etimesgut\'taki büyük bahçede yapıldı. Yüzlerce aile ferdi bir araya geldi.',
                'icerik'   => "<p>Her yıl geleneksel olarak düzenlenen SUÇEK aile buluşması bu yıl da büyük bir katılımla gerçekleşti. Etimesgut'taki büyük bahçede düzenlenen buluşmaya yurt içi ve yurt dışından çok sayıda aile ferdi katıldı.</p>\n\n<p>Sabah namazı buluşmasıyla başlayan etkinlikte ardından geleneksel kahvaltı yapıldı. Öğle yemeğinin ardından büyüklerin anıları dinlendi, soy ağacına yeni eklenen üyeler tanıtıldı.</p>\n\n<p>Geçen yıl hayatını kaybeden aile büyükleri için Fatiha okundu. Buluşma, bir sonraki yıl için yer ve tarih kararlaştırılarak son buldu.</p>",
                'yayinda'  => true,
            ],
            [
                'baslik'   => 'Soy Ağacı Projesi Güncellendi: 1200\'ü Aşkın Kayıt',
                'kategori' => 'duyuru',
                'ozet'     => 'SUÇEK dijital soy ağacı projesine yeni aile üyeleri eklendi. Kayıt sayısı 1200\'ü aştı, yeni arama ve filtreleme özellikleri devreye alındı.',
                'icerik'   => "<p>Aile üyelerinin katkısıyla sürekli büyüyen SUÇEK dijital soy ağacı projesine bu ay önemli güncellemeler getirildi.</p>\n\n<p>Toplam kayıt sayısı 1200'ü aşarken, yeni arama ve filtreleme özellikleri sayesinde üyeler istedikleri kişiyi kolayca bulabiliyor. Harita görünümü özelliğiyle aile fertlerinin coğrafi dağılımı da görselleştiriliyor.</p>\n\n<p>Eksik bilgilerin tamamlanması için tüm üyelerimizin katkısını bekliyoruz. Soy ağacına giriş yapmak için üye olmayı unutmayın.</p>",
                'yayinda'  => true,
            ],
            [
                'baslik'   => 'Mimarlık Bölümümüz ISO 9001:2015 Belgesini Yeniledi',
                'kategori' => 'kurumsal',
                'ozet'     => 'SUÇEK Mimarlık, ISO 9001:2015 Kalite Yönetim Sistemi belgesini başarıyla yeniledi. Denetim sıfır uygunsuzlukla tamamlandı.',
                'icerik'   => "<p>SUÇEK Mimarlık birimi, ISO 9001:2015 Kalite Yönetim Sistemi belgesini üçüncü kez yenilemek üzere gerçekleştirilen dış denetime girdi.</p>\n\n<p>Üç günlük yoğun denetim sürecinin sonunda sıfır uygunsuzlukla tamamlanan süreç, ekibimizin kalite standartlarına olan bağlılığının somut bir göstergesi oldu.</p>\n\n<p>Belge, 2027 yılına kadar geçerli olacak. Bu başarı tüm ekibimizin ortak emeğinin ürünüdür.</p>",
                'yayinda'  => true,
            ],
            [
                'baslik'   => 'Kış Sezonu İndirimli Ürünler Mağazada',
                'kategori' => 'kampanya',
                'ozet'     => 'Mağazamızda kış sezonu indirimleri başladı. Spor ve dekorasyon kategorilerinde seçili ürünlerde %30\'a varan indirim.',
                'icerik'   => "<p>SUÇEK Mağaza'da kış sezonu indirimleri başladı! Spor ve dekorasyon kategorilerindeki seçili ürünlerde %30'a varan indirimden yararlanabilirsiniz.</p>\n\n<p>Öne çıkan kampanyalar:</p>\n<ul>\n<li>Koşu bantları ve fitness ekipmanlarında %20 indirim</li>\n<li>Dekorasyon ürünlerinde %25 indirim</li>\n<li>500 TL üzeri alışverişlerde ücretsiz kargo</li>\n</ul>\n\n<p>Kampanya süresi sınırlıdır. Fırsatları kaçırmayın!</p>",
                'yayinda'  => true,
            ],
            [
                'baslik'   => 'Yeni Antika Koleksiyonu Eklendi',
                'kategori' => 'koleksiyon',
                'ozet'     => 'Koleksiyon bölümümüze nadir Osmanlı dönemi eserler eklendi. El yazması eserler ve sedef kakma mobilyalar öne çıkıyor.',
                'icerik'   => "<p>SUÇEK Koleksiyon bölümüne bu ay dikkat çekici yeni eserler eklendi.</p>\n\n<p>Özellikle dikkat çeken parçalar:</p>\n<ul>\n<li><strong>18. yüzyıl el yazması Kur'an-ı Kerim</strong> — Nesih hatlı, tezhipli, 350 varak</li>\n<li><strong>Osmanlı sedef kakma sehpa</strong> — Şam işçiliği, ceviz ve sedef</li>\n<li><strong>Osmanlı altın takımı</strong> — II. Abdülhamid dönemi, NGC sertifikalı</li>\n</ul>\n\n<p>Tüm eserlerin detaylı bilgisi ve fotoğrafları için koleksiyon sayfamızı ziyaret edebilir, teklif vermek için üye girişi yapabilirsiniz.</p>",
                'yayinda'  => true,
            ],
            [
                'baslik'   => 'İnşaat Maliyetleri Hesaplama Aracı Güncellendi',
                'kategori' => 'duyuru',
                'ozet'     => 'İnşaat maliyet hesaplama aracımız 2024 birim fiyatları ile güncellendi. Emsal hesaplama modülü de yenilendi.',
                'icerik'   => "<p>İnşaat sayfamızdaki maliyet hesaplama aracı, 2024 yılı Çevre, Şehircilik ve İklim Değişikliği Bakanlığı birim fiyatlarıyla güncellendi.</p>\n\n<p>Yenilikler:</p>\n<ul>\n<li>Güncel 2024 birim fiyatları</li>\n<li>Bölgeye göre fiyat katsayısı seçimi</li>\n<li>Emsal hesaplama modülü yeniden yazıldı</li>\n<li>PDF rapor çıktısı özelliği eklendi</li>\n</ul>\n\n<p>Hesaplama aracını kullanmak için üye olmanız gerekmektedir.</p>",
                'yayinda'  => false,
            ],
        ];

        foreach ($haberler as $veri) {
            $slug = Str::slug($veri['baslik'], '-');

            DB::table('haberler')->updateOrInsert(
                ['slug' => $slug],
                [
                    'baslik'     => $veri['baslik'],
                    'slug'       => $slug,
                    'ozet'       => $veri['ozet'],
                    'icerik'     => $veri['icerik'],
                    'kapak'      => null,
                    'kategori'   => $veri['kategori'],
                    'yayinda'    => $veri['yayinda'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
