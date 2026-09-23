@extends('admin.layouts.app')
@section('title', 'Çeviriler')
@section('page-title', 'Çeviriler')

@section('content')

<p class="text-[13px] text-[#64748B] mb-5">
  Sitedeki sabit metinlerin İngilizce karşılıkları. Yeni bir metin sayfada kullanıldığında otomatik olarak bu listeye düşer — çevirisi boş bırakılan metinler İngilizce modda da Türkçe görünür.
  @if($eksikSayisi ?? 0)
    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#FEF3C7] text-[#D97706] ml-1">{{ $eksikSayisi }} çevirisi eksik</span>
  @endif
</p>

{{-- Arama --}}
<form method="GET" action="{{ route('admin.ceviriler.index') }}" class="flex items-center gap-2.5 mb-5">
  <div class="relative flex-1 max-w-sm">
    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-[#B0B0B0] text-[15px] pointer-events-none"></i>
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="Metin ara..."
           class="w-full pl-9 pr-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
  </div>
  <button type="submit"
          class="flex items-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[.08em] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
    <i class="ti ti-filter text-sm"></i> Filtrele
  </button>
  @if(request('q'))
  <a href="{{ route('admin.ceviriler.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A] transition-colors px-1">Temizle ×</a>
  @endif
</form>

<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8] w-1/2">Türkçe</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8] w-1/2">İngilizce</th>
          <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
        @forelse($ceviriler as $c)
        <tr class="hover:bg-[#F8FAFC] transition-colors {{ !$c->en_metin ? 'bg-[#FFFBEB]' : '' }}">
          <form action="{{ route('admin.ceviriler.guncelle', $c) }}" method="POST" class="contents">
            @csrf @method('PATCH')
            <td class="px-5 py-3 text-[13px] text-[#0F172A] align-top">{{ $c->tr_metin }}</td>
            <td class="px-5 py-3 align-top">
              <input type="text" name="en_metin" value="{{ old('en_metin', $c->en_metin) }}"
                     placeholder="Çevrilmemiş"
                     class="w-full px-3 py-2 border border-[#E2E8F0] rounded-[6px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            </td>
            <td class="px-5 py-3 text-right align-top">
              <button type="submit" class="w-8 h-8 inline-flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors">
                <i class="ti ti-device-floppy text-sm"></i>
              </button>
            </td>
          </form>
        </tr>
        @empty
        <tr>
          <td colspan="3" class="px-5 py-16 text-center text-[13px] text-[#94A3B8]">
            <i class="ti ti-language text-3xl text-[#D0D0D0] block mb-2"></i>
            Henüz kayıtlı çeviri yok — sayfalar ziyaret edildikçe otomatik oluşur.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($ceviriler->hasPages())
  <div class="px-5 py-4 border-t border-[#E2E8F0]">{{ $ceviriler->links() }}</div>
  @endif
</div>
@endsection
