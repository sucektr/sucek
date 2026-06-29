<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UrunController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q        = $request->input('q');
        $kategori = $request->input('kategori');
        $durum    = $request->input('durum');

        $urunler = \App\Models\Urun::latest()
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('ad', 'like', "%{$q}%")->orWhere('stok_kodu', 'like', "%{$q}%");
            }))
            ->when($kategori, fn($query) => $query->where('kategori', $kategori))
            ->when($durum === 'aktif', fn($query) => $query->where('aktif', true))
            ->when($durum === 'pasif', fn($query) => $query->where('aktif', false))
            ->paginate(20)->withQueryString();

        return view('admin.urunler.index', compact('urunler'));
    }

    public function create()
    {
        return view('admin.urunler.form', ['urun' => new \App\Models\Urun]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad'           => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:urunler,slug',
            'fiyat'        => 'required|numeric|min:0',
            'eski_fiyat'   => 'nullable|numeric|min:0',
            'kategori'     => 'required|string|max:100',
            'alt_kategori' => 'nullable|string|max:100',
            'aciklama'     => 'nullable|string',
            'stok_kodu'    => 'nullable|string|max:50',
            'stok'         => 'nullable|integer|min:0',
            'kdv_orani'      => 'nullable|numeric|in:0,1,10,18,20',
            'kargo_bedeli'   => 'nullable|numeric|min:0',
            'kargo_kim_oder' => 'nullable|in:magaza,satici,musteri',
            'gorsel'          => 'nullable|image|max:4096',
            'gorseller.*'     => 'nullable|image|max:4096',
            'dosyalar_yeni.*' => 'nullable|file|max:10240',
            'aktif'           => 'boolean',
            'one_cikan'       => 'boolean',
            'kdv_dahil'       => 'boolean',
        ]);

        $data = $request->only(['ad','slug','fiyat','eski_fiyat','kategori','alt_kategori','aciklama','stok_kodu','stok']);
        $data['kdv_orani']      = $request->input('kdv_orani', 20);
        $data['kdv_dahil']      = $request->boolean('kdv_dahil');
        $data['kargo_bedeli']   = $request->input('kargo_bedeli', 0);
        $data['kargo_kim_oder'] = $request->input('kargo_kim_oder', 'magaza');
        $data['ozellikler']     = $this->ozelliklerParse($request->input('ozellikler_json', '[]'));

        if ($request->hasFile('gorsel')) {
            $data['gorsel'] = $request->file('gorsel')->store('urunler', 'public');
        }

        $ekGorseller = [];
        if ($request->hasFile('gorseller')) {
            foreach ($request->file('gorseller') as $file) {
                $ekGorseller[] = $file->store('urunler', 'public');
            }
        }
        $data['gorseller'] = !empty($ekGorseller) ? $ekGorseller : null;

        $dosyalar = [];
        if ($request->hasFile('dosyalar_yeni')) {
            foreach ($request->file('dosyalar_yeni') as $file) {
                $dosyalar[] = [
                    'ad'    => $file->getClientOriginalName(),
                    'dosya' => $file->store('dosyalar/urunler', 'public'),
                ];
            }
        }
        $data['dosyalar'] = !empty($dosyalar) ? $dosyalar : null;

        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        \App\Models\Urun::create($data);
        return redirect()->route('admin.urunler.index')->with('basari', 'Ürün eklendi.');
    }

    public function show(string $id) { abort(404); }

    public function edit(\App\Models\Urun $urun)
    {
        return view('admin.urunler.form', compact('urun'));
    }

    public function update(Request $request, \App\Models\Urun $urun)
    {
        $request->validate([
            'ad'           => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:urunler,slug,'.$urun->id,
            'fiyat'        => 'required|numeric|min:0',
            'eski_fiyat'   => 'nullable|numeric|min:0',
            'kategori'     => 'required|string|max:100',
            'alt_kategori' => 'nullable|string|max:100',
            'aciklama'     => 'nullable|string',
            'stok_kodu'    => 'nullable|string|max:50',
            'stok'         => 'nullable|integer|min:0',
            'kdv_orani'      => 'nullable|numeric|in:0,1,10,18,20',
            'kargo_bedeli'   => 'nullable|numeric|min:0',
            'kargo_kim_oder' => 'nullable|in:magaza,satici,musteri',
            'gorsel'       => 'nullable|image|max:4096',
            'gorseller.*'     => 'nullable|image|max:4096',
            'gorsel_sil'      => 'nullable|array',
            'gorsel_sil.*'    => 'nullable|string',
            'dosyalar_yeni.*' => 'nullable|file|max:10240',
            'dosya_sil'       => 'nullable|array',
            'dosya_sil.*'     => 'nullable|string',
            'aktif'           => 'boolean',
            'one_cikan'       => 'boolean',
            'kdv_dahil'       => 'boolean',
        ]);

        $data = $request->only(['ad','slug','fiyat','eski_fiyat','kategori','alt_kategori','aciklama','stok_kodu','stok']);
        $data['kdv_orani']      = $request->input('kdv_orani', 20);
        $data['kdv_dahil']      = $request->boolean('kdv_dahil');
        $data['kargo_bedeli']   = $request->input('kargo_bedeli', 0);
        $data['kargo_kim_oder'] = $request->input('kargo_kim_oder', 'magaza');
        $data['ozellikler']     = $this->ozelliklerParse($request->input('ozellikler_json', '[]'));

        if ($request->hasFile('gorsel')) {
            if ($urun->gorsel) \Illuminate\Support\Facades\Storage::disk('public')->delete($urun->gorsel);
            $data['gorsel'] = $request->file('gorsel')->store('urunler', 'public');
        }

        $mevcutGorseller = $urun->gorseller ?? [];
        if ($request->filled('gorsel_sil')) {
            foreach ($request->input('gorsel_sil') as $silinecek) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($silinecek);
                $mevcutGorseller = array_values(array_filter($mevcutGorseller, fn($g) => $g !== $silinecek));
            }
        }
        if ($request->hasFile('gorseller')) {
            foreach ($request->file('gorseller') as $file) {
                $mevcutGorseller[] = $file->store('urunler', 'public');
            }
        }
        $data['gorseller'] = !empty($mevcutGorseller) ? $mevcutGorseller : null;

        // Dosyalar
        $mevcutDosyalar = $urun->dosyalar ?? [];
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
                    'dosya' => $file->store('dosyalar/urunler', 'public'),
                ];
            }
        }
        $data['dosyalar'] = !empty($mevcutDosyalar) ? $mevcutDosyalar : null;

        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        $urun->update($data);
        return redirect()->route('admin.urunler.index')->with('basari', 'Ürün güncellendi.');
    }

    public function destroy(\App\Models\Urun $urun)
    {
        if ($urun->gorsel) \Illuminate\Support\Facades\Storage::disk('public')->delete($urun->gorsel);
        foreach ($urun->gorseller ?? [] as $g) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($g);
        }
        foreach ($urun->dosyalar ?? [] as $d) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($d['dosya']);
        }
        $urun->delete();
        return back()->with('basari', 'Ürün silindi.');
    }

    private function ozelliklerParse(string $json): ?array
    {
        try {
            $parsed = json_decode($json, true);
        } catch (\Throwable $e) {
            return null;
        }
        if (!is_array($parsed)) return null;
        $temiz = [];
        foreach ($parsed as $item) {
            $ad = trim($item['ad'] ?? '');
            $degerler = array_values(array_filter(array_map('trim', $item['degerler'] ?? []), fn($d) => $d !== ''));
            if ($ad !== '' && count($degerler) > 0) {
                $temiz[] = ['ad' => $ad, 'degerler' => $degerler];
            }
        }
        return count($temiz) > 0 ? $temiz : null;
    }
}
