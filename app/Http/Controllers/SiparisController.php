<?php

namespace App\Http\Controllers;

use App\Models\Siparis;
use App\Models\SiparisKalemi;
use Illuminate\Http\Request;

class SiparisController extends Controller
{
    public function checkout()
    {
        $sepet = session('sepet', []);

        if (empty($sepet)) {
            return redirect()->route('sepet.index')->with('hata', 'Sepetiniz boş.');
        }

        $araToplam    = collect($sepet)->sum(fn($item) => ($item['fiyat_net'] ?? $item['fiyat']) * $item['adet']);
        $kdvToplam    = collect($sepet)->sum(fn($item) => ($item['kdv_tutari'] ?? 0) * $item['adet']);
        $urunToplam   = $araToplam + $kdvToplam;
        $kargoUcreti  = kargoUcreti($sepet, $urunToplam);
        $ucretsizEsik = (float) icerik('kargo', 'ucretsiz_esik', '0');
        $toplam       = $urunToplam + $kargoUcreti;

        $kargoAdresi  = null;
        $faturaAdresi = null;

        if (auth()->check()) {
            $kargoAdresi  = auth()->user()->adresler()->where('tip', 'kargo')->first();
            $faturaAdresi = auth()->user()->adresler()->where('tip', 'fatura')->first();
        }

        $bankalar = $this->bankalar();

        return view('siparis.olustur', compact('sepet', 'araToplam', 'kdvToplam', 'kargoUcreti', 'ucretsizEsik', 'toplam', 'kargoAdresi', 'faturaAdresi', 'bankalar'));
    }

    public function store(Request $request)
    {
        $sepet = session('sepet', []);

        if (empty($sepet)) {
            return redirect()->route('sepet.index');
        }

        $request->validate([
            'ad_soyad'          => 'required|string|max:120',
            'email'             => 'required|email|max:120',
            'telefon'           => 'nullable|string|max:20',
            'teslimat_adres'    => 'required|string|max:300',
            'teslimat_sehir'    => 'required|string|max:80',
            'teslimat_ilce'     => 'nullable|string|max:80',
            'teslimat_posta'    => 'nullable|string|max:10',
            'musteri_notu'      => 'nullable|string|max:500',
            'odeme_yontemi'     => 'required|in:havale,kredi_karti',
        ]);

        $teslimatAdresi = [
            'adres_satiri' => $request->teslimat_adres,
            'sehir'        => $request->teslimat_sehir,
            'ilce'         => $request->teslimat_ilce,
            'posta_kodu'   => $request->teslimat_posta,
            'ulke'         => 'Türkiye',
        ];

        $araToplam   = collect($sepet)->sum(fn($item) => ($item['fiyat_net'] ?? $item['fiyat']) * $item['adet']);
        $kdvToplam   = collect($sepet)->sum(fn($item) => ($item['kdv_tutari'] ?? 0) * $item['adet']);
        $urunToplam  = $araToplam + $kdvToplam;
        $kargoTutar  = kargoUcreti($sepet, $urunToplam);
        $genelToplam = $urunToplam + $kargoTutar;

        $siparis = Siparis::create([
            'referans'         => Siparis::referansUret(),
            'user_id'          => auth()->id(),
            'ad_soyad'         => $request->ad_soyad,
            'email'            => $request->email,
            'telefon'          => $request->telefon,
            'teslimat_adresi'  => $teslimatAdresi,
            'odeme_yontemi'    => $request->odeme_yontemi,
            'durum'            => 'bekliyor',
            'musteri_notu'     => $request->musteri_notu,
            'ara_toplam'       => $araToplam,
            'kdv_tutari'       => $kdvToplam,
            'kargo_ucreti'     => $kargoTutar,
            'toplam'           => $genelToplam,
        ]);

        foreach ($sepet as $item) {
            $birimFiyat  = $item['fiyat'];        // KDV dahil fiyat
            $kdvBirim    = $item['kdv_tutari'] ?? 0;
            $kdvOrani    = $item['kdv_orani'] ?? 0;
            SiparisKalemi::create([
                'siparis_id'      => $siparis->id,
                'urun_tipi'       => $item['tip'],
                'urun_id'         => $item['id'],
                'varyant_id'      => $item['varyant_id'] ?? null,
                'varyant_bilgisi' => $item['varyant_bilgisi'] ?? null,
                'urun_adi'        => $item['ad'],
                'urun_gorsel'     => $item['gorsel'] ?? null,
                'birim_fiyat'     => $birimFiyat,
                'kdv_orani'       => $kdvOrani,
                'kdv_tutari'      => $kdvBirim * $item['adet'],
                'adet'            => $item['adet'],
                'toplam'          => $birimFiyat * $item['adet'],
            ]);
        }

        session()->forget('sepet');

        if ($request->odeme_yontemi === 'kredi_karti') {
            return redirect()->route('siparis.odeme.goster', $siparis->referans);
        }

        return redirect()->route('siparis.onay', $siparis->referans);
    }

    private function bankalar(): array
    {
        $json = icerik('odeme', 'bankalar', '');
        if ($json) {
            $arr = json_decode($json, true);
            if (is_array($arr) && !empty($arr)) {
                return array_map(fn($b) => [
                    'banka' => $b['banka_adi'] ?? '',
                    'iban'  => $b['iban'] ?? '',
                    'alici' => $b['alici_adi'] ?? '',
                ], $arr);
            }
        }
        // Eski tekli alan yapısından geriye dönük uyumluluk
        $eskiBanka = icerik('odeme', 'banka_adi', '');
        if ($eskiBanka) {
            return [[
                'banka' => $eskiBanka,
                'iban'  => icerik('odeme', 'iban', ''),
                'alici' => icerik('odeme', 'alici_adi', ''),
            ]];
        }
        return [];
    }

    public function onay(string $referans)
    {
        $siparis = Siparis::with('kalemler')
            ->where('referans', $referans)
            ->firstOrFail();

        // Yalnızca sipariş sahibi ya da misafir (session'da referans yok artık — sadece URL bilgisi yeterli)
        if ($siparis->user_id && auth()->id() !== $siparis->user_id) {
            abort(403);
        }

        $bankalar = $this->bankalar();

        return view('siparis.onay', compact('siparis', 'bankalar'));
    }
}
