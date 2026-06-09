@extends('layouts.app')
@section('title', 'Haberler')

@section('content')

{{-- Sayfa Başlığı --}}
<section class="pt-16 pb-10 px-6 border-b border-[rgba(0,0,0,0.07)]">
  <div class="max-w-6xl mx-auto">
    <p class="text-[11px] font-semibold tracking-[3px] uppercase text-[#A07850] mb-3">Premium</p>
    <h1 class="font-display text-[38px] md:text-[52px] font-semibold text-[#141414] tracking-[1px] leading-tight">Haberler</h1>
    <p class="text-[14px] text-[#6B6B6B] mt-3 max-w-xl">SUÇEK topluluğuna özel güncel haberler ve duyurular.</p>

    {{-- Kategoriler --}}
    @if($kategoriler->isNotEmpty())
    <div class="flex flex-wrap gap-2 mt-6">
      <a href="{{ route('haberler.index') }}"
         class="px-3 py-1.5 text-[11px] font-semibold tracking-[1px] uppercase border transition-colors {{ !request('kategori') ? 'bg-[#141414] text-white border-[#141414]' : 'bg-white text-[#5A5A5A] border-[rgba(0,0,0,0.15)] hover:border-[#141414]' }}"
         style="border-radius:4px;">
        Tümü
      </a>
      @foreach($kategoriler as $kat)
      <a href="{{ route('haberler.index', ['kategori' => $kat]) }}"
         class="px-3 py-1.5 text-[11px] font-semibold tracking-[1px] uppercase border transition-colors {{ request('kategori') === $kat ? 'bg-[#141414] text-white border-[#141414]' : 'bg-white text-[#5A5A5A] border-[rgba(0,0,0,0.15)] hover:border-[#141414]' }}"
         style="border-radius:4px;">
        {{ $kat }}
      </a>
      @endforeach
    </div>
    @endif
  </div>
</section>

{{-- Haber Listesi --}}
<section class="py-12 px-6">
  <div class="max-w-6xl mx-auto">

    @if($haberler->isEmpty())
    <div class="text-center py-20">
      <i class="ti ti-news text-5xl text-[#D0D0D0] mb-4 block"></i>
      <p class="text-[14px] text-[#A0A0A0]">Henüz haber yayınlanmadı.</p>
    </div>
    @else

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($haberler as $haber)
      <a href="{{ route('haberler.show', $haber->slug) }}"
         class="group bg-white border border-[rgba(0,0,0,0.08)] rounded-[4px] overflow-hidden hover:border-[rgba(0,0,0,0.20)] hover:shadow-md transition-all duration-200 flex flex-col">

        {{-- Kapak --}}
        <div class="aspect-[16/9] bg-[#F5F0EA] overflow-hidden">
          @if($haber->kapak)
          <img src="{{ Storage::url($haber->kapak) }}" alt="{{ $haber->baslik }}"
               class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
          @else
          <div class="w-full h-full flex items-center justify-center">
            <i class="ti ti-news text-4xl text-[#C8B89A]"></i>
          </div>
          @endif
        </div>

        {{-- İçerik --}}
        <div class="p-5 flex flex-col flex-1">
          @if($haber->kategori)
          <span class="text-[10px] font-bold tracking-[2px] uppercase text-[#A07850] mb-2 block">{{ $haber->kategori }}</span>
          @endif

          <h2 class="font-display text-[18px] font-semibold text-[#141414] leading-snug mb-2 group-hover:text-[#A07850] transition-colors">
            {{ $haber->baslik }}
          </h2>

          @if($haber->ozet)
          <p class="text-[13px] text-[#6B6B6B] leading-relaxed line-clamp-3 flex-1">{{ $haber->ozet }}</p>
          @endif

          <div class="flex items-center justify-between mt-4 pt-4 border-t border-[rgba(0,0,0,0.06)]">
            <span class="text-[11px] text-[#A0A0A0]">{{ $haber->created_at->format('d.m.Y') }}</span>
            <span class="text-[11px] font-semibold text-[#A07850] flex items-center gap-1">
              Oku <i class="ti ti-arrow-right text-xs"></i>
            </span>
          </div>
        </div>

      </a>
      @endforeach
    </div>

    {{-- Sayfalama --}}
    @if($haberler->hasPages())
    <div class="mt-12 flex justify-center">
      {{ $haberler->links() }}
    </div>
    @endif

    @endif
  </div>
</section>

@endsection
