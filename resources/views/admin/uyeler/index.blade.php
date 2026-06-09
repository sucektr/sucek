@extends('admin.layouts.app')
@section('title', 'Üyeler')
@section('page-title', 'Üyeler')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">Üye Yönetimi</span>
@endsection

@section('header-actions')
<form method="GET" action="{{ route('admin.uyeler.index') }}" class="flex items-center gap-2">
  <input type="text" name="ara" value="{{ $ara }}" placeholder="Ad veya e-posta ara…"
         class="px-3 py-1.5 bg-white border border-[#E2E8F0] rounded-[8px] text-[13px] text-[#0F172A] placeholder:text-[#94A3B8] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors w-56">
  <button type="submit" class="px-3 py-1.5 bg-[#CC2200] text-white text-[11px] font-semibold rounded-[8px] hover:bg-[#333] transition-colors">Ara</button>
  @if($ara)
  <a href="{{ route('admin.uyeler.index') }}" class="px-3 py-1.5 bg-white border border-[#E2E8F0] text-[11px] text-[#64748B] font-semibold rounded-[8px] hover:bg-[#F8FAFC] transition-colors">Temizle</a>
  @endif
</form>
@endsection

@section('content')

{{-- İstatistik --}}
<div class="grid grid-cols-3 gap-4 mb-6">
  @php
  $tierler = [
    ['rol' => 'sucek',    'isim' => 'Suçek',    'renk' => '#7c3aed', 'bg' => '#f5f3ff'],
    ['rol' => 'teknik',   'isim' => 'Teknik',   'renk' => '#d97706', 'bg' => '#fffbeb'],
    ['rol' => 'standart', 'isim' => 'Standart', 'renk' => '#2563eb', 'bg' => '#eff6ff'],
  ];
  @endphp
  @foreach($tierler as $tier)
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold" style="color:{{ $tier['renk'] }}">{{ $sayilar[$tier['rol']] ?? 0 }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">{{ $tier['isim'] }}</p>
  </div>
  @endforeach
</div>

{{-- Tablo --}}
<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="px-6 py-4 border-b border-[#E2E8F0]">
    <h2 class="text-[13px] font-semibold text-[#0F172A]">Tüm Üyeler</h2>
  </div>

  @if($uyeler->isEmpty())
  <div class="px-6 py-12 text-center">
    <i class="ti ti-users text-4xl text-[#D0D0D0] mb-3 block"></i>
    <p class="text-[13px] text-[#94A3B8]">{{ $ara ? 'Arama sonucu bulunamadı.' : 'Henüz kayıtlı üye yok.' }}</p>
  </div>
  @else
  <div class="divide-y divide-[rgba(0,0,0,0.05)]">
    @foreach($uyeler as $uye)
    @php $bilgi = \App\Models\User::rolBilgi($uye->rol); @endphp
    <div class="px-6 py-4 flex items-center gap-4">

      {{-- Avatar --}}
      <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-[13px] font-bold text-white"
           style="background:{{ $bilgi['renk'] }}">
        {{ mb_strtoupper(mb_substr($uye->name, 0, 1)) }}
      </div>

      {{-- Bilgi --}}
      <a href="{{ route('admin.uyeler.show', $uye) }}" class="flex-1 min-w-0 hover:text-[#0F172A] transition-colors">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="text-[13px] font-semibold text-[#0F172A] truncate">{{ $uye->name }}</span>
          <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-[0.06em] uppercase px-2 py-0.5 rounded-full border"
                style="background:{{ $bilgi['bg'] }};color:{{ $bilgi['renk'] }};border-color:{{ $bilgi['sinir'] }};">
            <i class="ti {{ $bilgi['ikon'] }} text-[9px]"></i> {{ $bilgi['isim'] }}
          </span>
        </div>
        <p class="text-[11px] text-[#94A3B8] truncate mt-0.5">{{ $uye->email }}</p>
        <p class="text-[10px] text-[#C0C0C0] mt-0.5">Kayıt: {{ $uye->created_at->format('d.m.Y') }}</p>
      </a>

      {{-- Sil --}}
      <form action="{{ route('admin.uyeler.destroy', $uye) }}" method="POST"
            onsubmit="return confirm('{{ $uye->name }} adlı üyeyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.')">
        @csrf @method('DELETE')
        <button type="submit"
                class="w-8 h-8 flex items-center justify-center border border-[#E2E8F0] rounded-[6px] text-[#94A3B8] hover:text-[#CC2200] hover:border-red-300 transition-colors bg-white"
                title="Üyeyi sil">
          <i class="ti ti-trash text-sm"></i>
        </button>
      </form>

      {{-- Rol Seçimi --}}
      <form action="{{ route('admin.uyeler.rol', $uye) }}" method="POST">
        @csrf @method('PATCH')
        <select name="rol" onchange="this.form.submit()"
                class="text-[11px] font-semibold px-3 py-2 rounded-[8px] border border-[#E2E8F0] bg-white text-[#0F172A] cursor-pointer focus:outline-none hover:border-[rgba(0,0,0,0.30)] transition-colors"
                title="Üyelik tipini değiştir">
          <option value="standart" {{ $uye->rol === 'standart' ? 'selected' : '' }}>Standart</option>
          <option value="sucek"    {{ $uye->rol === 'sucek'    ? 'selected' : '' }}>Suçek</option>
          <option value="teknik"   {{ $uye->rol === 'teknik'   ? 'selected' : '' }}>Teknik</option>
        </select>
      </form>

    </div>
    @endforeach
  </div>

  {{-- Sayfalama --}}
  @if($uyeler->hasPages())
  <div class="px-6 py-4 border-t border-[#E2E8F0]">
    {{ $uyeler->links() }}
  </div>
  @endif
  @endif
</div>
@endsection


