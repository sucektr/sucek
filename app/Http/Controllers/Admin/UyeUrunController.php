<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserUrun;
use App\Models\Urun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UyeUrunController extends Controller
{
    public function index()
    {
        $userUrunler = UserUrun::with('user')->latest()->paginate(20);
        return view('admin.uye-urunler.index', compact('userUrunler'));
    }

    public function donustur($id)
    {
        $kaynak = UserUrun::with('user')->findOrFail($id);
        $urun   = new Urun(['ad' => $kaynak->ad, 'aciklama' => $kaynak->aciklama]);
        return view('admin.uye-urunler.donustur', compact('kaynak', 'urun'));
    }

    public function kaydet(Request $request, $id)
    {
        $kaynak = UserUrun::findOrFail($id);

        $request->validate([
            'ad'             => 'required|string|max:200',
            'slug'           => 'required|string|max:200|unique:urunler,slug',
            'fiyat'          => 'required|numeric|min:0',
            'eski_fiyat'     => 'nullable|numeric|min:0',
            'kategori'       => 'required|string|max:100',
            'alt_kategori'   => 'nullable|string|max:100',
            'aciklama'       => 'nullable|string',
            'stok_kodu'      => 'nullable|string|max:50',
            'stok'           => 'nullable|integer|min:0',
            'kdv_orani'      => 'nullable|numeric|in:0,1,10,18,20',
            'kargo_bedeli'   => 'nullable|numeric|min:0',
            'kargo_kim_oder' => 'nullable|in:magaza,satici,musteri',
            'gorsel'         => 'nullable|image|max:4096',
            'aktif'          => 'boolean',
            'one_cikan'      => 'boolean',
            'kdv_dahil'      => 'boolean',
        ]);

        $data = $request->only(['ad', 'slug', 'fiyat', 'eski_fiyat', 'kategori', 'alt_kategori', 'aciklama', 'stok_kodu', 'stok']);
        $data['kdv_orani']      = $request->input('kdv_orani', 20);
        $data['kdv_dahil']      = $request->boolean('kdv_dahil');
        $data['kargo_bedeli']   = $request->input('kargo_bedeli', 0);
        $data['kargo_kim_oder'] = $request->input('kargo_kim_oder', 'magaza');
        $data['aktif']          = $request->boolean('aktif');
        $data['one_cikan']      = $request->boolean('one_cikan');

        if ($request->hasFile('gorsel')) {
            $data['gorsel'] = $request->file('gorsel')->store('urunler', 'public');
        } elseif ($request->boolean('kaynak_gorsel_kullan') && $kaynak->gorsel) {
            $ext    = pathinfo($kaynak->gorsel, PATHINFO_EXTENSION);
            $hedef  = 'urunler/' . Str::uuid() . '.' . $ext;
            if (Storage::disk('public')->exists($kaynak->gorsel)) {
                Storage::disk('public')->copy($kaynak->gorsel, $hedef);
                $data['gorsel'] = $hedef;
            }
        }

        Urun::create($data);

        return redirect()->route('admin.urunler.index')
            ->with('basari', '"' . $data['ad'] . '" mağaza ürünlerine eklendi.');
    }
}
