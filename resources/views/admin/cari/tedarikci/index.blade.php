@extends('admin.layouts.cari')

@section('title', 'Tedarikçiler')
@section('page-title', 'Tedarikçiler')

@section('header-actions')
  <button type="button" onclick="document.getElementById('yeni-tedarikci-modal').classList.remove('hidden')"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-[rgba(255,255,255,0.08)] text-white">
    <i class="ti ti-plus text-[18px]"></i>
  </button>
@endsection

@section('content')

  @include('admin.cari._sekme', ['aktif' => 'tedarikci'])

  {{-- Toplam borç kartı --}}
  <div class="bg-[#141414] rounded-[14px] px-5 py-5 mb-4 text-white">
    <p class="text-[10px] uppercase tracking-wider text-[rgba(255,255,255,0.4)] mb-1">Toplam Borcunuz</p>
    <p class="text-[28px] font-bold {{ $toplamBorc > 0 ? 'text-[#CC2200]' : ($toplamBorc < 0 ? 'text-[#4ADE80]' : 'text-white') }}">
      {{ number_format($toplamBorc, 2, ',', '.') }} ₺
    </p>
    <p class="text-[11px] text-[rgba(255,255,255,0.5)] mt-1">
      {{ $toplamBorc > 0 ? 'Tedarikçilere toplam borcunuz' : ($toplamBorc < 0 ? 'Tedarikçilerden toplam alacağınız' : 'Hesaplar dengede') }}
    </p>
  </div>

  {{-- Vadesi yaklaşan/geçen ödeme uyarısı --}}
  @if($vadesiYaklasanlar->isNotEmpty())
  <div class="bg-[#FFF7ED] border border-[#FED7AA] rounded-[12px] px-4 py-3 mb-4">
    <p class="text-[12px] font-semibold text-[#9A3412] flex items-center gap-1.5 mb-2">
      <i class="ti ti-alert-triangle"></i> Vadesi Yaklaşan/Geçen Ödemeleriniz
    </p>
    <div class="space-y-1.5">
      @foreach($vadesiYaklasanlar as $h)
        <a href="{{ route('admin.cari.show', $h->musteri) }}" class="flex items-center justify-between text-[12px] text-[#78350F]">
          <span class="truncate">{{ $h->musteri->ad_soyad }} — {{ $h->aciklama }}</span>
          <span class="shrink-0 font-semibold {{ $h->vade_tarihi->isPast() ? 'text-[#CC2200]' : '' }}">{{ $h->vade_tarihi->format('d.m.Y') }}</span>
        </a>
      @endforeach
    </div>
  </div>
  @endif

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
      </div>
    @endforelse
  </div>

  {{-- Yeni tedarikçi modalı --}}
  <div id="yeni-tedarikci-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Yeni Tedarikçi</p>
        <button type="button" onclick="document.getElementById('yeni-tedarikci-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.store') }}" method="POST" class="space-y-3">
        @csrf
        <input type="hidden" name="tip" value="tedarikci">
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Firma / Unvan *</label>
          <input type="text" name="ad_soyad" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Yetkili Kişi</label>
          <input type="text" name="yetkili_kisi" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Telefon</label>
            <input type="text" name="telefon" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">E-posta</label>
            <input type="email" name="email" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Adres</label>
          <textarea name="adres" rows="2" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi Dairesi</label>
            <input type="text" name="vergi_dairesi" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi No</label>
            <input type="text" name="vergi_no" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Tedarikçi Ekle</button>
      </form>
    </div>
  </div>

@endsection
