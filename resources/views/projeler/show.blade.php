@extends('layouts.app')

@section('title', $proje->baslik . ' — SUÇEK')
@section('meta-description', Str::limit(strip_tags($proje->detaylar ?? ''), 160) ?: ($proje->baslik . ' projesi.'))

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

      @if($proje->detaylar)
      <div class="prose prose-slate max-w-none text-[15px] text-[#374151] leading-[1.8]">
        {!! $proje->detaylar !!}
      </div>
      @endif

      {{-- Ek Görseller --}}
      @if(!empty($proje->gorseller) && count($proje->gorseller) > 0)
      @php $toplamGorsel = count($proje->gorseller); @endphp
      <div x-data="{
             aktif: 0,
             toplam: {{ $toplamGorsel }},
             lightbox: null,
             onceki() { this.aktif = (this.aktif - 1 + this.toplam) % this.toplam; },
             sonraki() { this.aktif = (this.aktif + 1) % this.toplam; }
           }"
           @keydown.escape.window="lightbox = null"
           @keydown.arrow-left.window="lightbox !== null ? lightbox = (lightbox - 1 + toplam) % toplam : onceki()"
           @keydown.arrow-right.window="lightbox !== null ? lightbox = (lightbox + 1) % toplam : sonraki()">

        <div class="flex items-center justify-between mb-4">
          <h2 class="text-[16px] font-semibold text-[#0F172A] tracking-tight">Proje Görselleri</h2>
          <span class="text-[12px] text-[#94A3B8]" x-text="(aktif + 1) + ' / {{ $toplamGorsel }}'"></span>
        </div>

        {{-- Ana Slider --}}
        <div class="relative rounded-xl overflow-hidden bg-[#F1F5F9] aspect-[16/10] group">

          {{-- Görseller --}}
          @foreach($proje->gorseller as $i => $gorsel)
          <div x-show="aktif === {{ $i }}"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 scale-[1.02]"
               x-transition:enter-end="opacity-100 scale-100"
               class="absolute inset-0 cursor-zoom-in"
               @click="lightbox = {{ $i }}"
               style="{{ $i > 0 ? 'display:none;' : '' }}">
            <img src="{{ asset('storage/' . $gorsel) }}"
                 alt="{{ $proje->baslik }} görsel {{ $i + 1 }}"
                 {{ $i > 0 ? 'loading=lazy' : '' }}
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-[#0F172A]/0 hover:bg-[#0F172A]/15 transition-all duration-300 flex items-center justify-center">
              <i class="ti ti-zoom-in text-3xl text-white opacity-0 group-hover:opacity-80 transition-opacity duration-300 drop-shadow-lg"></i>
            </div>
          </div>
          @endforeach

          @if($toplamGorsel > 1)
          {{-- Sol ok --}}
          <button @click.stop="onceki()"
                  class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 cursor-pointer z-10"
                  aria-label="Önceki görsel">
            <i class="ti ti-chevron-left text-[#0F172A] text-lg"></i>
          </button>
          {{-- Sağ ok --}}
          <button @click.stop="sonraki()"
                  class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 cursor-pointer z-10"
                  aria-label="Sonraki görsel">
            <i class="ti ti-chevron-right text-[#0F172A] text-lg"></i>
          </button>
          @endif
        </div>

        {{-- Thumbnail Strip --}}
        @if($toplamGorsel > 1)
        <div class="flex gap-2 mt-3 overflow-x-auto pb-1 scrollbar-hide">
          @foreach($proje->gorseller as $i => $gorsel)
          <button @click="aktif = {{ $i }}"
                  class="shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all duration-200 cursor-pointer"
                  :class="aktif === {{ $i }} ? 'border-[#CC2200] opacity-100' : 'border-transparent opacity-50 hover:opacity-80'"
                  aria-label="Görsel {{ $i + 1 }}">
            <img src="{{ asset('storage/' . $gorsel) }}"
                 alt="{{ $proje->baslik }} önizleme {{ $i + 1 }}"
                 loading="lazy"
                 class="w-full h-full object-cover">
          </button>
          @endforeach
        </div>
        @endif

        {{-- Lightbox --}}
        <div x-show="lightbox !== null"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 bg-black/92 flex items-center justify-center p-4"
             @click.self="lightbox = null"
             style="display:none;">
          <button @click="lightbox = null"
                  class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer z-10"
                  aria-label="Kapat">
            <i class="ti ti-x text-xl"></i>
          </button>
          <button @click="lightbox = (lightbox - 1 + toplam) % toplam"
                  class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer z-10"
                  aria-label="Önceki">
            <i class="ti ti-chevron-left text-xl"></i>
          </button>
          <button @click="lightbox = (lightbox + 1) % toplam"
                  class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer z-10"
                  aria-label="Sonraki">
            <i class="ti ti-chevron-right text-xl"></i>
          </button>
          <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/50 text-[12px]"
               x-text="(lightbox + 1) + ' / {{ $toplamGorsel }}'"></div>
          @foreach($proje->gorseller as $i => $gorsel)
          <img x-show="lightbox === {{ $i }}"
               src="{{ asset('storage/' . $gorsel) }}"
               alt="{{ $proje->baslik }} görsel {{ $i + 1 }}"
               class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
               style="display:none;">
          @endforeach
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
