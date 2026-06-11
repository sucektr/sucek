@extends('layouts.app')

@section('title', $proje->baslik . ' — SUÇEK')
@section('meta-description', $proje->aciklama ?? ($proje->baslik . ' projesi.'))

@section('content')

@php
  $katLabel = match($proje->kategori) {
    'mimarlik' => 'Mimarlık',
    'insaat'   => 'İnşaat',
    default    => ucfirst($proje->kategori ?? ''),
  };
@endphp

{{-- ─── Breadcrumb ─────────────────────────────────────────────────────── --}}
<div class="border-b border-[#E2E8F0] bg-[#FAFAFA]">
  <div class="section py-3">
    <nav class="flex items-center gap-2 text-[12px] text-[#94A3B8]" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="hover:text-[#0F172A] transition-colors">Ana Sayfa</a>
      <i class="ti ti-chevron-right text-[10px]"></i>
      <a href="{{ route('projeler.index') }}" class="hover:text-[#0F172A] transition-colors">Projeler</a>
      <i class="ti ti-chevron-right text-[10px]"></i>
      <span class="text-[#0F172A] font-medium truncate max-w-[200px]">{{ $proje->baslik }}</span>
    </nav>
  </div>
</div>

{{-- ─── Kapak Görseli ──────────────────────────────────────────────────── --}}
@if($proje->kapak_gorsel)
<div class="relative w-full aspect-[16/7] max-h-[500px] overflow-hidden bg-[#0F172A]">
  <img src="{{ asset('storage/' . $proje->kapak_gorsel) }}"
       alt="{{ $proje->baslik }}"
       class="w-full h-full object-cover opacity-80">
  <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.70)] to-transparent"></div>
  <div class="absolute bottom-0 left-0 right-0 px-6 lg:px-12 pb-10 pt-20">
    <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">{{ $katLabel }}</p>
    <h1 class="text-[32px] lg:text-[48px] font-bold text-white leading-tight tracking-tight">{{ $proje->baslik }}</h1>
  </div>
</div>
@endif

{{-- ─── İçerik ─────────────────────────────────────────────────────────── --}}
<div class="section">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

    {{-- Sol: Açıklama + Görseller --}}
    <div class="lg:col-span-2 space-y-8">

      @if(!$proje->kapak_gorsel)
      <div>
        <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">{{ $katLabel }}</p>
        <h1 class="text-[32px] lg:text-[40px] font-bold text-[#0F172A] leading-tight tracking-tight">{{ $proje->baslik }}</h1>
      </div>
      @endif

      @if($proje->aciklama)
      <div class="prose prose-slate max-w-none">
        <p class="text-[15px] text-[#374151] leading-[1.8]">{{ $proje->aciklama }}</p>
      </div>
      @endif

      {{-- Ek Görseller --}}
      @if(!empty($proje->gorseller) && count($proje->gorseller) > 0)
      <div>
        <h2 class="text-[16px] font-semibold text-[#0F172A] mb-4 tracking-tight">Proje Görselleri</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3"
             x-data="{ lightbox: null }"
             @keydown.escape.window="lightbox = null">

          @foreach($proje->gorseller as $i => $gorsel)
          <button @click="lightbox = {{ $i }}"
                  class="relative aspect-[4/3] rounded-xl overflow-hidden bg-[#F1F5F9] cursor-pointer group">
            <img src="{{ asset('storage/' . $gorsel) }}"
                 alt="{{ $proje->baslik }} görsel {{ $i + 1 }}"
                 loading="lazy"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-[#0F172A]/0 group-hover:bg-[#0F172A]/20 transition-all duration-300 flex items-center justify-center">
              <i class="ti ti-zoom-in text-2xl text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
            </div>
          </button>
          @endforeach

          {{-- Lightbox --}}
          <div x-show="lightbox !== null"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100"
               class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
               @click.self="lightbox = null"
               style="display:none;">
            <button @click="lightbox = null"
                    class="absolute top-4 right-4 text-white/70 hover:text-white text-3xl cursor-pointer" aria-label="Kapat">
              <i class="ti ti-x"></i>
            </button>
            <button @click="lightbox = (lightbox - 1 + {{ count($proje->gorseller) }}) % {{ count($proje->gorseller) }}"
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-3xl cursor-pointer" aria-label="Önceki">
              <i class="ti ti-chevron-left"></i>
            </button>
            <button @click="lightbox = (lightbox + 1) % {{ count($proje->gorseller) }}"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white text-3xl cursor-pointer" aria-label="Sonraki">
              <i class="ti ti-chevron-right"></i>
            </button>
            @foreach($proje->gorseller as $i => $gorsel)
            <img x-show="lightbox === {{ $i }}"
                 src="{{ asset('storage/' . $gorsel) }}"
                 alt="{{ $proje->baslik }} görsel {{ $i + 1 }}"
                 class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                 style="display:none;">
            @endforeach
          </div>

        </div>
      </div>
      @endif

    </div>

    {{-- Sağ: Bilgi Kartı --}}
    <aside class="space-y-4">

      <div class="bg-white rounded-xl border border-[#E2E8F0] p-6 sticky top-24">
        <h3 class="text-[11px] font-semibold tracking-[2px] uppercase text-[#94A3B8] mb-5">Proje Bilgileri</h3>

        <dl class="space-y-4">
          @if($proje->kategori)
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-[8px] bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center shrink-0">
              <i class="ti ti-category text-[#64748B] text-sm"></i>
            </div>
            <div>
              <dt class="text-[10px] font-medium text-[#94A3B8] uppercase tracking-[1px] mb-0.5">Kategori</dt>
              <dd class="text-[13px] font-medium text-[#0F172A]">{{ $katLabel }}</dd>
            </div>
          </div>
          @endif

          @if($proje->alt_kategori)
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-[8px] bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center shrink-0">
              <i class="ti ti-tag text-[#64748B] text-sm"></i>
            </div>
            <div>
              <dt class="text-[10px] font-medium text-[#94A3B8] uppercase tracking-[1px] mb-0.5">Alt Kategori</dt>
              <dd class="text-[13px] font-medium text-[#0F172A]">{{ $proje->alt_kategori }}</dd>
            </div>
          </div>
          @endif

          @if($proje->konum)
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-[8px] bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center shrink-0">
              <i class="ti ti-map-pin text-[#64748B] text-sm"></i>
            </div>
            <div>
              <dt class="text-[10px] font-medium text-[#94A3B8] uppercase tracking-[1px] mb-0.5">Konum</dt>
              <dd class="text-[13px] font-medium text-[#0F172A]">{{ $proje->konum }}</dd>
            </div>
          </div>
          @endif

          @if($proje->yil)
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-[8px] bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center shrink-0">
              <i class="ti ti-calendar text-[#64748B] text-sm"></i>
            </div>
            <div>
              <dt class="text-[10px] font-medium text-[#94A3B8] uppercase tracking-[1px] mb-0.5">Yıl</dt>
              <dd class="text-[13px] font-medium text-[#0F172A]">{{ $proje->yil }}</dd>
            </div>
          </div>
          @endif

          @if(!empty($proje->detaylar))
          @foreach($proje->detaylar as $detay)
          @if(!empty($detay['anahtar']) && !empty($detay['deger']))
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-[8px] bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center shrink-0">
              <i class="ti ti-info-circle text-[#64748B] text-sm"></i>
            </div>
            <div>
              <dt class="text-[10px] font-medium text-[#94A3B8] uppercase tracking-[1px] mb-0.5">{{ $detay['anahtar'] }}</dt>
              <dd class="text-[13px] font-medium text-[#0F172A]">{{ $detay['deger'] }}</dd>
            </div>
          </div>
          @endif
          @endforeach
          @endif
        </dl>

        <div class="mt-6 pt-5 border-t border-[#E2E8F0]">
          <a href="{{ route('iletisim.index') }}"
             class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-[#0F172A] text-white text-[11px] font-semibold tracking-[1.5px] uppercase rounded-[10px] hover:bg-[#2a2a2a] transition-colors min-h-[44px] cursor-pointer">
            <i class="ti ti-mail text-sm"></i> Benzer Proje Talep Et
          </a>
        </div>
      </div>

    </aside>
  </div>
</div>

{{-- ─── Diğer Projeler ─────────────────────────────────────────────────── --}}
@if($diger->count() > 0)
<section class="section border-t border-[#E2E8F0]" aria-labelledby="diger-projeler">
  <div class="flex items-end justify-between mb-7">
    <div>
      <p class="section-label mb-2">{{ strtoupper($katLabel) }}</p>
      <h2 id="diger-projeler" class="text-[22px] font-bold text-[#0F172A] tracking-tight">Diğer Projeler</h2>
    </div>
    <a href="{{ route('projeler.index') }}"
       class="hidden sm:flex items-center gap-1.5 text-sm font-medium text-[#64748B] hover:text-[#CC2200] transition-colors">
      Tümünü Gör <i class="ti ti-arrow-right text-sm"></i>
    </a>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($diger as $p)
    <article class="group bg-white rounded-xl border border-[#E2E8F0] overflow-hidden hover:shadow-[0_8px_32px_rgba(15,23,42,0.10)] hover:-translate-y-1 transition-all duration-300">
      <a href="{{ route('projeler.show', $p->slug) }}" class="block">
        <div class="relative aspect-[4/3] overflow-hidden bg-[#F1F5F9]">
          @if($p->kapak_gorsel)
          <img src="{{ asset('storage/' . $p->kapak_gorsel) }}"
               alt="{{ $p->baslik }}"
               loading="lazy"
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
          @else
          <div class="w-full h-full flex items-center justify-center">
            <i class="ti ti-building-arch text-4xl text-[#D0D0D0]"></i>
          </div>
          @endif
        </div>
        <div class="p-5">
          <p class="text-[10px] font-semibold tracking-[1.5px] uppercase text-[#CC2200] mb-1.5">
            {{ $p->yil ?? $katLabel }}
          </p>
          <h3 class="text-[16px] font-semibold text-[#0F172A] leading-snug group-hover:text-[#CC2200] transition-colors">
            {{ $p->baslik }}
          </h3>
          @if($p->konum)
          <p class="text-[12px] text-[#94A3B8] mt-1.5 flex items-center gap-1">
            <i class="ti ti-map-pin text-xs"></i> {{ $p->konum }}
          </p>
          @endif
        </div>
      </a>
    </article>
    @endforeach
  </div>
</section>
@endif

@endsection
