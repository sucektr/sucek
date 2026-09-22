@extends('layouts.app')

@section('title', 'Hafif Çelik ve Konteyner | SUÇEK')
@section('meta-description', 'SUÇEK hafif çelik yapı ve konteyner çözümleri.')

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden" style="min-height:420px;">
  <div class="absolute inset-0 bg-cover bg-center"
       style="background-image:url('{{ icerik_gorsel('hafif-celik-konteyner','hero_gorsel','https://images.unsplash.com/photo-1541976590-713941681591?w=1600&q=80') }}');"></div>
  <div class="absolute inset-0 bg-gradient-to-r from-[rgba(10,15,30,0.94)] via-[rgba(10,15,30,0.82)] to-[rgba(10,15,30,0.55)]"></div>
  <div class="absolute top-0 left-0 right-0 h-[3px] bg-[#CC2200]"></div>

  <div class="relative max-w-[1280px] mx-auto px-4 lg:px-6 py-16 lg:py-24">
    <p class="text-[11px] font-semibold tracking-[0.14em] uppercase text-[#CC2200] mb-4">SUÇEK İNŞAAT</p>
    <h1 class="text-[36px] lg:text-[54px] font-bold text-white leading-tight tracking-tight mb-4 max-w-2xl">
      Hafif Çelik<br><span class="text-[#CC2200]">ve Konteyner</span>
    </h1>
    <p class="text-[15px] lg:text-[17px] text-[#94A3B8] leading-relaxed max-w-xl mb-8">
      {{ icerik('hafif-celik-konteyner', 'hero_metin', 'Hafif çelik yapı sistemleri ve konteyner çözümleri için bizimle iletişime geçin.') }}
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
  </div>
</section>

{{-- ─── Ürün Listesi ────────────────────────────────────────────────────────── --}}
<section id="urunler" class="bg-white border-b border-[#E2E8F0]">
  <div class="max-w-[1280px] mx-auto px-4 lg:px-6 py-12 lg:py-16">

    <div class="flex items-end justify-between mb-8">
      <div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">ÜRÜN KATALOĞU</p>
        <h2 class="text-[22px] lg:text-[28px] font-bold text-[#0F172A] tracking-tight">Hafif Çelik ve Konteyner Ürünleri</h2>
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
            <i class="ti ti-building-warehouse text-[#C0C0C0] text-4xl"></i>
          </div>
          @endif
        </div>
        <div class="p-4">
          <h3 class="text-[13px] font-semibold text-[#0F172A] leading-snug mb-2 line-clamp-2">{{ $urun->ad }}</h3>
          @if($urun->fiyat)
          <span class="text-[15px] font-bold text-[#CC2200]">{{ number_format($urun->fiyat, 0, ',', '.') }} ₺</span>
          @endif
        </div>
      </a>
      @endforeach
    </div>
    @else
    <div class="text-center py-16 bg-[#F8FAFC] rounded-2xl border border-[#E2E8F0]">
      <div class="w-16 h-16 rounded-2xl bg-white border border-[#E2E8F0] shadow-sm flex items-center justify-center mx-auto mb-4">
        <i class="ti ti-building-warehouse text-[#CC2200] text-2xl"></i>
      </div>
      <h3 class="text-[16px] font-bold text-[#0F172A] mb-2">Ürünler Yakında</h3>
      <p class="text-[13px] text-[#64748B] max-w-sm mx-auto mb-6">Hafif çelik ve konteyner ürün kataloğumuz hazırlanmaktadır. Fiyat ve bilgi almak için bizimle iletişime geçin.</p>
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
    <div>
      <h2 class="text-[18px] lg:text-[22px] font-bold text-white mb-1">Projeniz için teklif alın</h2>
      <p class="text-[13px] text-[#64748B]">Alanınızı ve ihtiyacınızı belirtin, size en uygun çözümü sunalım.</p>
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
