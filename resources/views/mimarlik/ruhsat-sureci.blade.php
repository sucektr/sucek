@extends('layouts.app')

@section('title', 'Ruhsat Süreci — SUÇEK Mimarlık')
@section('meta-description', 'İmar ve inşaat ruhsatlarının başvuru, takip ve sonuçlandırma süreçleri hakkında bilgi alın.')

@section('banner')
  @include('components.banner', ['mesaj' => icerik('ruhsat','banner_metni','Ruhsat süreciniz için ücretsiz ön görüşme alın!')])
@endsection

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[320px] flex items-end" aria-label="Ruhsat Süreci hero">
  <div class="absolute inset-0"
       style="background-image:url('{{ icerik_gorsel('ruhsat','hero_gorsel','https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1280&q=80') }}'); background-size:cover; background-position:center;"></div>
  <div class="absolute inset-0 bg-gradient-to-r from-[rgba(15,15,15,0.90)] via-[rgba(15,15,15,0.55)] to-transparent"></div>
  <div class="relative z-10 px-9 lg:px-14 py-14 w-full">
    <nav class="flex items-center gap-2 mb-4" aria-label="Breadcrumb">
      <a href="{{ route('mimarlik.index') }}" class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.40)] hover:text-white transition-colors">Mimarlık</a>
      <span class="text-[rgba(255,255,255,0.20)] text-xs">›</span>
      <span class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.55)]">Ruhsat Süreci</span>
    </nav>
    <h1 class="font-display text-[40px] lg:text-[54px] font-semibold text-white leading-[1.05] mb-4">
      {{ icerik('ruhsat','hero_baslik','Ruhsat Süreci') }}
    </h1>
    <p class="text-[13px] text-[rgba(255,255,255,0.60)] leading-relaxed max-w-[420px]">
      {{ icerik('ruhsat','hero_alt_baslik','İmar ve inşaat ruhsatlarının başvuru, takip ve sonuçlandırma süreçlerini eksiksiz yönetiyoruz.') }}
    </p>
  </div>
</section>

{{-- ─── İçerik ─────────────────────────────────────────────────────────── --}}
@if(icerik('ruhsat','icerik',''))
<section class="section">
  <div class="max-w-3xl yasal-icerik">
    {!! icerik('ruhsat','icerik','') !!}
  </div>
</section>
@endif

{{-- ─── Proje Galerisi ─────────────────────────────────────────────────── --}}
@if($projeler->count() > 0)
<section class="section {{ icerik('ruhsat','icerik','') ? 'border-t border-[rgba(0,0,0,0.06)]' : '' }}" aria-labelledby="ruhsat-projeler-baslik">
  <div class="flex items-end justify-between mb-6">
    <div>
      <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-1.5">REFERANS PROJELER</p>
      <h2 id="ruhsat-projeler-baslik" class="font-serif-sc text-[28px] font-bold text-[#0F0F0F]">Tamamlanan Projeler</h2>
    </div>
    <a href="{{ route('mimarlik.index') }}"
       class="hidden sm:flex items-center gap-1.5 text-[10px] font-medium tracking-[1.5px] uppercase text-[#5A5A5A] hover:text-[#0F0F0F] transition-colors">
      Tümünü Gör <i class="ti ti-arrow-right text-sm" aria-hidden="true"></i>
    </a>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
    @foreach($projeler as $proje)
    <article class="group relative rounded-[12px] overflow-hidden aspect-[4/3] cursor-pointer shadow-[0_2px_6px_rgba(0,0,0,0.20)]">
      <div class="absolute inset-0 bg-[#E0E0E0]"
           style="{{ $proje->kapak_gorsel ? 'background-image:url('.asset('storage/'.$proje->kapak_gorsel).');background-size:cover;background-position:center;' : '' }}"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[rgba(0,0,0,0.75)] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
        <p class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.60)] mb-1">{{ $proje->yil ?? '' }}</p>
        <h3 class="font-display text-[18px] font-semibold text-white">{{ $proje->baslik }}</h3>
      </div>
    </article>
    @endforeach
  </div>
</section>
@endif

{{-- ─── CTA ────────────────────────────────────────────────────────────── --}}
<section class="section">
  <div class="bg-[#141414] rounded-[16px] px-10 lg:px-16 py-12 flex flex-col lg:flex-row items-center justify-between gap-6">
    <div>
      <h2 class="font-display text-[28px] lg:text-[34px] font-semibold text-white mb-2">Ruhsat Sürecinizi Başlatalım</h2>
      <p class="text-[13px] text-[rgba(255,255,255,0.50)]">Ücretsiz ön görüşme için bugün bize ulaşın.</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
      <a href="{{ route('iletisim.index') }}"
         class="btn btn-outline-inv text-[10px] tracking-[1.5px] px-7 py-3 min-h-[44px] justify-center">
        <i class="ti ti-mail text-sm" aria-hidden="true"></i> İletişime Geç
      </a>
      <a href="tel:{{ preg_replace('/[^+\d]/', '', icerik('site','telefon','+905442948402')) }}"
         class="btn bg-white text-[#0F0F0F] text-[10px] tracking-[1.5px] px-7 py-3 min-h-[44px] justify-center hover:bg-[#F0F0F0] transition-colors rounded-[8px]">
        <i class="ti ti-phone text-sm" aria-hidden="true"></i> Hemen Ara
      </a>
    </div>
  </div>
</section>

@endsection

@push('styles')
<style>
.yasal-icerik { color: #3A3A3A; line-height: 1.8; font-size: 14px; }
.yasal-icerik h2 { font-size: 20px; font-weight: 600; color: #0F0F0F; margin: 2rem 0 0.75rem; }
.yasal-icerik h3 { font-size: 16px; font-weight: 600; color: #0F0F0F; margin: 1.5rem 0 0.5rem; }
.yasal-icerik p  { margin-bottom: 1rem; }
.yasal-icerik ul, .yasal-icerik ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.yasal-icerik li { margin-bottom: 0.4rem; }
.yasal-icerik strong { color: #0F0F0F; font-weight: 600; }
</style>
@endpush
