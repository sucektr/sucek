<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjeController extends Controller
{
    public function index()
    {
        $projeler = Proje::latest()->paginate(20);
        return view('admin.projeler.index', compact('projeler'));
    }

    public function create()
    {
        return view('admin.projeler.form', ['proje' => new Proje]);
    }

    private function postMaxSizeAsildi(): bool
    {
        $contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
        if ($contentLength <= 0) return false;
        $postMaxSize = $this->iniDegerBayt(ini_get('post_max_size'));
        return $contentLength > $postMaxSize;
    }

    private function iniDegerBayt(string $deger): int
    {
        $son = strtolower(substr(trim($deger), -1));
        $sayi = (int) $deger;
        return match ($son) {
            'g' => $sayi * 1024 * 1024 * 1024,
            'm' => $sayi * 1024 * 1024,
            'k' => $sayi * 1024,
            default => $sayi,
        };
    }

    private function uploadHataKontrol(Request $request): ?string
    {
        if ($this->postMaxSizeAsildi()) {
            $limit = ini_get('post_max_size');
            return "Yüklenen dosyalar toplamda sunucunun limitini ({$limit}) aşıyor. "
                 . "Lütfen görselleri küçültün (squoosh.app veya tinypng.com) veya cPanel → MultiPHP INI Editor'dan post_max_size değerini artırın.";
        }
        $file = $request->file('kapak_gorsel');
        if ($file && $file->getError() !== UPLOAD_ERR_OK) {
            $limit = ini_get('upload_max_filesize');
            $map = [
                UPLOAD_ERR_INI_SIZE   => "Kapak görseli sunucunun PHP limitini ({$limit}) aşıyor.",
                UPLOAD_ERR_FORM_SIZE  => 'Kapak görseli form limitini aşıyor.',
                UPLOAD_ERR_PARTIAL    => 'Kapak görseli yalnızca kısmen yüklendi. Tekrar deneyin.',
                UPLOAD_ERR_NO_TMP_DIR => 'Geçici dizin bulunamadı (sunucu yapılandırma hatası).',
                UPLOAD_ERR_CANT_WRITE => 'Dosya diske yazılamadı (izin hatası).',
            ];
            return $map[$file->getError()] ?? "Yükleme hatası (kod: {$file->getError()}).";
        }
        return null;
    }

    public function store(Request $request)
    {
        if ($hata = $this->uploadHataKontrol($request)) {
            return redirect()->route('admin.projeler.create')
                ->withInput()->withErrors(['kapak_gorsel' => $hata]);
        }

        $data = $request->validate([
            'baslik'       => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:projeler,slug',
            'kategori'     => 'required|string|max:100',
            'alt_kategori' => 'nullable|string|max:100',
            'konum'        => 'nullable|string|max:150',
            'yil'          => 'nullable|integer|min:1900|max:2100',
            'kapak_gorsel' => 'nullable|image|max:8192',
            'detaylar'     => 'nullable|string',
            'sira'         => 'nullable|integer|min:0',
            'aktif'        => 'boolean',
            'one_cikan'    => 'boolean',
        ]);

        if ($request->hasFile('kapak_gorsel')) {
            $data['kapak_gorsel'] = $request->file('kapak_gorsel')->store('projeler', 'public');
        }

        $gorseller = [];
        foreach ((array) $request->file('yeni_gorseller', []) as $gorsel) {
            if ($gorsel && $gorsel->isValid()) {
                $gorseller[] = $gorsel->store('projeler', 'public');
            }
        }
        $data['gorseller'] = $gorseller;

        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        Proje::create($data);
        return redirect()->route('admin.projeler.index')->with('basari', 'Proje eklendi.');
    }

    public function show(string $id) { abort(404); }

    public function edit(Proje $proje)
    {
        return view('admin.projeler.form', compact('proje'));
    }

    public function update(Request $request, Proje $proje)
    {
        if ($hata = $this->uploadHataKontrol($request)) {
            return redirect()->route('admin.projeler.edit', $proje->id)
                ->withInput()->withErrors(['kapak_gorsel' => $hata]);
        }

        $request->validate([
            'baslik'       => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:projeler,slug,'.$proje->id,
            'kategori'     => 'required|string|max:100',
            'alt_kategori' => 'nullable|string|max:100',
            'konum'        => 'nullable|string|max:150',
            'yil'          => 'nullable|integer|min:1900|max:2100',
            'kapak_gorsel' => 'nullable|image|max:8192',
            'detaylar'     => 'nullable|string',
            'sira'         => 'nullable|integer|min:0',
            'aktif'        => 'boolean',
            'one_cikan'    => 'boolean',
        ]);

        $data = $request->only(['baslik', 'slug', 'kategori', 'alt_kategori', 'konum', 'yil', 'detaylar', 'sira']);

        if ($request->hasFile('kapak_gorsel')) {
            if ($proje->kapak_gorsel) Storage::disk('public')->delete($proje->kapak_gorsel);
            $data['kapak_gorsel'] = $request->file('kapak_gorsel')->store('projeler', 'public');
        }

        // Mevcut görseller: formdan gelen liste (silinmeyenler)
        $mevcutGorseller = array_filter((array) $request->input('mevcut_gorseller', []));

        // Silinen görselleri storage'dan kaldır
        foreach (array_diff($proje->gorseller ?: [], $mevcutGorseller) as $silinen) {
            Storage::disk('public')->delete($silinen);
        }

        // Yeni görselleri yükle
        $yeniGorseller = [];
        foreach ((array) $request->file('yeni_gorseller', []) as $gorsel) {
            if ($gorsel && $gorsel->isValid()) {
                $yeniGorseller[] = $gorsel->store('projeler', 'public');
            }
        }

        $data['gorseller'] = array_values(array_merge($mevcutGorseller, $yeniGorseller));
        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        $proje->update($data);
        return redirect()->route('admin.projeler.index')->with('basari', 'Proje güncellendi.');
    }

    public function destroy(Proje $proje)
    {
        if ($proje->kapak_gorsel) Storage::disk('public')->delete($proje->kapak_gorsel);
        foreach ($proje->gorseller ?: [] as $gorsel) {
            Storage::disk('public')->delete($gorsel);
        }
        $proje->delete();
        return back()->with('basari', 'Proje silindi.');
    }
}
