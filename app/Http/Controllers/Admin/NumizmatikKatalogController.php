<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Koleksiyon;
use App\Models\NumizmatikKatalog;
use Illuminate\Http\Request;

class NumizmatikKatalogController extends Controller
{
    public function index(Request $request)
    {
        $kataloglar = NumizmatikKatalog::orderByDesc('created_at')->get();
        $duzenlenenKatalog = null;
        $duzenlenenKalemler = collect();

        if ($request->filled('katalog')) {
            $duzenlenenKatalog = NumizmatikKatalog::find($request->katalog);
            if ($duzenlenenKatalog) {
                $duzenlenenKalemler = $this->kalemleriYukle($duzenlenenKatalog->koleksiyon_idler ?? [])
                    ->map(fn($k) => $this->kalemDizi($k));
            }
        }

        $kapakVarsayilan = [
            'marka'  => 'SUÇEK',
            'logo'   => '',
        ];
        $kapakBirlestir = array_merge($kapakVarsayilan, array_filter($duzenlenenKatalog?->kapak_ayarlari ?? [], fn($v) => $v !== null && $v !== ''));

        return view('admin.numizmatik-katalog.index', compact(
            'kataloglar', 'duzenlenenKatalog', 'duzenlenenKalemler', 'kapakBirlestir'
        ));
    }

    public function kalemler(Request $request)
    {
        $q = Koleksiyon::query()->where('kategori', 'numizmatik');
        if ($request->filled('ara')) {
            $q->where(function ($sub) use ($request) {
                $sub->where('ad', 'like', '%' . $request->ara . '%')
                    ->orWhere('stok_kodu', 'like', '%' . $request->ara . '%');
            });
        }
        if ($request->filled('durum')) {
            $q->where('durum', $request->durum);
        }

        $kalemler = $q->orderBy('ad')->get()->map(fn($k) => $this->kalemDizi($k))->values();

        return response()->json($kalemler);
    }

    public function kaydet(Request $request)
    {
        $request->validate([
            'baslik'           => 'required|string|max:255',
            'koleksiyon_idler' => 'required|array|min:1',
        ]);

        $data = [
            'baslik'           => $request->baslik,
            'alt_baslik'       => $request->alt_baslik,
            'kapak_ayarlari'   => (array) ($request->kapak ?? []),
            'koleksiyon_idler' => $request->koleksiyon_idler,
            'user_id'          => auth()->id(),
        ];

        if ($request->filled('id')) {
            $katalog = NumizmatikKatalog::findOrFail($request->id);
            $katalog->update($data);
        } else {
            $katalog = NumizmatikKatalog::create($data);
        }

        return response()->json(['success' => true, 'id' => $katalog->id]);
    }

    public function yazdir(int $id)
    {
        $katalog  = NumizmatikKatalog::findOrFail($id);
        $kalemler = $this->kalemleriYukle($katalog->koleksiyon_idler ?? []);

        return view('admin.numizmatik-katalog.yazdir', compact('katalog', 'kalemler'));
    }

    public function logoYukle(Request $request)
    {
        $request->validate(['logo' => 'required|image|max:3072']);
        $path = $request->file('logo')->store('katalog-logolar', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    }

    public function sil(int $id)
    {
        NumizmatikKatalog::findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('basari', 'Katalog silindi.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function kalemleriYukle(array $koleksiyonIdler): \Illuminate\Support\Collection
    {
        $map = Koleksiyon::whereIn('id', $koleksiyonIdler)->get()->keyBy('id');

        return collect($koleksiyonIdler)
            ->map(fn($id) => $map[$id] ?? null)
            ->filter()
            ->values();
    }

    private function kalemDizi(Koleksiyon $k): array
    {
        return [
            'id'        => $k->id,
            'ad'        => $k->ad,
            'stok_kodu' => $k->stok_kodu,
            'ulke'      => $k->ulke,
            'fiyat'     => $k->fiyat,
            'durum'     => $k->durum,
            'gorsel'    => $k->gorsel ? asset('storage/' . $k->gorsel) : null,
            'arka'      => !empty($k->gorseller[0]) ? asset('storage/' . $k->gorseller[0]) : null,
        ];
    }
}
