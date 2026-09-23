<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ceviri;
use Illuminate\Http\Request;

class CeviriController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $ceviriler = Ceviri::when($q, fn($query) => $query->where('tr_metin', 'like', "%{$q}%")
                ->orWhere('en_metin', 'like', "%{$q}%"))
            ->orderByRaw("CASE WHEN en_metin IS NULL OR en_metin = '' THEN 0 ELSE 1 END")
            ->orderBy('tr_metin')
            ->paginate(50)->withQueryString();

        $eksikSayisi = Ceviri::where('en_metin', '')->orWhereNull('en_metin')->count();

        return view('admin.ceviriler.index', compact('ceviriler', 'eksikSayisi'));
    }

    public function guncelle(Request $request, Ceviri $ceviri)
    {
        $request->validate([
            'en_metin' => 'nullable|string|max:2000',
        ]);

        $ceviri->update(['en_metin' => $request->input('en_metin')]);

        return back()->with('basari', 'Çeviri güncellendi.');
    }
}
