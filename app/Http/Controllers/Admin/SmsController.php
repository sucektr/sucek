<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Haber;
use App\Models\SmsSablon;
use App\Models\SmsLog;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
        $sablonlar  = SmsSablon::orderBy('anahtar')->get();
        $loglar     = SmsLog::latest()->limit(100)->get();
        $istatistik = [
            'bugun'    => SmsLog::whereDate('created_at', today())->count(),
            'bu_ay'    => SmsLog::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'basarili' => SmsLog::where('basarili', true)->count(),
            'hatali'   => SmsLog::where('basarili', false)->count(),
        ];
        $smsAyarlar = [
            'key'        => icerik('sistem', 'iletimerkezi_key'),
            'hash'       => icerik('sistem', 'iletimerkezi_hash'),
            'originator' => icerik('sistem', 'iletimerkezi_originator'),
        ];

        return view('admin.sms.index', compact('sablonlar', 'loglar', 'istatistik', 'smsAyarlar'));
    }

    public function ayarlarKaydet(Request $request)
    {
        $request->validate([
            'key'        => 'required|string|max:100',
            'hash'       => 'required|string|max:200',
            'originator' => 'required|string|max:11',
        ]);

        foreach ([
            'iletimerkezi_key'        => $request->key,
            'iletimerkezi_hash'       => $request->hash,
            'iletimerkezi_originator' => $request->originator,
        ] as $alan => $deger) {
            \App\Models\Icerik::updateOrCreate(
                ['sayfa' => 'sistem', 'alan' => $alan],
                ['deger' => $deger, 'baslik' => $alan, 'tip' => 'metin', 'sira' => 0]
            );
        }

        return back()->with('basari', 'SMS API ayarları kaydedildi.');
    }

    public function sablonGuncelle(Request $request, SmsSablon $sablon)
    {
        $request->validate([
            'sablon' => 'required|string|max:500',
            'aktif'  => 'nullable|boolean',
        ]);

        $sablon->update([
            'sablon' => $request->sablon,
            'aktif'  => $request->boolean('aktif'),
        ]);

        return back()->with('basari', '"' . $sablon->baslik . '" şablonu güncellendi.');
    }

    public function topluGonder(Request $request, SmsService $sms)
    {
        $request->validate([
            'mesaj' => 'required|string|max:500',
            'grup'  => 'required|in:sucek,tum_premium,sms_izinli,tum_uyeler',
        ]);

        $query = User::whereNotNull('telefon')->where('sms_izni', true);

        match ($request->grup) {
            'sucek'        => $query->where('rol', 'sucek'),
            'tum_premium'  => $query->whereIn('rol', ['standart', 'sucek', 'teknik']),
            'sms_izinli'   => $query, // sadece sms_izni filtresi
            'tum_uyeler'   => $query,
        };

        $telefonlar = $query->pluck('telefon')->toArray();

        if (empty($telefonlar)) {
            return back()->with('hata', 'Seçilen grupta telefon numarası olan ve SMS izni veren üye bulunamadı.');
        }

        $gonderilen = $sms->topluGonder($telefonlar, $request->mesaj);

        return back()->with('basari', count($telefonlar) . ' kişiye SMS gönderildi (' . $gonderilen . ' başarılı).');
    }

    public function haberSms(Request $request, Haber $haber, SmsService $sms)
    {
        $request->validate([
            'grup' => 'required|in:sucek,tum_premium,sms_izinli',
        ]);

        $query = User::whereNotNull('telefon')->where('sms_izni', true);

        match ($request->grup) {
            'sucek'       => $query->where('rol', 'sucek'),
            'tum_premium' => $query->whereIn('rol', ['standart', 'sucek', 'teknik']),
            'sms_izinli'  => $query,
        };

        $telefonlar = $query->pluck('telefon')->toArray();

        if (empty($telefonlar)) {
            return back()->with('hata', 'Uygun alıcı bulunamadı.');
        }

        $gonderilen = $sms->sablonTopluGonder('yeni_haber', $telefonlar, [
            'baslik' => $haber->baslik,
            'url'    => route('haberler.show', $haber->slug),
        ]);

        return back()->with('basari', '"' . $haber->baslik . '" için ' . count($telefonlar) . ' kişiye SMS gönderildi (' . $gonderilen . ' başarılı).');
    }

    public function tekGonder(Request $request, SmsService $sms)
    {
        $request->validate([
            'telefon' => 'required|string|max:20',
            'mesaj'   => 'required|string|max:500',
        ]);

        $gonderildi = $sms->gonder($request->telefon, $request->mesaj);

        return back()->with(
            $gonderildi ? 'basari' : 'hata',
            $gonderildi ? 'SMS gönderildi.' : 'SMS gönderilemedi.'
        );
    }
}
