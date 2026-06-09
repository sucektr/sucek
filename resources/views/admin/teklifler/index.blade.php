@extends('admin.layouts.app')
@section('title', 'Teklifler')
@section('page-title', 'Teklifler')

@section('content')

{{-- Filtre --}}
<form method="GET" class="flex flex-wrap items-center gap-2.5 mb-5">
  <select name="durum" onchange="this.form.submit()"
          class="px-3 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
    <option value="">Tüm Durumlar</option>
    <option value="beklemede" {{ request('durum') === 'beklemede' ? 'selected' : '' }}>Beklemede</option>
    <option value="kabul"     {{ request('durum') === 'kabul'     ? 'selected' : '' }}>Kabul</option>
    <option value="red"       {{ request('durum') === 'red'       ? 'selected' : '' }}>Red</option>
  </select>
  @if(request('durum'))
  <a href="{{ route('admin.teklifler.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">Temizle ×</a>
  @endif
</form>

<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Koleksiyon</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Üye</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Teklif Tutarı</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Mesaj</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Tarih</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Durum</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
        @forelse($teklifler as $t)
        <tr class="hover:bg-[#F8FAFC] transition-colors">
          <td class="px-5 py-3.5">
            <p class="font-medium text-[#0F172A] text-[13px]">{{ $t->koleksiyon?->ad ?? '—' }}</p>
            <p class="text-[11px] text-[#94A3B8]">{{ $t->koleksiyon?->kategori }}</p>
          </td>
          <td class="px-5 py-3.5">
            <p class="text-[13px] text-[#0F172A]">{{ $t->user?->name ?? '—' }}</p>
            <p class="text-[11px] text-[#94A3B8]">{{ $t->user?->email }}</p>
          </td>
          <td class="px-5 py-3.5">
            <span class="text-[14px] font-semibold text-[#0F172A] font-mono">{{ number_format($t->miktar, 0, ',', '.') }} ₺</span>
          </td>
          <td class="px-5 py-3.5 max-w-[220px]">
            <p class="text-[12px] text-[#64748B] line-clamp-2">{{ $t->mesaj ?: '—' }}</p>
          </td>
          <td class="px-5 py-3.5 text-[12px] text-[#94A3B8] whitespace-nowrap">
            {{ $t->created_at->format('d.m.Y H:i') }}
          </td>
          <td class="px-5 py-3.5">
            <form action="{{ route('admin.teklifler.durum', $t) }}" method="POST" class="flex items-center gap-2">
              @csrf @method('PATCH')
              @php
                $renkler = ['beklemede' => 'bg-[#FEF3C7] text-[#D97706]', 'kabul' => 'bg-[#E6F4EC] text-[#1A5C3A]', 'red' => 'bg-[#FEE2E2] text-[#991B1B]'];
                $labels  = ['beklemede' => 'Beklemede', 'kabul' => 'Kabul', 'red' => 'Red'];
              @endphp
              <select name="durum" onchange="this.form.submit()"
                      class="text-[11px] font-semibold px-2.5 py-1 rounded-full border-0 cursor-pointer {{ $renkler[$t->durum] }}">
                @foreach(['beklemede' => 'Beklemede', 'kabul' => 'Kabul', 'red' => 'Red'] as $val => $lbl)
                <option value="{{ $val }}" {{ $t->durum === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-5 py-16 text-center text-[13px] text-[#94A3B8]">
            <i class="ti ti-inbox text-3xl text-[#D0D0D0] block mb-2"></i>
            Henüz teklif gönderilmedi.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($teklifler->hasPages())
  <div class="px-5 py-4 border-t border-[#E2E8F0]">{{ $teklifler->links() }}</div>
  @endif
</div>
@endsection


