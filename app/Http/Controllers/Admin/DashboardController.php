<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'urun'        => \App\Models\Urun::count(),
            'koleksiyon'  => \App\Models\Koleksiyon::count(),
            'proje'       => \App\Models\Proje::count(),
            'belge'       => \App\Models\Belge::count(),
            'mesaj'       => \App\Models\IletisimMesaji::count(),
            'okunmamis'   => \App\Models\IletisimMesaji::where('okundu', false)->count(),
        ];

        $sonMesajlar = \App\Models\IletisimMesaji::latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'sonMesajlar'));
    }
}
