<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KoleksiyonController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->input('kategori', 'tumu');
        $ulke     = $request->input('ulke');

        $query = \App\Models\Koleksiyon::where('aktif', true)->orderBy('one_cikan', 'desc');

        if ($kategori !== 'tumu') {
            $query->where('kategori', $kategori);
        }
        if ($ulke) {
            $query->where('ulke', $ulke);
        }

        $koleksiyonlar = $query->get();

        $ulkeler = collect();
        if ($kategori === 'numizmatik') {
            $ulkeler = \App\Models\Koleksiyon::where('aktif', true)
                ->where('kategori', 'numizmatik')
                ->whereNotNull('ulke')
                ->distinct()
                ->orderBy('ulke')
                ->pluck('ulke');
        }

        return view('koleksiyon.index', compact('koleksiyonlar', 'kategori', 'ulke', 'ulkeler'));
    }

    public function show(string $slug)
    {
        $urun = \App\Models\Koleksiyon::where('slug', $slug)->where('aktif', true)->firstOrFail();
        return view('koleksiyon.show', compact('urun'));
    }
}
