<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MimarlikController extends Controller
{
    public function index()
    {
        $ruhsatProjeler  = \App\Models\Proje::where('aktif', true)
            ->where('kategori', 'mimarlik')
            ->where('alt_kategori', 'ruhsat')
            ->orderBy('one_cikan', 'desc')
            ->orderBy('sira')
            ->get();

        $icMimariProjeler = \App\Models\Proje::where('aktif', true)
            ->where('kategori', 'mimarlik')
            ->where('alt_kategori', 'ic-mimari')
            ->orderBy('one_cikan', 'desc')
            ->orderBy('sira')
            ->get();

        return view('mimarlik.index', compact('ruhsatProjeler', 'icMimariProjeler'));
    }

    public function ruhsatSureci()
    {
        $projeler = \App\Models\Proje::where('aktif', true)
            ->where('kategori', 'mimarlik')
            ->where('alt_kategori', 'ruhsat')
            ->orderBy('one_cikan', 'desc')
            ->orderBy('sira')
            ->get();

        return view('mimarlik.ruhsat-sureci', compact('projeler'));
    }

    public function icMimari()
    {
        $projeler = \App\Models\Proje::where('aktif', true)
            ->where('kategori', 'mimarlik')
            ->where('alt_kategori', 'ic-mimari')
            ->orderBy('one_cikan', 'desc')
            ->orderBy('sira')
            ->get();

        return view('mimarlik.ic-mimari', compact('projeler'));
    }

    public function belgeler()
    {
        $belgeler = \App\Models\Belge::where('aktif', true)
            ->where('herkese_acik', true)
            ->orderBy('sira')
            ->get()
            ->groupBy('kategori');

        return view('mimarlik.belgeler', compact('belgeler'));
    }

    public function indir(\App\Models\Belge $belge)
    {
        abort_unless($belge->aktif && $belge->herkese_acik, 404);
        $dosyaYolu = \Illuminate\Support\Facades\Storage::disk('public')->path($belge->dosya_yolu);
        abort_unless(file_exists($dosyaYolu), 404);
        return response()->download($dosyaYolu, $belge->baslik . '.' . strtolower($belge->dosya_turu));
    }
}
