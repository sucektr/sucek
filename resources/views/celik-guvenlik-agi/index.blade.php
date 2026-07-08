@extends('layouts.app')

@section('title', 'Çelik Güvenlik Ağı — MESH Yetkili Bayii | SUÇEK')
@section('meta-description', 'SUÇEK, MESH Çelik Ağ yetkili bayii olarak inşaat güvenliğinden balkon korumaya kadar her ihtiyaca özel çelik güvenlik ağı çözümleri sunmaktadır.')

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden" style="min-height:480px;">
  {{-- Arka plan: çelik ağ dokusu --}}
  <div class="absolute inset-0 bg-cover bg-center"
       style="background-image:url('{{ icerik_gorsel('celik-guvenlik-agi','hero_gorsel','/images/mesh/hizmet-4.webp') }}');"></div>
  {{-- Karartma --}}
  <div class="absolute inset-0 bg-gradient-to-r from-[rgba(10,15,30,0.94)] via-[rgba(10,15,30,0.82)] to-[rgba(10,15,30,0.55)]"></div>
  {{-- Kırmızı şerit --}}
  <div class="absolute top-0 left-0 right-0 h-[3px] bg-[#CC2200]"></div>

  <div class="relative max-w-[1280px] mx-auto px-4 lg:px-6 py-16 lg:py-24">

    {{-- MESH logosu --}}
    <div class="flex items-center gap-3 mb-8">
      <img src="{{ icerik_gorsel('celik-guvenlik-agi','logo','/images/mesh/logo-color.png') }}" alt="MESH Çelik Ağ" class="h-10 w-auto brightness-0 invert opacity-90">
      <div class="w-px h-6 bg-white/20"></div>
      <span class="text-[11px] font-semibold tracking-[0.14em] uppercase text-[#CC2200]">Yetkili Bayii</span>
    </div>

    <h1 class="text-[36px] lg:text-[54px] font-bold text-white leading-tight tracking-tight mb-4 max-w-2xl">
      Çelik<br><span class="text-[#CC2200]">Güvenlik Ağı</span>
    </h1>
    <p class="text-[15px] lg:text-[17px] text-[#94A3B8] leading-relaxed max-w-xl mb-8">
      {{ icerik('celik-guvenlik-agi', 'hero_metin', 'İnşaat güvenliğinden balkon korumaya, endüstriyel tesislerden konut projelerine kadar her ihtiyaca özel çelik güvenlik ağı çözümleri sunuyoruz.') }}
    </p>

    <div class="flex flex-col sm:flex-row gap-3">
      <a href="{{ route('iletisim.index') }}"
         class="inline-flex items-center justify-center gap-2 bg-[#CC2200] hover:bg-[#a31b00] text-white text-[13px] font-semibold tracking-wide px-6 py-3 rounded-[10px] transition-colors">
        <i class="ti ti-phone text-sm"></i> Teklif Al
      </a>
      @if($urunler->count() > 0)
      <a href="#urunler"
         class="inline-flex items-center justify-center gap-2 bg-white/8 hover:bg-white/12 border border-white/15 text-white text-[13px] font-semibold px-6 py-3 rounded-[10px] transition-colors">
        <i class="ti ti-layout-grid text-sm"></i> Ürünleri İncele
      </a>
      @endif
    </div>

    {{-- Stat sütunları --}}
    <div class="mt-12 flex flex-wrap gap-6 lg:gap-10">
      @foreach([
        ['rakam'=>'20+', 'metin'=>'Yıllık Tecrübe'],
        ['rakam'=>'500+', 'metin'=>'Tamamlanan Proje'],
        ['rakam'=>'TSE', 'metin'=>'Sertifikalı Üretim'],
      ] as $s)
      <div>
        <p class="text-[28px] lg:text-[34px] font-bold text-white leading-none">{{ $s['rakam'] }}</p>
        <p class="text-[12px] text-[#64748B] mt-1">{{ $s['metin'] }}</p>
      </div>
      @endforeach
    </div>

  </div>
</section>

{{-- ─── Hizmet Görselleri (3'lü grid) ─────────────────────────────────────── --}}
<section class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
  <div class="max-w-[1280px] mx-auto px-4 lg:px-6 py-12 lg:py-16">

    <div class="text-center mb-10">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">UYGULAMA ALANLARI</p>
      <h2 class="text-[24px] lg:text-[30px] font-bold text-[#0F172A] tracking-tight">Nerelerde Kullanılır?</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      @foreach([
        ['gorsel'=>icerik_gorsel('celik-guvenlik-agi','hizmet_1_gorsel','/images/mesh/hizmet-1.webp'), 'baslik'=>'İnşaat Güvenliği',  'metin'=>icerik('celik-guvenlik-agi','hizmet_1','Bina inşaatlarında düşme ve yaralanmalara karşı EN 1263 standardına uygun güvenlik ağı sistemleri.')],
        ['gorsel'=>icerik_gorsel('celik-guvenlik-agi','hizmet_2_gorsel','/images/mesh/hizmet-2.webp'), 'baslik'=>'Balkon & Teras',    'metin'=>icerik('celik-guvenlik-agi','hizmet_2','Konut ve ticari yapılarda balkon, teras ve boşluklara özel koruyucu çelik ağ uygulamaları.')],
        ['gorsel'=>icerik_gorsel('celik-guvenlik-agi','hizmet_3_gorsel','/images/mesh/hizmet-3.webp'), 'baslik'=>'Endüstriyel Alan',  'metin'=>icerik('celik-guvenlik-agi','hizmet_3','Fabrika, depo ve endüstriyel tesislerde makine koruma, bölme ve güvenlik ağı çözümleri.')],
      ] as $h)
      <div class="bg-white border border-[#E2E8F0] rounded-xl overflow-hidden hover:shadow-[0_4px_20px_rgba(15,23,42,0.08)] transition-shadow">
        <div class="aspect-[4/3] overflow-hidden">
          <img src="{{ $h['gorsel'] }}" alt="{{ $h['baslik'] }}"
               class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
               loading="lazy">
        </div>
        <div class="p-5">
          <h3 class="text-[15px] font-bold text-[#0F172A] mb-2">{{ $h['baslik'] }}</h3>
          <p class="text-[13px] text-[#64748B] leading-relaxed">{{ $h['metin'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ─── Özellikler (ikonlu) ───────────────────────────────────────────────── --}}
<section class="bg-white border-b border-[#E2E8F0]">
  <div class="max-w-[1280px] mx-auto px-4 lg:px-6 py-12 lg:py-14">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      @foreach([
        ['icon'=>'ti-shield-check',  'baslik'=>'Yüksek Güvenlik',   'metin'=>'EN ISO 1263 standartları'],
        ['icon'=>'ti-ruler-measure', 'baslik'=>'Özel Ölçü',         'metin'=>'Her projeye uygun boyut'],
        ['icon'=>'ti-certificate-2', 'baslik'=>'Sertifikalı Ürün',  'metin'=>'TSE belgeli üretim'],
        ['icon'=>'ti-tools',         'baslik'=>'Teknik Destek',     'metin'=>'Montaj & uygulama rehberliği'],
      ] as $o)
      <div class="flex items-start gap-3 p-5 rounded-xl border border-[#E2E8F0] bg-[#F8FAFC]">
        <div class="w-9 h-9 rounded-lg bg-[#FEF2F0] flex items-center justify-center shrink-0">
          <i class="ti {{ $o['icon'] }} text-[#CC2200] text-lg"></i>
        </div>
        <div>
          <p class="text-[13px] font-bold text-[#0F172A] mb-0.5">{{ $o['baslik'] }}</p>
          <p class="text-[11px] text-[#64748B]">{{ $o['metin'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ─── Referans Proje Görselleri ─────────────────────────────────────────── --}}
<section class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
  <div class="max-w-[1280px] mx-auto px-4 lg:px-6 py-12 lg:py-16">
    <div class="flex items-end justify-between mb-8">
      <div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">REFERANSLAR</p>
        <h2 class="text-[22px] lg:text-[28px] font-bold text-[#0F172A] tracking-tight">Tamamlanan Projeler</h2>
      </div>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
      @foreach([
        icerik_gorsel('celik-guvenlik-agi','proje_1_gorsel','/images/mesh/proje-1.jpg'),
        icerik_gorsel('celik-guvenlik-agi','proje_2_gorsel','/images/mesh/proje-2.jpg'),
        icerik_gorsel('celik-guvenlik-agi','proje_3_gorsel','/images/mesh/hero-2.jpg'),
      ] as $gorsel)
      <div class="aspect-[4/3] rounded-xl overflow-hidden bg-[#E2E8F0]">
        <img src="{{ $gorsel }}" alt="Referans Proje"
             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-zoom-in"
             loading="lazy">
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ─── Ürün Listesi ────────────────────────────────────────────────────────── --}}
<section id="urunler" class="bg-white border-b border-[#E2E8F0]">
  <div class="max-w-[1280px] mx-auto px-4 lg:px-6 py-12 lg:py-16">

    <div class="flex items-end justify-between mb-8">
      <div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">ÜRÜN KATALOĞU</p>
        <h2 class="text-[22px] lg:text-[28px] font-bold text-[#0F172A] tracking-tight">Güvenlik Ağı Ürünleri</h2>
      </div>
      @if($urunler->count() > 0)
      <span class="text-[13px] text-[#94A3B8]">{{ $urunler->count() }} ürün</span>
      @endif
    </div>

    @if($urunler->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
      @foreach($urunler as $urun)
      <a href="{{ route('magaza.urun', $urun->slug) }}"
         class="group bg-white border border-[#E2E8F0] rounded-xl overflow-hidden hover:shadow-[0_8px_24px_rgba(15,23,42,0.10)] hover:-translate-y-0.5 transition-all duration-200">
        <div class="aspect-square bg-[#F8FAFC] relative overflow-hidden">
          @if($urun->gorsel)
          <img src="{{ asset('storage/'.$urun->gorsel) }}" alt="{{ $urun->ad }}"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
               loading="lazy">
          @else
          <div class="w-full h-full flex items-center justify-center">
            <i class="ti ti-shield text-[#C0C0C0] text-4xl"></i>
          </div>
          @endif
          @if($urun->indirim_yuzdesi)
          <span class="absolute top-2 left-2 bg-[#CC2200] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">-%{{ $urun->indirim_yuzdesi }}</span>
          @endif
        </div>
        <div class="p-4">
          <h3 class="text-[13px] font-semibold text-[#0F172A] leading-snug mb-2 line-clamp-2">{{ $urun->ad }}</h3>
          @if($urun->stok_kodu)
          <p class="text-[10px] text-[#94A3B8] mb-2 font-mono">{{ $urun->stok_kodu }}</p>
          @endif
          <div class="flex items-baseline gap-2">
            <span class="text-[15px] font-bold text-[#CC2200]">{{ number_format($urun->fiyat, 0, ',', '.') }} ₺</span>
            @if($urun->eski_fiyat)
            <span class="text-[12px] text-[#94A3B8] line-through">{{ number_format($urun->eski_fiyat, 0, ',', '.') }} ₺</span>
            @endif
          </div>
        </div>
      </a>
      @endforeach
    </div>
    @else
    <div class="text-center py-16 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0]">
      <div class="w-16 h-16 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm flex items-center justify-center mx-auto mb-4">
        <i class="ti ti-shield text-[#CC2200] text-2xl"></i>
      </div>
      <h3 class="text-[16px] font-bold text-[#0F172A] mb-2">Ürünler Yakında</h3>
      <p class="text-[13px] text-[#64748B] max-w-sm mx-auto mb-6">Çelik güvenlik ağı ürün kataloğumuz hazırlanmaktadır. Fiyat ve bilgi almak için bizimle iletişime geçin.</p>
      <a href="{{ route('iletisim.index') }}"
         class="inline-flex items-center gap-2 bg-[#CC2200] hover:bg-[#a31b00] text-white text-[12px] font-semibold px-5 py-2.5 rounded-[8px] transition-colors">
        <i class="ti ti-phone text-sm"></i> İletişime Geç
      </a>
    </div>
    @endif

  </div>
</section>

{{-- ─── CTA Bandı ──────────────────────────────────────────────────────────── --}}
<section class="bg-[#0F172A]">
  <div class="max-w-[1280px] mx-auto px-4 lg:px-6 py-10 lg:py-14 flex flex-col lg:flex-row items-center justify-between gap-6">
    <div class="flex items-center gap-5">
      <img src="{{ icerik_gorsel('celik-guvenlik-agi','logo','/images/mesh/logo-color.png') }}" alt="MESH Çelik Ağ" class="h-8 w-auto brightness-0 invert opacity-60 shrink-0">
      <div>
        <h2 class="text-[18px] lg:text-[22px] font-bold text-white mb-1">Projeniz için teklif alın</h2>
        <p class="text-[13px] text-[#64748B]">Alanınızı ve ihtiyacınızı belirtin, size en uygun çözümü sunalım.</p>
      </div>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
      <a href="{{ route('iletisim.index') }}"
         class="inline-flex items-center justify-center gap-2 bg-[#CC2200] hover:bg-[#a31b00] text-white text-[13px] font-semibold px-6 py-3 rounded-[10px] transition-colors">
        <i class="ti ti-mail text-sm"></i> Teklif Formu
      </a>
      <a href="tel:{{ icerik('site','telefon','') }}"
         class="inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-[13px] font-semibold px-6 py-3 rounded-[10px] transition-colors">
        <i class="ti ti-phone text-sm"></i> Hemen Ara
      </a>
    </div>
  </div>
</section>

@endsection
