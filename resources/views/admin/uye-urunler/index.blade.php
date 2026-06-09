@extends('admin.layouts.app')
@section('title', 'Üye Ürünleri')
@section('page-title', 'Üye Ürünleri')

@section('header-actions')
<span class="text-[12px] text-[#94A3B8]">Teknik üyelerin katalog için gönderdiği ürünler</span>
@endsection

@section('content')

@if($userUrunler->isEmpty())
<div class="bg-white border border-[#E2E8F0] rounded-xl p-16 text-center">
  <div class="w-14 h-14 rounded-full bg-[#F1F5F9] flex items-center justify-center mx-auto mb-4">
    <i class="ti ti-box text-[#94A3B8] text-2xl"></i>
  </div>
  <p class="text-[15px] font-medium text-[#0F172A] mb-1">Henüz ürün gönderilmedi</p>
  <p class="text-[13px] text-[#94A3B8]">Teknik üyeler katalog oluşturucu üzerinden ürün gönderdiğinde burada görünür.</p>
</div>
@else

<div class="bg-white border border-[#E2E8F0] rounded-xl overflow-hidden">
  <table class="w-full text-[13px]">
    <thead>
      <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
        <th class="text-left px-5 py-3 text-[11px] font-semibold text-[#64748B] uppercase tracking-[.06em] w-16">Görsel</th>
        <th class="text-left px-5 py-3 text-[11px] font-semibold text-[#64748B] uppercase tracking-[.06em]">Ürün Adı</th>
        <th class="text-left px-5 py-3 text-[11px] font-semibold text-[#64748B] uppercase tracking-[.06em]">Üye</th>
        <th class="text-left px-5 py-3 text-[11px] font-semibold text-[#64748B] uppercase tracking-[.06em]">Tarih</th>
        <th class="text-right px-5 py-3 text-[11px] font-semibold text-[#64748B] uppercase tracking-[.06em]">İşlem</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-[#F1F5F9]">
      @foreach($userUrunler as $uu)
      <tr class="hover:bg-[#FAFAFA] transition-colors">

        {{-- Görsel --}}
        <td class="px-5 py-3">
          @if($uu->gorsel && \Illuminate\Support\Facades\Storage::disk('public')->exists($uu->gorsel))
          <img src="{{ Storage::url($uu->gorsel) }}" alt="{{ $uu->ad }}"
               class="w-12 h-12 rounded-[8px] object-cover border border-[#E2E8F0]">
          @else
          <div class="w-12 h-12 rounded-[8px] bg-[#F1F5F9] flex items-center justify-center border border-[#E2E8F0]">
            <i class="ti ti-photo text-[#CBD5E1] text-lg"></i>
          </div>
          @endif
        </td>

        {{-- Ad + Açıklama --}}
        <td class="px-5 py-3">
          <p class="font-medium text-[#0F172A]">{{ $uu->ad }}</p>
          @if($uu->aciklama)
          <p class="text-[11px] text-[#94A3B8] mt-0.5 line-clamp-1">{{ Str::limit(strip_tags($uu->aciklama), 70) }}</p>
          @endif
        </td>

        {{-- Üye --}}
        <td class="px-5 py-3">
          <p class="font-medium text-[#334155]">{{ $uu->user?->name ?? '—' }}</p>
          <p class="text-[11px] text-[#94A3B8]">{{ $uu->user?->email ?? '' }}</p>
        </td>

        {{-- Tarih --}}
        <td class="px-5 py-3 text-[#64748B] whitespace-nowrap">
          {{ $uu->created_at->format('d.m.Y') }}<br>
          <span class="text-[11px] text-[#94A3B8]">{{ $uu->created_at->format('H:i') }}</span>
        </td>

        {{-- İşlem --}}
        <td class="px-5 py-3 text-right">
          <a href="{{ route('admin.uye-urunleri.donustur', $uu->id) }}"
             class="inline-flex items-center gap-1.5 bg-[#0F172A] hover:bg-[#1E293B] text-white text-[11px] font-semibold px-3 py-1.5 rounded-[6px] transition-colors whitespace-nowrap">
            <i class="ti ti-arrow-right"></i>
            Mağazaya Ekle
          </a>
        </td>

      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@if($userUrunler->hasPages())
<div class="mt-4">{{ $userUrunler->links() }}</div>
@endif

@endif

@endsection
