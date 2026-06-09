<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KoleksiyonController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->input('kategori', 'tumu');

        $query = \App\Models\Koleksiyon::where('aktif', true)->orderBy('one_cikan', 'desc');

        if ($kategori !== 'tumu') {
            $query->where('kategori', $kategori);
        }

        $koleksiyonlar = $query->get();

        return view('koleksiyon.index', compact('koleksiyonlar', 'kategori'));
    }

    public function show(string $slug)
    {
        $urun = \App\Models\Koleksiyon::where('slug', $slug)->where('aktif', true)->firstOrFail();
        return view('koleksiyon.show', compact('urun'));
    }
}
