<?php

namespace App\Http\Controllers;

use App\Models\Urun;

class CelikGuvenlikAgiController extends Controller
{
    public function index()
    {
        $urunler = Urun::where('aktif', true)
            ->where('kategori', 'celik-guvenlik-agi')
            ->orderByDesc('one_cikan')
            ->orderByDesc('id')
            ->get();

        return view('celik-guvenlik-agi.index', compact('urunler'));
    }
}
