<?php

namespace App\Http\Controllers\Yardimci;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrunEkleController extends Controller
{
    public function create()
    {
        return view('yardimci.urun.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad'        => 'required|string|max:200',
            'kategori'  => 'required|in:spor,insaat,diger,celik-guvenlik-agi',
            'fiyat'     => 'required|numeric|min:0',
            'stok'      => 'required|integer|min:0',
            'aciklama'  => 'nullable|string',
            'stok_kodu' => 'nullable|string|max:50',
            'gorsel'      => 'required|image|max:8192',
            'gorseller.*' => 'nullable|image|max:8192',
        ]);

        $slug = $this->benzersizSlug($request->input('ad'));

        $data = $request->only(['ad', 'kategori', 'fiyat', 'stok', 'aciklama', 'stok_kodu']);
        $data['slug']                  = $slug;
        $data['aktif']                 = false;
        $data['onay_durumu']           = 'bekliyor';
        $data['eklenen_kullanici_id']  = auth()->id();
        $data['kdv_orani']             = 20;
        $data['kdv_dahil']             = true;
        $data['kargo_bedeli']          = 0;
        $data['kargo_kim_oder']        = 'magaza';

        $data['gorsel'] = $request->file('gorsel')->store('urunler', 'public');

        $ekGorseller = [];
        if ($request->hasFile('gorseller')) {
            foreach ($request->file('gorseller') as $file) {
                $ekGorseller[] = $file->store('urunler', 'public');
            }
        }
        $data['gorseller'] = !empty($ekGorseller) ? $ekGorseller : null;

        Urun::create($data);

        return redirect()->route('yardimci.index')->with('basari', 'Ürün eklendi, onay için gönderildi.');
    }

    private function benzersizSlug(string $ad): string
    {
        $taban = Str::slug($ad);
        $slug  = $taban;
        $i     = 2;
        while (Urun::where('slug', $slug)->exists()) {
            $slug = $taban . '-' . $i++;
        }
        return $slug;
    }
}
