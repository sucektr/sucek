<?php

namespace App\Http\Controllers\Yardimci;

use App\Http\Controllers\Controller;
use App\Models\Koleksiyon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KoleksiyonEkleController extends Controller
{
    public function create()
    {
        return view('yardimci.koleksiyon.form', ['koleksiyon' => new Koleksiyon]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ad'        => 'required|string|max:200',
            'kategori'  => 'required|in:saat,numizmatik,antika,diger',
            'ulke'      => 'nullable|string|max:100',
            'fiyat'     => 'nullable|numeric|min:0',
            'aciklama'  => 'nullable|string',
            'stok_kodu' => 'nullable|string|max:50',
            'gorsel'      => 'required|image|max:8192',
            'gorseller.*' => 'nullable|image|max:8192',
        ]);

        $slug = $this->benzersizSlug($request->input('ad'));

        $data = $request->only(['ad', 'kategori', 'ulke', 'fiyat', 'aciklama', 'stok_kodu']);
        $data['slug']                 = $slug;
        $data['durum']                = 'satista';
        $data['aktif']                = false;
        $data['onay_durumu']          = 'bekliyor';
        $data['eklenen_kullanici_id'] = auth()->id();
        $data['kargo_bedeli']         = 0;
        $data['kargo_kim_oder']       = 'magaza';

        $data['gorsel'] = $request->file('gorsel')->store('koleksiyonlar', 'public');

        $ekGorseller = [];
        if ($request->hasFile('gorseller')) {
            foreach ($request->file('gorseller') as $file) {
                $ekGorseller[] = $file->store('koleksiyonlar', 'public');
            }
        }
        $data['gorseller'] = !empty($ekGorseller) ? $ekGorseller : null;

        Koleksiyon::create($data);

        return redirect()->route('yardimci.index')->with('basari', 'Koleksiyon ürünü eklendi, onay için gönderildi.');
    }

    public function edit(Koleksiyon $koleksiyon)
    {
        $this->sahipMi($koleksiyon);
        return view('yardimci.koleksiyon.form', compact('koleksiyon'));
    }

    public function update(Request $request, Koleksiyon $koleksiyon)
    {
        $this->sahipMi($koleksiyon);

        $request->validate([
            'ad'        => 'required|string|max:200',
            'kategori'  => 'required|in:saat,numizmatik,antika,diger',
            'ulke'      => 'nullable|string|max:100',
            'fiyat'     => 'nullable|numeric|min:0',
            'aciklama'  => 'nullable|string',
            'stok_kodu' => 'nullable|string|max:50',
            'gorsel'      => 'nullable|image|max:8192',
            'gorseller.*' => 'nullable|image|max:8192',
        ]);

        $data = $request->only(['ad', 'kategori', 'ulke', 'fiyat', 'aciklama', 'stok_kodu']);

        if ($koleksiyon->onay_durumu === 'reddedildi') {
            $data['onay_durumu'] = 'bekliyor';
        }

        if ($request->hasFile('gorsel')) {
            if ($koleksiyon->gorsel) Storage::disk('public')->delete($koleksiyon->gorsel);
            $data['gorsel'] = $request->file('gorsel')->store('koleksiyonlar', 'public');
        }

        $mevcutGorseller = $koleksiyon->gorseller ?? [];
        if ($request->hasFile('gorseller')) {
            foreach ($request->file('gorseller') as $file) {
                $mevcutGorseller[] = $file->store('koleksiyonlar', 'public');
            }
        }
        $data['gorseller'] = !empty($mevcutGorseller) ? $mevcutGorseller : null;

        $koleksiyon->update($data);

        return redirect()->route('yardimci.index')->with('basari', 'Koleksiyon ürünü güncellendi, onay için gönderildi.');
    }

    public function destroy(Koleksiyon $koleksiyon)
    {
        $this->sahipMi($koleksiyon);

        if ($koleksiyon->gorsel) Storage::disk('public')->delete($koleksiyon->gorsel);
        foreach ($koleksiyon->gorseller ?? [] as $g) {
            Storage::disk('public')->delete($g);
        }
        $koleksiyon->delete();

        return redirect()->route('yardimci.index')->with('basari', 'Koleksiyon ürünü silindi.');
    }

    private function sahipMi(Koleksiyon $koleksiyon): void
    {
        abort_unless($koleksiyon->eklenen_kullanici_id === auth()->id(), 403);
    }

    private function benzersizSlug(string $ad): string
    {
        $taban = Str::slug($ad);
        $slug  = $taban;
        $i     = 2;
        while (Koleksiyon::where('slug', $slug)->exists()) {
            $slug = $taban . '-' . $i++;
        }
        return $slug;
    }
}
