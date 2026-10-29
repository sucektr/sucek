@extends('yardimci.layouts.app')

@section('title', 'Ürün Ekle')

@section('content')
<div class="grid grid-cols-1 gap-3 mb-6">
  <a href="{{ route('yardimci.urun.yeni') }}"
     class="flex items-center gap-4 bg-white border border-[rgba(0,0,0,0.08)] rounded-[14px] p-5 hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)] transition-all duration-200">
    <div class="w-12 h-12 rounded-[12px] bg-[#FDECEC] flex items-center justify-center shrink-0">
      <i class="ti ti-shopping-bag text-[22px] text-[#CC2200]"></i>
    </div>
    <div class="flex-1">
      <div class="font-semibold text-[15px] text-[#0F0F0F]">Mağaza Ürünü Ekle</div>
      <div class="text-[12px] text-[#A8A8A8] mt-0.5">İnşaat, spor, çelik ağ malzemeleri</div>
    </div>
    <i class="ti ti-chevron-right text-[#D0D0D0]"></i>
  </a>

  <a href="{{ route('yardimci.koleksiyon.yeni') }}"
     class="flex items-center gap-4 bg-white border border-[rgba(0,0,0,0.08)] rounded-[14px] p-5 hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)] transition-all duration-200">
    <div class="w-12 h-12 rounded-[12px] bg-[#f5f3ff] flex items-center justify-center shrink-0">
      <i class="ti ti-diamond text-[22px] text-[#7c3aed]"></i>
    </div>
    <div class="flex-1">
      <div class="font-semibold text-[15px] text-[#0F0F0F]">Koleksiyon Ürünü Ekle</div>
      <div class="text-[12px] text-[#A8A8A8] mt-0.5">Antika, saat, nümizmatik</div>
    </div>
    <i class="ti ti-chevron-right text-[#D0D0D0]"></i>
  </a>
</div>

<div>
  <h2 class="text-[11px] font-semibold tracking-[1.5px] uppercase text-[#A8A8A8] mb-3">Son Eklediklerim</h2>

  @if($eklenenler->isEmpty())
  <div class="text-center py-10 text-[13px] text-[#A8A8A8]">
    Henüz ürün eklemediniz.
  </div>
  @else
  <div class="flex flex-col gap-2">
    @foreach($eklenenler as $e)
    @php
      $durumBilgi = match($e->durum) {
        'bekliyor'   => ['label' => 'Onay Bekliyor', 'bg' => '#FFF7E6', 'renk' => '#B8962E'],
        'onaylandi'  => ['label' => 'Yayında', 'bg' => '#E6F4EC', 'renk' => '#1A5C3A'],
        'reddedildi' => ['label' => 'Reddedildi', 'bg' => '#FDECEC', 'renk' => '#8A1F1F'],
        default      => ['label' => $e->durum, 'bg' => '#F5F5F5', 'renk' => '#5A5A5A'],
      };
    @endphp
    <div class="flex items-center gap-3 bg-white border border-[rgba(0,0,0,0.07)] rounded-[12px] p-3">
      <div class="w-12 h-12 rounded-[8px] bg-[#F5F5F5] overflow-hidden shrink-0 flex items-center justify-center">
        @if($e->gorsel)
        <img src="{{ asset('storage/'.$e->gorsel) }}" alt="" class="w-full h-full object-cover">
        @else
        <i class="ti ti-photo text-[#D0D0D0]"></i>
        @endif
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-[13px] font-medium text-[#0F0F0F] truncate">{{ $e->ad }}</div>
        <div class="text-[11px] text-[#A8A8A8] mt-0.5">{{ $e->tip === 'urun' ? 'Mağaza' : 'Koleksiyon' }} · {{ $e->tarih->diffForHumans() }}</div>
      </div>
      <span class="text-[10px] font-semibold px-2.5 py-1 rounded-[6px] shrink-0" style="background:{{ $durumBilgi['bg'] }};color:{{ $durumBilgi['renk'] }};">
        {{ $durumBilgi['label'] }}
      </span>
    </div>
    @endforeach
  </div>
  @endif
</div>
@endsection
