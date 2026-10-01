<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $katalog->baslik }} — SUÇEK Nümizmatik Kataloğu</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Inter',system-ui,sans-serif;background:#EBEBEB;}
.katalog-sayfa{
    width:210mm;height:297mm;background:white;padding:16mm;
    margin:24px auto;box-shadow:0 4px 24px rgba(0,0,0,0.1);
    display:flex;flex-direction:column;overflow:hidden;
}
.bar-actions{position:fixed;bottom:24px;right:24px;z-index:100;display:flex;gap:10px;}
.btn-yazdir{background:#0F172A;color:white;padding:10px 20px;border-radius:8px;border:none;cursor:pointer;font-family:inherit;font-size:13px;font-weight:500;display:flex;align-items:center;gap:7px;}
.btn-yazdir:hover{background:#333;}
.btn-geri{background:white;color:#0F172A;padding:10px 20px;border-radius:8px;border:1px solid rgba(0,0,0,0.15);cursor:pointer;font-family:inherit;font-size:13px;font-weight:500;}
.btn-geri:hover{background:#F5F5F5;}
.kart{display:flex;flex-direction:column;gap:10px;flex:1;border-bottom:1px solid #F1F5F9;padding:14px 0;}
.kart:last-child{border-bottom:none;}
@media print{
    *,-webkit-*{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
    html,body{margin:0!important;padding:0!important;background:white!important;}
    .bar-actions{display:none!important;}
    .katalog-sayfa{
        height:297mm!important;max-height:297mm!important;min-height:unset!important;
        overflow:hidden!important;
        margin:0!important;box-shadow:none!important;
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
$kapak = $katalog->kapak_ayarlari ?? [];
$marka = $kapak['marka'] ?? 'SUÇEK';
$logo  = $kapak['logo'] ?? '';
$sayfalar = $kalemler->chunk(3);
@endphp

{{-- ── KAPAK ── --}}
<div class="katalog-sayfa" style="align-items:center;justify-content:center;text-align:center;">
    <div style="width:110px;height:110px;border-radius:50%;border:3px solid #0F172A;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-bottom:24px;background:#F8FAFC;flex-shrink:0;">
        @if($logo)
        <img src="{{ $logo }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:10px;">
        @else
        <span style="font-size:40px;font-weight:700;color:#0F172A;font-family:'Inter',sans-serif;">{{ strtoupper(substr($marka, 0, 1)) }}</span>
        @endif
    </div>
    <div style="font-size:14px;letter-spacing:.28em;text-transform:uppercase;color:#B8962E;margin-bottom:18px;font-weight:600;">NÜMİZMATİK KATALOĞU</div>
    <div style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:600;color:#0F172A;letter-spacing:.01em;margin-bottom:10px;max-width:140mm;">{{ $katalog->baslik }}</div>
    @if($katalog->alt_baslik)
    <div style="font-size:13px;color:#94A3B8;letter-spacing:.04em;margin-bottom:26px;">{{ $katalog->alt_baslik }}</div>
    @endif
    <div style="width:48px;height:2px;background:#B8962E;margin-bottom:26px;"></div>
    <div style="font-size:16px;font-weight:600;color:#334155;letter-spacing:.06em;">{{ $marka }}</div>
    <div style="font-size:11px;color:#CBD5E1;margin-top:6px;letter-spacing:.04em;">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
</div>

{{-- ── ÜRÜN SAYFALARI: her sayfada 3 kalem ── --}}
@foreach($sayfalar as $sIndex => $sayfa)
<div class="katalog-sayfa">
    <div style="margin-bottom:12px;">
        <span style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:600;color:#0F172A;">{{ $katalog->baslik }}</span>
    </div>
    <div style="height:2px;background:#B8962E;margin-bottom:1px;"></div>
    <div style="height:1px;background:#0F172A;margin-bottom:4px;"></div>

    @foreach($sayfa as $kIndex => $kalem)
    <div class="kart">
        @php $arkaGorsel = $kalem->gorseller[0] ?? null; @endphp
        <div style="display:flex;gap:10px;">
            <div style="flex:1;min-width:0;">
                <div style="border:1px solid #E2E8F0;border-radius:4px;overflow:hidden;background:#F8FAFC;display:flex;align-items:center;justify-content:center;height:150px;">
                    @if($kalem->gorsel)
                    <img src="{{ asset('storage/' . $kalem->gorsel) }}" alt="{{ $kalem->ad }}" style="max-width:100%;max-height:100%;object-fit:contain;padding:8px;">
                    @else
                    <div style="text-align:center;color:#CBD5E1;font-size:11px;padding:16px;">
                        <i class="ti ti-photo" style="font-size:22px;display:block;margin-bottom:4px;"></i>Görsel yok
                    </div>
                    @endif
                </div>
                <div style="text-align:center;font-size:8px;letter-spacing:.14em;text-transform:uppercase;color:#94A3B8;margin-top:4px;">Ön Yüz</div>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="border:1px solid #E2E8F0;border-radius:4px;overflow:hidden;background:#F8FAFC;display:flex;align-items:center;justify-content:center;height:150px;">
                    @if($arkaGorsel)
                    <img src="{{ asset('storage/' . $arkaGorsel) }}" alt="{{ $kalem->ad }}" style="max-width:100%;max-height:100%;object-fit:contain;padding:8px;">
                    @else
                    <div style="text-align:center;color:#CBD5E1;font-size:11px;padding:16px;">
                        <i class="ti ti-photo" style="font-size:22px;display:block;margin-bottom:4px;"></i>Görsel yok
                    </div>
                    @endif
                </div>
                <div style="text-align:center;font-size:8px;letter-spacing:.14em;text-transform:uppercase;color:#94A3B8;margin-top:4px;">Arka Yüz</div>
            </div>
        </div>
        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;">
            <div style="min-width:0;">
                <span style="font-family:'Cormorant Garamond',serif;font-size:13px;color:#B8962E;font-weight:600;margin-right:6px;">{{ ($sIndex * 3) + $kIndex + 1 }}.</span>
                <span style="font-family:'Cormorant Garamond',serif;font-size:19px;font-weight:600;color:#0F172A;">{{ $kalem->ad }}</span>
            </div>
            @if($kalem->fiyat)
            <div style="font-size:16px;color:#0F172A;font-weight:700;flex-shrink:0;white-space:nowrap;">{{ number_format($kalem->fiyat, 2, ',', '.') }} ₺</div>
            @endif
        </div>
        <div style="display:flex;gap:20px;font-size:11px;">
            @if($kalem->ulke)
            <span><span style="color:#94A3B8;">Ülke </span><span style="color:#334155;font-weight:500;">{{ $kalem->ulke }}</span></span>
            @endif
            @if($kalem->stok_kodu)
            <span><span style="color:#94A3B8;">Stok Kodu </span><span style="color:#334155;font-weight:500;font-family:monospace;">{{ $kalem->stok_kodu }}</span></span>
            @endif
        </div>
    </div>
    @endforeach

    <div style="flex:1;"></div>
    <div style="margin:10px -16mm -16mm;">
        <div style="height:2px;background:#B8962E;"></div>
        <div style="height:6px;background:#0F172A;"></div>
    </div>
</div>
@endforeach

</body>
</html>
