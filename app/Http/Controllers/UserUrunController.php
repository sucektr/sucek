<?php

namespace App\Http\Controllers;

use App\Models\UserUrun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserUrunController extends Controller
{
    public function index()
    {
        $urunler = UserUrun::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($u) => $this->dizi($u));

        return view('katalog.urunlerim', compact('urunler'));
    }

    public function gorselYukle(Request $request)
    {
        $request->validate(['gorsel' => 'required|image|max:4096']);
        $yol = $request->file('gorsel')->store('user-urunler', 'public');
        return response()->json(['path' => $yol, 'url' => asset('storage/' . $yol)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad'       => 'required|string|max:255',
            'aciklama' => 'nullable|string',
        ]);

        // Ön yüklenen yollar (sequential upload) veya eski tek-dosya (kisisel modal)
        $yollar = json_decode($request->input('gorseller_koru', '[]'), true) ?: [];
        if (empty($yollar)) {
            $yollar = $this->yukleGorseller($request);
        }

        $urun = UserUrun::create([
            'user_id'   => auth()->id(),
            'ad'        => $request->ad,
            'gorsel'    => $yollar[0] ?? null,
            'gorseller' => $yollar,
            'aciklama'  => $request->aciklama,
        ]);

        return response()->json(['success' => true, 'urun' => $this->dizi($urun)]);
    }

    public function update(Request $request, int $id)
    {
        $urun = UserUrun::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'ad'                => 'required|string|max:255',
            'gorseller_yeni.*' => 'nullable|image|max:4096',
            'aciklama'          => 'nullable|string',
        ]);

        // Korunan yollar (mevcut, silinmeyecek)
        $korunan = json_decode($request->input('gorseller_koru', '[]'), true) ?: [];

        // Artık kullanılmayan görselleri sil
        $eskiYollar = $urun->gorseller ?? ($urun->gorsel ? [$urun->gorsel] : []);
        foreach ($eskiYollar as $yol) {
            if (!in_array($yol, $korunan)) {
                Storage::disk('public')->delete($yol);
            }
        }

        // Yeni görselleri yükle ve birleştir
        $yeniYollar = $this->yukleGorseller($request);
        $tumYollar  = array_values(array_merge($korunan, $yeniYollar));

        $urun->update([
            'ad'        => $request->ad,
            'gorsel'    => $tumYollar[0] ?? null,
            'gorseller' => $tumYollar,
            'aciklama'  => $request->aciklama,
        ]);

        return response()->json(['success' => true, 'urun' => $this->dizi($urun->fresh())]);
    }

    public function destroy(int $id)
    {
        $urun = UserUrun::where('user_id', auth()->id())->findOrFail($id);

        $yollar = $urun->gorseller ?? ($urun->gorsel ? [$urun->gorsel] : []);
        foreach ($yollar as $yol) {
            Storage::disk('public')->delete($yol);
        }

        $urun->delete();
        return response()->json(['success' => true]);
    }

    private function yukleGorseller(Request $request): array
    {
        $yollar = [];
        if ($request->hasFile('gorseller_yeni')) {
            foreach ($request->file('gorseller_yeni') as $file) {
                if ($file->isValid()) {
                    $yollar[] = $file->store('user-urunler', 'public');
                }
            }
        } elseif ($request->hasFile('gorsel')) {
            $file = $request->file('gorsel');
            if ($file->isValid()) {
                $yollar[] = $file->store('user-urunler', 'public');
            }
        }
        return $yollar;
    }

    private function dizi(UserUrun $u): array
    {
        $yollar = !empty($u->gorseller) ? $u->gorseller : ($u->gorsel ? [$u->gorsel] : []);
        return [
            'id'               => $u->id,
            'ad'               => $u->ad,
            'gorsel'           => !empty($yollar) ? asset('storage/' . $yollar[0]) : null,
            'gorseller'        => array_map(fn($p) => asset('storage/' . $p), $yollar),
            'gorseller_yollar' => $yollar,
            'aciklama'         => $u->aciklama ?? '',
        ];
    }
}
