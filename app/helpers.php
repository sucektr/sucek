<?php

if (!function_exists('_icerik_cache')) {
    function _icerik_cache(string $dil = 'tr'): array
    {
        static $cache = [];
        if (!isset($cache[$dil])) {
            $cache[$dil] = [];
            try {
                foreach (\App\Models\Icerik::where('dil', $dil)->get() as $row) {
                    $cache[$dil]["{$row->sayfa}.{$row->alan}"] = $row;
                }
            } catch (\Exception $e) {
                // DB henüz hazır değil (migration öncesi vb.)
            }
        }
        return $cache[$dil];
    }
}

if (!function_exists('icerik')) {
    function icerik(string $sayfa, string $alan, string $varsayilan = ''): string
    {
        $dil = app()->getLocale();
        $cache = _icerik_cache($dil);
        $row = $cache["{$sayfa}.{$alan}"] ?? null;
        if ($row && $row->deger !== null && $row->deger !== '') {
            return $row->deger;
        }
        // İngilizce yoksa Türkçeye düş
        if ($dil !== 'tr') {
            $cacheTr = _icerik_cache('tr');
            $rowTr = $cacheTr["{$sayfa}.{$alan}"] ?? null;
            if ($rowTr && $rowTr->deger !== null && $rowTr->deger !== '') {
                return $rowTr->deger;
            }
        }
        return $varsayilan;
    }
}

if (!function_exists('kargoUcreti')) {
    /**
     * Sepetteki ürünlerin müşteri kargo ücretlerinin en yükseğini döner.
     * Global ücretsiz eşiği aşılmışsa sıfır döner.
     */
    function kargoUcreti(array $sepet, float $toplamTutar): float
    {
        $esik = (float) icerik('kargo', 'ucretsiz_esik', '0');
        if ($esik > 0 && $toplamTutar >= $esik) {
            return 0.0;
        }

        $ucret = 0.0;
        foreach ($sepet as $item) {
            $ucret = max($ucret, (float) ($item['kargo_ucreti'] ?? 0));
        }
        return $ucret;
    }
}

if (!function_exists('icerik_gorsel')) {
    function icerik_gorsel(string $sayfa, string $alan, string $varsayilan = ''): string
    {
        $dil = app()->getLocale();
        $cache = _icerik_cache($dil);
        $row = $cache["{$sayfa}.{$alan}"] ?? null;
        if ($row && $row->gorsel) {
            return url('/uploads/' . $row->gorsel);
        }
        // İngilizce yoksa Türkçeye düş
        if ($dil !== 'tr') {
            $cacheTr = _icerik_cache('tr');
            $rowTr = $cacheTr["{$sayfa}.{$alan}"] ?? null;
            if ($rowTr && $rowTr->gorsel) {
                return url('/uploads/' . $rowTr->gorsel);
            }
        }
        return $varsayilan;
    }
}
