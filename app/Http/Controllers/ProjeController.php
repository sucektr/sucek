<?php

namespace App\Http\Controllers;

use App\Models\Proje;

class ProjeController extends Controller
{
    public function index()
    {
        $projeler = Proje::where('aktif', true)
            ->orderBy('sira')
            ->orderByDesc('yil')
            ->get();

        $kategoriler = $projeler->pluck('kategori')->unique()->filter()->values();

        return view('projeler.index', compact('projeler', 'kategoriler'));
    }

    public function show(string $slug)
    {
        $proje = Proje::where('slug', $slug)->where('aktif', true)->firstOrFail();

        $diger = Proje::where('aktif', true)
            ->where('id', '!=', $proje->id)
            ->where('kategori', $proje->kategori)
            ->orderBy('sira')
            ->limit(3)
            ->get();

        return view('projeler.show', compact('proje', 'diger'));
    }
}
