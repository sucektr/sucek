<?php

namespace App\Http\Controllers;

use App\Models\Koleksiyon;
use App\Models\Teklif;
use Illuminate\Http\Request;

class TeklifController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $koleksiyon = Koleksiyon::where('slug', $slug)->where('aktif', true)->firstOrFail();

        abort_unless(auth()->check(), 401);

        $request->validate([
            'miktar' => 'required|numeric|min:1',
            'mesaj'  => 'nullable|string|max:500',
        ], [
            'miktar.required' => 'Teklif tutarı zorunludur.',
            'miktar.numeric'  => 'Teklif tutarı sayı olmalıdır.',
            'miktar.min'      => 'Teklif tutarı en az 1 ₺ olmalıdır.',
        ]);

        Teklif::create([
            'user_id'       => auth()->id(),
            'koleksiyon_id' => $koleksiyon->id,
            'miktar'        => $request->miktar,
            'mesaj'         => $request->mesaj,
            'durum'         => 'beklemede',
        ]);

        return back()->with('teklif_basari', 'Teklifiniz başarıyla gönderildi. En kısa sürede dönüş yapılacaktır.');
    }
}
