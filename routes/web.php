<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MimarlikController;
use App\Http\Controllers\InsaatController;
use App\Http\Controllers\KoleksiyonController;
use App\Http\Controllers\MagazaController;
use App\Http\Controllers\IletisimController;
use App\Http\Controllers\SepetController;
use App\Http\Controllers\SiparisController;
use App\Http\Controllers\SayfaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TeklifController;
use App\Http\Controllers\HesabimController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\SiparisController as AdminSiparisController;
use App\Http\Controllers\Admin\DeployController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\SoyAgaciController;
use App\Http\Controllers\HaberController;
use App\Http\Controllers\OdemeController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\UserUrunController;
use App\Http\Controllers\SifremiUnuttumController;
use App\Http\Controllers\ProjeController;
use App\Http\Controllers\MerchantFeedController;
use App\Http\Controllers\CelikGuvenlikAgiController;

// Storage / uploads dosyalarını symlink olmadan sun (fallback; Apache direkt okuyabiliyorsa bu çalışmaz zaten)
Route::get('/storage/{path}', function (string $path) {
    $file = storage_path('app/public/' . $path);
    abort_unless(file_exists($file) && is_file($file), 404);
    return response()->file($file);
})->where('path', '.*')->name('storage.serve');

Route::get('/uploads/{path}', function (string $path) {
    $file = public_path('uploads/' . $path);
    abort_unless(file_exists($file) && is_file($file), 404);
    return response()->file($file);
})->where('path', '.*')->name('uploads.serve');

// Google Merchant Center ürün feed'i
Route::get('/feed.xml', [MerchantFeedController::class, 'feed'])->name('merchant.feed');

// Dil değiştirme
Route::get('/dil/{dil}', function (string $dil) {
    if (in_array($dil, ['tr', 'en'])) {
        session(['site_dil' => $dil]);
    }
    return redirect(url()->previous('/'));
})->name('dil.degistir');

// Eski Wix URL'lerinden 301 yönlendirme
Route::get('/mimari',          fn() => redirect('/mimarlik', 301));
Route::get('/icmimari',        fn() => redirect('/mimarlik/ic-mimari', 301));
Route::get('/içmimari',        fn() => redirect('/mimarlik/ic-mimari', 301));
Route::get('/evraklar',        fn() => redirect('/iletisim', 301));
Route::get('/blog',            fn() => redirect('/', 301));
Route::get('/onlinerandevu',   fn() => redirect('/iletisim', 301));

// Ana Sayfa
Route::get('/', [HomeController::class, 'index'])->name('home');

// İletişim
Route::get('/iletisim', [IletisimController::class, 'index'])->name('iletisim.index');
Route::post('/iletisim', [IletisimController::class, 'gonder'])->name('iletisim.gonder');

// Mimarlık
Route::prefix('mimarlik')->name('mimarlik.')->group(function () {
    Route::get('/', [MimarlikController::class, 'index'])->name('index');
    Route::get('/ruhsat-sureci', [MimarlikController::class, 'ruhsatSureci'])->name('ruhsat');
    Route::get('/ic-mimari', [MimarlikController::class, 'icMimari'])->name('icmimari');
    Route::get('/belgeler', [MimarlikController::class, 'belgeler'])->name('belgeler');
    Route::get('/belgeler/{belge}/indir', [MimarlikController::class, 'indir'])->name('belgeler.indir');
});

// İnşaat
Route::prefix('insaat')->name('insaat.')->group(function () {
    Route::get('/', [InsaatController::class, 'index'])->name('index');
    Route::get('/hesaplama', [InsaatController::class, 'hesaplama'])->name('hesaplama');
    Route::post('/hesaplama', [InsaatController::class, 'hesapla'])->name('hesapla');
    Route::get('/emsal-hesaplama', [InsaatController::class, 'emsalHesaplama'])->name('emsal');
});

// Koleksiyon (antika, saat, nümizmatik)
Route::prefix('koleksiyon')->name('koleksiyon.')->group(function () {
    Route::get('/', [KoleksiyonController::class, 'index'])->name('index');
    Route::get('/{slug}', [KoleksiyonController::class, 'show'])->name('show');
    Route::post('/{slug}/teklif', [TeklifController::class, 'store'])->middleware('auth')->name('teklif');
});

// Mağaza
Route::prefix('magaza')->name('magaza.')->group(function () {
    Route::get('/', [MagazaController::class, 'index'])->name('index');
    Route::get('/{slug}', [MagazaController::class, 'urun'])->name('urun');
});

// Sipariş
Route::prefix('siparis')->name('siparis.')->group(function () {
    Route::get('/olustur', [SiparisController::class, 'checkout'])->name('checkout');
    Route::post('/olustur', [SiparisController::class, 'store'])->name('store');
    Route::get('/{referans}/onay', [SiparisController::class, 'onay'])->name('onay');
    Route::prefix('{referans}/odeme')->name('odeme.')->group(function () {
        Route::get('/', [OdemeController::class, 'goster'])->name('goster');
        Route::get('/basari', [OdemeController::class, 'basari'])->name('basari');
        Route::get('/hata', [OdemeController::class, 'hata'])->name('hata');
    });
});

// PayTR bildirim (webhook) — CSRF muaf, sadece PayTR sunucusundan gelir
Route::post('/paytr/bildirim', [OdemeController::class, 'bildirim'])->name('paytr.bildirim');

// Sepet (AJAX + sayfa)
Route::prefix('sepet')->name('sepet.')->group(function () {
    Route::get('/', [SepetController::class, 'index'])->name('index');
    Route::post('/ekle', [SepetController::class, 'ekle'])->name('ekle');
    Route::patch('/guncelle', [SepetController::class, 'guncelle'])->name('guncelle');
    Route::delete('/kaldir/{id}', [SepetController::class, 'kaldir'])->name('kaldir');
    Route::delete('/temizle', [SepetController::class, 'temizle'])->name('temizle');
});

// Çelik Güvenlik Ağı (bayilik)
Route::get('/celik-guvenlik-agi', [CelikGuvenlikAgiController::class, 'index'])->name('celik-guvenlik-agi.index');

// Projeler
Route::get('/projeler', [ProjeController::class, 'index'])->name('projeler.index');
Route::get('/projeler/{slug}', [ProjeController::class, 'show'])->name('projeler.show');

// Yasal Sayfalar
Route::get('/yasal/{sayfa}', [SayfaController::class, 'goster'])->name('yasal');

// Hesabım (auth gerekli)
Route::prefix('hesabim')->name('hesabim.')->middleware('auth')->group(function () {
    Route::get('/', [HesabimController::class, 'index'])->name('index');
    Route::patch('/profil', [HesabimController::class, 'profilGuncelle'])->name('profil');
    Route::patch('/sifre', [HesabimController::class, 'sifreDegistir'])->name('sifre');
    Route::post('/kargo-adres', [HesabimController::class, 'kargoAdresKaydet'])->name('kargo-adres');
    Route::post('/fatura-adres', [HesabimController::class, 'faturaAdresKaydet'])->name('fatura-adres');
});

// Katalog Oluşturucu (teknik üyeler)
Route::prefix('katalog')->name('katalog.')->middleware(['auth', 'premium:katalog-olusturucu'])->group(function () {
    Route::get('/', [KatalogController::class, 'index'])->name('index');
    Route::get('/urunler', [KatalogController::class, 'urunler'])->name('urunler');
    Route::post('/', [KatalogController::class, 'kaydet'])->name('kaydet');
    Route::get('/{id}/yazdir', [KatalogController::class, 'yazdir'])->name('yazdir');
    Route::delete('/{id}', [KatalogController::class, 'sil'])->name('sil');
    Route::post('/logo-yukle', [KatalogController::class, 'logoYukle'])->name('logo.yukle');
    // Kullanıcının kendi ürünleri
    Route::get('/urunlerim', [UserUrunController::class, 'index'])->name('urunlerim.index');
    Route::post('/urunlerim', [UserUrunController::class, 'store'])->name('urunlerim.store');
    Route::post('/urunlerim/gorsel-yukle', [UserUrunController::class, 'gorselYukle'])->name('urunlerim.gorsel.yukle');
    Route::post('/urunlerim/{id}', [UserUrunController::class, 'update'])->name('urunlerim.update');
    Route::delete('/urunlerim/{id}', [UserUrunController::class, 'destroy'])->name('urunlerim.destroy');
});

// Premium erişim engeli
Route::get('/premium-gerekli', [PremiumController::class, 'gerekli'])->name('premium.gerekli')->middleware('auth');

// Haberler (erisim kontrolü)
Route::prefix('haberler')->name('haberler.')->middleware(['auth', 'premium:haberler'])->group(function () {
    Route::get('/', [HaberController::class, 'index'])->name('index');
    Route::get('/{haber:slug}', [HaberController::class, 'show'])->name('show');
});

// Soy Ağacı (erisim kontrolü)
Route::prefix('soy-agaci')->name('soy-agaci.')->middleware(['auth', 'premium:soy-agaci'])->group(function () {
    Route::get('/', [SoyAgaciController::class, 'index'])->name('index');
    Route::get('/veri', [SoyAgaciController::class, 'veri'])->name('veri');
    Route::post('/degisiklik', [SoyAgaciController::class, 'degisiklik'])->name('degisiklik');
    Route::post('/konum', [SoyAgaciController::class, 'konumGuncelle'])->name('konum');
    Route::post('/grup', [SoyAgaciController::class, 'grupKaydet'])->name('grup.kaydet');
    Route::delete('/grup/{grup}', [SoyAgaciController::class, 'grupSil'])->name('grup.sil');
});

// Auth (public)
Route::get('/giris', [LoginController::class, 'girisForm'])->name('giris')->middleware('guest');
Route::post('/giris', [LoginController::class, 'giris'])->name('giris.post')->middleware('guest');
Route::post('/cikis', [LoginController::class, 'cikis'])->name('cikis');
Route::get('/uye-ol', [RegisterController::class, 'uyeOlForm'])->name('uye-ol')->middleware('guest');
Route::post('/uye-ol', [RegisterController::class, 'uyeOl'])->name('uye-ol.post')->middleware('guest');

// Şifremi Unuttum
Route::middleware('guest')->group(function () {
    Route::get('/sifremi-unuttum', [SifremiUnuttumController::class, 'form'])->name('sifremi-unuttum');
    Route::post('/sifremi-unuttum', [SifremiUnuttumController::class, 'gonder'])->name('sifremi-unuttum.gonder');
    Route::get('/sifre-sifirla/{token}', [SifremiUnuttumController::class, 'sifirlaForm'])->name('sifre-sifirla');
    Route::post('/sifre-sifirla', [SifremiUnuttumController::class, 'sifirla'])->name('sifre-sifirla.kaydet');
});

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/giris', [Admin\AuthController::class, 'girisForm'])->name('giris');
    Route::post('/giris', [Admin\AuthController::class, 'giris'])->name('giris.post');

    Route::middleware('admin')->group(function () {
        Route::post('/cikis', [Admin\AuthController::class, 'cikis'])->name('cikis');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('urunler', Admin\UrunController::class)->except('show')->parameters(['urunler' => 'urun']);
        Route::post('urunler/{urun}/varyantlar', [Admin\UrunVaryantController::class, 'kaydet'])->name('urunler.varyantlar.kaydet');
        Route::post('urunler/{urun}/varyantlar/gorseller', [Admin\UrunVaryantController::class, 'gorsellerKaydet'])->name('urunler.varyantlar.gorseller');
        Route::delete('urunler/{urun}/varyantlar', [Admin\UrunVaryantController::class, 'sil'])->name('urunler.varyantlar.sil');
        Route::resource('koleksiyonlar', Admin\KoleksiyonController::class)->except('show')->parameters(['koleksiyonlar' => 'koleksiyon']);
        Route::resource('projeler', Admin\ProjeController::class)->except('show')->parameters(['projeler' => 'proje']);
        Route::resource('belgeler', Admin\BelgeController::class)->except('show')->parameters(['belgeler' => 'belge']);

        Route::get('iletisim', [Admin\IletisimController::class, 'index'])->name('iletisim.index');
        Route::patch('iletisim/{mesaj}/oku', [Admin\IletisimController::class, 'oku'])->name('iletisim.oku');
        Route::delete('iletisim/{mesaj}', [Admin\IletisimController::class, 'destroy'])->name('iletisim.destroy');

        Route::get('icerik', [Admin\IcerikController::class, 'index'])->name('icerik.index');
        Route::get('icerik/{sayfa}', [Admin\IcerikController::class, 'sayfa'])->name('icerik.sayfa');
        Route::post('icerik/{sayfa}', [Admin\IcerikController::class, 'guncelle'])->name('icerik.guncelle');

        Route::get('siparisler', [AdminSiparisController::class, 'index'])->name('siparisler.index');
        Route::get('siparisler/{siparis}', [AdminSiparisController::class, 'show'])->name('siparisler.show');
        Route::patch('siparisler/{siparis}/durum', [AdminSiparisController::class, 'durumGuncelle'])->name('siparisler.durum');
        Route::post('siparisler/toplu-islem', [AdminSiparisController::class, 'topluIslem'])->name('siparisler.toplu');

        Route::get('teklifler', [Admin\TeklifController::class, 'index'])->name('teklifler.index');
        Route::patch('teklifler/{teklif}/durum', [Admin\TeklifController::class, 'durum'])->name('teklifler.durum');

        Route::get('uyeler', [Admin\UyeController::class, 'index'])->name('uyeler.index');
        Route::get('uyeler/{uye}', [Admin\UyeController::class, 'show'])->name('uyeler.show');
        Route::patch('uyeler/{uye}/rol', [Admin\UyeController::class, 'rolGuncelle'])->name('uyeler.rol');
        Route::delete('uyeler/{uye}', [Admin\UyeController::class, 'destroy'])->name('uyeler.destroy');

        Route::get('erisim', [Admin\ErisimController::class, 'index'])->name('erisim.index');
        Route::patch('erisim/{id}', [Admin\ErisimController::class, 'guncelle'])->name('erisim.guncelle');
        Route::post('erisim', [Admin\ErisimController::class, 'ekle'])->name('erisim.ekle');
        Route::delete('erisim/{id}', [Admin\ErisimController::class, 'sil'])->name('erisim.sil');

        Route::resource('haberler', Admin\HaberController::class)->except('show')->parameters(['haberler' => 'haber']);
        Route::patch('haberler/{haber}/yayin-toggle', [Admin\HaberController::class, 'yayinToggle'])->name('haberler.yayin-toggle');

        Route::get('soy-agaci', [Admin\SoyAgaciController::class, 'index'])->name('soy-agaci.index');
        Route::post('soy-agaci/{degisiklik}/karar', [Admin\SoyAgaciController::class, 'karar'])->name('soy-agaci.karar');

        Route::get('uye-urunleri', [Admin\UyeUrunController::class, 'index'])->name('uye-urunleri.index');
        Route::get('uye-urunleri/{id}/donustur', [Admin\UyeUrunController::class, 'donustur'])->name('uye-urunleri.donustur');
        Route::post('uye-urunleri/{id}/kaydet', [Admin\UyeUrunController::class, 'kaydet'])->name('uye-urunleri.kaydet');

        Route::get('guncelle', [DeployController::class, 'index'])->name('deploy.index');
        Route::post('guncelle', [DeployController::class, 'guncelle'])->name('deploy.guncelle');

        Route::get('fiyat-guncelle', [Admin\FiyatGuncelleController::class, 'index'])->name('fiyat-guncelle.index');
        Route::get('fiyat-guncelle/indir', [Admin\FiyatGuncelleController::class, 'indir'])->name('fiyat-guncelle.indir');
        Route::post('fiyat-guncelle/yukle', [Admin\FiyatGuncelleController::class, 'yukle'])->name('fiyat-guncelle.yukle');

        Route::get('urun-import', [Admin\UrunImportController::class, 'index'])->name('urun-import.index');
        Route::get('urun-import/sablon', [Admin\UrunImportController::class, 'sablon'])->name('urun-import.sablon');
        Route::post('urun-import/yukle', [Admin\UrunImportController::class, 'yukle'])->name('urun-import.yukle');

        Route::get('odeme', [Admin\OdemeController::class, 'index'])->name('odeme.index');
        Route::post('odeme/ayarlar', [Admin\OdemeController::class, 'ayarlarKaydet'])->name('odeme.ayarlar');

        Route::get('mail', [Admin\MailController::class, 'index'])->name('mail.index');
        Route::post('mail/ayarlar', [Admin\MailController::class, 'ayarlarKaydet'])->name('mail.ayarlar');
        Route::post('mail/toplu-gonder', [Admin\MailController::class, 'topluGonder'])->name('mail.toplu');
        Route::post('mail/tek-gonder', [Admin\MailController::class, 'tekGonder'])->name('mail.tek');
        Route::post('mail/haber/{haber}', [Admin\MailController::class, 'haberMail'])->name('mail.haber');
        Route::post('mail/yazisma-gonder', [Admin\MailController::class, 'yazismaGonder'])->name('mail.yazisma');
        Route::get('mail/onizleme/{tip}', [Admin\MailController::class, 'onizleme'])->name('mail.onizleme');

        Route::get('sms', [Admin\SmsController::class, 'index'])->name('sms.index');
        Route::post('sms/ayarlar', [Admin\SmsController::class, 'ayarlarKaydet'])->name('sms.ayarlar');
        Route::patch('sms/sablonlar/{sablon}', [Admin\SmsController::class, 'sablonGuncelle'])->name('sms.sablon');
        Route::post('sms/toplu-gonder', [Admin\SmsController::class, 'topluGonder'])->name('sms.toplu');
        Route::post('sms/tek-gonder', [Admin\SmsController::class, 'tekGonder'])->name('sms.tek');
        Route::post('sms/haber/{haber}', [Admin\SmsController::class, 'haberSms'])->name('sms.haber');
    });

    // Katalog Oluşturucu — admin + izinli premium üyeler erişebilir
    Route::middleware(['auth', 'premium:katalog-olusturucu'])->group(function () {
        Route::get('katalog', [Admin\KatalogController::class, 'index'])->name('katalog.index');
        Route::get('katalog-urunler', [Admin\KatalogController::class, 'urunler'])->name('katalog.urunler');
        Route::post('katalog', [Admin\KatalogController::class, 'kaydet'])->name('katalog.kaydet');
        Route::post('katalog-logo', [Admin\KatalogController::class, 'logoYukle'])->name('katalog.logo-yukle');
        Route::get('katalog/{id}/yazdir', [Admin\KatalogController::class, 'yazdir'])->name('katalog.yazdir');
        Route::delete('katalog/{id}', [Admin\KatalogController::class, 'sil'])->name('katalog.sil');
    });
});
