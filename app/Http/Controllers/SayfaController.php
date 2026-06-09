<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SayfaController extends Controller
{
    private function tanimlar(): array
    {
        return [
            'kisisel-verilerin-korunmasi' => [
                'baslik'      => 'Kişisel Verilerin Korunması',
                'alan'        => 'kvkk',
                'breadcrumb'  => 'Kişisel Verilerin Korunması',
            ],
            'gizlilik-politikasi' => [
                'baslik'      => 'Gizlilik Politikası',
                'alan'        => 'gizlilik',
                'breadcrumb'  => 'Gizlilik Politikası',
            ],
            'sss' => [
                'baslik'      => 'Sıkça Sorulan Sorular',
                'alan'        => 'sss',
                'breadcrumb'  => 'SSS',
            ],
            'mesafeli-satis-sozlesmesi' => [
                'baslik'      => 'Mesafeli Satış Sözleşmesi',
                'alan'        => 'mesafeli',
                'breadcrumb'  => 'Mesafeli Satış Sözleşmesi',
            ],
            'iade-degisim' => [
                'baslik'      => 'İade & Değişim',
                'alan'        => 'iade',
                'breadcrumb'  => 'İade & Değişim',
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
