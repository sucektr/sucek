<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use App\Models\UrunSecenekDegeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UrunVaryantController extends Controller
{
    public function kaydet(Request $request, Urun $urun)
    {
        $secenekler = json_decode($request->input('secenekler_json', '[]'), true) ?? [];
        $varyantlar = json_decode($request->input('varyantlar_json', '[]'), true) ?? [];

        // Mevcut seçenek değerlerinin görsellerini sakla (yeniden kayıtta kaybolmasın)
        $mevcutGorseller = [];
        foreach ($urun->secenekler()->with('degerler')->get() as $sec) {
            foreach ($sec->degerler as $d) {
                if ($d->gorsel) {
                    $mevcutGorseller[$sec->ad][$d->deger] = $d->gorsel;
                }
            }
        }

        // Seçenekleri sil ve yeniden oluştur
        $urun->secenekler()->delete();

        foreach ($secenekler as $i => $sec) {
            $ad = trim($sec['ad'] ?? '');
            if (!$ad) continue;
            $secModel = $urun->secenekler()->create(['ad' => $ad, 'sira' => $i]);
            foreach ($sec['degerler'] ?? [] as $j => $deger) {
                $deger = trim($deger);
                if ($deger === '') continue;
                $secModel->degerler()->create([
                    'deger'  => $deger,
                    'sira'   => $j,
                    'gorsel' => $mevcutGorseller[$ad][$deger] ?? null,
                ]);
            }
        }

        // Varyantları sil ve yeniden oluştur
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

    public function gorsellerKaydet(Request $request, Urun $urun)
    {
        $request->validate(['gorsel.*' => 'nullable|image|max:4096']);

        foreach ($request->file('gorsel', []) as $degerId => $file) {
            $deger = UrunSecenekDegeri::whereHas('secenek', fn($q) => $q->where('urun_id', $urun->id))
                ->find((int) $degerId);
            if (!$deger) continue;

            if ($deger->gorsel) {
                Storage::disk('public')->delete($deger->gorsel);
            }
            $deger->gorsel = $file->store('urun-secenek-gorseller', 'public');
            $deger->save();
        }

        return back()->with('basari', 'Seçenek görselleri kaydedildi.');
    }

    public function sil(Urun $urun)
    {
        // Görsel dosyalarını da sil
        foreach ($urun->secenekler()->with('degerler')->get() as $sec) {
            foreach ($sec->degerler as $d) {
                if ($d->gorsel) {
                    Storage::disk('public')->delete($d->gorsel);
                }
            }
        }
        $urun->secenekler()->delete();
        $urun->varyantlar()->delete();

        return back()->with('basari', 'Varyant tanımları silindi.');
    }
}
