<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InsaatController extends Controller
{
    public function index()
    {
        $projeler = \App\Models\Proje::where('aktif', true)
            ->where('kategori', 'insaat')
            ->orderBy('one_cikan', 'desc')
            ->take(9)->get();

        return view('insaat.index', compact('projeler'));
    }

    public function hesaplama()
    {
        return view('insaat.hesaplama');
    }

    public function emsalHesaplama()
    {
        $f = fn(string $alan, int $varsayilan) => (int) icerik('hesaplama', $alan, (string) $varsayilan);

        $emsalFiyatlar = [
            'sinif_IA'  => $f('sinif_IA',   2600),
            'sinif_IB'  => $f('sinif_IB',   3900),
            'sinif_IC'  => $f('sinif_IC',   4200),
            'sinif_ID'  => $f('sinif_ID',   4800),
            'sinif_IIA' => $f('sinif_IIA',  8100),
            'sinif_IIB' => $f('sinif_IIB', 12500),
            'sinif_IIC' => $f('sinif_IIC', 15100),
            'sinif_IIIA'=> $f('sinif_IIIA',19800),
            'sinif_IIIB'=> $f('sinif_IIIB',21050),
            'sinif_IIIC'=> $f('sinif_IIIC',23400),
            'sinif_IVA' => $f('sinif_IVA', 26450),
            'sinif_IVB' => $f('sinif_IVB', 33900),
            'sinif_IVC' => $f('sinif_IVC', 40500),
            'sinif_VA'  => $f('sinif_VA',  42350),
            'sinif_VB'  => $f('sinif_VB',  43800),
            'sinif_VC'  => $f('sinif_VC',  48700),
            'sinif_VD'  => $f('sinif_VD',  53500),
            'sinif_VE'  => $f('sinif_VE', 103500),
        ];
        $tebligNot = icerik('hesaplama', 'teblig_not', 'ÇŞİDB Tebliği — RG 33157 / 03.02.2026');

        return view('insaat.emsal-hesaplama', compact('emsalFiyatlar', 'tebligNot'));
    }

    public function hesapla(Request $request)
    {
        $request->validate([
            'tip'   => 'required|in:kaba,ince,dekorasyon,anahtar',
            'alan'  => 'required|numeric|min:10|max:5000',
            'sinif' => 'required|in:ekonomik,standart,luks,ultra',
        ]);

        $f = fn(string $alan, int $v) => (int) icerik('hesaplama', $alan, (string) $v);

        $birimFiyatlar = [
            'kaba'       => ['ekonomik' => $f('kaba_ekonomik',1200),  'standart' => $f('kaba_standart',1600),  'luks' => $f('kaba_luks',2200),  'ultra' => $f('kaba_ultra',3000)],
            'ince'       => ['ekonomik' => $f('ince_ekonomik',1800),  'standart' => $f('ince_standart',2400),  'luks' => $f('ince_luks',3500),  'ultra' => $f('ince_ultra',5000)],
            'dekorasyon' => ['ekonomik' => $f('dekorasyon_ekonomik',800), 'standart' => $f('dekorasyon_standart',1200), 'luks' => $f('dekorasyon_luks',2000), 'ultra' => $f('dekorasyon_ultra',3500)],
            'anahtar'    => ['ekonomik' => $f('anahtar_ekonomik',3500), 'standart' => $f('anahtar_standart',5000), 'luks' => $f('anahtar_luks',8000), 'ultra' => $f('anahtar_ultra',12000)],
        ];

        $birimFiyat = $birimFiyatlar[$request->tip][$request->sinif];
        $alan = (float) $request->alan;
        $toplam = $alan * $birimFiyat;

        return response()->json([
            'toplam'       => number_format($toplam, 0, ',', '.'),
            'birim_fiyat'  => number_format($birimFiyat, 0, ',', '.'),
            'alan'         => $alan,
            'kdv'          => number_format($toplam * 0.20, 0, ',', '.'),
            'genel_toplam' => number_format($toplam * 1.20, 0, ',', '.'),
        ]);
    }
}
