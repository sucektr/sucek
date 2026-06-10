<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $katalog->baslik }} — SUÇEK Katalog</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'DM Sans',sans-serif;background:#EBEBEB;}
.katalog-sayfa{
    width:210mm;height:297mm;background:white;padding:18mm 20mm;
    margin:24px auto;box-shadow:0 4px 24px rgba(0,0,0,0.1);
    display:flex;flex-direction:column;overflow:hidden;
}
.bar-actions{
    position:fixed;bottom:24px;right:24px;z-index:100;display:flex;gap:10px;
}
.btn-yazdir{
    background:#0F0F0F;color:white;padding:10px 20px;border-radius:8px;
    border:none;cursor:pointer;font-family:inherit;font-size:13px;font-weight:500;
    display:flex;align-items:center;gap:7px;
}
.btn-yazdir:hover{background:#333;}
.btn-geri{
    background:white;color:#0F0F0F;padding:10px 20px;border-radius:8px;
    border:1px solid rgba(0,0,0,0.15);cursor:pointer;font-family:inherit;font-size:13px;font-weight:500;
}
.btn-geri:hover{background:#F5F5F5;}
@media print{
    *,-webkit-*{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
    html,body{margin:0!important;padding:0!important;background:white!important;}
    .bar-actions{display:none!important;}
    .katalog-sayfa{
        height:297mm!important;min-height:unset!important;
        overflow:hidden!important;
        margin:0!important;box-shadow:none!important;
        page-break-inside:avoid;break-inside:avoid;
    }
    @page{size:A4 portrait;margin:0;}
}
</style>
</head>
<body>

<div class="bar-actions">
    <button class="btn-geri" onclick="window.close()">← Geri</button>
    <button class="btn-yazdir" onclick="window.print()">
        <i class="ti ti-printer"></i> Yazdır / PDF
    </button>
</div>

@php
$kapak     = $katalog->kapak_ayarlari ?? [];
$ekstralar = $kapak['ekstralar'] ?? [];
$logo      = $kapak['logo'] ?? '';
$tocAdi    = fn($urun) => $icindekiler[$urun->id] ?? $icindekiler[(string)$urun->id] ?? $urun->ad;
@endphp

{{-- ── KAPAK ── --}}
<div class="katalog-sayfa">
    <div style="height:8px;background:#0F0F0F;margin:-18mm -20mm 0;"></div>
    <div style="height:3px;background:#B8962E;margin:0 -20mm 44px;"></div>

    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:0 10mm;">

        @if($logo)
        <img src="{{ $logo }}" alt="Logo" style="height:80px;max-width:160px;object-fit:contain;margin-bottom:16px;">
        @endif

        @if(!empty($kapak['kurum']))
        <div style="font-family:'Cormorant Garamond',serif;font-size:42px;font-weight:600;color:#0F0F0F;letter-spacing:.06em;text-transform:uppercase;line-height:1.15;margin-bottom:16px;">{{ $kapak['kurum'] }}</div>
        @endif

        <div style="width:52px;height:2px;background:#B8962E;margin:0 auto 16px;"></div>

        <div style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:400;color:#5A5A5A;line-height:1.5;letter-spacing:.06em;text-transform:uppercase;">{{ $katalog->baslik }}</div>
        @if($katalog->alt_baslik)
        <div style="font-size:11px;color:#A8A8A8;margin-top:5px;">{{ $katalog->alt_baslik }}</div>
        @endif

        @if(!empty($kapak['ihale_no']) || !empty($kapak['ihale_tarih']) || !empty($kapak['ihale_saat']) || !empty($ekstralar))
        <div style="margin-top:28px;border:1px solid rgba(0,0,0,0.12);border-radius:4px;padding:14px 20px;width:100%;text-align:left;">
            @if(!empty($kapak['ihale_no']))
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;padding-bottom:8px;border-bottom:1px solid rgba(0,0,0,0.07);margin-bottom:8px;">
                <span style="color:#A8A8A8;font-size:10px;letter-spacing:.06em;text-transform:uppercase;">İhale Kayıt No</span>
                <span style="color:#0F0F0F;font-weight:600;font-size:13px;">{{ $kapak['ihale_no'] }}</span>
            </div>
            @endif
            @if(!empty($kapak['ihale_tarih']))
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;padding-bottom:8px;border-bottom:1px solid rgba(0,0,0,0.07);margin-bottom:8px;">
                <span style="color:#A8A8A8;font-size:10px;letter-spacing:.06em;text-transform:uppercase;">İhale Tarihi</span>
                <span style="color:#0F0F0F;font-weight:500;">{{ \Carbon\Carbon::parse($kapak['ihale_tarih'])->format('d.m.Y') }}</span>
            </div>
            @endif
            @if(!empty($kapak['ihale_saat']))
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;{{ !empty($ekstralar) ? 'padding-bottom:8px;border-bottom:1px solid rgba(0,0,0,0.07);margin-bottom:8px;' : '' }}">
                <span style="color:#A8A8A8;font-size:10px;letter-spacing:.06em;text-transform:uppercase;">İhale Saati</span>
                <span style="color:#0F0F0F;font-weight:500;">{{ $kapak['ihale_saat'] }}</span>
            </div>
            @endif
            @foreach($ekstralar as $ekstra)
            @if(!empty($ekstra['etiket']) || !empty($ekstra['deger']))
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;padding-top:8px;border-top:1px solid rgba(0,0,0,0.07);">
                <span style="color:#A8A8A8;font-size:10px;letter-spacing:.06em;text-transform:uppercase;">{{ $ekstra['etiket'] ?? '' }}</span>
                <span style="color:#0F0F0F;font-weight:500;">{{ $ekstra['deger'] ?? '' }}</span>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        <div style="font-size:9px;letter-spacing:.22em;color:#B8962E;text-transform:uppercase;margin-top:22px;font-family:'DM Sans',sans-serif;">{{ $kapak['marka'] ?? 'SUÇEK' }}</div>
    </div>

    <div style="margin:0 -20mm -18mm;">
        <div style="height:3px;background:#B8962E;"></div>
        <div style="height:8px;background:#0F0F0F;"></div>
    </div>
</div>

{{-- ── İÇİNDEKİLER ── --}}
<div class="katalog-sayfa" style="page-break-before:always;break-before:page;">
    <div style="margin-bottom:6px;display:flex;justify-content:space-between;align-items:baseline;">
        <div style="font-family:'Cormorant Garamond',serif;font-size:24px;font-weight:600;color:#0F0F0F;letter-spacing:.04em;">İçindekiler</div>
        <span style="font-size:10px;letter-spacing:.22em;color:#A8A8A8;text-transform:uppercase;">{{ $kapak['marka'] ?? 'SUÇEK' }}</span>
    </div>
    <div style="height:3px;background:#B8962E;margin-bottom:3px;"></div>
    <div style="height:1px;background:#0F0F0F;margin-bottom:28px;"></div>

    <div style="flex:1;">
        @foreach($urunler as $i => $urun)
        <div style="display:flex;align-items:baseline;padding:10px 0;border-bottom:1px solid rgba(0,0,0,0.06);{{ $i === 0 ? 'border-top:1px solid rgba(0,0,0,0.06);' : '' }}">
            <span style="min-width:28px;font-family:'Cormorant Garamond',serif;font-size:15px;color:#B8962E;font-weight:600;flex-shrink:0;">{{ $i + 1 }}.</span>
            <span style="flex:1;font-size:13px;color:#0F0F0F;line-height:1.4;">{{ $tocAdi($urun) }}</span>
            <span style="font-size:11px;color:#B8962E;font-weight:600;margin-left:12px;flex-shrink:0;font-family:'Cormorant Garamond',serif;">{{ $i + 3 }}</span>
        </div>
        @endforeach
    </div>

    <div style="margin:0 -20mm -18mm;">
        <div style="height:3px;background:#B8962E;"></div>
        <div style="height:8px;background:#0F0F0F;"></div>
    </div>
</div>

{{-- ── ÜRÜN SAYFALARI ── --}}
@foreach($urunler as $i => $urun)
<div class="katalog-sayfa" style="page-break-before:always;break-before:page;">

    <div style="margin-bottom:6px;display:flex;justify-content:space-between;align-items:baseline;">
        <div>
            <span style="font-family:'Cormorant Garamond',serif;font-size:16px;color:#B8962E;margin-right:8px;font-weight:600;">{{ $i + 1 }}.</span>
            <span style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0F0F0F;">{{ $urun->ad }}</span>
        </div>
        <span style="font-size:10px;letter-spacing:.22em;color:#A8A8A8;text-transform:uppercase;">{{ $kapak['marka'] ?? 'SUÇEK' }}</span>
    </div>
    <div style="height:3px;background:#B8962E;margin-bottom:3px;"></div>
    <div style="height:1px;background:#0F0F0F;margin-bottom:20px;"></div>

    @php
    $gorselYollar = !empty($urun->gorseller) ? $urun->gorseller : ($urun->gorsel ? [$urun->gorsel] : []);
    $gorselYollar = array_slice($gorselYollar, 0, 4);
    @endphp
    <div style="display:flex;flex:1;gap:20px;min-height:0;">

        {{-- Sol: Görseller --}}
        <div style="width:42%;display:flex;flex-direction:column;gap:10px;min-height:0;">
            @forelse($gorselYollar as $gYol)
            <div style="flex:1;border:1px solid rgba(0,0,0,0.08);border-radius:4px;overflow:hidden;background:#F5F5F5;display:flex;align-items:center;justify-content:center;min-height:0;">
                <img src="{{ asset('storage/' . $gYol) }}" alt="{{ $urun->ad }}"
                     style="max-width:100%;max-height:100%;object-fit:contain;padding:8px;">
            </div>
            @empty
            <div style="flex:1;border:1px solid rgba(0,0,0,0.08);border-radius:4px;background:#F5F5F5;display:flex;align-items:center;justify-content:center;text-align:center;color:#C0C0C0;font-size:12px;padding:24px;">
                <div>
                    <i class="ti ti-photo" style="font-size:28px;display:block;margin-bottom:6px;"></i>
                    Görsel yüklenmemiş
                </div>
            </div>
            @endforelse
        </div>

        {{-- Sağ: Teknik Özellikler --}}
        <div style="flex:1;display:flex;flex-direction:column;min-height:0;overflow:hidden;">
            <div style="font-size:9px;letter-spacing:.18em;text-transform:uppercase;color:#B8962E;margin-bottom:10px;font-weight:500;padding-bottom:6px;border-bottom:1px solid rgba(184,150,46,0.25);flex-shrink:0;">Teknik Özellikler</div>
            @if(trim(strip_tags($urun->aciklama ?? '')))
            <div style="flex:1;min-height:0;overflow:hidden;font-size:12px;color:#3A3A3A;line-height:1.7;">{!! $urun->aciklama !!}</div>
            @else
            <div style="font-size:11px;color:#C0C0C0;font-style:italic;">Açıklama girilmemiş</div>
            @endif
        </div>

    </div>


</div>
@endforeach

</body>
</html>
