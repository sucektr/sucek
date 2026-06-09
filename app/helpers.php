<?php

if (!function_exists('_icerik_cache')) {
    function _icerik_cache(): array
    {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            try {
                foreach (\App\Models\Icerik::all() as $row) {
                    $cache["{$row->sayfa}.{$row->alan}"] = $row;
                }
            } catch (\Exception $e) {
                // DB henüz hazır değil (migration öncesi vb.)
            }
        }
        return $cache;
    }
}

if (!function_exists('icerik')) {
    function icerik(string $sayfa, string $alan, string $varsayilan = ''): string
    {
        $cache = _icerik_cache();
        $row = $cache["{$sayfa}.{$alan}"] ?? null;
        return ($row && $row->deger !== null && $row->deger !== '') ? $row->deger : $varsayilan;
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
        $cache = _icerik_cache();
        $row = $cache["{$sayfa}.{$alan}"] ?? null;
        if ($row && $row->gorsel) {
            return \Illuminate\Support\Facades\Storage::url($row->gorsel);
        }
        return $varsayilan;
    }
}
