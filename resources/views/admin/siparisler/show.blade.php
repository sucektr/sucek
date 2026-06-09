@extends('admin.layouts.app')
@section('title', 'Sipariş — '.$siparis->referans)
@section('page-title', 'Sipariş Detayı')

@section('breadcrumb')
<a href="{{ route('admin.siparisler.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A]">Siparişler</a>
<span class="text-[#D0D0D0] text-xs">›</span>
<span class="text-[12px] text-[#64748B] font-mono">{{ $siparis->referans }}</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.siparisler.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">← Geri</a>
@endsection

@section('content')

@if(session('basari'))
<div class="mb-5 flex items-center gap-2 bg-[#E6F4EC] border border-[#86EFAC] text-[#1A5C3A] text-[12px] px-4 py-3 rounded-[8px]">
  <i class="ti ti-circle-check"></i>{{ session('basari') }}
</div>
@endif

<div class="grid lg:grid-cols-3 gap-6">

  {{-- ─── Sol: Kalemler + Adres ──────────────────────────────────────── --}}
  <div class="lg:col-span-2 space-y-5">

    {{-- Sipariş Kalemleri --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
      <h2 class="text-[13px] font-semibold text-[#0F172A] mb-5 pb-4 border-b border-[#E2E8F0]">
        Sipariş Kalemleri
      </h2>
      <ul class="space-y-3 mb-5">
        @foreach($siparis->kalemler as $kalem)
        <li class="flex items-center gap-3">
          @if($kalem->urun_gorsel)
          <img src="{{ asset('storage/'.$kalem->urun_gorsel) }}" alt="{{ $kalem->urun_adi }}"
               class="w-12 h-12 object-cover rounded-[6px] shrink-0 bg-[#F1F5F9]">
          @else
          <div class="w-12 h-12 rounded-[6px] bg-[#F1F5F9] flex items-center justify-center shrink-0">
            <i class="ti ti-photo text-[#C0C0C0]"></i>
          </div>
          @endif
          <div class="flex-1 min-w-0">
            <p class="text-[13px] font-medium text-[#0F172A]">{{ $kalem->urun_adi }}</p>
            <p class="text-[11px] text-[#94A3B8]">
              {{ ucfirst($kalem->urun_tipi) }} — {{ $kalem->adet }} adet × {{ number_format($kalem->birim_fiyat, 0, ',', '.') }} ₺
            </p>
          </div>
          <span class="text-[13px] font-semibold text-[#0F172A]">{{ number_format($kalem->toplam, 0, ',', '.') }} ₺</span>
        </li>
        @endforeach
      </ul>
      <div class="border-t border-[#E2E8F0] pt-4 space-y-1.5">
        <div class="flex justify-between text-[12px] text-[#64748B]">
          <span>Ara Toplam (KDV hariç)</span><span>{{ number_format($siparis->ara_toplam, 2, ',', '.') }} ₺</span>
        </div>
        @if($siparis->kdv_tutari > 0)
        <div class="flex justify-between text-[12px] text-[#64748B]">
          <span>KDV</span><span>{{ number_format($siparis->kdv_tutari, 2, ',', '.') }} ₺</span>
        </div>
        @endif
        <div class="flex justify-between text-[12px] text-[#64748B]">
          <span>Kargo</span><span>{{ $siparis->kargo_ucreti > 0 ? number_format($siparis->kargo_ucreti, 2, ',', '.') . ' ₺' : 'Ücretsiz' }}</span>
        </div>
        <div class="flex justify-between text-[14px] font-bold text-[#0F172A] pt-1.5 border-t border-[#E2E8F0]">
          <span>Toplam</span><span>{{ number_format($siparis->toplam, 2, ',', '.') }} ₺</span>
        </div>
      </div>
    </div>

    {{-- Teslimat Adresi --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
      <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Teslimat Adresi</h2>
      <p class="text-[13px] font-medium text-[#0F172A]">{{ $siparis->ad_soyad }}</p>
      @if($siparis->telefon)
      <p class="text-[12px] text-[#64748B]">{{ $siparis->telefon }}</p>
      @endif
      <p class="text-[12px] text-[#64748B] mt-1">{{ $siparis->teslimat_adresi['adres_satiri'] }}</p>
      <p class="text-[12px] text-[#64748B]">
        {{ ($siparis->teslimat_adresi['ilce'] ?? '') ? $siparis->teslimat_adresi['ilce'] . ' / ' : '' }}{{ $siparis->teslimat_adresi['sehir'] }}
        {{ ($siparis->teslimat_adresi['posta_kodu'] ?? '') ? '— ' . $siparis->teslimat_adresi['posta_kodu'] : '' }}
      </p>
      <p class="text-[12px] text-[#94A3B8] mt-2">
        <i class="ti ti-mail text-xs mr-1"></i>{{ $siparis->email }}
      </p>
    </div>

    @if($siparis->musteri_notu)
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
      <h2 class="text-[13px] font-semibold text-[#0F172A] mb-3">Müşteri Notu</h2>
      <p class="text-[13px] text-[#64748B] leading-relaxed">{{ $siparis->musteri_notu }}</p>
    </div>
    @endif

  </div>

  {{-- ─── Sağ: Durum Yönetimi ────────────────────────────────────────── --}}
  <div class="space-y-5">

    {{-- Sipariş Bilgisi --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
      <p class="text-[9px] font-semibold tracking-[2px] uppercase text-[#A8A8A8] mb-1">Sipariş No</p>
      <p class="font-mono text-[15px] font-bold text-[#0F172A] mb-4">{{ $siparis->referans }}</p>

      <div class="space-y-2 text-[12px]">
        <div class="flex justify-between">
          <span class="text-[#94A3B8]">Tarih</span>
          <span class="text-[#0F172A]">{{ $siparis->created_at->format('d.m.Y H:i') }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#94A3B8]">Ödeme</span>
          <span class="text-[#0F172A]">Havale / EFT</span>
        </div>
        @if($siparis->user)
        <div class="flex justify-between">
          <span class="text-[#94A3B8]">Üye</span>
          <a href="{{ route('admin.uyeler.show', $siparis->user) }}" class="text-[#0F172A] hover:underline">{{ $siparis->user->name }}</a>
        </div>
        @endif
      </div>

      <div class="mt-4 pt-4 border-t border-[#E2E8F0]">
        <span class="inline-flex px-3 py-1.5 rounded-full text-[11px] font-semibold {{ $siparis->durumRenk() }}">
          {{ $siparis->durumEtiketi() }}
        </span>
      </div>
    </div>

    {{-- Durum Güncelle --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
      <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Durumu Güncelle</h2>
      <form action="{{ route('admin.siparisler.durum', $siparis) }}" method="POST" class="space-y-4">
        @csrf @method('PATCH')

        <div>
          <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#64748B] mb-1.5">Yeni Durum</label>
          <select name="durum" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
            @foreach([
              'bekliyor'      => 'Ödeme Bekleniyor',
              'odeme_alindi'  => 'Ödeme Alındı',
              'hazirlaniyor'  => 'Hazırlanıyor',
              'kargolandi'    => 'Kargolandı',
              'teslim_edildi' => 'Teslim Edildi',
              'iptal'         => 'İptal Edildi',
            ] as $val => $lbl)
            <option value="{{ $val }}" {{ $siparis->durum === $val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#64748B] mb-1.5">Admin Notu</label>
          <textarea name="admin_notu" rows="3"
                    class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors resize-none"
                    placeholder="Kargo takip numarası, açıklama...">{{ $siparis->admin_notu }}</textarea>
        </div>

        <button type="submit"
                class="w-full bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[8px] hover:bg-[#a31b00] transition-colors">
          Güncelle
        </button>
      </form>
    </div>

  </div>
</div>

@endsection


