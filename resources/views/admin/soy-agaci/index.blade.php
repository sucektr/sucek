@extends('admin.layouts.app')
@section('title', 'Soy Ağacı Değişiklikleri')
@section('page-title', 'Soy Ağacı Değişiklikleri')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">Soy Ağacı</span>
@endsection

@section('content')

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-[12px] font-medium px-4 py-3 rounded-[8px]">
  {{ session('success') }}
</div>
@endif

{{-- İstatistik --}}
<div class="grid grid-cols-3 gap-4 mb-6">
  @php
    $bekleyen   = $degisiklikler->where('durum', 'beklemede')->count();
    $onaylandi  = $degisiklikler->where('durum', 'onaylandi')->count();
    $reddedildi = $degisiklikler->where('durum', 'reddedildi')->count();
  @endphp
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#D97706]">{{ $bekleyenSayisi }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Bekleyen</p>
  </div>
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-green-600">{{ $onaylandi }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Onaylanan</p>
  </div>
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#CC2200]">{{ $reddedildi }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Reddedilen</p>
  </div>
</div>

{{-- Değişiklikler Tablosu --}}
<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center justify-between">
    <h2 class="text-[13px] font-semibold text-[#0F172A]">Tüm Değişiklik Talepleri</h2>
    <a href="{{ route('soy-agaci.index') }}" target="_blank"
       class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors flex items-center gap-1">
      <i class="ti ti-external-link text-xs"></i> Ağacı Görüntüle
    </a>
  </div>

  @if($degisiklikler->isEmpty())
  <div class="px-6 py-12 text-center">
    <i class="ti ti-check-circle text-4xl text-green-500 mb-3 block"></i>
    <p class="text-[13px] text-[#94A3B8]">Bekleyen değişiklik talebi yok.</p>
  </div>
  @else
  <div class="divide-y divide-[rgba(0,0,0,0.05)]">
    @foreach($degisiklikler as $d)
    <div class="px-6 py-5" x-data="{ acik: {{ $d->durum === 'beklemede' ? 'true' : 'false' }} }">
      <div class="flex items-start gap-4">

        {{-- Tip Badge --}}
        <div class="shrink-0 mt-0.5">
          @php
            $tipRenk = match($d->tip) {
              'kisi_ekle'    => 'bg-blue-50 text-blue-700 border-blue-200',
              'kisi_duzenle' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
              'kisi_sil'     => 'bg-red-50 text-[#CC2200] border-red-200',
              'iliski_ekle'  => 'bg-green-50 text-green-700 border-green-200',
              'iliski_sil'   => 'bg-orange-50 text-orange-700 border-orange-200',
              default        => 'bg-[#F8FAFC] text-[#64748B] border-[#E2E8F0]',
            };
          @endphp
          <span class="inline-flex items-center border text-[10px] font-bold tracking-[0.06em] uppercase px-2 py-1 rounded-[6px] {{ $tipRenk }}">
            {{ $d->tipLabel() }}
          </span>
        </div>

        {{-- İçerik --}}
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-3 mb-1 flex-wrap">
            <span class="text-[13px] font-semibold text-[#0F172A]">
              {{ $d->user->name }}
            </span>
            <span class="text-[11px] text-[#94A3B8]">{{ $d->created_at->format('d.m.Y H:i') }}</span>
            @if($d->durum === 'beklemede')
              <span class="inline-flex items-center gap-1 bg-[#FEF3C7] border border-yellow-300 text-[#92400E] text-[10px] font-bold px-2 py-0.5 rounded-full">
                <i class="ti ti-clock text-[9px]"></i> Bekliyor
              </span>
            @elseif($d->durum === 'onaylandi')
              <span class="inline-flex items-center gap-1 bg-green-50 border border-green-200 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                <i class="ti ti-check text-[9px]"></i> Onaylandı
              </span>
            @else
              <span class="inline-flex items-center gap-1 bg-red-50 border border-red-200 text-[#CC2200] text-[10px] font-bold px-2 py-0.5 rounded-full">
                <i class="ti ti-x text-[9px]"></i> Reddedildi
              </span>
            @endif
          </div>

          {{-- Veri özeti --}}
          <div class="text-[12px] text-[#64748B]">
            @if(in_array($d->tip, ['kisi_ekle', 'kisi_duzenle']))
              <span class="font-medium text-[#0F172A]">{{ $d->veri['ad'] ?? '—' }} {{ $d->veri['soyad'] ?? '' }}</span>
              @if(!empty($d->veri['meslek'])) · {{ $d->veri['meslek'] }}@endif
              @if(!empty($d->veri['bd_yil'])) · {{ $d->veri['bd_yil'] }}@endif
            @elseif($d->tip === 'kisi_sil')
              <span>Kişi ID: #{{ $d->veri['id'] }}</span>
            @elseif(in_array($d->tip, ['iliski_ekle', 'iliski_sil']))
              <span>Kişi #{{ $d->veri['from'] }} → Kişi #{{ $d->veri['to'] }} ({{ $d->veri['tip'] }})</span>
            @endif
          </div>

          {{-- Admin notu --}}
          @if($d->admin_notu)
          <p class="text-[11px] text-[#94A3B8] mt-1 italic">"{{ $d->admin_notu }}"</p>
          @endif

          {{-- Onaylayan --}}
          @if($d->onaylayan)
          <p class="text-[11px] text-[#94A3B8] mt-1">
            {{ $d->durum === 'onaylandi' ? 'Onaylayan' : 'Reddeden' }}: {{ $d->onaylayan->name }} · {{ $d->kararlandi_at->format('d.m.Y H:i') }}
          </p>
          @endif
        </div>

        {{-- Detay toggle --}}
        <button @click="acik = !acik"
                class="shrink-0 text-[#94A3B8] hover:text-[#0F172A] transition-colors">
          <i :class="acik ? 'ti ti-chevron-up' : 'ti ti-chevron-down'" class="text-sm"></i>
        </button>
      </div>

      {{-- Detay + Onay formu --}}
      <div x-show="acik" x-transition class="mt-4 ml-[82px]">

        {{-- Tüm veri --}}
        <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-[8px] p-4 mb-4">
          <p class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.06em] mb-2">Gönderilen Veri</p>
          <dl class="grid grid-cols-2 gap-x-6 gap-y-1.5">
            @foreach($d->veri as $key => $val)
              @if(!is_array($val) && $val !== null && $val !== '')
              <div class="flex gap-2 text-[11px]">
                <dt class="text-[#94A3B8] font-medium min-w-[80px]">{{ $key }}</dt>
                <dd class="text-[#0F172A] font-medium truncate">{{ $val }}</dd>
              </div>
              @endif
            @endforeach
          </dl>
          @if(!empty($d->veri['foto']))
          <div class="mt-3">
            <p class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.06em] mb-2">Fotoğraf</p>
            <img src="{{ Storage::url($d->veri['foto']) }}" class="w-16 h-16 rounded-full object-cover border border-[#E2E8F0]" alt="">
          </div>
          @endif
        </div>

        {{-- Karar formu (sadece beklemede olanlar için) --}}
        @if($d->durum === 'beklemede')
        <form action="{{ route('admin.soy-agaci.karar', $d) }}" method="POST">
          @csrf
          <div class="flex gap-3 items-end flex-wrap">
            <div class="flex-1 min-w-[200px]">
              <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.06em] mb-1.5">Admin Notu (opsiyonel)</label>
              <input type="text" name="admin_notu" placeholder="Ret gerekçesi veya onay notu…"
                     class="w-full px-3 py-2 bg-white text-[#0F172A] placeholder:text-[#94A3B8] border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            </div>
            <button type="submit" name="karar" value="onaylandi"
                    class="flex items-center gap-1.5 text-[11px] font-semibold tracking-[1px] uppercase px-5 py-2.5 rounded-[8px] transition-colors"
                    style="background:#16a34a;color:#ffffff;"
                    onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
              <i class="ti ti-check text-sm"></i> Onayla
            </button>
            <button type="submit" name="karar" value="reddedildi"
                    onclick="return confirm('Bu değişikliği reddetmek istediğinizden emin misiniz?')"
                    class="flex items-center gap-1.5 bg-white border border-[#E2E8F0] text-[#CC2200] text-[11px] font-semibold tracking-[1px] uppercase px-5 py-2.5 rounded-[8px] hover:bg-red-50 transition-colors">
              <i class="ti ti-x text-sm"></i> Reddet
            </button>
          </div>
        </form>
        @endif
      </div>
    </div>
    @endforeach
  </div>

  {{-- Sayfalama --}}
  @if($degisiklikler->hasPages())
  <div class="px-6 py-4 border-t border-[#E2E8F0]">
    {{ $degisiklikler->links() }}
  </div>
  @endif
  @endif
</div>
@endsection


