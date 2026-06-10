<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FiyatGuncelleController extends Controller
{
    public function index()
    {
        $toplamUrun = Urun::count();
        return view('admin.fiyat-guncelle.index', compact('toplamUrun'));
    }

    public function indir()
    {
        $urunler = Urun::orderBy('kategori')->orderBy('ad')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Fiyatlar');

        // Başlıklar
        $basliklar = ['ID', 'Stok Kodu', 'Ürün Adı', 'Kategori', 'Fiyat (TL)', 'Eski Fiyat (TL)', 'KDV Oranı (%)'];
        foreach ($basliklar as $i => $baslik) {
            $sheet->setCellValue(chr(65 + $i) . '1', $baslik);
        }

        // Başlık stili
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        $lastRow = max($urunler->count() + 1, 2);

        // Veri satırları
        foreach ($urunler as $i => $urun) {
            $row = $i + 2;
            $sheet->setCellValue('A' . $row, $urun->id);
            $sheet->setCellValue('B' . $row, $urun->stok_kodu ?? '');
            $sheet->setCellValue('C' . $row, $urun->ad);
            $sheet->setCellValue('D' . $row, $urun->kategori ?? '');
            $sheet->setCellValue('E' . $row, $urun->fiyat !== null ? (float)$urun->fiyat : '');
            $sheet->setCellValue('F' . $row, $urun->eski_fiyat !== null ? (float)$urun->eski_fiyat : '');
            $sheet->setCellValue('G' . $row, $urun->kdv_orani !== null ? (float)$urun->kdv_orani : '');
        }

        if ($urunler->count() > 0) {
            // Salt okunur sütunlar — gri arka plan
            foreach (['A', 'B', 'C', 'D', 'G'] as $col) {
                $sheet->getStyle($col . '2:' . $col . $lastRow)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                    'font' => ['color' => ['rgb' => '64748B']],
                ]);
            }
            // Düzenlenebilir sütunlar — sarı arka plan
            $sheet->getStyle('E2:F' . $lastRow)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEFCE8']],
                'font' => ['bold' => true],
            ]);
        }

        // Sütun genişlikleri
        $genislikler = ['A' => 8, 'B' => 16, 'C' => 36, 'D' => 16, 'E' => 16, 'F' => 16, 'G' => 14];
        foreach ($genislikler as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Sayı formatı
        if ($urunler->count() > 0) {
            $sheet->getStyle('E2:F' . $lastRow)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        $filename = 'urun-fiyatlari-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new XlsxWriter($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function yukle(Request $request)
    {
        $request->validate([
            'dosya' => 'required|file|mimes:xlsx|max:10240',
        ], [
            'dosya.required' => 'Dosya seçilmedi.',
            'dosya.mimes'    => 'Yalnızca .xlsx dosyası yüklenebilir.',
            'dosya.max'      => 'Dosya 10 MB\'dan büyük olamaz.',
        ]);

        $fullPath = $request->file('dosya')->getRealPath();

        try {
            $reader = new XlsxReader();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fullPath);
            $rows        = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Throwable $e) {
            return back()->withErrors(['dosya' => 'Dosya okunamadı: ' . $e->getMessage()]);
        }

        array_shift($rows); // başlık satırını atla

        $guncellenenler = [];
        $hatalar        = [];
        $atlananlar     = 0;

        foreach ($rows as $row) {
            $id = isset($row[0]) ? (int)$row[0] : 0;
            if (!$id) { $atlananlar++; continue; }

            $urun = Urun::find($id);
            if (!$urun) { $hatalar[] = "ID {$id}: ürün bulunamadı"; continue; }

            $yeniFiyat    = $this->parseFloat($row[4] ?? '');
            $yeniEskiFiyat = $this->parseFloat($row[5] ?? '');

            $eskiFiyat    = (float)($urun->fiyat ?? 0);
            $eskiEskiFiyat = (float)($urun->eski_fiyat ?? 0);

            $fiyatDegisti    = $yeniFiyat !== null && abs($yeniFiyat - $eskiFiyat) > 0.005;
            $eskiFiyatDegisti = $yeniEskiFiyat !== null && abs($yeniEskiFiyat - $eskiEskiFiyat) > 0.005;

            if (!$fiyatDegisti && !$eskiFiyatDegisti) continue;

            $guncellenenler[] = [
                'id'               => $id,
                'ad'               => $urun->ad,
                'fiyat_eski'       => $eskiFiyat,
                'fiyat_yeni'       => $fiyatDegisti ? $yeniFiyat : $eskiFiyat,
                'eski_fiyat_eski'  => $eskiEskiFiyat ?: null,
                'eski_fiyat_yeni'  => $eskiFiyatDegisti ? ($yeniEskiFiyat > 0 ? $yeniEskiFiyat : null) : ($eskiEskiFiyat ?: null),
            ];

            if ($fiyatDegisti)     $urun->fiyat = $yeniFiyat;
            if ($eskiFiyatDegisti) $urun->eski_fiyat = $yeniEskiFiyat > 0 ? $yeniEskiFiyat : null;
            $urun->save();
        }

        return back()->with([
            'guncellenenler' => $guncellenenler,
            'hatalar'        => $hatalar,
            'atlananlar'     => $atlananlar,
        ]);
    }

    private function parseFloat($val): ?float
    {
        if ($val === null || $val === '') return null;
        $val = str_replace([' ', '.'], ['', ''], str_replace(',', '.', (string)$val));
        return is_numeric($val) ? (float)$val : null;
    }
}
