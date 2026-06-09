@extends('admin.layouts.app')
@section('title', 'Siparişler')
@section('page-title', 'Siparişler')

@section('content')

@php
$durumlar = [
  'bekliyor'      => ['etiket' => 'Ödeme Bekleniyor', 'renk' => 'bg-[#FEF3C7] text-[#D97706]'],
  'odeme_alindi'  => ['etiket' => 'Ödeme Alındı',     'renk' => 'bg-[#DBEAFE] text-[#1D4ED8]'],
  'hazirlaniyor'  => ['etiket' => 'Hazırlanıyor',     'renk' => 'bg-[#EDE9FE] text-[#7C3AED]'],
  'kargolandi'    => ['etiket' => 'Kargolandı',        'renk' => 'bg-[#D1FAE5] text-[#065F46]'],
  'teslim_edildi' => ['etiket' => 'Teslim Edildi',     'renk' => 'bg-[#E6F4EC] text-[#1A5C3A]'],
  'iptal'         => ['etiket' => 'İptal',             'renk' => 'bg-[#FEE2E2] text-[#DC2626]'],
];
@endphp

<div class="flex flex-wrap gap-2 mb-5">
  <a href="{{ route('admin.siparisler.index') }}"
     class="px-4 py-2 text-[10px] font-semibold tracking-[1px] uppercase rounded-[8px] border transition-all
            {{ !$durum ? 'bg-[#CC2200] text-white border-transparent' : 'bg-white text-[#64748B] border-[#E2E8F0] hover:border-[rgba(0,0,0,0.25)]' }}">
    Tümü <span class="ml-1 opacity-60">({{ $sayilar->sum() }})</span>
  </a>
  @foreach($durumlar as $key => $d)
  <a href="{{ route('admin.siparisler.index', ['durum' => $key]) }}"
     class="px-4 py-2 text-[10px] font-semibold tracking-[1px] uppercase rounded-[8px] border transition-all
            {{ $durum === $key ? 'bg-[#CC2200] text-white border-transparent' : 'bg-white text-[#64748B] border-[#E2E8F0] hover:border-[rgba(0,0,0,0.25)]' }}">
    {{ $d['etiket'] }} <span class="ml-1 opacity-60">({{ $sayilar[$key] ?? 0 }})</span>
  </a>
  @endforeach
</div>

@if(session('basari'))
<div class="mb-4 flex items-center gap-2 bg-[#E6F4EC] border border-[#86EFAC] text-[#1A5C3A] text-[12px] px-4 py-3 rounded-[8px]">
  <i class="ti ti-circle-check"></i>{{ session('basari') }}
</div>
@endif

<div x-data="{
  secili: [],
  tumunuSec: false,
  tumIds: {{ $siparisler->pluck('id')->toJson() }},
  toggle(id) {
    if (this.secili.includes(id)) {
      this.secili = this.secili.filter(i => i !== id);
    } else {
      this.secili.push(id);
    }
    this.tumunuSec = this.secili.length === this.tumIds.length;
  },
  tumunuToggle() {
    if (this.tumunuSec) {
      this.secili = [...this.tumIds];
    } else {
      this.secili = [];
    }
  },
  islem(tip) {
    if (this.secili.length === 0) return;
    const mesaj = tip === 'sil'
      ? this.secili.length + ' siparişi kalıcı olarak silmek istediğinizden emin misiniz?'
      : this.secili.length + ' siparişi iptal etmek istediğinizden emin misiniz?';
    if (!confirm(mesaj)) return;
    document.getElementById('toplu-islem-form').querySelector('[name=islem]').value = tip;
    document.getElementById('toplu-islem-form').submit();
  }
}">

  {{-- Toplu İşlem Formu (gizli) --}}
  <form id="toplu-islem-form" method="POST" action="{{ route('admin.siparisler.toplu') }}" class="hidden">
    @csrf
    <input type="hidden" name="islem" value="">
    <template x-for="id in secili" :key="id">
      <input type="hidden" name="ids[]" :value="id">
    </template>
  </form>

  {{-- Aksiyon Çubuğu --}}
  <div x-show="secili.length > 0"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 translate-y-2"
       class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-[#CC2200] text-white px-5 py-3 rounded-[12px] shadow-xl">
    <span class="text-[12px] font-medium" x-text="secili.length + ' sipariş seçildi'"></span>
    <div class="w-px h-4 bg-white/20"></div>
    <button type="button" @click="islem('iptal')"
            class="flex items-center gap-1.5 text-[11px] font-semibold bg-[#D97706] hover:bg-[#B45309] px-3 py-1.5 rounded-[6px] transition-colors">
      <i class="ti ti-ban text-xs"></i>İptal Et
    </button>
    <button type="button" @click="islem('sil')"
            class="flex items-center gap-1.5 text-[11px] font-semibold bg-[#DC2626] hover:bg-[#B91C1C] px-3 py-1.5 rounded-[6px] transition-colors">
      <i class="ti ti-trash text-xs"></i>Sil
    </button>
    <button type="button" @click="secili = []; tumunuSec = false"
            class="flex items-center gap-1 text-[11px] text-white/60 hover:text-white transition-colors ml-1">
      <i class="ti ti-x text-xs"></i>
    </button>
  </div>

  <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
            <th class="pl-5 pr-2 py-3.5 w-10">
              <input type="checkbox" x-model="tumunuSec" @change="tumunuToggle()"
                     class="w-4 h-4 rounded accent-[#CC2200] cursor-pointer">
            </th>
            <th class="px-4 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Sipariş No</th>
            <th class="px-4 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Müşteri</th>
            <th class="px-4 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ürünler</th>
            <th class="px-4 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Toplam</th>
            <th class="px-4 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Durum</th>
            <th class="px-4 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Tarih</th>
            <th class="px-4 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
          @forelse($siparisler as $s)
          <tr class="hover:bg-[#F8FAFC] transition-colors" :class="secili.includes({{ $s->id }}) ? 'bg-[#F8F6FF]' : ''">
            <td class="pl-5 pr-2 py-3">
              <input type="checkbox"
                     :checked="secili.includes({{ $s->id }})"
                     @change="toggle({{ $s->id }})"
                     class="w-4 h-4 rounded accent-[#CC2200] cursor-pointer">
            </td>
            <td class="px-4 py-3">
              <span class="font-mono text-[12px] font-medium text-[#0F172A]">{{ $s->referans }}</span>
            </td>
            <td class="px-4 py-3">
              <p class="text-[13px] font-medium text-[#0F172A]">{{ $s->ad_soyad }}</p>
              <p class="text-[11px] text-[#94A3B8]">{{ $s->email }}</p>
            </td>
            <td class="px-4 py-3">
              <span class="text-[12px] text-[#64748B]">{{ $s->kalemler->count() }} kalem</span>
            </td>
            <td class="px-4 py-3">
              <span class="text-[13px] font-semibold text-[#0F172A]">{{ number_format($s->toplam, 0, ',', '.') }} ₺</span>
            </td>
            <td class="px-4 py-3">
              <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $s->durumRenk() }}">
                {{ $s->durumEtiketi() }}
              </span>
            </td>
            <td class="px-4 py-3 text-[12px] text-[#64748B]">
              {{ $s->created_at->format('d.m.Y H:i') }}
            </td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('admin.siparisler.show', $s) }}"
                 class="w-8 h-8 inline-flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors">
                <i class="ti ti-eye text-sm"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="px-5 py-16 text-center text-[13px] text-[#94A3B8]">
              <i class="ti ti-shopping-bag text-3xl text-[#D0D0D0] block mb-2"></i>
              Henüz sipariş yok.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($siparisler->hasPages())
    <div class="px-5 py-4 border-t border-[#E2E8F0]">{{ $siparisler->links() }}</div>
    @endif
  </div>

</div>

@endsection


