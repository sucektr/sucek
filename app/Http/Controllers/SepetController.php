<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SepetController extends Controller
{
    public function index()
    {
        $sepet        = session('sepet', []);
        $araToplam    = collect($sepet)->sum(fn($item) => ($item['fiyat_net'] ?? $item['fiyat']) * $item['adet']);
        $kdvToplam    = collect($sepet)->sum(fn($item) => ($item['kdv_tutari'] ?? 0) * $item['adet']);
        $urunToplam   = $araToplam + $kdvToplam;
        $kargoUcreti  = kargoUcreti($sepet, $urunToplam);
        $ucretsizEsik = (float) icerik('kargo', 'ucretsiz_esik', '0');
        $toplam       = $urunToplam + $kargoUcreti;
        return view('sepet.index', compact('sepet', 'araToplam', 'kdvToplam', 'kargoUcreti', 'ucretsizEsik', 'toplam'));
    }

    public function ekle(Request $request)
    {
        $request->validate([
            'urun_id'   => 'required|integer',
            'urun_tipi' => 'required|in:urun,koleksiyon',
            'adet'      => 'required|integer|min:1|max:99',
        ]);

        $sepet = session('sepet', []);
        $key   = $request->urun_tipi . '_' . $request->urun_id;

        $model = $request->urun_tipi === 'koleksiyon'
            ? \App\Models\Koleksiyon::findOrFail($request->urun_id)
            : \App\Models\Urun::findOrFail($request->urun_id);

        // KDV alanları yoksa (eski model) sıfır kullan
        $kdvOrani    = method_exists($model, 'kdvTutari') ? (float) $model->kdv_orani : 0;
        $fiyatNet    = method_exists($model, 'kdvHaricFiyat') ? $model->kdvHaricFiyat() : (float) $model->fiyat;
        $kdvBirim    = method_exists($model, 'kdvTutari') ? $model->kdvTutari() : 0.0;
        $fiyatEfekt  = method_exists($model, 'kdvDahilFiyat') ? $model->kdvDahilFiyat() : (float) $model->fiyat;
        $musteriKargo = method_exists($model, 'musteriKargoUcreti') ? $model->musteriKargoUcreti() : 0.0;

        if (isset($sepet[$key])) {
            $sepet[$key]['adet'] += $request->adet;
        } else {
            $sepet[$key] = [
                'id'           => $model->id,
                'tip'          => $request->urun_tipi,
                'ad'           => $model->ad,
                'fiyat'        => $fiyatEfekt,    // KDV dahil fiyat (müşteri görür)
                'fiyat_net'    => $fiyatNet,       // KDV hariç fiyat
                'kdv_orani'    => $kdvOrani,
                'kdv_tutari'   => $kdvBirim,       // birim başına KDV
                'kargo_ucreti' => $musteriKargo,   // müşterinin ödeyeceği kargo (0 = ücretsiz)
                'gorsel'       => $model->gorsel,
                'adet'         => $request->adet,
            ];
        }

        session(['sepet' => $sepet]);

        return response()->json([
            'durum' => 'ok',
            'adet'  => collect($sepet)->sum('adet'),
        ]);
    }

    public function guncelle(Request $request)
    {
        $sepet = session('sepet', []);
        if (isset($sepet[$request->key])) {
            $sepet[$request->key]['adet'] = max(1, (int) $request->adet);
            session(['sepet' => $sepet]);
        }
        return response()->json(['durum' => 'ok']);
    }

    public function kaldir(string $id)
    {
        $sepet = session('sepet', []);
        unset($sepet[$id]);
        session(['sepet' => $sepet]);

        if (request()->expectsJson()) {
            return response()->json(['durum' => 'ok', 'adet' => collect($sepet)->sum('adet')]);
        }

        return back();
    }

    public function temizle()
    {
        session()->forget('sepet');
        return response()->json(['durum' => 'ok']);
    }
}
