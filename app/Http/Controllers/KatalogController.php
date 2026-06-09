<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use App\Models\Urun;
use App\Models\UserUrun;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $kataloglar = Katalog::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        $duzenlenenKatalog = null;
        $duzenlenenUrunler = collect();

        if ($request->filled('katalog')) {
            $duzenlenenKatalog = Katalog::where('user_id', auth()->id())->find($request->katalog);
            if ($duzenlenenKatalog) {
                $duzenlenenUrunler = $this->urunleriYukle($duzenlenenKatalog->urun_idler ?? [], auth()->id());
            }
        }

        $kapakBase   = $duzenlenenKatalog?->kapak_ayarlari ?? [];
        $icindekiler = $kapakBase['icindekiler'] ?? [];
        unset($kapakBase['icindekiler']);

        $kapakVarsayilan = [
            'marka'       => 'SUÇEK',
            'marka2'      => '',
            'logo'        => '',
            'kurum'       => '',
            'ihale_no'    => '',
            'ihale_tarih' => '',
            'ihale_yeri'  => '',
            'ihale_saat'  => '',
            'ekstralar'   => [],
            'stiller'     => [
                'kurum_font'   => 'inter',
                'kurum_boyut'  => 52,
                'baslik_boyut' => 19,
                'vurgu_renk'   => '#B8962E',
            ],
        ];
        $kapakBirlestir = array_merge($kapakVarsayilan, array_filter($kapakBase, fn($v) => $v !== null && $v !== ''));
        if (!isset($kapakBirlestir['ekstralar']) || !is_array($kapakBirlestir['ekstralar'])) {
            $kapakBirlestir['ekstralar'] = [];
        }
        if (empty($kapakBirlestir['logo'])) $kapakBirlestir['logo'] = '';

        return view('katalog.index', compact(
            'kataloglar', 'duzenlenenKatalog', 'duzenlenenUrunler', 'kapakBirlestir', 'icindekiler'
        ));
    }

    public function urunler(Request $request)
    {
        try {
            $q = Urun::where('aktif', true);
            if ($request->filled('ara')) $q->where('ad', 'like', '%' . $request->ara . '%');
            if ($request->filled('kategori')) $q->where('kategori', $request->kategori);

            $adminUrunler = $q->orderBy('ad')->get()->map(fn($u) => $this->urunDizi($u))->values();

            $uq = UserUrun::where('user_id', auth()->id());
            if ($request->filled('ara')) $uq->where('ad', 'like', '%' . $request->ara . '%');
            $userUrunler = $uq->orderBy('ad')->get()->map(fn($u) => $this->userUrunDizi($u))->values();

            $hepsi = $adminUrunler->concat($userUrunler)->values();
            return response()->json($hepsi);
        } catch (\Throwable $e) {
            return response()->json(['hata' => $e->getMessage()], 500);
        }
    }

    public function kaydet(Request $request)
    {
        $request->validate([
            'baslik'     => 'required|string|max:255',
            'urun_idler' => 'required|array|min:1',
        ]);

        $kapakData = (array) ($request->kapak ?? []);
        $kapakData['icindekiler'] = (array) ($request->icindekiler ?? []);

        $data = [
            'baslik'         => $request->baslik,
            'alt_baslik'     => $request->alt_baslik,
            'kapak_ayarlari' => $kapakData,
            'urun_idler'     => $request->urun_idler,
            'user_id'        => auth()->id(),
        ];

        if ($request->filled('id')) {
            $katalog = Katalog::where('user_id', auth()->id())->findOrFail($request->id);
            $katalog->update($data);
        } else {
            $katalog = Katalog::create($data);
        }

        return response()->json(['success' => true, 'id' => $katalog->id]);
    }

    public function yazdir(int $id)
    {
        $katalog     = Katalog::where('user_id', auth()->id())->findOrFail($id);
        $kapakBase   = $katalog->kapak_ayarlari ?? [];
        $icindekiler = $kapakBase['icindekiler'] ?? [];
        $urunler     = $this->urunleriYukle($katalog->urun_idler ?? [], auth()->id());

        return view('katalog.yazdir', compact('katalog', 'urunler', 'icindekiler'));
    }

    public function sil(int $id)
    {
        Katalog::where('user_id', auth()->id())->findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('basari', 'Katalog silindi.');
    }

    public function logoYukle(Request $request)
    {
        $request->validate(['logo' => 'required|image|max:3072']);
        $path = $request->file('logo')->store('katalog-logolar', 'public');
        return response()->json(['url' => asset('storage/' . $path)]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function urunleriYukle(array $urunIdler, int $userId): \Illuminate\Support\Collection
    {
        $adminIds = array_values(array_filter($urunIdler, fn($id) => is_numeric($id)));
        $userRaw  = array_filter($urunIdler, fn($id) => is_string($id) && str_starts_with((string)$id, 'u'));
        $userIds  = array_values(array_map(fn($id) => (int) substr($id, 1), $userRaw));

        $adminMap = Urun::whereIn('id', $adminIds)->get()->keyBy('id');
        $userMap  = UserUrun::where('user_id', $userId)->whereIn('id', $userIds)->get()->keyBy('id');

        return collect($urunIdler)->map(function ($id) use ($adminMap, $userMap) {
            if (is_string($id) && str_starts_with((string)$id, 'u')) {
                return $userMap[(int) substr($id, 1)] ?? null;
            }
            return $adminMap[$id] ?? null;
        })->filter()->values();
    }

    private function urunDizi(Urun $u): array
    {
        return [
            'id'       => $u->id,
            'ad'       => $u->ad,
            'kategori' => $u->kategori,
            'gorsel'   => $u->gorsel ? asset('storage/' . $u->gorsel) : null,
            'aciklama' => $u->aciklama ?? '',
            'tip'      => 'admin',
        ];
    }

    private function userUrunDizi(UserUrun $u): array
    {
        return [
            'id'       => 'u' . $u->id,
            'ad'       => $u->ad,
            'kategori' => 'Kişisel',
            'gorsel'   => $u->gorsel ? asset('storage/' . $u->gorsel) : null,
            'aciklama' => $u->aciklama ?? '',
            'tip'      => 'kullanici',
        ];
    }
}
