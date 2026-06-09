<?php

namespace App\Http\Controllers;

use App\Models\Haber;

class HaberController extends Controller
{
    public function index()
    {
        $haberler = Haber::where('yayinda', true)->latest()->paginate(12);
        $kategoriler = Haber::where('yayinda', true)
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('haberler.index', compact('haberler', 'kategoriler'));
    }

    public function show(Haber $haber)
    {
        if (!$haber->yayinda) {
            abort(404);
        }

        $diger = Haber::where('yayinda', true)
            ->where('id', '!=', $haber->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('haberler.show', compact('haber', 'diger'));
    }
}
