@extends('admin.layouts.app')
@section('title', $uye->name . ' — Üye Detayı')
@section('page-title', $uye->name)

@section('breadcrumb')
<a href="{{ route('admin.uyeler.index') }}" class="text-[12px] text-[#64748B] hover:text-[#0F172A] transition-colors">Üyeler</a>
<span class="text-[#C0C0C0] mx-1">/</span>
<span class="text-[12px] text-[#64748B]">{{ $uye->name }}</span>
@endsection

@section('header-actions')
<div class="flex items-center gap-2">
  {{-- Rol Seçimi --}}
  <form action="{{ route('admin.uyeler.rol', $uye) }}" method="POST" class="flex items-center gap-2">
    @csrf @method('PATCH')
    <select name="rol" onchange="this.form.submit()"
            class="text-[11px] font-semibold px-3 py-2 rounded-[8px] border border-[#E2E8F0] bg-white text-[#0F172A] cursor-pointer focus:outline-none hover:border-[rgba(0,0,0,0.30)] transition-colors"
            title="Üyelik tipini değiştir">
      <option value="standart" {{ $uye->rol === 'standart' ? 'selected' : '' }}>Standart</option>
      <option value="sucek"    {{ $uye->rol === 'sucek'    ? 'selected' : '' }}>Suçek</option>
      <option value="teknik"   {{ $uye->rol === 'teknik'   ? 'selected' : '' }}>Teknik</option>
    </select>
  </form>

  {{-- Sil --}}
  @if(!$uye->is_admin)
  <form action="{{ route('admin.uyeler.destroy', $uye) }}" method="POST"
        onsubmit="return confirm('{{ $uye->name }} adlı üyeyi silmek istediğinizden emin misiniz?')">
    @csrf @method('DELETE')
    <button type="submit"
            class="flex items-center gap-1.5 text-[11px] font-semibold tracking-[1px] uppercase px-4 py-2 rounded-[8px] border border-[#E2E8F0] text-[#CC2200] bg-white hover:bg-red-50 transition-colors">
      <i class="ti ti-trash text-sm"></i> Sil
    </button>
  </form>
  @endif
</div>
@endsection

@section('content')
@php
  $aylar = ['','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
@endphp

<div class="grid grid-cols-3 gap-6">

  {{-- Sol kolon: Kişisel bilgiler --}}
  <div class="col-span-2 space-y-5">

    {{-- Temel Bilgiler --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC] flex items-center gap-3">
        @php $bilgi = \App\Models\User::rolBilgi($uye->rol); @endphp
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-[15px] font-bold shrink-0"
             style="background:{{ $bilgi['renk'] }}">
          {{ mb_strtoupper(mb_substr($uye->name, 0, 1)) }}
        </div>
        <div>
          <p class="text-[14px] font-semibold text-[#0F172A]">{{ $uye->name }}</p>
          <p class="text-[11px] text-[#94A3B8]">{{ $uye->email }}</p>
        </div>
        <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-bold tracking-[0.06em] uppercase px-2 py-0.5 rounded-full border"
              style="background:{{ $bilgi['bg'] }};color:{{ $bilgi['renk'] }};border-color:{{ $bilgi['sinir'] }};">
          <i class="ti {{ $bilgi['ikon'] }} text-[9px]"></i> {{ $bilgi['isim'] }}
        </span>
      </div>
      <div class="p-6">
        <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-1">Kayıt Tarihi</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $uye->created_at->format('d F Y') }}</dd>
          </div>
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-1">Telefon</dt>
            <dd class="text-[13px] text-[#0F172A]">
              @if($uye->telefon)
                <a href="tel:{{ $uye->telefon }}" class="hover:underline">{{ $uye->telefon }}</a>
              @else
                <span class="text-[#C0C0C0]">—</span>
              @endif
            </dd>
          </div>
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-1">Doğum Tarihi</dt>
            <dd class="text-[13px] text-[#0F172A]">
              @if($uye->dogum_gun && $uye->dogum_ay)
                {{ $uye->dogum_gun }} {{ $aylar[$uye->dogum_ay] }}{{ $uye->dogum_yil ? ' ' . $uye->dogum_yil : '' }}
              @else
                <span class="text-[#C0C0C0]">—</span>
              @endif
            </dd>
          </div>
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-1">SMS İzni</dt>
            <dd class="text-[13px]">
              @if($uye->sms_izni)
                <span class="inline-flex items-center gap-1 text-[#1A5C3A] bg-[#E6F4EC] px-2 py-0.5 rounded-full text-[11px] font-semibold">
                  <i class="ti ti-check text-[10px]"></i> İzin verildi
                </span>
              @else
                <span class="inline-flex items-center gap-1 text-[#64748B] bg-[#F8FAFC] px-2 py-0.5 rounded-full text-[11px] font-semibold">
                  <i class="ti ti-x text-[10px]"></i> İzin verilmedi
                </span>
              @endif
            </dd>
          </div>
        </dl>
      </div>
    </div>

    {{-- Kargo Adresi --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC] flex items-center gap-2">
        <i class="ti ti-truck text-[#64748B]"></i>
        <h2 class="text-[13px] font-semibold text-[#0F172A]">Kargo Adresi</h2>
        @if($kargoAdresi)
        <span class="ml-auto text-[9px] font-semibold tracking-[1px] uppercase text-[#1A5C3A] bg-[#E6F4EC] px-2 py-0.5 rounded-full">Kayıtlı</span>
        @endif
      </div>
      <div class="p-6">
        @if($kargoAdresi)
        <dl class="grid grid-cols-2 gap-x-8 gap-y-3">
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Ad Soyad</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $kargoAdresi->ad_soyad }}</dd>
          </div>
          @if($kargoAdresi->telefon)
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Telefon</dt>
            <dd class="text-[13px] text-[#0F172A]"><a href="tel:{{ $kargoAdresi->telefon }}" class="hover:underline">{{ $kargoAdresi->telefon }}</a></dd>
          </div>
          @endif
          <div class="col-span-2">
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Adres</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $kargoAdresi->adres_satiri }}</dd>
          </div>
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Şehir / İlçe</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $kargoAdresi->sehir }}{{ $kargoAdresi->ilce ? ' / ' . $kargoAdresi->ilce : '' }}</dd>
          </div>
          @if($kargoAdresi->posta_kodu)
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Posta Kodu</dt>
            <dd class="text-[13px] font-mono text-[#0F172A]">{{ $kargoAdresi->posta_kodu }}</dd>
          </div>
          @endif
        </dl>
        @else
        <p class="text-[13px] text-[#C0C0C0]">Kargo adresi girilmemiş.</p>
        @endif
      </div>
    </div>

    {{-- Fatura Bilgileri --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC] flex items-center gap-2">
        <i class="ti ti-file-invoice text-[#64748B]"></i>
        <h2 class="text-[13px] font-semibold text-[#0F172A]">Fatura Bilgileri</h2>
        @if($faturaAdresi)
        <span class="ml-auto text-[9px] font-semibold tracking-[1px] uppercase text-[#1A5C3A] bg-[#E6F4EC] px-2 py-0.5 rounded-full">Kayıtlı</span>
        @endif
      </div>
      <div class="p-6">
        @if($faturaAdresi)
        <dl class="grid grid-cols-2 gap-x-8 gap-y-3">
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Ad Soyad</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $faturaAdresi->ad_soyad }}</dd>
          </div>
          @if($faturaAdresi->sirket_adi)
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Şirket</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $faturaAdresi->sirket_adi }}</dd>
          </div>
          @if($faturaAdresi->vergi_dairesi || $faturaAdresi->vergi_no)
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Vergi Dairesi</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $faturaAdresi->vergi_dairesi ?? '—' }}</dd>
          </div>
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Vergi No</dt>
            <dd class="text-[13px] font-mono text-[#0F172A]">{{ $faturaAdresi->vergi_no ?? '—' }}</dd>
          </div>
          @endif
          @endif
          <div class="col-span-2">
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Adres</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $faturaAdresi->adres_satiri }}</dd>
          </div>
          <div>
            <dt class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-0.5">Şehir / İlçe</dt>
            <dd class="text-[13px] text-[#0F172A]">{{ $faturaAdresi->sehir }}{{ $faturaAdresi->ilce ? ' / ' . $faturaAdresi->ilce : '' }}</dd>
          </div>
        </dl>
        @else
        <p class="text-[13px] text-[#C0C0C0]">Fatura bilgisi girilmemiş.</p>
        @endif
      </div>
    </div>

  </div>

  {{-- Sağ kolon: Teklifler --}}
  <div class="col-span-1 space-y-5">

    {{-- İstatistik --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 space-y-3">
      <p class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em]">İstatistik</p>
      <div class="flex items-center justify-between">
        <span class="text-[12px] text-[#64748B]">Toplam Teklif</span>
        <span class="text-[13px] font-bold text-[#0F172A]">{{ $teklifler->count() }}</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-[12px] text-[#64748B]">Kabul Edilen</span>
        <span class="text-[13px] font-bold text-[#1A5C3A]">{{ $teklifler->where('durum','kabul')->count() }}</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-[12px] text-[#64748B]">Bekleyen</span>
        <span class="text-[13px] font-bold text-[#D97706]">{{ $teklifler->where('durum','beklemede')->count() }}</span>
      </div>
    </div>

    {{-- Teklifler --}}
    @if($teklifler->isNotEmpty())
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="px-5 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC]">
        <h2 class="text-[12px] font-semibold text-[#0F172A]">Teklifler</h2>
      </div>
      <div class="divide-y divide-[rgba(0,0,0,0.05)]">
        @foreach($teklifler as $teklif)
        @php
          $renk = match($teklif->durum) {
            'kabul'     => 'text-[#1A5C3A] bg-[#E6F4EC]',
            'red'       => 'text-[#991B1B] bg-[#FEE2E2]',
            default     => 'text-[#92400E] bg-[#FEF3C7]',
          };
          $etiket = match($teklif->durum) {
            'kabul'  => 'Kabul',
            'red'    => 'Red',
            default  => 'Bekliyor',
          };
        @endphp
        <div class="px-5 py-3 flex items-center gap-3">
          <div class="flex-1 min-w-0">
            <p class="text-[12px] font-semibold text-[#0F172A] truncate">{{ $teklif->koleksiyon?->ad ?? '—' }}</p>
            <p class="text-[10px] text-[#94A3B8]">{{ $teklif->created_at->format('d.m.Y') }}</p>
          </div>
          <div class="text-right shrink-0">
            <p class="text-[12px] font-bold text-[#0F172A]">{{ number_format($teklif->miktar, 0, ',', '.') }} ₺</p>
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full {{ $renk }}">{{ $etiket }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>
</div>
@endsection


