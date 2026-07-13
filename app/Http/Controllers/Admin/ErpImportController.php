<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;

class ErpImportController extends Controller
{
    // ERP Excel sütun indeksleri (0 tabanlı)
    // A=0 Marka | B=1 Stok Kodu | C=2 Açıklama | D=3 Alt Stok | E=4 Renk | F=5 ANAGRUP | G=6 Miktar

    const MAGAZA_KATEGORILER = ['spor', 'dekorasyon', 'insaat', 'diger'];
    const HARITA_DOSYA = 'erp_kategori_haritasi.json';

    public function index()
    {
        $toplamUrun = Urun::whereNotNull('stok_kodu')->count();

        // DB'deki tüm ERP kaynaklı kategoriler (ham ERP değeri)
        $erpKategoriler = Urun::whereNotNull('stok_kodu')
            ->whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values();

        $harita = $this->haritaYukle();

        return view('admin.erp-import.index', compact('toplamUrun', 'erpKategoriler', 'harita'));
    }

    public function haritaKaydet(Request $request)
    {
        $gelen  = (array) $request->input('harita', []);
        $temiz  = [];
        foreach ($gelen as $erpKat => $magazaKat) {
            $erpKat    = trim((string) $erpKat);
            $magazaKat = trim((string) $magazaKat);
            if ($erpKat !== '' && in_array($magazaKat, self::MAGAZA_KATEGORILER)) {
                $temiz[$erpKat] = $magazaKat;
            }
        }

        file_put_contents(storage_path('app/' . self::HARITA_DOSYA), json_encode($temiz, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return back()->with('harita_kaydedildi', true);
    }

    public function haritaUygula()
    {
        $harita = $this->haritaYukle();
        if (empty($harita)) {
            return back()->with('harita_uygula_sonuc', ['tip' => 'warning', 'mesaj' => 'Önce eşleştirme kaydedin.']);
        }

        $guncellenen = 0;
        foreach ($harita as $erpKat => $magazaKat) {
            $guncellenen += Urun::whereNotNull('stok_kodu')
                ->where('kategori', $erpKat)
                ->update(['kategori' => $magazaKat]);
        }

        return back()->with('harita_uygula_sonuc', [
            'tip'   => 'success',
            'mesaj' => "{$guncellenen} ürünün kategorisi güncellendi.",
        ]);
    }

    public function yukle(Request $request)
    {
        $request->validate([
            'dosya' => 'required|file|mimes:xlsx,xls|max:20480',
        ], [
            'dosya.required' => 'Dosya seçilmedi.',
            'dosya.mimes'    => 'Yalnızca .xlsx veya .xls dosyası yüklenebilir.',
            'dosya.max'      => 'Dosya 20 MB\'dan büyük olamaz.',
        ]);

        try {
            $path = $request->file('dosya')->getRealPath();
            $ext  = strtolower($request->file('dosya')->getClientOriginalExtension());
            $reader = $ext === 'xls' ? new XlsReader() : new XlsxReader();
            $reader->setReadDataOnly(true);
            $rows = $reader->load($path)->getActiveSheet()->toArray();
        } catch (\Throwable $e) {
            return back()->withErrors(['dosya' => 'Dosya okunamadı: ' . $e->getMessage()]);
        }

        array_shift($rows); // başlık satırını atla

        $harita        = $this->haritaYukle();
        $yeniler       = 0;
        $guncellenenler = 0;
        $atlananlar    = 0;
        $hatalar       = [];

        foreach ($rows as $idx => $row) {
            $satirNo = $idx + 2;

            if (empty(array_filter(array_map('strval', $row), fn($v) => trim($v) !== ''))) {
                $atlananlar++;
                continue;
            }

            $marka    = trim((string)($row[0] ?? ''));
            $stokKodu = trim((string)($row[1] ?? ''));
            $ad       = trim((string)($row[2] ?? ''));
            $altKod   = trim((string)($row[3] ?? ''));
            $renk     = trim((string)($row[4] ?? ''));
            $anaGrup  = trim((string)($row[5] ?? ''));
            $miktar   = isset($row[6]) && trim((string)$row[6]) !== '' ? (int)$row[6] : 0;

            if ($stokKodu === '') { $hatalar[] = "Satır {$satirNo}: Stok kodu boş, atlandı."; continue; }
            if ($ad === '')       { $hatalar[] = "Satır {$satirNo}: Ürün adı boş (Stok: {$stokKodu}), atlandı."; continue; }

            $mevcut = Urun::where('stok_kodu', $stokKodu)->first();

            if ($mevcut) {
                // Sadece stok güncellenir — manuel girilen tüm bilgiler korunur
                $mevcut->update(['stok' => $miktar]);
                $guncellenenler++;
            } else {
                $ozellikler = [];
                if ($altKod !== '') $ozellikler[] = ['ad' => 'Alt Stok Kodu', 'degerler' => [$altKod]];
                if ($renk !== '')   $ozellikler[] = ['ad' => 'Renk', 'degerler' => [$renk]];

                // Kategori eşleştirme: haritada varsa kullan, yoksa ham ERP değerini sakla
                $kategori = $harita[$anaGrup] ?? ($anaGrup ?: 'diger');

                $slug = $this->benzersizSlug($this->slugify($stokKodu));

                Urun::create([
                    'ad'             => $ad,
                    'slug'           => $slug,
                    'marka'          => $marka ?: null,
                    'kategori'       => $kategori,
                    'alt_kategori'   => $renk ?: null,
                    'stok_kodu'      => $stokKodu,
                    'fiyat'          => null,
                    'stok'           => $miktar,
                    'aktif'          => true,
                    'one_cikan'      => false,
                    'kdv_orani'      => 0,
                    'kdv_dahil'      => true,
                    'kargo_bedeli'   => 0,
                    'kargo_kim_oder' => 'magaza',
                    'ozellikler'     => $ozellikler ?: null,
                ]);
                $yeniler++;
            }
        }

        return back()->with([
            'erp_yeniler'        => $yeniler,
            'erp_guncellenenler' => $guncellenenler,
            'erp_hatalar'        => $hatalar,
            'erp_atlananlar'     => $atlananlar,
        ]);
    }

    private function haritaYukle(): array
    {
        $dosya = storage_path('app/' . self::HARITA_DOSYA);
        if (!file_exists($dosya)) return [];
        return json_decode(file_get_contents($dosya), true) ?? [];
    }

    private function slugify(string $str): string
    {
        $map = ['ğ'=>'g','ü'=>'u','ş'=>'s','ı'=>'i','ö'=>'o','ç'=>'c','Ğ'=>'g','Ü'=>'u','Ş'=>'s','İ'=>'i','Ö'=>'o','Ç'=>'c'];
        $str = strtr($str, $map);
        return preg_replace('/-+/', '-', trim(preg_replace('/[^a-z0-9-]/i', '-', strtolower($str)), '-'));
    }

    private function benzersizSlug(string $slug): string
    {
        $base = $slug;
        $i = 1;
        while (Urun::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
