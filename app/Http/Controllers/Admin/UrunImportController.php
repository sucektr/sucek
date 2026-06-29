<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UrunImportController extends Controller
{
    // Kolon sırası
    const KOLONLAR = [
        'A' => 'ID (boş = yeni ürün)',
        'B' => 'Ürün Adı *',
        'C' => 'Slug (boş = otomatik)',
        'D' => 'Kategori *',
        'E' => 'Alt Kategori',
        'F' => 'Stok Kodu',
        'G' => 'Fiyat (TL) *',
        'H' => 'Eski Fiyat (TL)',
        'I' => 'KDV Oranı (%)',
        'J' => 'Stok Adedi',
        'K' => 'Aktif (1/0)',
        'L' => 'Öne Çıkan (1/0)',
        'M' => 'Kargo Bedeli (TL)',
        'N' => 'Açıklama',
    ];

    public function index()
    {
        $toplamUrun = Urun::count();
        return view('admin.urun-import.index', compact('toplamUrun'));
    }

    public function sablon()
    {
        $spreadsheet = new Spreadsheet();

        // ─── Veri Sayfası ───────────────────────────────────────────────
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Ürünler');

        // Başlıklar
        foreach (self::KOLONLAR as $col => $baslik) {
            $sheet->setCellValue($col . '1', $baslik);
        }

        // Başlık stili
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Örnek satır
        $ornekler = [
            ['', 'Örnek Yeni Ürün', '', 'spor', 'fitness', 'STK-001', '299.90', '399.90', '20', '10', '1', '0', '0', 'Ürün açıklaması buraya yazılır.'],
            ['42', 'Mevcut Ürün Adı', 'mevcut-urun-slug', 'insaat', '', 'STK-002', '149.90', '', '10', '5', '1', '1', '29.90', ''],
        ];
        foreach ($ornekler as $i => $ornek) {
            $row = $i + 2;
            foreach (array_keys(self::KOLONLAR) as $j => $col) {
                $sheet->setCellValue($col . $row, $ornek[$j] ?? '');
            }
        }

        // Örnek satır stili
        $sheet->getStyle('A2:N3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            'font' => ['color' => ['rgb' => '94A3B8'], 'italic' => true, 'size' => 9],
        ]);

        // Zorunlu alanlar — sarı
        $sheet->getStyle('B1')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF08A']], 'font' => ['bold' => true, 'color' => ['rgb' => '000000']]]);
        $sheet->getStyle('D1')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF08A']], 'font' => ['bold' => true, 'color' => ['rgb' => '000000']]]);
        $sheet->getStyle('G1')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF08A']], 'font' => ['bold' => true, 'color' => ['rgb' => '000000']]]);

        // Otomatik sütunlar — gri
        $sheet->getStyle('A1')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '64748B']], 'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]]);
        $sheet->getStyle('C1')->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '64748B']], 'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]]);

        // Sütun genişlikleri
        $genislikler = ['A'=>8,'B'=>32,'C'=>28,'D'=>14,'E'=>16,'F'=>14,'G'=>14,'H'=>16,'I'=>12,'J'=>12,'K'=>10,'L'=>12,'M'=>14,'N'=>40];
        foreach ($genislikler as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Çerçeve
        $sheet->getStyle('A1:N1')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);

        // ─── Açıklamalar Sayfası ─────────────────────────────────────────
        $info = $spreadsheet->createSheet();
        $info->setTitle('Açıklamalar');
        $bilgiler = [
            ['Alan', 'Açıklama', 'Örnek'],
            ['ID', 'Boş bırakın → yeni ürün. Mevcut ürün ID\'si yazın → güncelleme.', '42'],
            ['Ürün Adı *', 'Zorunlu. Ürünün tam adı.', 'Kayak Sopası Seti'],
            ['Slug', 'Boş bırakın, otomatik oluşturulur. URL\'de görünür.', 'kayak-sopasi-seti'],
            ['Kategori *', 'Zorunlu. spor / insaat / dekorasyon / diger', 'spor'],
            ['Alt Kategori', 'İsteğe bağlı alt kategori.', 'fitness'],
            ['Stok Kodu', 'Opsiyonel barkod/SKU.', 'STK-001'],
            ['Fiyat (TL) *', 'Zorunlu. KDV dahil müşteri fiyatı.', '299.90'],
            ['Eski Fiyat (TL)', 'İndirim öncesi fiyat. Boş bırakılabilir.', '399.90'],
            ['KDV Oranı (%)', 'KDV yüzdesi (10, 20 vb.). Boş = 0.', '20'],
            ['Stok Adedi', 'Stok miktarı. Boş = 0.', '50'],
            ['Aktif (1/0)', '1 = yayında, 0 = gizli. Boş = 1 (yayında).', '1'],
            ['Öne Çıkan (1/0)', '1 = öne çıkan. Boş = 0.', '0'],
            ['Kargo Bedeli (TL)', 'Müşteri kargosu. 0 = ücretsiz. Boş = 0.', '29.90'],
            ['Açıklama', 'Ürün açıklaması. HTML desteklenmez.', 'Kaliteli malzeme...'],
        ];
        foreach ($bilgiler as $r => $satir) {
            foreach ($satir as $c => $deger) {
                $info->setCellValue(chr(65 + $c) . ($r + 1), $deger);
            }
        }
        $info->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
        ]);
        $info->getColumnDimension('A')->setWidth(18);
        $info->getColumnDimension('B')->setWidth(55);
        $info->getColumnDimension('C')->setWidth(22);

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'urun-import-sablon-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            (new XlsxWriter($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function yukle(Request $request)
    {
        $request->validate([
            'dosya' => 'required|file|mimes:xlsx|max:20480',
        ], [
            'dosya.required' => 'Dosya seçilmedi.',
            'dosya.mimes'    => 'Yalnızca .xlsx dosyası yüklenebilir.',
            'dosya.max'      => 'Dosya 20 MB\'dan büyük olamaz.',
        ]);

        try {
            $reader = new XlsxReader();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($request->file('dosya')->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Throwable $e) {
            return back()->withErrors(['dosya' => 'Dosya okunamadı: ' . $e->getMessage()]);
        }

        array_shift($rows); // başlık satırını atla

        $yeniler     = [];
        $guncellenenler = [];
        $hatalar     = [];
        $atlananlar  = 0;

        foreach ($rows as $rowIndex => $row) {
            $satirNo = $rowIndex + 2;

            // Tamamen boş satırı atla
            if (empty(array_filter(array_map('strval', $row), fn($v) => trim($v) !== ''))) {
                $atlananlar++;
                continue;
            }

            $id       = isset($row[0]) ? trim((string)$row[0]) : '';
            $ad       = isset($row[1]) ? trim((string)$row[1]) : '';
            $slug     = isset($row[2]) ? trim((string)$row[2]) : '';
            $kategori = isset($row[3]) ? trim((string)$row[3]) : '';
            $altKat   = isset($row[4]) ? trim((string)$row[4]) : '';
            $stokKodu = isset($row[5]) ? trim((string)$row[5]) : '';
            $fiyat    = $this->parseFloat($row[6] ?? '');
            $eskiFiyat = $this->parseFloat($row[7] ?? '');
            $kdv      = $this->parseFloat($row[8] ?? '');
            $stok     = isset($row[9]) && trim((string)$row[9]) !== '' ? (int)$row[9] : null;
            $aktif    = isset($row[10]) && trim((string)$row[10]) !== '' ? (bool)(int)$row[10] : null;
            $oneCikan = isset($row[11]) && trim((string)$row[11]) !== '' ? (bool)(int)$row[11] : null;
            $kargo    = $this->parseFloat($row[12] ?? '');
            $aciklama = isset($row[13]) ? trim((string)$row[13]) : '';

            if ($id === '') {
                // ─── YENİ ÜRÜN ───────────────────────────────────────────
                if ($ad === '') {
                    $hatalar[] = "Satır {$satirNo}: Ürün adı boş, atlandı.";
                    continue;
                }
                if ($kategori === '') {
                    $hatalar[] = "Satır {$satirNo}: Kategori boş, atlandı. (Ürün: {$ad})";
                    continue;
                }
                if ($fiyat === null) {
                    $hatalar[] = "Satır {$satirNo}: Fiyat geçersiz, atlandı. (Ürün: {$ad})";
                    continue;
                }

                if ($slug === '') {
                    $slug = $this->slugify($ad);
                }
                // Benzersiz slug kontrolü
                $slug = $this->benzersizSlug($slug);

                $urun = Urun::create([
                    'ad'          => $ad,
                    'slug'        => $slug,
                    'kategori'    => $kategori,
                    'alt_kategori' => $altKat ?: null,
                    'stok_kodu'   => $stokKodu ?: null,
                    'fiyat'       => $fiyat,
                    'eski_fiyat'  => $eskiFiyat,
                    'kdv_orani'   => $kdv ?? 0,
                    'kdv_dahil'   => true,
                    'stok'        => $stok ?? 0,
                    'aktif'       => $aktif ?? true,
                    'one_cikan'   => $oneCikan ?? false,
                    'kargo_bedeli' => $kargo ?? 0,
                    'kargo_kim_oder' => ($kargo && $kargo > 0) ? 'musteri' : 'magaza',
                    'aciklama'    => $aciklama ?: null,
                ]);

                $yeniler[] = ['id' => $urun->id, 'ad' => $ad, 'slug' => $slug];

            } else {
                // ─── MEVCUT ÜRÜN GÜNCELLEME ──────────────────────────────
                $urun = Urun::find((int)$id);
                if (!$urun) {
                    $hatalar[] = "Satır {$satirNo}: ID {$id} bulunamadı.";
                    continue;
                }

                if ($ad !== '')        $urun->ad          = $ad;
                if ($slug !== '')      $urun->slug        = $slug;
                if ($kategori !== '')  $urun->kategori    = $kategori;
                if ($altKat !== '')    $urun->alt_kategori = $altKat;
                if ($stokKodu !== '')  $urun->stok_kodu   = $stokKodu;
                if ($fiyat !== null)   $urun->fiyat       = $fiyat;
                if ($eskiFiyat !== null) $urun->eski_fiyat = $eskiFiyat > 0 ? $eskiFiyat : null;
                if ($kdv !== null)     $urun->kdv_orani   = $kdv;
                if ($stok !== null)    $urun->stok        = $stok;
                if ($aktif !== null)   $urun->aktif       = $aktif;
                if ($oneCikan !== null) $urun->one_cikan  = $oneCikan;
                if ($kargo !== null)   { $urun->kargo_bedeli = $kargo; $urun->kargo_kim_oder = $kargo > 0 ? 'musteri' : 'magaza'; }
                if ($aciklama !== '')  $urun->aciklama    = $aciklama;

                $urun->save();
                $guncellenenler[] = ['id' => $urun->id, 'ad' => $urun->ad];
            }
        }

        return back()->with([
            'import_yeniler'        => $yeniler,
            'import_guncellenenler' => $guncellenenler,
            'import_hatalar'        => $hatalar,
            'import_atlananlar'     => $atlananlar,
        ]);
    }

    private function slugify(string $str): string
    {
        $map = ['ğ'=>'g','ü'=>'u','ş'=>'s','ı'=>'i','ö'=>'o','ç'=>'c','Ğ'=>'g','Ü'=>'u','Ş'=>'s','İ'=>'i','Ö'=>'o','Ç'=>'c'];
        $str = strtr($str, $map);
        return preg_replace('/-+/', '-', trim(preg_replace('/[^a-z0-9-]/', '-', strtolower($str)), '-'));
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

    private function parseFloat($val): ?float
    {
        if ($val === null || trim((string)$val) === '') return null;
        $str = trim((string)$val);
        if (str_contains($str, ',')) {
            // Türkçe format: 1.299,90 → 1299.90
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        }
        // Standart format: 1299.90 → olduğu gibi
        return is_numeric($str) ? (float)$str : null;
    }
}
