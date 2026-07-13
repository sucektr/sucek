<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;

class ErpImportController extends Controller
{
    // ERP Excel sütun indeksleri (0 tabanlı)
    // A=0 Marka Açıklaması | B=1 Stok Kodu | C=2 Stok Kodu Açıklama
    // D=3 Alt Stok Kodu    | E=4 Renk       | F=5 ANAGRUP | G=6 Miktar

    public function index()
    {
        $toplamUrun = Urun::whereNotNull('stok_kodu')->count();
        return view('admin.erp-import.index', compact('toplamUrun'));
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

            if ($ext === 'xls') {
                $reader = new XlsReader();
            } else {
                $reader = new XlsxReader();
            }

            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            $rows = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Throwable $e) {
            return back()->withErrors(['dosya' => 'Dosya okunamadı: ' . $e->getMessage()]);
        }

        // Başlık satırını atla
        array_shift($rows);

        $yeniler       = 0;
        $guncellenenler = 0;
        $atlananlar    = 0;
        $hatalar       = [];

        foreach ($rows as $idx => $row) {
            $satirNo = $idx + 2;

            // Boş satırı atla
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

            if ($stokKodu === '') {
                $hatalar[] = "Satır {$satirNo}: Stok kodu boş, atlandı.";
                continue;
            }

            if ($ad === '') {
                $hatalar[] = "Satır {$satirNo}: Ürün adı boş (Stok: {$stokKodu}), atlandı.";
                continue;
            }

            $ozellikler = [];
            if ($altKod !== '') {
                $ozellikler[] = ['ad' => 'Alt Stok Kodu', 'degerler' => [$altKod]];
            }
            if ($renk !== '') {
                $ozellikler[] = ['ad' => 'Renk', 'degerler' => [$renk]];
            }

            $mevcut = Urun::where('stok_kodu', $stokKodu)->first();

            if ($mevcut) {
                // Sadece stok güncellenir — manuel girilen ad, görsel, açıklama, varyant vb. korunur
                $mevcut->update(['stok' => $miktar]);
                $guncellenenler++;
            } else {
                $slug = $this->benzersizSlug($this->slugify($stokKodu));

                Urun::create([
                    'ad'          => $ad,
                    'slug'        => $slug,
                    'marka'       => $marka ?: null,
                    'kategori'    => $anaGrup ?: 'genel',
                    'alt_kategori' => $renk ?: null,
                    'stok_kodu'   => $stokKodu,
                    'fiyat'       => null,
                    'stok'        => $miktar,
                    'aktif'       => true,
                    'one_cikan'   => false,
                    'kdv_orani'   => 0,
                    'kdv_dahil'   => true,
                    'kargo_bedeli' => 0,
                    'kargo_kim_oder' => 'magaza',
                    'ozellikler'  => $ozellikler ?: null,
                ]);
                $yeniler++;
            }
        }

        return back()->with([
            'erp_yeniler'       => $yeniler,
            'erp_guncellenenler' => $guncellenenler,
            'erp_hatalar'       => $hatalar,
            'erp_atlananlar'    => $atlananlar,
        ]);
    }

    private function slugify(string $str): string
    {
        $map = ['ğ'=>'g','ü'=>'u','ş'=>'s','ı'=>'i','ö'=>'o','ç'=>'c','Ğ'=>'G','Ü'=>'U','Ş'=>'S','İ'=>'I','Ö'=>'O','Ç'=>'C'];
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
