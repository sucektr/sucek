<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $katalog->baslik }} — SUÇEK Katalog</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Inter',system-ui,sans-serif;background:#EBEBEB;}
.katalog-sayfa{
    width:210mm;height:297mm;background:white;padding:18mm 20mm;
    margin:24px auto;box-shadow:0 4px 24px rgba(0,0,0,0.1);
    display:flex;flex-direction:column;overflow:hidden;
}
.bar-actions{
    position:fixed;bottom:24px;right:24px;z-index:100;display:flex;gap:10px;
}
.btn-yazdir{
    background:#0F172A;color:white;padding:10px 20px;border-radius:8px;
    border:none;cursor:pointer;font-family:inherit;font-size:13px;font-weight:500;
    display:flex;align-items:center;gap:7px;
}
.btn-yazdir:hover{background:#333;}
.btn-geri{
    background:white;color:#0F172A;padding:10px 20px;border-radius:8px;
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
        page-break-after:avoid;break-after:avoid;
    }
    .katalog-sayfa + .katalog-sayfa{
        page-break-before:always;break-before:page;
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
$stiller   = $kapak['stiller'] ?? [];
$vurguRenk = $stiller['vurgu_renk']   ?? '#B8962E';
$kurumFont = ($stiller['kurum_font']  ?? 'inter') === 'cormorant' ? "'Cormorant Garamond',serif" : "'Inter',sans-serif";
$kurumBoyut = ($stiller['kurum_boyut'] ?? 130) . 'px';
$baslikBoyut = ($stiller['baslik_boyut'] ?? 19) . 'px';
$tocAdi    = fn($urun) => $icindekiler[$urun->id] ?? $icindekiler[(string)$urun->id] ?? $urun->ad;
@endphp

{{-- ── KAPAK ── --}}
<div class="katalog-sayfa">

    {{-- Üst dekoratif bant --}}
    <div style="margin:-18mm -20mm 0;height:5px;background:#0F172A;"></div>
    <div style="margin:0 -20mm 0;height:3px;background:{{ $vurguRenk }};"></div>
    <div style="height:22px;"></div>

    {{-- Üst beyaz alan --}}
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;text-align:center;padding:20mm 6mm 8mm;">

        {{-- Logo dairesi --}}
        <div style="width:110px;height:110px;border-radius:50%;border:3px solid #0F172A;overflow:hidden;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;background:#F8FAFC;flex-shrink:0;">
            @if($logo)
            <img src="{{ $logo }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:10px;">
            @else
            <span style="font-size:40px;font-weight:700;color:#0F172A;font-family:'Inter',sans-serif;">{{ strtoupper(substr($kapak['marka'] ?? 'S', 0, 1)) }}</span>
            @endif
        </div>

        {{-- Etiket --}}
        <div style="font-size:16px;letter-spacing:.28em;text-transform:uppercase;color:{{ $vurguRenk }};margin-bottom:14px;font-family:'Inter',sans-serif;font-weight:600;">ÜRÜN KATALOĞU</div>

        {{-- Kurum adı --}}
        @if(!empty($kapak['kurum']))
        <div style="font-family:{{ $kurumFont }};font-size:{{ $kurumBoyut }};font-weight:700;color:#0F172A;letter-spacing:-.02em;line-height:1.0;margin-bottom:16px;">{{ $kapak['kurum'] }}</div>
        @else
        <div style="font-family:{{ $kurumFont }};font-size:{{ $kurumBoyut }};font-weight:700;color:#CBD5E1;letter-spacing:-.02em;line-height:1.0;margin-bottom:16px;">— Kurum Adı —</div>
        @endif

        <div style="width:48px;height:2px;background:{{ $vurguRenk }};margin-bottom:16px;"></div>

        {{-- İşin adı --}}
        <div style="font-size:{{ $baslikBoyut }};font-weight:500;color:#334155;letter-spacing:.02em;margin-bottom:6px;font-family:'Inter',sans-serif;">{{ $katalog->baslik }}</div>

        @if($katalog->alt_baslik)
        <div style="font-size:11px;color:#94A3B8;letter-spacing:.06em;text-transform:uppercase;margin-bottom:16px;">{{ $katalog->alt_baslik }}</div>
        @endif

        <div style="flex:1;min-height:0;"></div>

        {{-- İhale bilgileri --}}
        @if(!empty($kapak['ihale_no']) || !empty($kapak['ihale_tarih']) || !empty($kapak['ihale_yeri']) || !empty($kapak['ihale_saat']) || !empty($ekstralar))
        <div style="width:100%;border:1.5px solid #E2E8F0;border-radius:8px;overflow:hidden;text-align:left;">
            <div style="background:#0F172A;padding:9px 16px;">
                <span style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,0.5);">İhale Bilgileri</span>
            </div>
            @if(!empty($kapak['ihale_no']))
            <div style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
                <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Kayıt No</span>
                <span style="flex:1;text-align:right;font-size:16px;font-weight:700;color:#0F172A;font-family:'Inter',sans-serif;">{{ $kapak['ihale_no'] }}</span>
            </div>
            @endif
            @if(!empty($kapak['ihale_tarih']))
            <div style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
                <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Tarihi</span>
                <span style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;">{{ \Carbon\Carbon::parse($kapak['ihale_tarih'])->format('d.m.Y') }}</span>
            </div>
            @endif
            @if(!empty($kapak['ihale_yeri']))
            <div style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
                <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Yeri</span>
                <span style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;">{{ $kapak['ihale_yeri'] }}</span>
            </div>
            @endif
            @if(!empty($kapak['ihale_saat']))
            <div style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
                <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Saati</span>
                <span style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;">{{ $kapak['ihale_saat'] }}</span>
            </div>
            @endif
            @foreach($ekstralar as $ekstra)
            @if(!empty($ekstra['etiket']) || !empty($ekstra['deger']))
            <div style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
                <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">{{ $ekstra['etiket'] ?? '' }}</span>
                <span style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;">{{ $ekstra['deger'] ?? '' }}</span>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        <div style="flex:1;min-height:0;"></div>
    </div>

    {{-- Ayırıcı altın çizgi --}}
    <div style="height:3px;background:{{ $vurguRenk }};margin:0 -20mm;"></div>

    {{-- Alt koyu bölüm: firma bilgisi --}}
    <div style="margin:0 -20mm -18mm;background:#0F172A;padding:14mm 24mm 16mm;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;">
        <div style="position:absolute;bottom:-14mm;right:-10mm;width:64mm;height:64mm;border-radius:50%;background:rgba(184,150,46,0.06);pointer-events:none;"></div>
        <div style="font-size:9px;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:10px;font-family:'Inter',sans-serif;font-weight:500;">Hazırlayan Firma</div>
        <div style="font-size:28px;font-weight:700;color:white;letter-spacing:.02em;line-height:1.1;font-family:'Inter',sans-serif;">{{ $kapak['marka'] ?? 'SUÇEK' }}</div>
        @if(!empty($kapak['marka2']))
        <div style="font-size:14px;font-weight:400;color:rgba(255,255,255,0.48);margin-top:6px;letter-spacing:.04em;font-family:'Inter',sans-serif;">{{ $kapak['marka2'] }}</div>
        @endif
    </div>

</div>

{{-- ── İÇİNDEKİLER ── --}}
<div class="katalog-sayfa">
    <div style="margin-bottom:5px;">
        <div style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:600;color:#0F172A;letter-spacing:.03em;">İçindekiler</div>
    </div>
    <div style="height:3px;background:#B8962E;margin-bottom:3px;"></div>
    <div style="height:1px;background:#0F172A;margin-bottom:24px;"></div>

    <div style="flex:1;">
        @foreach($urunler as $i => $urun)
        <div style="display:flex;align-items:center;padding:9px 0;border-bottom:1px solid #F1F5F9;{{ $i === 0 ? 'border-top:1px solid #F1F5F9;' : '' }}">
            <span style="min-width:26px;font-family:'Cormorant Garamond',serif;font-size:14px;color:#B8962E;font-weight:600;flex-shrink:0;">{{ $i + 1 }}.</span>
            <span style="flex:1;font-size:13px;color:#0F172A;line-height:1.4;padding:0 6px;">{{ $tocAdi($urun) }}</span>
            <span style="font-size:10px;color:#B8962E;font-weight:600;margin-left:10px;flex-shrink:0;font-family:'Cormorant Garamond',serif;min-width:20px;text-align:right;">{{ $i + 3 }}</span>
        </div>
        @endforeach
    </div>

    <div style="margin:0 -20mm -18mm;">
        <div style="height:3px;background:#B8962E;"></div>
        <div style="height:8px;background:#0F172A;"></div>
    </div>
</div>

{{-- ── ÜRÜN SAYFALARI ── --}}
@foreach($urunler as $i => $urun)
<div class="katalog-sayfa">

    {{-- Sayfa başlığı --}}
    <div style="margin-bottom:5px;">
        <span style="font-family:'Cormorant Garamond',serif;font-size:15px;color:#B8962E;font-weight:600;margin-right:7px;">{{ $i + 1 }}.</span>
        <span style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0F172A;">{{ $urun->ad }}</span>
    </div>
    <div style="height:3px;background:#B8962E;margin-bottom:3px;"></div>
    <div style="height:1px;background:#0F172A;margin-bottom:18px;"></div>

    {{-- 2 kolon --}}
    <div style="display:flex;flex:1;gap:18px;align-items:flex-start;">

        {{-- Sol: Görsel --}}
        <div style="width:46%;flex-shrink:0;">
            <div style="border:1px solid #E2E8F0;border-radius:4px;overflow:hidden;background:#F8FAFC;display:flex;align-items:center;justify-content:center;min-height:200px;max-height:280px;">
                @if($urun->gorsel)
                <img src="{{ asset('storage/' . $urun->gorsel) }}" alt="{{ $urun->ad }}"
                     style="max-width:100%;max-height:280px;object-fit:contain;padding:12px;">
                @else
                <div style="text-align:center;color:#CBD5E1;font-size:12px;padding:28px;">
                    <i class="ti ti-photo" style="font-size:28px;display:block;margin-bottom:6px;"></i>Görsel yüklenmemiş
                </div>
                @endif
            </div>
            {{-- Meta bilgi --}}
            <div style="margin-top:10px;padding:7px 0;border-top:1px solid #F1F5F9;">
                @if($urun->stok_kodu)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:2px 0;margin-top:3px;">
                    <span style="font-size:9px;color:#94A3B8;text-transform:uppercase;letter-spacing:.08em;">Stok Kodu</span>
                    <span style="font-size:10px;color:#334155;font-weight:600;font-family:monospace;">{{ $urun->stok_kodu }}</span>
                </div>
                @endif
                @if($urun->fiyat)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:2px 0;margin-top:3px;">
                    <span style="font-size:9px;color:#94A3B8;text-transform:uppercase;letter-spacing:.08em;">Birim Fiyat</span>
                    <span style="font-size:11px;color:#0F172A;font-weight:700;">{{ number_format($urun->fiyat, 2, ',', '.') }} ₺</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Sağ: Teknik özellikler --}}
        <div style="flex:1;min-width:0;">
            <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#B8962E;margin-bottom:8px;font-weight:600;padding-bottom:6px;border-bottom:1.5px solid rgba(184,150,46,0.25);">Teknik Özellikler</div>
            @if(trim(strip_tags($urun->aciklama ?? '')))
            <div style="font-size:12px;color:#334155;line-height:1.85;">{!! $urun->aciklama !!}</div>
            @else
            <div style="font-size:11px;color:#CBD5E1;font-style:italic;">Açıklama girilmemiş</div>
            @endif
        </div>
    </div>

    <div style="margin:0 -20mm -18mm;">
        <div style="height:3px;background:#B8962E;"></div>
        <div style="height:8px;background:#0F172A;"></div>
    </div>

</div>
@endforeach

</body>
</html>


