@extends('admin.layouts.cari')

@section('title', 'Tedarikçiler')
@section('page-title', 'Tedarikçiler')

@section('content')

  @include('admin.cari._sekme', ['aktif' => 'tedarikci'])

  {{-- Arama --}}
  <form method="GET" class="mb-4">
    <div class="relative">
      <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#94A3B8] text-[16px]"></i>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Tedarikçi ara..."
             class="w-full pl-10 pr-4 py-2.5 rounded-[10px] border border-[#E2E8F0] bg-white text-[13px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20 focus:border-[#CC2200]">
    </div>
  </form>

  {{-- Tedarikçi listesi --}}
  <div class="space-y-2">
    @forelse($tedarikciler as $t)
      <a href="{{ route('admin.cari.show', $t) }}" class="block bg-white rounded-[12px] border border-[#E2E8F0] px-4 py-3.5 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p class="text-[14px] font-semibold text-[#0F172A] truncate">{{ $t->ad_soyad }}</p>
          @if($t->telefon)<p class="text-[11px] text-[#94A3B8] mt-0.5">{{ $t->telefon }}</p>@endif
        </div>
        <div class="shrink-0 text-right">
          <p class="text-[14px] font-bold {{ $t->hesaplanan_bakiye > 0 ? 'text-[#CC2200]' : ($t->hesaplanan_bakiye < 0 ? 'text-[#1D7A4C]' : 'text-[#94A3B8]') }}">
            {{ number_format($t->hesaplanan_bakiye, 2, ',', '.') }} ₺
          </p>
          <i class="ti ti-chevron-right text-[#CBD5E1] text-[16px]"></i>
        </div>
      </a>
    @empty
      <div class="text-center py-12 text-[#94A3B8]">
        <i class="ti ti-truck-delivery text-[32px]"></i>
        <p class="text-[13px] mt-2">Henüz tedarikçi eklenmedi.</p>
        <a href="{{ route('admin.cari.index') }}" class="text-[12px] text-[#CC2200] font-medium mt-1 inline-block">Genel sayfadan ekleyebilirsin →</a>
      </div>
    @endforelse
  </div>

@endsection
