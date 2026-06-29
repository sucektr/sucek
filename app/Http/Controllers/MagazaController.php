<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;

class MagazaController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim($request->input('q', ''));
        $kategori = $request->input('kategori');
        $siralama = $request->input('siralama', 'yeni');
        $ozellikFiltreler = $request->input('oz', []); // ['Cinsiyet' => 'Kadın', 'Numara' => '40']
        $aramaAktif = $q !== '' || $kategori || !empty($ozellikFiltreler);

        if ($aramaAktif) {
            $sorgu = Urun::where('aktif', true)
                ->when($q, fn($q2) => $q2->where('ad', 'like', "%{$q}%"))
                ->when($kategori, fn($q2) => $q2->where('kategori', $kategori));

            // Özellik filtreleri — JSON içinde arama
            foreach ($ozellikFiltreler as $ad => $deger) {
                if ($deger === '' || $deger === null) continue;
                $sorgu->where(function ($q2) use ($ad, $deger) {
                    $q2->whereRaw(
                        "EXISTS (SELECT 1 FROM JSON_TABLE(ozellikler, '$[*]' COLUMNS(ad VARCHAR(200) PATH '$.ad', degerler JSON PATH '$.degerler')) AS jt WHERE jt.ad = ? AND JSON_SEARCH(jt.degerler, 'one', ?) IS NOT NULL)",
                        [$ad, $deger]
                    );
                });
            }

            match ($siralama) {
                'fiyat_artan'  => $sorgu->orderBy('fiyat'),
                'fiyat_azalan' => $sorgu->orderByDesc('fiyat'),
                default        => $sorgu->latest(),
            };

            $urunler = $sorgu->paginate(24)->withQueryString();

            // Mevcut kategorideki tüm ürünlerden özellik seçeneklerini topla
            $ozellikSecenekleri = $this->ozellikSecenekleri($kategori, $q);

            return view('magaza.index', compact('urunler', 'q', 'kategori', 'siralama', 'aramaAktif', 'ozellikFiltreler', 'ozellikSecenekleri'));
        }

        $spor       = Urun::where('aktif', true)->where('kategori', 'spor')->take(8)->get();
        $dekorasyon = Urun::where('aktif', true)->where('kategori', 'dekorasyon')->take(8)->get();
        $insaat     = Urun::where('aktif', true)->where('kategori', 'insaat')->take(8)->get();
        $diger      = Urun::where('aktif', true)->where('kategori', 'diger')->take(8)->get();

        $ozellikFiltreler   = [];
        $ozellikSecenekleri = [];

        return view('magaza.index', compact('spor', 'dekorasyon', 'insaat', 'diger', 'q', 'kategori', 'siralama', 'aramaAktif', 'ozellikFiltreler', 'ozellikSecenekleri'));
    }

    private function ozellikSecenekleri(?string $kategori, string $q): array
    {
        $sorgu = Urun::where('aktif', true)->whereNotNull('ozellikler');
        if ($kategori) $sorgu->where('kategori', $kategori);
        if ($q) $sorgu->where('ad', 'like', "%{$q}%");

        $gruplar = [];
        $sorgu->select('ozellikler')->get()->each(function ($urun) use (&$gruplar) {
            foreach ($urun->ozellikler ?? [] as $oz) {
                $ad = $oz['ad'] ?? '';
                if (!$ad) continue;
                if (!isset($gruplar[$ad])) $gruplar[$ad] = [];
                foreach ($oz['degerler'] ?? [] as $d) {
                    if ($d !== '' && !in_array($d, $gruplar[$ad])) {
                        $gruplar[$ad][] = $d;
                    }
                }
            }
        });
        return $gruplar;
    }

    public function urun(string $slug)
    {
        $urun = Urun::where('slug', $slug)->where('aktif', true)->firstOrFail();
        $ilgiliUrunler = Urun::where('aktif', true)
            ->where('kategori', $urun->kategori)
            ->where('id', '!=', $urun->id)
            ->take(4)->get();

        return view('magaza.urun', compact('urun', 'ilgiliUrunler'));
    }
}
