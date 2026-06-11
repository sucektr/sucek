@extends('layouts.app')

@section('title', 'Projelerimiz — SUÇEK')
@section('meta-description', 'SUÇEK mimarlık ve inşaat projeleri: tamamlanan projelerimizi inceleyin.')

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[300px] flex" aria-label="Projeler hero">
  <div class="absolute inset-0 bg-[#0F172A]"></div>
  <div class="absolute inset-0 opacity-[0.04]"
       style="background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:24px 24px;"></div>
  <div class="absolute top-0 left-0 w-1 h-full bg-[#CC2200]"></div>

  <div class="relative z-10 flex flex-col justify-end px-6 lg:px-12 py-14 w-full">
    <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-3">PORTFÖLYOMİZ</p>
    <h1 class="text-[36px] lg:text-[52px] font-bold text-white leading-tight tracking-tight mb-4">
      Projelerimiz
    </h1>
    <p class="text-[14px] text-[rgba(255,255,255,0.50)] leading-relaxed max-w-[480px]">
      Tamamladığımız mimarlık ve inşaat projelerini inceleyin.
    </p>
  </div>
</section>

{{-- ─── Proje Listesi ──────────────────────────────────────────────────── --}}
<section class="section" aria-labelledby="projeler-baslik">

  @if($projeler->isEmpty())
  <div class="text-center py-20">
    <i class="ti ti-building-arch text-5xl text-[#D0D0D0] block mb-4"></i>
    <p class="text-[14px] text-[#94A3B8]">Henüz proje eklenmemiş.</p>
  </div>
  @else

  {{-- Kategori Filtresi --}}
  @if($kategoriler->count() > 1)
  <div class="flex flex-wrap gap-2 mb-8"
       x-data="{ aktif: 'tumu' }">

    <button @click="aktif = 'tumu'"
            :class="aktif === 'tumu' ? 'bg-[#0F172A] text-white border-[#0F172A]' : 'bg-white text-[#64748B] border-[#E2E8F0] hover:border-[#0F172A] hover:text-[#0F172A]'"
            class="px-4 py-2 rounded-[8px] border text-[12px] font-semibold tracking-[0.5px] transition-all duration-150 cursor-pointer">
      Tümü <span class="ml-1 opacity-60">({{ $projeler->count() }})</span>
    </button>

    @foreach($kategoriler as $kat)
    @php
      $katLabel = match($kat) {
        'mimarlik' => 'Mimarlık',
        'insaat'   => 'İnşaat',
        default    => ucfirst($kat),
      };
      $katSayi = $projeler->where('kategori', $kat)->count();
    @endphp
    <button @click="aktif = '{{ $kat }}'"
            :class="aktif === '{{ $kat }}' ? 'bg-[#0F172A] text-white border-[#0F172A]' : 'bg-white text-[#64748B] border-[#E2E8F0] hover:border-[#0F172A] hover:text-[#0F172A]'"
            class="px-4 py-2 rounded-[8px] border text-[12px] font-semibold tracking-[0.5px] transition-all duration-150 cursor-pointer">
      {{ $katLabel }} <span class="ml-1 opacity-60">({{ $katSayi }})</span>
    </button>
    @endforeach

  </div>
  @endif

  {{-- Grid --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"
       @if($kategoriler->count() > 1) x-data="{ aktif: 'tumu' }" @endif>

    @foreach($projeler as $proje)
    @php
      $katLabel = match($proje->kategori) {
        'mimarlik' => 'Mimarlık',
        'insaat'   => 'İnşaat',
        default    => ucfirst($proje->kategori ?? ''),
      };
    @endphp
    <article
      @if($kategoriler->count() > 1)
        x-show="aktif === 'tumu' || aktif === '{{ $proje->kategori }}'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
      @endif
      class="group bg-white rounded-xl border border-[#E2E8F0] overflow-hidden hover:shadow-[0_8px_32px_rgba(15,23,42,0.10)] hover:-translate-y-1 transition-all duration-300 cursor-pointer">

      <a href="{{ route('projeler.show', $proje->slug) }}" class="block">
        {{-- Görsel --}}
        <div class="relative aspect-[4/3] overflow-hidden bg-[#F1F5F9]">
          @if($proje->kapak_gorsel)
          <img src="{{ asset('storage/' . $proje->kapak_gorsel) }}"
               alt="{{ $proje->baslik }}"
               loading="lazy"
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
          @else
          <div class="w-full h-full flex items-center justify-center">
            <i class="ti ti-building-arch text-5xl text-[#D0D0D0]"></i>
          </div>
          @endif

          @if($proje->one_cikan)
          <div class="absolute top-3 left-3">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#0F172A]/80 backdrop-blur-sm text-white text-[10px] font-semibold rounded-full">
              <i class="ti ti-star-filled text-[#FCD34D] text-[9px]"></i> Öne Çıkan
            </span>
          </div>
          @endif
        </div>

        {{-- İçerik --}}
        <div class="p-5">
          <div class="flex items-center gap-2 mb-2.5">
            @if($proje->kategori)
            <span class="text-[10px] font-semibold tracking-[1.5px] uppercase text-[#CC2200]">{{ $katLabel }}</span>
            @endif
            @if($proje->yil)
            <span class="text-[#D0D0D0] text-xs">·</span>
            <span class="text-[11px] text-[#94A3B8]">{{ $proje->yil }}</span>
            @endif
          </div>

          <h2 class="text-[17px] font-semibold text-[#0F172A] mb-2 leading-snug tracking-tight group-hover:text-[#CC2200] transition-colors">
            {{ $proje->baslik }}
          </h2>

          @if($proje->aciklama)
          <p class="text-[13px] text-[#64748B] leading-relaxed line-clamp-2 mb-3">{{ $proje->aciklama }}</p>
          @endif

          @if($proje->konum)
          <div class="flex items-center gap-1.5 text-[12px] text-[#94A3B8]">
            <i class="ti ti-map-pin text-xs"></i>
            <span>{{ $proje->konum }}</span>
          </div>
          @endif
        </div>
      </a>
    </article>
    @endforeach

  </div>
  @endif

</section>

{{-- ─── CTA ────────────────────────────────────────────────────────────── --}}
<section class="section">
  <div class="bg-[#0F172A] rounded-2xl px-8 lg:px-14 py-12 flex flex-col lg:flex-row items-center justify-between gap-6">
    <div>
      <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-3">BİRLİKTE ÇALIŞALIM</p>
      <h2 class="text-[24px] lg:text-[32px] font-bold text-white tracking-tight mb-2">Projenizi Konuşalım</h2>
      <p class="text-[13px] text-[rgba(255,255,255,0.45)]">Yeni projeniz için ücretsiz ön görüşme talep edin.</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
      <a href="{{ route('iletisim.index') }}"
         class="flex items-center justify-center gap-2 px-7 py-3 rounded-lg border border-white/20 text-white text-[12px] font-semibold tracking-[1px] uppercase hover:bg-white/10 transition-colors min-h-[44px]">
        <i class="ti ti-mail text-sm"></i> İletişime Geç
      </a>
    </div>
  </div>
</section>

@endsection
