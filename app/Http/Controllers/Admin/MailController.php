<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BultenMail;
use App\Mail\HaberDuyuruMail;
use App\Mail\KurumsalYazismaMail;
use App\Models\Haber;
use App\Models\MailLog;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function index()
    {
        $loglar     = MailLog::latest()->limit(100)->get();
        $istatistik = [
            'bugun'    => MailLog::whereDate('created_at', today())->count(),
            'bu_ay'    => MailLog::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'basarili' => MailLog::where('basarili', true)->count(),
            'hatali'   => MailLog::where('basarili', false)->count(),
        ];
        $mailAyarlar = [
            'host'       => icerik('sistem', 'mail_host'),
            'port'       => icerik('sistem', 'mail_port', '587'),
            'username'   => icerik('sistem', 'mail_username'),
            'password'   => icerik('sistem', 'mail_password'),
            'encryption' => icerik('sistem', 'mail_encryption', 'tls'),
            'from'       => icerik('sistem', 'mail_from', 'info@sucek.com.tr'),
            'from_name'  => icerik('sistem', 'mail_from_name', 'SUÇEK'),
        ];

        return view('admin.mail.index', compact('loglar', 'istatistik', 'mailAyarlar'));
    }

    public function ayarlarKaydet(Request $request)
    {
        $request->validate([
            'host'       => 'required|string|max:100',
            'port'       => 'required|integer',
            'username'   => 'required|string|max:150',
            'password'   => 'required|string|max:200',
            'encryption' => 'required|in:tls,ssl,none',
            'from'       => 'required|email|max:150',
            'from_name'  => 'required|string|max:100',
        ]);

        foreach ([
            'mail_host'       => $request->host,
            'mail_port'       => $request->port,
            'mail_username'   => $request->username,
            'mail_password'   => $request->password,
            'mail_encryption' => $request->encryption,
            'mail_from'       => $request->from,
            'mail_from_name'  => $request->from_name,
        ] as $alan => $deger) {
            \App\Models\Icerik::updateOrCreate(
                ['sayfa' => 'sistem', 'alan' => $alan],
                ['deger' => $deger, 'baslik' => $alan, 'tip' => 'metin', 'sira' => 0]
            );
        }

        return back()->with('basari', 'Mail ayarları kaydedildi.');
    }

    public function topluGonder(Request $request, MailService $mail)
    {
        $request->validate([
            'konu'  => 'required|string|max:200',
            'mesaj' => 'required|string|max:5000',
            'grup'  => 'required|in:sucek,tum_premium,tum_uyeler',
        ]);

        $query = User::whereNotNull('email');

        match ($request->grup) {
            'sucek'       => $query->where('rol', 'sucek'),
            'tum_premium' => $query->whereIn('rol', ['standart', 'sucek', 'teknik']),
            'tum_uyeler'  => $query,
        };

        $alicilar = $query->pluck('email')->toArray();

        if (empty($alicilar)) {
            return back()->with('hata', 'Seçilen grupta üye bulunamadı.');
        }

        $bulten    = new BultenMail($request->konu, $request->mesaj);
        $gonderilen = $mail->topluGonder($alicilar, $bulten, 'bulten');

        return back()->with('basari', count($alicilar) . ' kişiye mail gönderildi (' . $gonderilen . ' başarılı).');
    }

    public function tekGonder(Request $request, MailService $mail)
    {
        $request->validate([
            'email' => 'required|email',
            'konu'  => 'required|string|max:200',
            'mesaj' => 'required|string|max:5000',
        ]);

        $bulten = new BultenMail($request->konu, $request->mesaj);
        $ok     = $mail->gonder($request->email, $bulten, 'tek');

        return back()->with(
            $ok ? 'basari' : 'hata',
            $ok ? 'E-posta gönderildi.' : 'E-posta gönderilemedi.'
        );
    }

    public function onizleme(string $tip)
    {
        $ornekSiparis = (object)[
            'referans'      => 'SC-2026-00042',
            'ad_soyad'      => 'Fatma Kaya',
            'email'         => 'fatma@ornek.com',
            'odeme_yontemi' => 'havale',
            'durum'         => 'bekliyor',
            'admin_notu'    => null,
            'ara_toplam'    => 850.00,
            'kdv_tutari'    => 153.00,
            'kargo_ucreti'  => 0,
            'toplam'        => 1003.00,
            'kalemler'      => collect([
                (object)['urun_adi' => 'Ahşap Çerçeve — Kayın', 'varyant_bilgisi' => '30×40 cm', 'adet' => 1, 'toplam' => 450.00],
                (object)['urun_adi' => 'Dekoratif Tabak', 'varyant_bilgisi' => null, 'adet' => 2, 'toplam' => 400.00],
            ]),
        ];

        return match($tip) {
            'hosgeldin' => view('mail.hosgeldin', [
                'user' => (object)['name' => 'Ahmet Yılmaz', 'email' => 'ahmet@ornek.com'],
            ])->render(),

            'siparis-onay' => view('mail.siparis-onay', [
                'siparis' => $ornekSiparis,
            ])->render(),

            'siparis-durum-odeme' => view('mail.siparis-durum', [
                'siparis'    => (object)array_merge((array)$ornekSiparis, ['durum' => 'odeme_alindi']),
                'durumBaslik' => 'Ödemeniz Alındı',
                'durumMesaj'  => 'Ödemeniz onaylandı, siparişiniz hazırlanmaya başlıyor.',
            ])->render(),

            'siparis-durum-kargo' => view('mail.siparis-durum', [
                'siparis'    => (object)array_merge((array)$ornekSiparis, ['durum' => 'kargolandi', 'admin_notu' => 'Kargo takip no: 12345678', 'odeme_yontemi' => 'kredi_karti']),
                'durumBaslik' => 'Siparişiniz Kargoya Verildi',
                'durumMesaj'  => 'Siparişiniz kargo firmasına teslim edildi.',
            ])->render(),

            'siparis-durum-iptal' => view('mail.siparis-durum', [
                'siparis'    => (object)array_merge((array)$ornekSiparis, ['durum' => 'iptal']),
                'durumBaslik' => 'Siparişiniz İptal Edildi',
                'durumMesaj'  => 'Siparişiniz iptal edildi. Detay için bizimle iletişime geçebilirsiniz.',
            ])->render(),

            'haber' => view('mail.haber-duyuru', [
                'haber' => (object)['baslik' => 'Yeni Koleksiyon: 2026 İlkbahar', 'slug' => 'ornek', 'ozet' => 'SUÇEK\'in 2026 ilkbahar koleksiyonu şimdi mağazada! Modern tasarımlar ve özel fırsatlar sizi bekliyor.', 'icerik' => ''],
            ])->render(),

            'bulten' => view('mail.bulten', [
                'konu'   => 'Haziran Kampanyası Başladı',
                'icerik' => "Sayın üyemiz,\n\nHaziran ayına özel kampanyamız başlamıştır.\n\nTüm ürünlerde %15 indirim fırsatını kaçırmayın!\n\nİyi günler.",
            ])->render(),

            'yazisma' => view('mail.yazisma', [
                'konu'       => 'Proje Teklifi Hakkında',
                'icerik'     => "Sayın Yetkili,\n\nTarafınızla gerçekleştirdiğimiz ön görüşme kapsamında söz konusu projeye ilişkin teklifimizi sunmaktan memnuniyet duyarız.\n\nEk belgeler ve teknik şartname detayları için lütfen bizimle iletişime geçiniz.\n\nSaygılarımızla,",
                'imzaAd'     => 'Ahmet Suçek',
                'imzaGorev'  => 'Kurucu Ortak & Mimar',
                'imzaTel'    => '+90 555 555 55 55',
                'imzaEmail'  => 'info@sucek.com.tr',
                'imzaAdres'  => 'Merkez Mah. Örnek Cad. No:1\nKarabük / Türkiye',
            ])->render(),

            default => abort(404),
        };
    }

    public function yazismaGonder(Request $request, MailService $mail)
    {
        $data = $request->validate([
            'alici'       => 'required|email',
            'konu'        => 'required|string|max:200',
            'icerik'      => 'required|string|max:10000',
            'imza_ad'     => 'required|string|max:100',
            'imza_gorev'  => 'required|string|max:100',
            'imza_tel'    => 'required|string|max:50',
            'imza_email'  => 'required|email|max:100',
            'imza_adres'  => 'nullable|string|max:300',
        ]);

        $basarili = $mail->gonder(
            $data['alici'],
            new KurumsalYazismaMail(
                konu:       $data['konu'],
                icerik:     $data['icerik'],
                imzaAd:     $data['imza_ad'],
                imzaGorev:  $data['imza_gorev'],
                imzaTel:    $data['imza_tel'],
                imzaEmail:  $data['imza_email'],
                imzaAdres:  $data['imza_adres'] ?? '',
            ),
            'yazisma'
        );

        return back()->with(
            $basarili ? 'basari' : 'hata',
            $basarili ? '"' . $data['alici'] . '" adresine e-posta başarıyla gönderildi.' : 'E-posta gönderilemedi. SMTP ayarlarını kontrol edin.'
        );
    }

    public function haberMail(Request $request, Haber $haber, MailService $mail)
    {
        $request->validate([
            'grup' => 'required|in:sucek,tum_premium,tum_uyeler',
        ]);

        $query = User::whereNotNull('email');

        match ($request->grup) {
            'sucek'       => $query->where('rol', 'sucek'),
            'tum_premium' => $query->whereIn('rol', ['standart', 'sucek', 'teknik']),
            'tum_uyeler'  => $query,
        };

        $alicilar = $query->pluck('email')->toArray();

        if (empty($alicilar)) {
            return back()->with('hata', 'Uygun alıcı bulunamadı.');
        }

        $haberMail  = new HaberDuyuruMail($haber);
        $gonderilen = $mail->topluGonder($alicilar, $haberMail, 'haber_duyuru');

        return back()->with('basari', '"' . $haber->baslik . '" için ' . count($alicilar) . ' kişiye mail gönderildi (' . $gonderilen . ' başarılı).');
    }
}
