<?php

namespace App\Http\Controllers\Yardimci;

use App\Http\Controllers\Controller;
use App\Models\Koleksiyon;
use App\Models\Urun;

class PanelController extends Controller
{
    public function index()
    {
        $kullaniciId = auth()->id();

        $urunler = Urun::where('eklenen_kullanici_id', $kullaniciId)
            ->latest()->take(10)->get()
            ->map(fn ($u) => (object) [
                'tip' => 'urun', 'ad' => $u->ad, 'gorsel' => $u->gorsel,
                'durum' => $u->onay_durumu, 'tarih' => $u->created_at,
            ]);

        $koleksiyonlar = Koleksiyon::where('eklenen_kullanici_id', $kullaniciId)
            ->latest()->take(10)->get()
            ->map(fn ($k) => (object) [
                'tip' => 'koleksiyon', 'ad' => $k->ad, 'gorsel' => $k->gorsel,
                'durum' => $k->onay_durumu, 'tarih' => $k->created_at,
            ]);

        $eklenenler = $urunler->concat($koleksiyonlar)->sortByDesc('tarih')->take(15)->values();

        return view('yardimci.index', compact('eklenenler'));
    }
}
