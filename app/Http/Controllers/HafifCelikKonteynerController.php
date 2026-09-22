<?php

namespace App\Http\Controllers;

use App\Models\Urun;

class HafifCelikKonteynerController extends Controller
{
    public function index()
    {
        $urunler = Urun::where('aktif', true)
            ->where('kategori', 'hafif-celik-konteyner')
            ->orderByDesc('one_cikan')
            ->orderByDesc('id')
            ->get();

        return view('hafif-celik-konteyner.index', compact('urunler'));
    }
}
