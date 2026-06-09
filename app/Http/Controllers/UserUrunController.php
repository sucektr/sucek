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

    public function store(Request $request)
    {
        $request->validate([
            'ad'      => 'required|string|max:255',
            'gorsel'  => 'nullable|image|max:4096',
            'aciklama'=> 'nullable|string',
        ]);

        $gorsel = null;
        if ($request->hasFile('gorsel')) {
            $gorsel = $request->file('gorsel')->store('user-urunler', 'public');
        }

        $urun = UserUrun::create([
            'user_id'  => auth()->id(),
            'ad'       => $request->ad,
            'gorsel'   => $gorsel,
            'aciklama' => $request->aciklama,
        ]);

        return response()->json(['success' => true, 'urun' => $this->dizi($urun)]);
    }

    public function update(Request $request, int $id)
    {
        $urun = UserUrun::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'ad'      => 'required|string|max:255',
            'gorsel'  => 'nullable|image|max:4096',
            'aciklama'=> 'nullable|string',
        ]);

        $gorsel = $urun->gorsel;
        if ($request->hasFile('gorsel')) {
            if ($gorsel) Storage::disk('public')->delete($gorsel);
            $gorsel = $request->file('gorsel')->store('user-urunler', 'public');
        }

        $urun->update(['ad' => $request->ad, 'gorsel' => $gorsel, 'aciklama' => $request->aciklama]);

        return response()->json(['success' => true, 'urun' => $this->dizi($urun->fresh())]);
    }

    public function destroy(int $id)
    {
        $urun = UserUrun::where('user_id', auth()->id())->findOrFail($id);
        if ($urun->gorsel) Storage::disk('public')->delete($urun->gorsel);
        $urun->delete();
        return response()->json(['success' => true]);
    }

    private function dizi(UserUrun $u): array
    {
        return [
            'id'       => $u->id,
            'ad'       => $u->ad,
            'gorsel'   => $u->gorsel ? asset('storage/' . $u->gorsel) : null,
            'aciklama' => $u->aciklama ?? '',
        ];
    }
}
