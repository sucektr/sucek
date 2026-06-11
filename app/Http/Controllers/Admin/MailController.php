<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BultenMail;
use App\Mail\HaberDuyuruMail;
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
