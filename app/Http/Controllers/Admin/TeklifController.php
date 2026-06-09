<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teklif;
use Illuminate\Http\Request;

class TeklifController extends Controller
{
    public function index(Request $request)
    {
        $durum = $request->input('durum');

        $teklifler = Teklif::with(['user', 'koleksiyon'])
            ->when($durum, fn($q) => $q->where('durum', $durum))
            ->latest()
            ->paginate(20)->withQueryString();

        return view('admin.teklifler.index', compact('teklifler'));
    }

    public function durum(Request $request, Teklif $teklif)
    {
        $request->validate(['durum' => 'required|in:beklemede,kabul,red']);
        $teklif->update(['durum' => $request->durum]);
        return back()->with('basari', 'Teklif durumu güncellendi.');
    }
}
