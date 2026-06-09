@extends('layouts.app')
@section('title', $haber->baslik)

@push('styles')
<style>
  .haber-icerik h1, .haber-icerik h2, .haber-icerik h3 {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 600;
    color: #141414;
    margin: 1.5rem 0 0.75rem;
    line-height: 1.3;
  }
  .haber-icerik h1 { font-size: 2rem; }
  .haber-icerik h2 { font-size: 1.6rem; }
  .haber-icerik h3 { font-size: 1.3rem; }
  .haber-icerik p { margin: 0 0 1rem; line-height: 1.8; color: #3A3A3A; font-size: 15px; }
  .haber-icerik ul, .haber-icerik ol { margin: 0 0 1rem 1.5rem; line-height: 1.8; color: #3A3A3A; font-size: 15px; }
  .haber-icerik ul { list-style: disc; }
  .haber-icerik ol { list-style: decimal; }
  .haber-icerik strong { color: #141414; font-weight: 600; }
  .haber-icerik em { font-style: italic; }
  .haber-icerik a { color: #A07850; text-decoration: underline; text-underline-offset: 3px; }
  .haber-icerik a:hover { color: #7a5c3a; }
  .haber-icerik blockquote {
    border-left: 3px solid #A07850;
    padding: 0.75rem 1.25rem;
    margin: 1.5rem 0;
    background: #FAF7F2;
    font-style: italic;
    color: #5A5A5A;
  }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="px-6 py-4 border-b border-[rgba(0,0,0,0.06)]">
  <div class="max-w-4xl mx-auto flex items-center gap-2 text-[12px] text-[#A0A0A0]">
    <a href="{{ route('haberler.index') }}" class="hover:text-[#141414] transition-colors">Haberler</a>
    <i class="ti ti-chevron-right text-[10px]"></i>
    @if($haber->kategori)
    <a href="{{ route('haberler.index', ['kategori' => $haber->kategori]) }}"
       class="hover:text-[#141414] transition-colors">{{ $haber->kategori }}</a>
    <i class="ti ti-chevron-right text-[10px]"></i>
    @endif
    <span class="text-[#3A3A3A] truncate max-w-[200px]">{{ $haber->baslik }}</span>
  </div>
</div>

{{-- Haber --}}
<article class="py-12 px-6">
  <div class="max-w-4xl mx-auto">

    {{-- Meta --}}
    <div class="mb-6">
      @if($haber->kategori)
      <span class="text-[10px] font-bold tracking-[3px] uppercase text-[#A07850] block mb-3">{{ $haber->kategori }}</span>
      @endif
      <h1 class="font-display text-[32px] md:text-[44px] font-semibold text-[#141414] leading-tight tracking-[0.5px]">
        {{ $haber->baslik }}
      </h1>
      @if($haber->ozet)
      <p class="text-[16px] text-[#6B6B6B] leading-relaxed mt-4 font-light">{{ $haber->ozet }}</p>
      @endif
      <div class="flex items-center gap-3 mt-4 text-[12px] text-[#A0A0A0]">
        <i class="ti ti-calendar text-sm"></i>
        <span>{{ $haber->created_at->format('d F Y') }}</span>
      </div>
    </div>

    {{-- Kapak --}}
    @if($haber->kapak)
    <div class="mb-10 rounded-[4px] overflow-hidden aspect-[21/9]">
      <img src="{{ Storage::url($haber->kapak) }}" alt="{{ $haber->baslik }}"
           class="w-full h-full object-cover">
    </div>
    @else
    <div class="mb-10 h-px bg-[rgba(0,0,0,0.08)]"></div>
    @endif

    {{-- İçerik --}}
    <div class="haber-icerik max-w-2xl">
      {!! $haber->icerik !!}
    </div>

    {{-- Alt çizgi --}}
    <div class="mt-12 pt-8 border-t border-[rgba(0,0,0,0.08)]">
      <a href="{{ route('haberler.index') }}"
         class="inline-flex items-center gap-2 text-[12px] font-semibold tracking-[2px] uppercase text-[#5A5A5A] hover:text-[#141414] transition-colors">
        <i class="ti ti-arrow-left text-sm"></i> Tüm Haberler
      </a>
    </div>

  </div>
</article>

{{-- Diğer Haberler --}}
@if($diger->isNotEmpty())
<section class="py-12 px-6 bg-[#FAF7F2] border-t border-[rgba(0,0,0,0.06)]">
  <div class="max-w-6xl mx-auto">
    <h2 class="font-display text-[22px] font-semibold text-[#141414] mb-6">Diğer Haberler</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      @foreach($diger as $d)
      <a href="{{ route('haberler.show', $d->slug) }}"
         class="group flex gap-4 bg-white border border-[rgba(0,0,0,0.08)] rounded-[4px] p-4 hover:border-[rgba(0,0,0,0.20)] transition-colors">
        @if($d->kapak)
        <div class="w-16 h-16 rounded-[4px] overflow-hidden shrink-0">
          <img src="{{ Storage::url($d->kapak) }}" alt="{{ $d->baslik }}" class="w-full h-full object-cover">
        </div>
        @endif
        <div class="min-w-0">
          @if($d->kategori)
          <span class="text-[9px] font-bold tracking-[2px] uppercase text-[#A07850]">{{ $d->kategori }}</span>
          @endif
          <p class="text-[13px] font-semibold text-[#141414] leading-snug mt-0.5 group-hover:text-[#A07850] transition-colors line-clamp-2">
            {{ $d->baslik }}
          </p>
          <span class="text-[10px] text-[#A0A0A0] mt-1 block">{{ $d->created_at->format('d.m.Y') }}</span>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@endsection
