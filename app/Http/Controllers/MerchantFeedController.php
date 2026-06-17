<?php

namespace App\Http\Controllers;

use App\Models\Urun;
use Illuminate\Http\Response;

class MerchantFeedController extends Controller
{
    public function feed(): Response
    {
        $urunler = Urun::where('aktif', true)
            ->orderBy('id')
            ->get();

        $sirketAdi = icerik('site', 'sirket_adi', 'SUÇEK');
        $siteUrl   = rtrim(url('/'), '/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\n";
        $xml .= '  <channel>' . "\n";
        $xml .= '    <title>' . e($sirketAdi) . ' Mağaza</title>' . "\n";
        $xml .= '    <link>' . $siteUrl . '</link>' . "\n";
        $xml .= '    <description>' . e($sirketAdi) . ' ürün kataloğu</description>' . "\n";

        foreach ($urunler as $urun) {
            $stok       = $urun->hasVaryant() ? $urun->toplamStok() : (int) $urun->stok;
            $musaitlik  = $stok > 0 ? 'in_stock' : 'out_of_stock';
            $fiyat      = number_format($urun->kdvDahilFiyat(), 2, '.', '') . ' TRY';
            $link       = $siteUrl . '/magaza/' . $urun->slug;
            $gorselUrl  = $urun->gorsel ? $siteUrl . '/storage/' . $urun->gorsel : '';
            $aciklama   = strip_tags($urun->aciklama ?? '');
            $aciklama   = mb_substr($aciklama, 0, 5000);

            $xml .= '    <item>' . "\n";
            $xml .= '      <g:id>' . e($urun->stok_kodu ?: $urun->id) . '</g:id>' . "\n";
            $xml .= '      <g:title>' . e($urun->ad) . '</g:title>' . "\n";
            $xml .= '      <g:description>' . e($aciklama ?: $urun->ad) . '</g:description>' . "\n";
            $xml .= '      <g:link>' . e($link) . '</g:link>' . "\n";
            if ($gorselUrl) {
                $xml .= '      <g:image_link>' . e($gorselUrl) . '</g:image_link>' . "\n";
            }
            if ($urun->eski_fiyat && $urun->eski_fiyat > $urun->fiyat) {
                $eskiFiyat = number_format((float) $urun->eski_fiyat, 2, '.', '') . ' TRY';
                $xml .= '      <g:price>' . $eskiFiyat . '</g:price>' . "\n";
                $xml .= '      <g:sale_price>' . $fiyat . '</g:sale_price>' . "\n";
            } else {
                $xml .= '      <g:price>' . $fiyat . '</g:price>' . "\n";
            }
            $xml .= '      <g:availability>' . $musaitlik . '</g:availability>' . "\n";
            $xml .= '      <g:condition>new</g:condition>' . "\n";
            $xml .= '      <g:brand>' . e($sirketAdi) . '</g:brand>' . "\n";
            if ($urun->stok_kodu) {
                $xml .= '      <g:mpn>' . e($urun->stok_kodu) . '</g:mpn>' . "\n";
            }
            if ($urun->kategori) {
                $xml .= '      <g:product_type>' . e($urun->kategori) . '</g:product_type>' . "\n";
            }
            $xml .= '    </item>' . "\n";
        }

        $xml .= '  </channel>' . "\n";
        $xml .= '</rss>';

        return response($xml, 200, [
            'Content-Type'  => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
