@extends('layouts.app')

@section('title', 'Siparişiniz Alındı — SUÇEK')

@section('content')

<div class="section max-w-2xl mx-auto">

  {{-- Başarı Mesajı --}}
  <div class="text-center mb-10">
    <div class="w-16 h-16 rounded-full bg-[#E6F4EC] flex items-center justify-center mx-auto mb-5">
      <i class="ti ti-check text-2xl text-[#1A5C3A]"></i>
    </div>
    <h1 class="font-serif-sc text-[28px] font-bold text-[#0F0F0F] mb-2">Siparişiniz Alındı</h1>
    <p class="text-[13px] text-[#5A5A5A] leading-relaxed max-w-sm mx-auto">
      Teşekkürler! Ödemeniz onaylandıktan sonra siparişiniz hazırlanmaya başlanacak.
    </p>
  </div>

  {{-- Sipariş Özeti --}}
  <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] p-6 mb-5">
    <div class="flex items-center justify-between mb-5 pb-4 border-b border-[rgba(0,0,0,0.06)]">
      <div>
        <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-0.5">Sipariş No</p>
        <p class="text-[16px] font-bold text-[#0F0F0F] font-mono tracking-wide">{{ $siparis->referans }}</p>
      </div>
      <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-semibold {{ $siparis->durumRenk() }}">
        {{ $siparis->durumEtiketi() }}
      </span>
    </div>

    <ul class="space-y-3 mb-5">
      @foreach($siparis->kalemler as $kalem)
      <li class="flex items-center gap-3">
        @if($kalem->urun_gorsel)
        <img src="{{ asset('storage/'.$kalem->urun_gorsel) }}" alt="{{ $kalem->urun_adi }}"
             class="w-12 h-12 object-cover rounded-[6px] shrink-0 bg-[#F0F0F0]">
        @else
        <div class="w-12 h-12 rounded-[6px] bg-[#F0F0F0] flex items-center justify-center shrink-0">
          <i class="ti ti-photo text-[#C0C0C0]"></i>
        </div>
        @endif
        <div class="flex-1 min-w-0">
          <p class="text-[12px] font-medium text-[#0F0F0F] truncate">{{ $kalem->urun_adi }}</p>
          <p class="text-[11px] text-[#A0A0A0]">{{ $kalem->adet }} adet × {{ number_format($kalem->birim_fiyat, 0, ',', '.') }} ₺</p>
        </div>
        <span class="text-[13px] font-semibold text-[#0F0F0F] shrink-0">
          {{ number_format($kalem->toplam, 0, ',', '.') }} ₺
        </span>
      </li>
      @endforeach
    </ul>

    <div class="border-t border-[rgba(0,0,0,0.06)] pt-4 space-y-1.5">
      <div class="flex justify-between text-[12px] text-[#5A5A5A]">
        <span>Ara Toplam (KDV hariç)</span>
        <span>{{ number_format($siparis->ara_toplam, 2, ',', '.') }} ₺</span>
      </div>
      @if($siparis->kdv_tutari > 0)
      <div class="flex justify-between text-[12px] text-[#5A5A5A]">
        <span>KDV</span>
        <span>{{ number_format($siparis->kdv_tutari, 2, ',', '.') }} ₺</span>
      </div>
      @endif
      <div class="flex justify-between text-[12px] text-[#5A5A5A]">
        <span>Kargo</span>
        @if($siparis->kargo_ucreti > 0)
        <span>{{ number_format($siparis->kargo_ucreti, 2, ',', '.') }} ₺</span>
        @else
        <span class="text-[#1A5C3A]">Ücretsiz</span>
        @endif
      </div>
      <div class="flex justify-between text-[14px] font-bold text-[#0F0F0F] pt-1.5 border-t border-[rgba(0,0,0,0.06)]">
        <span>Toplam</span>
        <span>{{ number_format($siparis->toplam, 2, ',', '.') }} ₺</span>
      </div>
    </div>
  </div>

  {{-- Havale Bilgileri --}}
  @if(!empty($bankalar))
  <div class="bg-[#0F0F0F] rounded-[12px] p-6 mb-5">
    <p class="text-[9px] font-semibold tracking-[2px] uppercase text-[rgba(255,255,255,0.45)] mb-4">Ödeme Bilgileri</p>

    @php $tekBanka = count($bankalar) === 1; @endphp
    <div class="{{ $tekBanka ? 'space-y-4' : 'space-y-4' }}">
      @foreach($bankalar as $banka)

      @if(!$tekBanka)
      {{-- Çoklu banka: her biri kompakt kart --}}
      <div class="p-4 rounded-[10px] bg-[rgba(255,255,255,0.05)] space-y-3{{ !$loop->last ? ' mb-1' : '' }}">
        <p class="text-[10px] font-semibold text-[rgba(255,255,255,0.55)] tracking-[1px] uppercase flex items-center gap-1.5">
          <i class="ti ti-building-bank text-xs"></i>
          {{ $banka['banka'] ?: ('Hesap ' . $loop->iteration) }}
        </p>
        @if($banka['iban'])
        <p class="text-[13px] font-mono font-medium text-white tracking-wider">{{ $banka['iban'] }}</p>
        @endif
        @if($banka['alici'])
        <p class="text-[11px] text-[rgba(255,255,255,0.55)]">{{ $banka['alici'] }}</p>
        @endif
      </div>
      @if(!$loop->last)
      <div class="border-t border-[rgba(255,255,255,0.07)]"></div>
      @endif

      @else
      {{-- Tek banka: geniş ikon-satır düzeni --}}
      <div class="flex items-start gap-4">
        <div class="w-9 h-9 rounded-[8px] bg-[rgba(255,255,255,0.08)] flex items-center justify-center shrink-0">
          <i class="ti ti-building-bank text-[rgba(255,255,255,0.60)] text-base"></i>
        </div>
        <div>
          <p class="text-[10px] text-[rgba(255,255,255,0.40)] uppercase tracking-[1px] mb-0.5">Banka</p>
          <p class="text-[13px] font-medium text-white">{{ $banka['banka'] }}</p>
        </div>
      </div>
      @if($banka['iban'])
      <div class="flex items-start gap-4">
        <div class="w-9 h-9 rounded-[8px] bg-[rgba(255,255,255,0.08)] flex items-center justify-center shrink-0">
          <i class="ti ti-credit-card text-[rgba(255,255,255,0.60)] text-base"></i>
        </div>
        <div>
          <p class="text-[10px] text-[rgba(255,255,255,0.40)] uppercase tracking-[1px] mb-0.5">IBAN</p>
          <p class="text-[13px] font-mono font-medium text-white tracking-wider">{{ $banka['iban'] }}</p>
        </div>
      </div>
      @endif
      @if($banka['alici'])
      <div class="flex items-start gap-4">
        <div class="w-9 h-9 rounded-[8px] bg-[rgba(255,255,255,0.08)] flex items-center justify-center shrink-0">
          <i class="ti ti-user text-[rgba(255,255,255,0.60)] text-base"></i>
        </div>
        <div>
          <p class="text-[10px] text-[rgba(255,255,255,0.40)] uppercase tracking-[1px] mb-0.5">Alıcı</p>
          <p class="text-[13px] font-medium text-white">{{ $banka['alici'] }}</p>
        </div>
      </div>
      @endif
      @endif

      @endforeach
    </div>

    <div class="mt-5 pt-4 border-t border-[rgba(255,255,255,0.08)]">
      <div class="flex items-start gap-3 bg-[rgba(255,255,255,0.05)] rounded-[8px] px-4 py-3">
        <i class="ti ti-info-circle text-[#B8962E] text-base shrink-0 mt-0.5"></i>
        <p class="text-[11px] text-[rgba(255,255,255,0.65)] leading-relaxed">
          Transfer açıklamasına <strong class="text-white font-mono">{{ $siparis->referans }}</strong> sipariş numaranızı yazmayı unutmayın. Ödemeniz 1–2 iş günü içinde onaylanır.
        </p>
      </div>
    </div>
  </div>
  @endif

  {{-- Teslimat Adresi --}}
  <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] p-5 mb-8">
    <p class="text-[9px] font-semibold tracking-[2px] uppercase text-[#A8A8A8] mb-3">Teslimat Adresi</p>
    <p class="text-[13px] font-medium text-[#0F0F0F]">{{ $siparis->ad_soyad }}</p>
    <p class="text-[12px] text-[#5A5A5A] mt-1">{{ $siparis->teslimat_adresi['adres_satiri'] }}</p>
    <p class="text-[12px] text-[#5A5A5A]">
      {{ $siparis->teslimat_adresi['ilce'] ? $siparis->teslimat_adresi['ilce'].' / ' : '' }}{{ $siparis->teslimat_adresi['sehir'] }}
      {{ $siparis->teslimat_adresi['posta_kodu'] ? '— '.$siparis->teslimat_adresi['posta_kodu'] : '' }}
    </p>
  </div>

  {{-- Aksiyon Butonları --}}
  <div class="flex flex-col sm:flex-row gap-3 justify-center">
    <a href="{{ route('home') }}"
       class="flex items-center justify-center gap-2 px-8 py-3 bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase rounded-[10px] hover:bg-[#2a2a2a] transition-colors min-h-[48px]">
      <i class="ti ti-home text-sm"></i>Ana Sayfaya Dön
    </a>
    @auth
    <a href="{{ route('hesabim.index') }}"
       class="flex items-center justify-center gap-2 px-8 py-3 border border-[rgba(0,0,0,0.15)] text-[#5A5A5A] text-[11px] font-semibold tracking-[1.5px] uppercase rounded-[10px] hover:border-[#0F0F0F] hover:text-[#0F0F0F] transition-colors min-h-[48px]">
      <i class="ti ti-user text-sm"></i>Hesabım
    </a>
    @endauth
  </div>

</div>

@endsection
