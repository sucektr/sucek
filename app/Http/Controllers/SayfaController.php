<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SayfaController extends Controller
{
    private function tanimlar(): array
    {
        return [
            'kisisel-verilerin-korunmasi' => [
                'baslik'      => ceviri('Kişisel Verilerin Korunması'),
                'alan'        => 'kvkk',
                'breadcrumb'  => ceviri('Kişisel Verilerin Korunması'),
            ],
            'gizlilik-politikasi' => [
                'baslik'      => ceviri('Gizlilik Politikası'),
                'alan'        => 'gizlilik',
                'breadcrumb'  => ceviri('Gizlilik Politikası'),
            ],
            'sss' => [
                'baslik'      => ceviri('Sıkça Sorulan Sorular'),
                'alan'        => 'sss',
                'breadcrumb'  => ceviri('SSS'),
            ],
            'mesafeli-satis-sozlesmesi' => [
                'baslik'      => ceviri('Mesafeli Satış Sözleşmesi'),
                'alan'        => 'mesafeli',
                'breadcrumb'  => ceviri('Mesafeli Satış Sözleşmesi'),
            ],
            'iade-degisim' => [
                'baslik'      => ceviri('İade & Değişim'),
                'alan'        => 'iade',
                'breadcrumb'  => ceviri('İade & Değişim'),
            ],
        ];
    }

    public function goster(string $sayfa)
    {
        $tanimlar = $this->tanimlar();
        abort_unless(isset($tanimlar[$sayfa]), 404);

        $tanim   = $tanimlar[$sayfa];
        $icerik  = icerik('yasal', $tanim['alan'] . '_icerik', '');
        $guncellenme = icerik('yasal', $tanim['alan'] . '_tarih', '');

        return view('yasal.goster', compact('tanim', 'icerik', 'guncellenme'));
    }
}
