<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siparis;
use Illuminate\Http\Request;

class OdemeController extends Controller
{
    public function index()
    {
        $istatistik = [
            'bugun'    => Siparis::where('odeme_yontemi', 'kredi_karti')->whereDate('created_at', today())->count(),
            'bu_ay'    => Siparis::where('odeme_yontemi', 'kredi_karti')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'basarili' => Siparis::where('odeme_yontemi', 'kredi_karti')->where('durum', '!=', 'bekliyor')->where('durum', '!=', 'iptal')->count(),
            'bekliyor' => Siparis::where('odeme_yontemi', 'kredi_karti')->where('durum', 'bekliyor')->count(),
        ];

        $kartSiparisler = Siparis::where('odeme_yontemi', 'kredi_karti')
            ->latest()
            ->limit(30)
            ->get();

        $paytrAyarlar = [
            'merchant_id'   => icerik('sistem', 'paytr_merchant_id',   (string) config('services.paytr.merchant_id', '')),
            'merchant_key'  => icerik('sistem', 'paytr_merchant_key',  (string) config('services.paytr.merchant_key', '')),
            'merchant_salt' => icerik('sistem', 'paytr_merchant_salt', (string) config('services.paytr.merchant_salt', '')),
            'test_mode'     => icerik('sistem', 'paytr_test_mode',     (string) config('services.paytr.test_mode', '0')),
        ];

        return view('admin.odeme.index', compact('istatistik', 'kartSiparisler', 'paytrAyarlar'));
    }

    public function ayarlarKaydet(Request $request)
    {
        $request->validate([
            'merchant_id'   => 'required|string|max:20',
            'merchant_key'  => 'required|string|max:64',
            'merchant_salt' => 'required|string|max:64',
            'test_mode'     => 'required|in:0,1',
        ]);

        foreach ([
            'paytr_merchant_id'   => $request->merchant_id,
            'paytr_merchant_key'  => $request->merchant_key,
            'paytr_merchant_salt' => $request->merchant_salt,
            'paytr_test_mode'     => $request->test_mode,
        ] as $alan => $deger) {
            \App\Models\Icerik::updateOrCreate(
                ['sayfa' => 'sistem', 'alan' => $alan],
                ['deger' => $deger, 'baslik' => $alan, 'tip' => 'metin', 'sira' => 0]
            );
        }

        return back()->with('basari', 'PayTR ayarları kaydedildi.');
    }
}
