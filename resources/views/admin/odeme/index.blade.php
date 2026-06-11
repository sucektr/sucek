@extends('admin.layouts.app')
@section('title', 'Ödeme Yönetimi')
@section('page-title', 'Ödeme Yönetimi')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">Araçlar</span>
@endsection

@section('content')
<div class="space-y-6">

  {{-- Flash --}}
  @if(session('basari'))
    <div class="bg-[#F0FDF4] border border-[#86EFAC] rounded-[10px] px-4 py-3 text-[13px] text-[#166534] flex items-center gap-2">
      <i class="ti ti-circle-check text-sm shrink-0"></i>{{ session('basari') }}
    </div>
  @endif
  @if(session('hata'))
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[10px] px-4 py-3 text-[13px] text-[#991B1B] flex items-center gap-2">
      <i class="ti ti-alert-circle text-sm shrink-0"></i>{{ session('hata') }}
    </div>
  @endif

  {{-- İstatistikler --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach([
      ['Bugün', $istatistik['bugun'], '#3B82F6'],
      ['Bu Ay', $istatistik['bu_ay'], '#8B5CF6'],
      ['Başarılı', $istatistik['basarili'], '#22C55E'],
      ['Bekliyor', $istatistik['bekliyor'], '#F59E0B'],
    ] as [$etiket, $sayi, $renk])
    <div class="bg-white border border-[#E2E8F0] rounded-[12px] p-4">
      <p class="text-[12px] text-[#94A3B8] mb-1">{{ $etiket }}</p>
      <p class="text-[26px] font-bold" style="color:{{ $renk }}">{{ $sayi }}</p>
      <p class="text-[10px] text-[#C0C0C0] mt-0.5">Kredi kartı siparişi</p>
    </div>
    @endforeach
  </div>

  {{-- PayTR Ayarları --}}
  <div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden">
    <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center justify-between">
      <div class="flex items-center gap-2">
        <i class="ti ti-credit-card text-[#CC2200]"></i>
        <span class="text-[14px] font-semibold text-[#0F172A]">PayTR Ayarları</span>
      </div>
      @if($paytrAyarlar['merchant_id'])
        @if($paytrAyarlar['test_mode'] === '1')
          <span class="text-[11px] bg-[#FEF3C7] text-[#92400E] border border-yellow-300 px-2 py-0.5 rounded-full font-semibold">
            <i class="ti ti-flask text-[10px]"></i> Test Modu
          </span>
        @else
          <span class="text-[11px] bg-[#F0FDF4] text-[#166534] border border-[#86EFAC] px-2 py-0.5 rounded-full font-semibold">
            <i class="ti ti-check text-[10px]"></i> Canlı Mod
          </span>
        @endif
      @else
        <span class="text-[11px] bg-[#FEF2F2] text-[#991B1B] border border-[#FECACA] px-2 py-0.5 rounded-full">Yapılandırılmadı</span>
      @endif
    </div>
    <div class="p-6" x-data="{ ac: false }">
      <button type="button" @click="ac=!ac"
              class="text-[12px] text-[#64748B] hover:text-[#0F172A] flex items-center gap-1 mb-4">
        <i class="ti text-xs" :class="ac ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
        <span x-text="ac ? 'Gizle' : 'Düzenle'">Düzenle</span>
      </button>
      <form x-show="ac" action="{{ route('admin.odeme.ayarlar') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Merchant ID</label>
            <input type="text" name="merchant_id" value="{{ $paytrAyarlar['merchant_id'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] font-mono text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="123456">
          </div>
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Merchant Key</label>
            <input type="text" name="merchant_key" value="{{ $paytrAyarlar['merchant_key'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] font-mono text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="xxxxxxxxxxx">
          </div>
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Merchant Salt</label>
            <input type="text" name="merchant_salt" value="{{ $paytrAyarlar['merchant_salt'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] font-mono text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="xxxxxxxxxxx">
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-[12px] font-medium text-[#374151] mb-2">Mod</label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="test_mode" value="0" {{ $paytrAyarlar['test_mode'] !== '1' ? 'checked' : '' }}
                     class="accent-[#CC2200]">
              <span class="text-[13px] text-[#0F172A]">Canlı Mod</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" name="test_mode" value="1" {{ $paytrAyarlar['test_mode'] === '1' ? 'checked' : '' }}
                     class="accent-[#F59E0B]">
              <span class="text-[13px] text-[#0F172A]">Test Modu</span>
              <span class="text-[10px] text-[#94A3B8]">(gerçek ödeme alınmaz)</span>
            </label>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <button type="submit"
                  class="bg-[#CC2200] text-white text-[13px] font-semibold px-5 py-2 rounded-[8px] hover:bg-[#A31B00] transition-colors">
            Ayarları Kaydet
          </button>
        </div>
        <p class="text-[11px] text-[#94A3B8] mt-3">
          PayTR panel: <a href="https://www.paytr.com" target="_blank" class="text-[#CC2200] hover:underline">paytr.com</a>
          → Entegrasyon → API Bilgileri bölümünden alınır.
        </p>
      </form>
    </div>
  </div>

  {{-- Son Kart Siparişleri --}}
  <div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden">
    <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center justify-between">
      <div class="flex items-center gap-2">
        <i class="ti ti-list text-[#CC2200]"></i>
        <span class="text-[14px] font-semibold text-[#0F172A]">Son Kredi Kartı Siparişleri</span>
      </div>
      <a href="{{ route('admin.siparisler.index', ['odeme' => 'kredi_karti']) }}"
         class="text-[11px] text-[#64748B] hover:text-[#CC2200] transition-colors">
        Tümünü Gör →
      </a>
    </div>

    @if($kartSiparisler->isEmpty())
    <div class="px-6 py-10 text-center">
      <i class="ti ti-credit-card text-3xl text-[#D0D0D0] mb-2 block"></i>
      <p class="text-[13px] text-[#94A3B8]">Henüz kredi kartı ile sipariş yok.</p>
    </div>
    @else
    <div class="divide-y divide-[#F1F5F9]">
      @foreach($kartSiparisler as $siparis)
      <div class="px-6 py-3 flex items-center gap-4">
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2">
            <span class="text-[12px] font-mono font-semibold text-[#0F172A]">#{{ $siparis->referans }}</span>
            <span class="text-[11px] text-[#94A3B8]">{{ $siparis->ad_soyad }}</span>
          </div>
          <p class="text-[10px] text-[#C0C0C0] mt-0.5">{{ $siparis->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <span class="text-[13px] font-semibold text-[#0F172A] shrink-0">
          {{ number_format($siparis->toplam, 2, ',', '.') }} ₺
        </span>
        @php
          $durumMap = [
            'bekliyor'      => ['Bekliyor',             'bg-[#FEF3C7] text-[#92400E] border-yellow-300'],
            'odeme_alindi'  => ['Ödeme Alındı',         'bg-[#F0FDF4] text-[#166534] border-[#86EFAC]'],
            'hazirlaniyor'  => ['Hazırlanıyor',         'bg-[#EFF6FF] text-[#1D4ED8] border-blue-200'],
            'kargolandi'    => ['Kargolandı',           'bg-[#F5F3FF] text-[#6D28D9] border-purple-200'],
            'teslim_edildi' => ['Teslim Edildi',        'bg-[#F0FDF4] text-[#166534] border-[#86EFAC]'],
            'iptal'         => ['İptal',                'bg-[#FEF2F2] text-[#991B1B] border-[#FECACA]'],
          ];
          [$durumEtiket, $durumClass] = $durumMap[$siparis->durum] ?? [$siparis->durum, 'bg-[#F8FAFC] text-[#64748B] border-[#E2E8F0]'];
        @endphp
        <span class="shrink-0 inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded-full border {{ $durumClass }}">
          {{ $durumEtiket }}
        </span>
        <a href="{{ route('admin.siparisler.show', $siparis) }}"
           class="shrink-0 w-7 h-7 flex items-center justify-center border border-[#E2E8F0] rounded-[6px] text-[#94A3B8] hover:text-[#CC2200] hover:border-[#CC2200] transition-colors">
          <i class="ti ti-eye text-xs"></i>
        </a>
      </div>
      @endforeach
    </div>
    @endif
  </div>

</div>
@endsection
