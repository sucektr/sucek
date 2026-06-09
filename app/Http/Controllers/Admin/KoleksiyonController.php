<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KoleksiyonController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q        = $request->input('q');
        $kategori = $request->input('kategori');
        $durum    = $request->input('durum');

        $koleksiyonlar = \App\Models\Koleksiyon::latest()
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('ad', 'like', "%{$q}%")->orWhere('stok_kodu', 'like', "%{$q}%");
            }))
            ->when($kategori, fn($query) => $query->where('kategori', $kategori))
            ->when($durum, fn($query) => $query->where('durum', $durum))
            ->paginate(20)->withQueryString();

        return view('admin.koleksiyonlar.index', compact('koleksiyonlar'));
    }

    public function create()
    {
        return view('admin.koleksiyonlar.form', ['koleksiyon' => new \App\Models\Koleksiyon]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad'           => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:koleksiyonlar,slug',
            'kategori'     => 'required|string|max:100',
            'fiyat'        => 'nullable|numeric|min:0',
            'aciklama'     => 'nullable|string',
            'stok_kodu'    => 'nullable|string|max:50',
            'durum'          => 'required|in:satista,satildi,rezerve',
            'kargo_bedeli'   => 'nullable|numeric|min:0',
            'kargo_kim_oder' => 'nullable|in:magaza,satici,musteri',
            'gorsel'          => 'nullable|image|max:4096',
            'gorseller.*'     => 'nullable|image|max:4096',
            'dosyalar_yeni.*' => 'nullable|file|max:10240',
            'aktif'           => 'boolean',
            'one_cikan'       => 'boolean',
        ]);

        $data = $request->only(['ad', 'slug', 'kategori', 'fiyat', 'aciklama', 'stok_kodu', 'durum']);
        $data['kargo_bedeli']   = $request->input('kargo_bedeli', 0);
        $data['kargo_kim_oder'] = $request->input('kargo_kim_oder', 'magaza');

        if ($request->hasFile('gorsel')) {
            $data['gorsel'] = $request->file('gorsel')->store('koleksiyonlar', 'public');
        }

        $ekGorseller = [];
        if ($request->hasFile('gorseller')) {
            foreach ($request->file('gorseller') as $file) {
                $ekGorseller[] = $file->store('koleksiyonlar', 'public');
            }
        }
        $data['gorseller'] = !empty($ekGorseller) ? $ekGorseller : null;

        $dosyalar = [];
        if ($request->hasFile('dosyalar_yeni')) {
            foreach ($request->file('dosyalar_yeni') as $file) {
                $dosyalar[] = [
                    'ad'    => $file->getClientOriginalName(),
                    'dosya' => $file->store('dosyalar/koleksiyonlar', 'public'),
                ];
            }
        }
        $data['dosyalar'] = !empty($dosyalar) ? $dosyalar : null;

        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        \App\Models\Koleksiyon::create($data);
        return redirect()->route('admin.koleksiyonlar.index')->with('basari', 'Koleksiyon eklendi.');
    }

    public function show(string $id) { abort(404); }

    public function edit(\App\Models\Koleksiyon $koleksiyon)
    {
        return view('admin.koleksiyonlar.form', compact('koleksiyon'));
    }

    public function update(Request $request, \App\Models\Koleksiyon $koleksiyon)
    {
        $request->validate([
            'ad'           => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:koleksiyonlar,slug,'.$koleksiyon->id,
            'kategori'     => 'required|string|max:100',
            'fiyat'        => 'nullable|numeric|min:0',
            'aciklama'     => 'nullable|string',
            'stok_kodu'    => 'nullable|string|max:50',
            'durum'          => 'required|in:satista,satildi,rezerve',
            'kargo_bedeli'   => 'nullable|numeric|min:0',
            'kargo_kim_oder' => 'nullable|in:magaza,satici,musteri',
            'gorsel'          => 'nullable|image|max:4096',
            'gorseller.*'     => 'nullable|image|max:4096',
            'gorsel_sil'      => 'nullable|array',
            'gorsel_sil.*'    => 'nullable|string',
            'dosyalar_yeni.*' => 'nullable|file|max:10240',
            'dosya_sil'       => 'nullable|array',
            'dosya_sil.*'     => 'nullable|string',
            'aktif'           => 'boolean',
            'one_cikan'       => 'boolean',
        ]);

        $data = $request->only(['ad', 'slug', 'kategori', 'fiyat', 'aciklama', 'stok_kodu', 'durum']);
        $data['kargo_bedeli']   = $request->input('kargo_bedeli', 0);
        $data['kargo_kim_oder'] = $request->input('kargo_kim_oder', 'magaza');

        if ($request->hasFile('gorsel')) {
            if ($koleksiyon->gorsel) \Illuminate\Support\Facades\Storage::disk('public')->delete($koleksiyon->gorsel);
            $data['gorsel'] = $request->file('gorsel')->store('koleksiyonlar', 'public');
        }

        $mevcutGorseller = $koleksiyon->gorseller ?? [];
        if ($request->filled('gorsel_sil')) {
            foreach ($request->input('gorsel_sil') as $silinecek) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($silinecek);
                $mevcutGorseller = array_values(array_filter($mevcutGorseller, fn($g) => $g !== $silinecek));
            }
        }
        if ($request->hasFile('gorseller')) {
            foreach ($request->file('gorseller') as $file) {
                $mevcutGorseller[] = $file->store('koleksiyonlar', 'public');
            }
        }
        $data['gorseller'] = !empty($mevcutGorseller) ? $mevcutGorseller : null;

        $mevcutDosyalar = $koleksiyon->dosyalar ?? [];
        if ($request->filled('dosya_sil')) {
            foreach ($request->input('dosya_sil') as $silinecek) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($silinecek);
                $mevcutDosyalar = array_values(array_filter($mevcutDosyalar, fn($d) => $d['dosya'] !== $silinecek));
            }
        }
        if ($request->hasFile('dosyalar_yeni')) {
            foreach ($request->file('dosyalar_yeni') as $file) {
                $mevcutDosyalar[] = [
                    'ad'    => $file->getClientOriginalName(),
                    'dosya' => $file->store('dosyalar/koleksiyonlar', 'public'),
                ];
            }
        }
        $data['dosyalar'] = !empty($mevcutDosyalar) ? $mevcutDosyalar : null;

        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        $koleksiyon->update($data);
        return redirect()->route('admin.koleksiyonlar.index')->with('basari', 'Koleksiyon güncellendi.');
    }

    public function destroy(\App\Models\Koleksiyon $koleksiyon)
    {
        if ($koleksiyon->gorsel) \Illuminate\Support\Facades\Storage::disk('public')->delete($koleksiyon->gorsel);
        foreach ($koleksiyon->gorseller ?? [] as $g) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($g);
        }
        foreach ($koleksiyon->dosyalar ?? [] as $d) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($d['dosya']);
        }
        $koleksiyon->delete();
        return back()->with('basari', 'Koleksiyon silindi.');
    }
}
