<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IletisimController extends Controller
{
    public function index()
    {
        $mesajlar = \App\Models\IletisimMesaji::latest()->paginate(20);
        return view('admin.iletisim.index', compact('mesajlar'));
    }

    public function oku(\App\Models\IletisimMesaji $mesaj)
    {
        $mesaj->update(['okundu' => !$mesaj->okundu, 'okundu_at' => $mesaj->okundu ? null : now()]);
        return back()->with('basari', 'Durum güncellendi.');
    }

    public function destroy(\App\Models\IletisimMesaji $mesaj)
    {
        $mesaj->delete();
        return back()->with('basari', 'Mesaj silindi.');
    }
}
