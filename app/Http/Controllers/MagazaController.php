<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MagazaController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q        = trim($request->input('q', ''));
        $kategori = $request->input('kategori');
        $siralama = $request->input('siralama', 'yeni');
        $aramaAktif = $q !== '' || $kategori;

        if ($aramaAktif) {
            $sorgu = \App\Models\Urun::where('aktif', true)
                ->when($q, fn($query) => $query->where('ad', 'like', "%{$q}%"))
                ->when($kategori, fn($query) => $query->where('kategori', $kategori));

            match ($siralama) {
                'fiyat_artan'  => $sorgu->orderBy('fiyat'),
                'fiyat_azalan' => $sorgu->orderByDesc('fiyat'),
                default        => $sorgu->latest(),
            };

            $urunler = $sorgu->paginate(24)->withQueryString();
            return view('magaza.index', compact('urunler', 'q', 'kategori', 'siralama', 'aramaAktif'));
        }

        $spor       = \App\Models\Urun::where('aktif', true)->where('kategori', 'spor')->take(8)->get();
        $dekorasyon = \App\Models\Urun::where('aktif', true)->where('kategori', 'dekorasyon')->take(8)->get();
        $insaat     = \App\Models\Urun::where('aktif', true)->where('kategori', 'insaat')->take(8)->get();
        $diger      = \App\Models\Urun::where('aktif', true)->where('kategori', 'diger')->take(8)->get();

        return view('magaza.index', compact('spor', 'dekorasyon', 'insaat', 'diger', 'q', 'kategori', 'siralama', 'aramaAktif'));
    }

    public function urun(string $slug)
    {
        $urun = \App\Models\Urun::where('slug', $slug)->where('aktif', true)->firstOrFail();
        $ilgiliUrunler = \App\Models\Urun::where('aktif', true)
            ->where('kategori', $urun->kategori)
            ->where('id', '!=', $urun->id)
            ->take(4)->get();

        return view('magaza.urun', compact('urun', 'ilgiliUrunler'));
    }
}
