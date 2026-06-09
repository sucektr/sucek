@extends('admin.layouts.app')
@section('title', 'Sayfa Erişim Yetkileri')
@section('page-title', 'Erişim Yetkileri')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">Üyelik Yönetimi</span>
@endsection

@section('content')

@if(session('basari'))
<div class="mb-4 flex items-center gap-2 px-4 py-3 bg-[#E6F4EC] border border-[#A8D5B8] rounded-[10px] text-[13px] text-[#1A5C3A]">
  <i class="ti ti-circle-check"></i> {{ session('basari') }}
</div>
@endif
@if(session('hata'))
<div class="mb-4 flex items-center gap-2 px-4 py-3 bg-[#FEE2E2] border border-[#FCA5A5] rounded-[10px] text-[13px] text-[#991B1B]">
  <i class="ti ti-alert-circle"></i> {{ session('hata') }}
</div>
@endif

@php
$tierler = [
  ['rol' => 'standart', 'isim' => 'Standart', 'renk' => '#2563eb', 'bg' => '#eff6ff', 'sinir' => '#bfdbfe', 'ikon' => 'ti-star'],
  ['rol' => 'sucek',    'isim' => 'Suçek',    'renk' => '#7c3aed', 'bg' => '#f5f3ff', 'sinir' => '#ddd6fe', 'ikon' => 'ti-crown'],
  ['rol' => 'teknik',   'isim' => 'Teknik',   'renk' => '#d97706', 'bg' => '#fffbeb', 'sinir' => '#fde68a', 'ikon' => 'ti-tool'],
];
@endphp

{{-- Açıklama --}}
<div class="mb-6 bg-[#F0F7FF] border border-[#BFDBFE] rounded-[12px] px-5 py-4 flex items-start gap-3">
  <i class="ti ti-info-circle text-[#2563eb] text-lg shrink-0 mt-0.5"></i>
  <div>
    <p class="text-[13px] font-semibold text-[#1e3a5f] mb-1">Bu bölümler hangi üyelik tiplerinin görebileceğini belirler</p>
    <p class="text-[12px] text-[#4A6A8A] leading-relaxed">
      Her bölüm için hangi üyelik tiplerinin erişebileceğini seçin. Hiç seçilmezse o bölüme kimse erişemez.
      Yeni bölüm eklemek için rotaya <code class="bg-white px-1 py-0.5 rounded text-[11px] font-mono border border-[rgba(0,0,0,0.1)]">->middleware(['auth', 'premium:bolum-adi'])</code> ekleyin.
    </p>
  </div>
</div>

{{-- Bölümler --}}
<div class="space-y-3 mb-8">
  @foreach($kurallar as $kural)
  <div class="bg-white rounded-[12px] border border-[#E2E8F0]">
    <div class="px-6 py-4 flex items-center gap-5 flex-wrap">

      {{-- Bölüm bilgisi --}}
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
          <span class="text-[13px] font-semibold text-[#0F172A]">{{ $kural->bolum_adi }}</span>
          <span class="text-[10px] font-mono text-[#94A3B8] bg-[#F8FAFC] px-1.5 py-0.5 rounded">/{{ $kural->bolum_slug }}</span>
        </div>
        @if($kural->aciklama)
        <p class="text-[11px] text-[#94A3B8] mt-0.5">{{ $kural->aciklama }}</p>
        @endif
      </div>

      {{-- Kaydet formu (checkboxlar dahil) --}}
      <form action="{{ route('admin.erisim.guncelle', $kural->id) }}" method="POST"
            class="flex items-center gap-4 flex-wrap">
        @csrf
        @method('PATCH')

        @foreach($tierler as $tier)
        @php $secili = in_array($tier['rol'], $kural->izin_tipler ?? []); @endphp
        <label x-data="{ on: {{ $secili ? 'true' : 'false' }} }"
               class="flex items-center gap-1.5 cursor-pointer select-none">
          <input type="checkbox" name="izin_tipler[]" value="{{ $tier['rol'] }}"
                 x-model="on" class="sr-only">
          <div class="w-5 h-5 rounded-[5px] border-2 flex items-center justify-center transition-all"
               :style="on
                 ? 'border-color:{{ $tier['renk'] }};background:{{ $tier['bg'] }}'
                 : 'border-color:rgba(0,0,0,0.2);background:white'">
            <i class="ti ti-check text-[10px]" x-show="on" style="color:{{ $tier['renk'] }}"></i>
          </div>
          <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-[0.06em] uppercase px-2 py-0.5 rounded-full border"
                style="background:{{ $tier['bg'] }};color:{{ $tier['renk'] }};border-color:{{ $tier['sinir'] }};">
            <i class="ti {{ $tier['ikon'] }} text-[9px]"></i> {{ $tier['isim'] }}
          </span>
        </label>
        @endforeach

        <button type="submit"
                class="px-4 py-2 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1px] uppercase rounded-[8px] hover:bg-[#333] transition-colors shrink-0">
          Kaydet
        </button>
      </form>

      {{-- Sil formu — kaydet formundan AYRI --}}
      <form action="{{ route('admin.erisim.sil', $kural->id) }}" method="POST"
            onsubmit="return confirm('{{ $kural->bolum_adi }} bölümünü silmek istediğinizden emin misiniz?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-8 h-8 flex items-center justify-center border border-[#E2E8F0] rounded-[6px] text-[#94A3B8] hover:text-[#CC2200] hover:border-red-300 transition-colors bg-white shrink-0">
          <i class="ti ti-trash text-sm"></i>
        </button>
      </form>

    </div>
  </div>
  @endforeach

  @if($kurallar->isEmpty())
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] px-6 py-12 text-center">
    <i class="ti ti-lock-open text-4xl text-[#D0D0D0] mb-3 block"></i>
    <p class="text-[13px] text-[#94A3B8]">Henüz tanımlanmış erişim kuralı yok.</p>
  </div>
  @endif
</div>

{{-- Yeni Bölüm Ekle --}}
<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC]">
    <h2 class="text-[13px] font-semibold text-[#0F172A]">Yeni Bölüm Ekle</h2>
  </div>
  <form action="{{ route('admin.erisim.ekle') }}" method="POST" class="p-6">
    @csrf
    <div class="grid grid-cols-3 gap-4">
      <div>
        <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-1.5">Bölüm Adı</label>
        <input type="text" name="bolum_adi" placeholder="ör. Belgeler"
               class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-[8px] text-[13px] text-[#0F172A] placeholder:text-[#C0C0C0] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
        @error('bolum_adi')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-1.5">URL Slug</label>
        <input type="text" name="bolum_slug" placeholder="ör. belgeler"
               class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-[8px] text-[13px] text-[#0F172A] placeholder:text-[#C0C0C0] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono">
        @error('bolum_slug')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-1.5">Açıklama (opsiyonel)</label>
        <input type="text" name="aciklama" placeholder="ör. Teknik belgeler ve dokümanlar"
               class="w-full px-3 py-2 bg-white border border-[#E2E8F0] rounded-[8px] text-[13px] text-[#0F172A] placeholder:text-[#C0C0C0] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
      </div>
    </div>
    <div class="mt-4 flex justify-end">
      <button type="submit"
              class="px-5 py-2 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1px] uppercase rounded-[8px] hover:bg-[#333] transition-colors">
        <i class="ti ti-plus mr-1"></i> Bölüm Ekle
      </button>
    </div>
  </form>
</div>

@endsection


