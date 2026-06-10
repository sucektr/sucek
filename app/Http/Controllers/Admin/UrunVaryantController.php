<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use App\Models\UrunVaryant;
use Illuminate\Http\Request;

class UrunVaryantController extends Controller
{
    public function kaydet(Request $request, Urun $urun)
    {
        $secenekler = json_decode($request->input('secenekler_json', '[]'), true) ?? [];
        $varyantlar = json_decode($request->input('varyantlar_json', '[]'), true) ?? [];

        // Mevcut seçenekleri temizle (cascade: değerler de silinir)
        $urun->secenekler()->delete();

        foreach ($secenekler as $i => $sec) {
            $ad = trim($sec['ad'] ?? '');
            if (!$ad) continue;
            $secModel = $urun->secenekler()->create(['ad' => $ad, 'sira' => $i]);
            foreach ($sec['degerler'] ?? [] as $j => $deger) {
                $deger = trim($deger);
                if ($deger !== '') {
                    $secModel->degerler()->create(['deger' => $deger, 'sira' => $j]);
                }
            }
        }

        // Mevcut varyantları temizle ve yeniden oluştur
        $urun->varyantlar()->delete();

        foreach ($varyantlar as $v) {
            $degerler = $v['degerler'] ?? [];
            if (empty($degerler)) continue;
            $urun->varyantlar()->create([
                'degerler'  => $degerler,
                'stok'      => max(0, (int) ($v['stok'] ?? 0)),
                'stok_kodu' => trim($v['stok_kodu'] ?? '') ?: null,
                'aktif'     => !empty($v['aktif']),
            ]);
        }

        return back()->with('basari', 'Varyantlar kaydedildi.');
    }

    public function sil(Urun $urun)
    {
        $urun->secenekler()->delete();
        $urun->varyantlar()->delete();
        return back()->with('basari', 'Varyant tanımları silindi.');
    }
}
