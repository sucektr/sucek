<?php

namespace App\Http\Controllers;

use App\Models\Proje;

class HafifCelikKonteynerController extends Controller
{
    public function index()
    {
        $projeler = Proje::where('aktif', true)
            ->where('kategori', 'hafif-celik-konteyner')
            ->orderBy('sira')
            ->orderByDesc('yil')
            ->get();

        return view('hafif-celik-konteyner.index', compact('projeler'));
    }
}
