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

if (!function_exists('icerik_metin')) {
    /**
     * icerik() ile aynı, ama admin hiç içerik girmemişse (TR/EN ikisi de boş)
     * kod içindeki sabit varsayılan metni ceviri() üzerinden İngilizce'ye çevirir.
     * Sadece gerçek kullanıcı metinleri için kullanılır — URL, API anahtarı,
     * sayısal değer gibi teknik varsayılanlar için icerik() kullanılmaya devam eder.
     */
    function icerik_metin(string $sayfa, string $alan, string $varsayilan = ''): string
    {
        $dil = app()->getLocale();
        $cache = _icerik_cache($dil);
        $row = $cache["{$sayfa}.{$alan}"] ?? null;
        if ($row && $row->deger !== null && $row->deger !== '') {
            return $row->deger;
        }
        if ($dil !== 'tr') {
            $cacheTr = _icerik_cache('tr');
            $rowTr = $cacheTr["{$sayfa}.{$alan}"] ?? null;
            if ($rowTr && $rowTr->deger !== null && $rowTr->deger !== '') {
                return $rowTr->deger;
            }
            if ($varsayilan !== '') {
                return ceviri($varsayilan);
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

if (!function_exists('ceviri')) {
    /**
     * Blade'e sabit yazılmış Türkçe metinleri İngilizce'ye çevirir.
     * Bilinmeyen bir metin gelirse veritabanına otomatik kaydedilir (en_metin boş) —
     * admin panelin Çeviriler ekranında görünür hale gelir. Çeviri girilmemişse
     * Türkçe metin döner (site hiçbir zaman bozulmaz).
     */
    function ceviri(string $tr): string
    {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            try {
                foreach (\App\Models\Ceviri::all() as $row) {
                    $cache[$row->tr_metin] = $row->en_metin;
                }
            } catch (\Exception $e) {
                // DB henüz hazır değil (migration öncesi vb.)
            }
        }

        if (!array_key_exists($tr, $cache)) {
            $cache[$tr] = null;
            try {
                // Not: tr_metin sütunu case-insensitive collation kullanıyor (utf8mb4_unicode_ci),
                // yani örn. "Adet" ile "adet" veritabanında aynı satıra eşleşir. firstOrCreate()
                // böyle bir durumda YENİ satır açmaz, mevcut satırı bulur — bu yüzden bulunan
                // satırın gerçek en_metin değerini önbelleğe almamız gerekir; aksi halde bu farklı
                // harf varyasyonu hep boşmuş gibi işlenip Türkçe'ye düşer.
                $row = \App\Models\Ceviri::firstOrCreate(['tr_metin' => $tr]);
                $cache[$tr] = $row->en_metin;
            } catch (\Exception $e) {
                // DB henüz hazır değil
            }
        }

        if (app()->getLocale() === 'en' && !empty($cache[$tr])) {
            return $cache[$tr];
        }

        return $tr;
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
