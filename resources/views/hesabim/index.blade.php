@extends('layouts.app')
@section('title', 'Hesabım — SUÇEK')

@section('content')
@php
  $user     = auth()->user();
  $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(mb_substr($w,0,1)))->take(2)->implode('');

  $toplamTeklif  = $teklifler->total();
  $bekleyenTeklif= $user->teklifler()->where('durum','beklemede')->count();
  $kabulTeklif   = $user->teklifler()->where('durum','kabul')->count();

  $aktif = $aktifSekme ?? 'siparisler';
@endphp

{{-- ─── Profil Header ───────────────────────────────────────────────────────── --}}
<section class="bg-[#0F0F0F] relative overflow-hidden">
  <div class="absolute inset-0 opacity-[0.04]"
       style="background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:24px 24px;"></div>
  <div class="absolute top-0 left-0 w-1 h-full bg-[#B8962E] opacity-50"></div>

  <div class="relative z-10 px-9 lg:px-14 py-10 flex flex-col sm:flex-row sm:items-center gap-6">
    <div class="w-16 h-16 rounded-full bg-[#B8962E] flex items-center justify-center shrink-0 shadow-[0_4px_16px_rgba(184,150,46,0.40)]">
      <span class="font-display text-[22px] font-semibold text-white tracking-[2px]">{{ $initials }}</span>
    </div>
    <div class="flex-1">
      <h1 class="font-display text-[24px] lg:text-[28px] font-semibold text-white leading-tight">{{ $user->name }}</h1>
      <p class="text-[12px] text-[rgba(255,255,255,0.45)] mt-0.5">{{ $user->email }}</p>
      <p class="text-[10px] text-[rgba(255,255,255,0.25)] mt-1 tracking-[1px]">ÜYE TARİHİ: {{ $user->created_at->format('d F Y') }}</p>
    </div>
    <form action="{{ route('cikis') }}" method="POST" class="shrink-0">
      @csrf
      <button type="submit" class="flex items-center gap-2 text-[10px] font-medium tracking-[1.5px] uppercase text-[rgba(255,255,255,0.45)] hover:text-white border border-[rgba(255,255,255,0.12)] hover:border-[rgba(255,255,255,0.35)] px-4 py-2.5 rounded-[8px] transition-all min-h-[40px]">
        <i class="ti ti-logout text-sm"></i> Çıkış Yap
      </button>
    </form>
  </div>

  {{-- İstatistik bar --}}
  <div class="relative z-10 border-t border-[rgba(255,255,255,0.06)] px-9 lg:px-14 py-4 flex flex-wrap gap-8">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-[7px] bg-[rgba(255,255,255,0.07)] flex items-center justify-center">
        <i class="ti ti-shopping-bag text-[13px] text-[rgba(255,255,255,0.50)]"></i>
      </div>
      <div>
        <p class="text-[17px] font-semibold text-white leading-none">{{ $siparisler->total() }}</p>
        <p class="text-[10px] text-[rgba(255,255,255,0.35)] uppercase tracking-[1px] mt-0.5">Sipariş</p>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-[7px] bg-[rgba(255,255,255,0.07)] flex items-center justify-center">
        <i class="ti ti-tag text-[13px] text-[rgba(255,255,255,0.50)]"></i>
      </div>
      <div>
        <p class="text-[17px] font-semibold text-white leading-none">{{ $toplamTeklif }}</p>
        <p class="text-[10px] text-[rgba(255,255,255,0.35)] uppercase tracking-[1px] mt-0.5">Teklif</p>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-[7px] bg-[rgba(184,150,46,0.20)] flex items-center justify-center">
        <i class="ti ti-clock text-[13px] text-[#B8962E]"></i>
      </div>
      <div>
        <p class="text-[17px] font-semibold text-white leading-none">{{ $bekleyenTeklif }}</p>
        <p class="text-[10px] text-[rgba(255,255,255,0.35)] uppercase tracking-[1px] mt-0.5">Beklemede</p>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-[7px] bg-[rgba(26,92,58,0.35)] flex items-center justify-center">
        <i class="ti ti-circle-check text-[13px] text-[#4ade80]"></i>
      </div>
      <div>
        <p class="text-[17px] font-semibold text-white leading-none">{{ $kabulTeklif }}</p>
        <p class="text-[10px] text-[rgba(255,255,255,0.35)] uppercase tracking-[1px] mt-0.5">Kabul</p>
      </div>
    </div>
  </div>
</section>

{{-- ─── Üyelik Bant (sadece sucek üyeler) ─────────────────────────────────── --}}
@if($user->isSucek())
@php
  $uyelikRenk = match($user->rol) {
    'teknik'   => ['bg' => '#fffbeb', 'sinir' => '#fde68a', 'renk' => '#d97706', 'koyu' => '#92400e', 'ikon_bg' => '#fef3c7', 'ikon' => 'ti-tool',  'etiket' => 'Teknik Üye'],
    'sucek'    => ['bg' => '#faf5ff', 'sinir' => '#ddd6fe', 'renk' => '#7c3aed', 'koyu' => '#4c1d95', 'ikon_bg' => '#ede9fe', 'ikon' => 'ti-crown', 'etiket' => 'Suçek Üye'],
    default    => ['bg' => '#eff6ff', 'sinir' => '#bfdbfe', 'renk' => '#2563eb', 'koyu' => '#1e3a8a', 'ikon_bg' => '#dbeafe', 'ikon' => 'ti-star',  'etiket' => 'Standart Üye'],
  };
@endphp
<section class="px-9 lg:px-14 py-5 border-b border-[rgba(0,0,0,0.07)]"
         style="background:linear-gradient(135deg,{{ $uyelikRenk['bg'] }} 0%,white 100%);">
  <div class="flex items-center gap-3 mb-3">
    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
         style="background:{{ $uyelikRenk['renk'] }};">
      <i class="ti {{ $uyelikRenk['ikon'] }} text-[12px] text-white"></i>
    </div>
    <p class="text-[11px] font-bold tracking-[2px] uppercase"
       style="color:{{ $uyelikRenk['koyu'] }};">{{ $uyelikRenk['etiket'] }} — Özel İçerikler</p>
  </div>
  <div class="flex flex-wrap gap-3">
    <a href="{{ route('haberler.index') }}"
       class="flex items-center gap-2.5 px-4 py-3 bg-white rounded-[10px] border transition-all hover:shadow-md group"
       style="border-color:{{ $uyelikRenk['sinir'] }};">
      <div class="w-8 h-8 rounded-[7px] flex items-center justify-center shrink-0"
           style="background:{{ $uyelikRenk['ikon_bg'] }};">
        <i class="ti ti-news text-[14px]" style="color:{{ $uyelikRenk['renk'] }};"></i>
      </div>
      <div>
        <p class="text-[12px] font-semibold text-[#141414] transition-colors"
           style="hover:color:{{ $uyelikRenk['renk'] }};">Haberler</p>
        <p class="text-[10px] text-[#A0A0A0]">Güncel duyurular</p>
      </div>
      <i class="ti ti-arrow-right text-sm text-[#C0C0C0] ml-2"></i>
    </a>
    <a href="{{ route('soy-agaci.index') }}"
       class="flex items-center gap-2.5 px-4 py-3 bg-white rounded-[10px] border transition-all hover:shadow-md group"
       style="border-color:{{ $uyelikRenk['sinir'] }};">
      <div class="w-8 h-8 rounded-[7px] flex items-center justify-center shrink-0"
           style="background:{{ $uyelikRenk['ikon_bg'] }};">
        <i class="ti ti-topology-star-3 text-[14px]" style="color:{{ $uyelikRenk['renk'] }};"></i>
      </div>
      <div>
        <p class="text-[12px] font-semibold text-[#141414]">Soy Ağacı</p>
        <p class="text-[10px] text-[#A0A0A0]">Aile ağacını görüntüle</p>
      </div>
      <i class="ti ti-arrow-right text-sm text-[#C0C0C0] ml-2"></i>
    </a>
    @if($user->isTeknik())
    <a href="{{ route('katalog.index') }}"
       class="flex items-center gap-2.5 px-4 py-3 bg-white rounded-[10px] border transition-all hover:shadow-md group"
       style="border-color:{{ $uyelikRenk['sinir'] }};">
      <div class="w-8 h-8 rounded-[7px] flex items-center justify-center shrink-0"
           style="background:{{ $uyelikRenk['ikon_bg'] }};">
        <i class="ti ti-book-2 text-[14px]" style="color:{{ $uyelikRenk['renk'] }};"></i>
      </div>
      <div>
        <p class="text-[12px] font-semibold text-[#141414]">Katalog Oluşturucu</p>
        <p class="text-[10px] text-[#A0A0A0]">PDF katalog hazırla</p>
      </div>
      <i class="ti ti-arrow-right text-sm text-[#C0C0C0] ml-2"></i>
    </a>
    @endif
  </div>
</section>
@endif

{{-- ─── Sekmeler ────────────────────────────────────────────────────────────── --}}
<section class="section" x-data="{ sekme: '{{ $aktif }}' }">

  {{-- Tab bar --}}
  <div class="flex overflow-x-auto gap-0.5 border-b border-[rgba(0,0,0,0.08)] mb-8 -mx-1 px-1 scrollbar-hide">
    @foreach([
      ['siparisler', 'ti-shopping-bag', 'Siparişlerim'],
      ['teklifler',  'ti-tag',          'Tekliflerim'],
      ['adresler',   'ti-map-pin',      'Adres & Fatura'],
      ['profil',     'ti-user',         'Profil'],
    ] as [$id, $ikon, $etiket])
    <button @click="sekme='{{ $id }}'"
            :class="sekme==='{{ $id }}'
              ? 'border-b-2 border-[#0F0F0F] text-[#0F0F0F]'
              : 'text-[#A8A8A8] hover:text-[#5A5A5A]'"
            class="flex items-center gap-1.5 px-4 py-3 text-[11px] font-semibold tracking-[.08em] uppercase whitespace-nowrap transition-colors -mb-px shrink-0">
      <i class="ti {{ $ikon }} text-sm"></i>
      {{ $etiket }}
      @if($id === 'teklifler' && $toplamTeklif > 0)
      <span class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full text-[8px] font-bold bg-[#0F0F0F] text-white"
            x-show="sekme!=='teklifler'">{{ min($toplamTeklif,99) }}</span>
      @endif
    </button>
    @endforeach
    @if($user->isTeknik())
    <button @click="sekme='urunlerim'"
            :class="sekme==='urunlerim'
              ? 'border-b-2 border-[#B8962E] text-[#B8962E]'
              : 'text-[#A8A8A8] hover:text-[#5A5A5A]'"
            class="flex items-center gap-1.5 px-4 py-3 text-[11px] font-semibold tracking-[.08em] uppercase whitespace-nowrap transition-colors -mb-px shrink-0">
      <i class="ti ti-package text-sm"></i>
      Ürünlerim
    </button>
    @endif
  </div>

  {{-- Flash mesajları --}}
  @foreach([
    ['profil_basari',  'E6F4EC','9DD4B5','1A5C3A','ti-circle-check'],
    ['sifre_basari',   'E6F4EC','9DD4B5','1A5C3A','ti-circle-check'],
    ['kargo_basari',   'E6F4EC','9DD4B5','1A5C3A','ti-circle-check'],
    ['fatura_basari',  'E6F4EC','9DD4B5','1A5C3A','ti-circle-check'],
    ['teklif_basari',  'E6F4EC','9DD4B5','1A5C3A','ti-circle-check'],
  ] as [$key, $bg, $border, $text, $ikon])
  @if(session($key))
  <div class="mb-5 flex items-center gap-3 bg-[#{{ $bg }}] border border-[#{{ $border }}] text-[#{{ $text }}] rounded-[10px] px-4 py-3.5 text-[13px]" role="alert">
    <i class="ti {{ $ikon }} shrink-0"></i> {{ session($key) }}
  </div>
  @endif
  @endforeach

  @if($errors->any())
  <div class="mb-5 flex items-start gap-3 bg-[#FEE2E2] border border-[#FCA5A5] text-[#991B1B] rounded-[10px] px-4 py-3.5 text-[13px]" role="alert">
    <i class="ti ti-alert-circle shrink-0 mt-0.5"></i>
    <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
  </div>
  @endif

  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  {{-- SİPARİŞLERİM                                                          --}}
  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  <div x-show="sekme==='siparisler'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
    @if($siparisler->count() > 0)
    <div class="space-y-3">
      @foreach($siparisler as $siparis)
      @php
        $ilkKalem   = $siparis->kalemler->first();
        $kalemSayisi = $siparis->kalemler->count();
      @endphp
      <div class="bg-white border border-[rgba(0,0,0,0.07)] rounded-[14px] p-5 hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)] transition-shadow">

        {{-- Üst satır: referans + durum --}}
        <div class="flex items-start justify-between gap-4 mb-4">
          <div>
            <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A0A0A0] mb-0.5">Sipariş No</p>
            <p class="font-mono text-[13px] font-semibold text-[#0F0F0F] tracking-wide">{{ $siparis->referans }}</p>
            <p class="text-[11px] text-[#A0A0A0] mt-0.5">{{ $siparis->created_at->format('d.m.Y') }}</p>
          </div>
          <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-semibold shrink-0 {{ $siparis->durumRenk() }}">
            {{ $siparis->durumEtiketi() }}
          </span>
        </div>

        {{-- Ürün önizleme --}}
        <div class="flex items-center gap-3 mb-4">
          @if($ilkKalem?->urun_gorsel)
          <img src="{{ asset('storage/'.$ilkKalem->urun_gorsel) }}"
               class="w-12 h-12 object-cover rounded-[8px] bg-[#F0F0F0] shrink-0" alt="{{ $ilkKalem->urun_adi }}">
          @else
          <div class="w-12 h-12 rounded-[8px] bg-[#F0F0F0] flex items-center justify-center shrink-0">
            <i class="ti ti-photo text-[#C0C0C0] text-lg"></i>
          </div>
          @endif
          <div class="flex-1 min-w-0">
            <p class="text-[13px] font-medium text-[#0F0F0F] truncate">{{ $ilkKalem?->urun_adi ?? '—' }}</p>
            @if($kalemSayisi > 1)
            <p class="text-[11px] text-[#A0A0A0] mt-0.5">+{{ $kalemSayisi - 1 }} ürün daha</p>
            @endif
          </div>
        </div>

        {{-- Alt satır: toplam + detay butonu --}}
        <div class="flex items-center justify-between pt-3 border-t border-[rgba(0,0,0,0.06)]">
          <div>
            <p class="text-[10px] text-[#A0A0A0] uppercase tracking-[1px]">Toplam</p>
            <p class="text-[17px] font-bold text-[#0F0F0F] leading-tight">{{ number_format($siparis->toplam, 2, ',', '.') }} ₺</p>
          </div>
          <a href="{{ route('siparis.onay', $siparis->referans) }}"
             class="flex items-center gap-1.5 text-[11px] font-semibold tracking-[1px] uppercase border border-[rgba(0,0,0,0.12)] px-4 py-2.5 rounded-[8px] text-[#5A5A5A] hover:text-[#0F0F0F] hover:border-[rgba(0,0,0,0.30)] hover:bg-[#FAFAFA] transition-colors min-h-[40px]">
            Detay <i class="ti ti-arrow-right text-xs"></i>
          </a>
        </div>

      </div>
      @endforeach
    </div>
    @if($siparisler->hasPages())
    <div class="mt-6">{{ $siparisler->links() }}</div>
    @endif

    @else
    <div class="flex flex-col items-center justify-center py-16 text-center max-w-md mx-auto">
      <div class="w-20 h-20 rounded-full bg-[#F0F0F0] flex items-center justify-center mb-5">
        <i class="ti ti-shopping-bag text-4xl text-[#D0D0D0]"></i>
      </div>
      <h2 class="font-display text-[22px] font-semibold text-[#0F0F0F] mb-2">Henüz sipariş vermediniz</h2>
      <p class="text-[13px] text-[#A8A8A8] leading-relaxed mb-6">
        Mağaza ve koleksiyon ürünlerini keşfederek alışverişe başlayabilirsiniz.
      </p>
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('magaza.index') }}"
           class="flex items-center justify-center gap-2 bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase px-6 py-3 rounded-[8px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
          <i class="ti ti-shopping-bag text-sm"></i> Mağazaya Git
        </a>
        <a href="{{ route('koleksiyon.index') }}"
           class="flex items-center justify-center gap-2 border border-[rgba(0,0,0,0.15)] text-[#5A5A5A] text-[11px] font-semibold tracking-[1.5px] uppercase px-6 py-3 rounded-[8px] hover:bg-[#F0F0F0] transition-colors min-h-[44px]">
          <i class="ti ti-diamond text-sm"></i> Koleksiyon
        </a>
      </div>
    </div>
    @endif
  </div>

  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  {{-- TEKLİFLERİM                                                            --}}
  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  <div x-show="sekme==='teklifler'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
    @if($teklifler->count() > 0)
    <div class="space-y-3">
      @foreach($teklifler as $teklif)
      @php
        $stiller = [
          'beklemede' => ['bg'=>'bg-[#FEF3C7]','text'=>'text-[#D97706]','ikon'=>'ti-clock','label'=>'Beklemede'],
          'kabul'     => ['bg'=>'bg-[#E6F4EC]','text'=>'text-[#1A5C3A]','ikon'=>'ti-circle-check','label'=>'Kabul Edildi'],
          'red'       => ['bg'=>'bg-[#FEE2E2]','text'=>'text-[#991B1B]','ikon'=>'ti-circle-x','label'=>'Reddedildi'],
        ][$teklif->durum];
      @endphp
      <div class="bg-white border border-[rgba(0,0,0,0.07)] rounded-[14px] p-5 flex flex-col sm:flex-row sm:items-center gap-4 hover:shadow-[0_4px_16px_rgba(0,0,0,0.07)] transition-shadow">
        <div class="w-16 h-16 rounded-[10px] bg-[#F0F0F0] overflow-hidden shrink-0 flex items-center justify-center">
          @if($teklif->koleksiyon?->gorsel)
            <img src="{{ asset('storage/'.$teklif->koleksiyon->gorsel) }}" class="w-full h-full object-cover" alt="{{ $teklif->koleksiyon->ad }}">
          @else
            <i class="ti ti-diamond text-2xl text-[#D0D0D0]"></i>
          @endif
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-[#0F0F0F] text-[14px] truncate">{{ $teklif->koleksiyon?->ad ?? '—' }}</p>
          <p class="text-[11px] text-[#A8A8A8] mt-0.5">{{ ucfirst($teklif->koleksiyon?->kategori ?? '') }}</p>
          @if($teklif->mesaj)
          <p class="text-[12px] text-[#5A5A5A] mt-1 italic line-clamp-1">"{{ $teklif->mesaj }}"</p>
          @endif
          <p class="text-[10px] text-[#C0C0C0] mt-1">{{ $teklif->created_at->format('d.m.Y H:i') }}</p>
        </div>
        <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-2 shrink-0">
          <span class="font-display text-[20px] font-semibold text-[#0F0F0F]">{{ number_format($teklif->miktar, 0, ',', '.') }} ₺</span>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-semibold {{ $stiller['bg'] }} {{ $stiller['text'] }}">
            <i class="ti {{ $stiller['ikon'] }} text-[11px]"></i> {{ $stiller['label'] }}
          </span>
        </div>
        @if($teklif->koleksiyon)
        <a href="{{ route('koleksiyon.show', $teklif->koleksiyon->slug) }}"
           class="shrink-0 w-9 h-9 flex items-center justify-center rounded-[8px] border border-[rgba(0,0,0,0.10)] text-[#A8A8A8] hover:text-[#0F0F0F] hover:bg-[#F0F0F0] transition-colors">
          <i class="ti ti-arrow-right text-sm"></i>
        </a>
        @endif
      </div>
      @endforeach
    </div>
    @if($teklifler->hasPages())
    <div class="mt-6">{{ $teklifler->links() }}</div>
    @endif
    @else
    <div class="flex flex-col items-center justify-center py-16 text-center">
      <div class="w-16 h-16 rounded-full bg-[#F0F0F0] flex items-center justify-center mb-4">
        <i class="ti ti-tag text-3xl text-[#D0D0D0]"></i>
      </div>
      <p class="text-[15px] font-medium text-[#5A5A5A] mb-1">Henüz teklif vermediniz</p>
      <p class="text-[13px] text-[#A8A8A8] mb-5">Koleksiyon ürünlerine göz atarak teklif verebilirsiniz.</p>
      <a href="{{ route('koleksiyon.index') }}" class="flex items-center gap-2 bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase px-6 py-3 rounded-[8px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
        <i class="ti ti-diamond text-sm"></i> Koleksiyona Göz At
      </a>
    </div>
    @endif
  </div>

  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  {{-- ADRES & FATURA                                                         --}}
  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  <div x-show="sekme==='adresler'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      {{-- ─── Kargo Adresi ─── --}}
      <div class="bg-white rounded-[14px] border border-[rgba(0,0,0,0.07)] overflow-hidden">
        <div class="px-6 py-4 border-b border-[rgba(0,0,0,0.07)] flex items-center gap-3 bg-[#FAFAFA]">
          <div class="w-8 h-8 rounded-[7px] bg-[#F0F0F0] flex items-center justify-center">
            <i class="ti ti-truck text-[14px] text-[#5A5A5A]"></i>
          </div>
          <div>
            <h2 class="text-[13px] font-semibold text-[#0F0F0F]">Kargo Adresi</h2>
            <p class="text-[11px] text-[#A8A8A8]">Teslimat adresi</p>
          </div>
          @if($kargoAdresi)
          <span class="ml-auto text-[9px] font-semibold tracking-[1px] uppercase text-[#1A5C3A] bg-[#E6F4EC] px-2 py-0.5 rounded-full">Kayıtlı</span>
          @endif
        </div>
        <div class="p-6">
          <form action="{{ route('hesabim.kargo-adres') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
              <div class="col-span-2">
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Ad Soyad <span class="text-[#CC2200]">*</span></label>
                <input type="text" name="ad_soyad" value="{{ old('ad_soyad', $kargoAdresi?->ad_soyad) }}" required
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Telefon</label>
                <input type="tel" name="telefon" value="{{ old('telefon', $kargoAdresi?->telefon) }}"
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors"
                       placeholder="+90 5xx xxx xx xx">
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Posta Kodu</label>
                <input type="text" name="posta_kodu" value="{{ old('posta_kodu', $kargoAdresi?->posta_kodu) }}"
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors font-mono"
                       placeholder="06000">
              </div>
              <div class="col-span-2">
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Adres <span class="text-[#CC2200]">*</span></label>
                <textarea name="adres_satiri" rows="2" required
                          class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors resize-none"
                          placeholder="Mahalle, sokak, kapı no, daire">{{ old('adres_satiri', $kargoAdresi?->adres_satiri) }}</textarea>
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">İl <span class="text-[#CC2200]">*</span></label>
                <input type="text" name="sehir" value="{{ old('sehir', $kargoAdresi?->sehir) }}" required
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors"
                       placeholder="Ankara">
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">İlçe</label>
                <input type="text" name="ilce" value="{{ old('ilce', $kargoAdresi?->ilce) }}"
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors"
                       placeholder="Çankaya">
              </div>
            </div>
            <button type="submit"
                    class="w-full bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[8px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
              <i class="ti ti-device-floppy text-sm mr-1"></i>
              {{ $kargoAdresi ? 'Güncelle' : 'Kaydet' }}
            </button>
          </form>
        </div>
      </div>

      {{-- ─── Fatura Bilgileri ─── --}}
      <div class="bg-white rounded-[14px] border border-[rgba(0,0,0,0.07)] overflow-hidden">
        <div class="px-6 py-4 border-b border-[rgba(0,0,0,0.07)] flex items-center gap-3 bg-[#FAFAFA]">
          <div class="w-8 h-8 rounded-[7px] bg-[#F0F0F0] flex items-center justify-center">
            <i class="ti ti-file-invoice text-[14px] text-[#5A5A5A]"></i>
          </div>
          <div>
            <h2 class="text-[13px] font-semibold text-[#0F0F0F]">Fatura Bilgileri</h2>
            <p class="text-[11px] text-[#A8A8A8]">Fatura adresi ve şirket bilgileri</p>
          </div>
          @if($faturaAdresi)
          <span class="ml-auto text-[9px] font-semibold tracking-[1px] uppercase text-[#1A5C3A] bg-[#E6F4EC] px-2 py-0.5 rounded-full">Kayıtlı</span>
          @endif
        </div>
        <div class="p-6">
          <form action="{{ route('hesabim.fatura-adres') }}" method="POST" class="space-y-4">
            @csrf
            {{-- Fatura tipi --}}
            <div x-data="{ tip: '{{ $faturaAdresi?->sirket_adi ? 'kurumsal' : 'bireysel' }}' }">
              <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-2">Fatura Tipi</label>
              <div class="flex gap-2 mb-4">
                <button type="button" @click="tip='bireysel'"
                        :class="tip==='bireysel' ? 'bg-[#0F0F0F] text-white border-transparent' : 'bg-white text-[#5A5A5A] border-[rgba(0,0,0,0.12)] hover:border-[rgba(0,0,0,0.25)]'"
                        class="flex-1 text-[11px] font-medium tracking-[.08em] uppercase py-2 rounded-[7px] border transition-all">
                  Bireysel
                </button>
                <button type="button" @click="tip='kurumsal'"
                        :class="tip==='kurumsal' ? 'bg-[#0F0F0F] text-white border-transparent' : 'bg-white text-[#5A5A5A] border-[rgba(0,0,0,0.12)] hover:border-[rgba(0,0,0,0.25)]'"
                        class="flex-1 text-[11px] font-medium tracking-[.08em] uppercase py-2 rounded-[7px] border transition-all">
                  Kurumsal
                </button>
              </div>

              {{-- Kurumsal alanlar --}}
              <div x-show="tip==='kurumsal'" class="space-y-3 mb-3">
                <div>
                  <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Şirket Adı</label>
                  <input type="text" name="sirket_adi" value="{{ old('sirket_adi', $faturaAdresi?->sirket_adi) }}"
                         class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors"
                         placeholder="SUÇEK A.Ş.">
                </div>
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Vergi Dairesi</label>
                    <input type="text" name="vergi_dairesi" value="{{ old('vergi_dairesi', $faturaAdresi?->vergi_dairesi) }}"
                           class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors"
                           placeholder="Çankaya VD">
                  </div>
                  <div>
                    <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Vergi No</label>
                    <input type="text" name="vergi_no" value="{{ old('vergi_no', $faturaAdresi?->vergi_no) }}"
                           class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors font-mono"
                           placeholder="1234567890">
                  </div>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div class="col-span-2">
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Ad Soyad <span class="text-[#CC2200]">*</span></label>
                <input type="text" name="ad_soyad" value="{{ old('ad_soyad', $faturaAdresi?->ad_soyad) }}" required
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
              </div>
              <div class="col-span-2">
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Adres <span class="text-[#CC2200]">*</span></label>
                <textarea name="adres_satiri" rows="2" required
                          class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors resize-none"
                          placeholder="Mahalle, sokak, kapı no, daire">{{ old('adres_satiri', $faturaAdresi?->adres_satiri) }}</textarea>
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">İl <span class="text-[#CC2200]">*</span></label>
                <input type="text" name="sehir" value="{{ old('sehir', $faturaAdresi?->sehir) }}" required
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">İlçe</label>
                <input type="text" name="ilce" value="{{ old('ilce', $faturaAdresi?->ilce) }}"
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Posta Kodu</label>
                <input type="text" name="posta_kodu" value="{{ old('posta_kodu', $faturaAdresi?->posta_kodu) }}"
                       class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors font-mono">
              </div>
            </div>
            <button type="submit"
                    class="w-full bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[8px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
              <i class="ti ti-device-floppy text-sm mr-1"></i>
              {{ $faturaAdresi ? 'Güncelle' : 'Kaydet' }}
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  {{-- PROFİL                                                                 --}}
  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  <div x-show="sekme==='profil'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-3xl">

      {{-- Kişisel bilgiler --}}
      <div class="bg-white rounded-[14px] border border-[rgba(0,0,0,0.07)] overflow-hidden">
        <div class="px-6 py-4 border-b border-[rgba(0,0,0,0.07)] bg-[#FAFAFA] flex items-center gap-3">
          <div class="w-8 h-8 rounded-[7px] bg-[#F0F0F0] flex items-center justify-center">
            <i class="ti ti-user-edit text-[14px] text-[#B8962E]"></i>
          </div>
          <h2 class="text-[13px] font-semibold text-[#0F0F0F]">Kişisel Bilgiler</h2>
        </div>
        <div class="p-6">
          <form action="{{ route('hesabim.profil') }}" method="POST" class="space-y-4">
            @csrf @method('PATCH')
            <div>
              <label for="p-name" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Ad Soyad</label>
              <input id="p-name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                     class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[14px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
            </div>
            <div>
              <label for="p-email" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">E-posta</label>
              <input id="p-email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                     class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[14px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
            </div>
            <div>
              <label for="p-tel" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Telefon</label>
              <input id="p-tel" type="tel" name="telefon" value="{{ old('telefon', $user->telefon) }}"
                     placeholder="+90 5xx xxx xx xx"
                     class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[14px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
            </div>
            <div>
              <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Doğum Tarihi</label>
              <div class="grid grid-cols-3 gap-2">
                <select name="dogum_gun" class="px-3 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] bg-white text-[#0F0F0F]">
                  <option value="">Gün</option>
                  @for($g = 1; $g <= 31; $g++)
                  <option value="{{ $g }}" {{ old('dogum_gun', $user->dogum_gun) == $g ? 'selected' : '' }}>{{ $g }}</option>
                  @endfor
                </select>
                <select name="dogum_ay" class="px-3 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] bg-white text-[#0F0F0F]">
                  <option value="">Ay</option>
                  @foreach(['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'] as $i => $ay)
                  <option value="{{ $i+1 }}" {{ old('dogum_ay', $user->dogum_ay) == $i+1 ? 'selected' : '' }}>{{ $ay }}</option>
                  @endforeach
                </select>
                <input type="number" name="dogum_yil" value="{{ old('dogum_yil', $user->dogum_yil) }}"
                       placeholder="Yıl" min="1900" max="{{ date('Y') }}"
                       class="px-3 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors bg-white text-[#0F0F0F]">
              </div>
            </div>
            <label class="flex items-center gap-3 cursor-pointer py-1">
              <input type="checkbox" name="sms_izni" value="1" {{ old('sms_izni', $user->sms_izni) ? 'checked' : '' }}
                     class="w-4 h-4 rounded border-[rgba(0,0,0,0.20)] accent-[#0F0F0F] cursor-pointer">
              <span class="text-[12px] text-[#5A5A5A]">SMS bildirimi almak istiyorum</span>
            </label>
            <button type="submit" class="w-full bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[8px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
              Bilgileri Güncelle
            </button>
          </form>
        </div>
      </div>

      {{-- Şifre değiştir --}}
      <div class="bg-white rounded-[14px] border border-[rgba(0,0,0,0.07)] overflow-hidden" x-data="{ s1:false,s2:false,s3:false }">
        <div class="px-6 py-4 border-b border-[rgba(0,0,0,0.07)] bg-[#FAFAFA] flex items-center gap-3">
          <div class="w-8 h-8 rounded-[7px] bg-[#F0F0F0] flex items-center justify-center">
            <i class="ti ti-lock text-[14px] text-[#B8962E]"></i>
          </div>
          <h2 class="text-[13px] font-semibold text-[#0F0F0F]">Şifre Değiştir</h2>
        </div>
        <div class="p-6">
          <form action="{{ route('hesabim.sifre') }}" method="POST" class="space-y-4">
            @csrf @method('PATCH')
            @foreach([
              ['s1','mevcut_sifre','Mevcut Şifre','current-password'],
              ['s2','yeni_sifre','Yeni Şifre','new-password'],
              ['s3','yeni_sifre_confirmation','Yeni Şifre Tekrar','new-password'],
            ] as [$sv,$nm,$lbl,$ac])
            <div>
              <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">{{ $lbl }}</label>
              <div class="relative">
                <input :type="{{ $sv }} ? 'text' : 'password'" name="{{ $nm }}" required {{ $nm === 'yeni_sifre' ? 'minlength=8' : '' }}
                       autocomplete="{{ $ac }}"
                       class="w-full px-3.5 py-2.5 pr-10 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[14px] focus:outline-none focus:border-[#0F0F0F] transition-colors @error($nm) border-[#CC2200] @enderror"
                       placeholder="••••••••">
                <button type="button" @click="{{ $sv }}=!{{ $sv }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#A8A8A8] hover:text-[#0F0F0F]">
                  <i :class="{{ $sv }} ? 'ti ti-eye-off' : 'ti ti-eye'" class="text-[15px]"></i>
                </button>
              </div>
              @error($nm)<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
            </div>
            @endforeach
            <button type="submit" class="w-full bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[8px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
              Şifreyi Değiştir
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  {{-- ÜRÜNLERİM (sadece teknik üye)                                          --}}
  {{-- ══════════════════════════════════════════════════════════════════════ --}}
  @if($user->isTeknik())
  <div x-show="sekme==='urunlerim'"
       x-transition:enter="transition ease-out duration-150"
       x-transition:enter-start="opacity-0 translate-y-1"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-data="urunlerimHesabim()"
       @paste.window="modalAcik && gorselYapistir($event)"
       style="display:none;">

    {{-- Başlık --}}
    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-[10px] text-[#A8A8A8] uppercase tracking-[1.5px] mb-0.5">Teknik Üye</p>
        <h2 class="text-[20px] font-semibold text-[#0F0F0F]">Ürünlerim</h2>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('katalog.index') }}"
           class="flex items-center gap-1.5 text-[11px] font-medium text-[#A8A8A8] hover:text-[#0F0F0F] border border-[rgba(0,0,0,0.12)] hover:border-[rgba(0,0,0,0.3)] px-3 py-2 rounded-[7px] transition-colors min-h-[36px]">
          <i class="ti ti-book-2 text-xs"></i> Katalog Oluşturucu
        </a>
        <button @click="yeniAc()"
                class="flex items-center gap-1.5 px-4 py-2.5 bg-[#B8962E] text-[#0F0F0F] text-[11px] font-semibold tracking-[.1em] uppercase rounded-[8px] hover:bg-[#c8a84b] transition-colors min-h-[36px] cursor-pointer">
          <i class="ti ti-plus text-sm"></i> Ürün Ekle
        </button>
      </div>
    </div>

    {{-- Boş durum --}}
    <div x-show="urunler.length===0"
         class="flex flex-col items-center justify-center py-16 text-center">
      <div class="w-16 h-16 rounded-full bg-[#F0F0F0] flex items-center justify-center mb-4">
        <i class="ti ti-box-off text-3xl text-[#D0D0D0]"></i>
      </div>
      <p class="text-[15px] font-medium text-[#5A5A5A] mb-1">Henüz ürün eklemediniz</p>
      <p class="text-[13px] text-[#A8A8A8] mb-5">Ürün ekleyerek kataloglarınızda kullanabilirsiniz.</p>
      <button @click="yeniAc()"
              class="flex items-center gap-2 bg-[#B8962E] text-[#0F0F0F] text-[11px] font-semibold tracking-[1.5px] uppercase px-6 py-3 rounded-[8px] hover:bg-[#c8a84b] transition-colors cursor-pointer">
        <i class="ti ti-plus text-sm"></i> İlk Ürünü Ekle
      </button>
    </div>

    {{-- Ürün listesi --}}
    <div class="space-y-3" x-show="urunler.length>0">
      <template x-for="urun in urunler" :key="urun.id">
        <div class="bg-white border border-[rgba(0,0,0,0.07)] rounded-[14px] p-4 flex items-center gap-4 hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)] transition-shadow">
          <div class="w-16 h-16 rounded-[8px] bg-[#F0F0F0] flex items-center justify-center overflow-hidden shrink-0">
            <img x-show="urun.gorsel" :src="urun.gorsel" :alt="urun.ad" class="w-full h-full object-cover">
            <i x-show="!urun.gorsel" class="ti ti-photo text-2xl text-[#C0C0C0]"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-[14px] font-semibold text-[#0F0F0F] mb-0.5" x-text="urun.ad"></p>
            <p class="text-[12px] text-[#A8A8A8] truncate" x-html="urun.aciklama ? urun.aciklama.replace(/<[^>]+>/g,'').substring(0,100) : '—'"></p>
          </div>
          <div class="flex gap-2 shrink-0">
            <button @click="duzenleAc(urun)"
                    class="w-9 h-9 flex items-center justify-center border border-[rgba(0,0,0,0.12)] rounded-[7px] text-[#5A5A5A] hover:text-[#0F0F0F] hover:border-[rgba(0,0,0,0.3)] transition-all cursor-pointer">
              <i class="ti ti-edit text-sm"></i>
            </button>
            <button @click="sil(urun.id)"
                    class="w-9 h-9 flex items-center justify-center border border-[rgba(0,0,0,0.12)] rounded-[7px] text-[#5A5A5A] hover:text-[#dc2626] hover:border-[#dc2626] transition-all cursor-pointer">
              <i class="ti ti-trash text-sm"></i>
            </button>
          </div>
        </div>
      </template>
    </div>

    {{-- Modal --}}
    <div x-show="modalAcik"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         @click.self="modalKapat()"
         x-cloak>
      <div class="bg-white rounded-[14px] w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.3)]">

        <div class="px-6 py-4 border-b border-[rgba(0,0,0,0.08)] flex items-center justify-between">
          <span class="text-[15px] font-semibold text-[#0F0F0F]"
                x-text="duzenlenenId ? 'Ürünü Düzenle' : 'Yeni Ürün Ekle'"></span>
          <button @click="modalKapat()"
                  class="w-8 h-8 flex items-center justify-center text-[#A8A8A8] hover:text-[#0F0F0F] cursor-pointer transition-colors">
            <i class="ti ti-x text-lg"></i>
          </button>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Ürün Adı <span class="text-[#CC2200]">*</span></label>
            <input x-model="form.ad" type="text"
                   class="w-full px-3.5 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#B8962E] transition-colors"
                   placeholder="Ürün adını girin…">
            <p x-show="hatalar.ad" x-text="hatalar.ad" class="text-[11px] text-[#dc2626] mt-1"></p>
          </div>

          <div>
            <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">
              Görseller
              <span class="text-[#C0C0C0] font-normal normal-case tracking-normal ml-1">en fazla 10</span>
            </label>
            {{-- Mevcut görseller --}}
            <div class="flex flex-wrap gap-2 mb-2" x-show="gorseller.length > 0">
              <template x-for="(g, idx) in gorseller" :key="idx">
                <div class="relative w-[68px] h-[68px] rounded-[7px] overflow-hidden border border-[rgba(0,0,0,0.1)] bg-[#F5F5F5] flex-shrink-0">
                  <img :src="g.url" class="w-full h-full object-cover">
                  <button @click.stop="gorselSil(idx)"
                          class="absolute top-0.5 right-0.5 w-[18px] h-[18px] bg-black/60 hover:bg-black/90 rounded-full flex items-center justify-center cursor-pointer transition-colors">
                    <i class="ti ti-x text-white" style="font-size:8px;line-height:1;"></i>
                  </button>
                  <div x-show="idx === 0"
                       class="absolute bottom-0 left-0 right-0 text-center bg-[rgba(184,150,46,0.85)] text-[#0F0F0F]"
                       style="font-size:7px;font-weight:700;padding:1px 0;letter-spacing:.05em;">ANA</div>
                </div>
              </template>
            </div>
            {{-- Drop zone --}}
            <div x-show="gorseller.length < 10"
                 @click="$refs.hGorselInput.click()"
                 @dragover.prevent="dragUzerinde=true"
                 @dragleave.prevent="dragUzerinde=false"
                 @drop.prevent="gorselBirak($event)"
                 :class="dragUzerinde ? 'border-[#B8962E] bg-[#FEFBF0]' : 'border-[rgba(0,0,0,0.15)] bg-[#FAFAFA] hover:border-[#B8962E]'"
                 class="w-full py-5 border-2 border-dashed rounded-[8px] flex flex-col items-center justify-center cursor-pointer transition-colors">
              <i class="ti ti-upload text-2xl text-[#C0C0C0] mb-1 pointer-events-none"></i>
              <span class="text-[12px] text-[#B0B0B0] pointer-events-none">Tıklayın, sürükleyin veya Ctrl+V</span>
              <span x-show="gorseller.length === 0" class="text-[10px] text-[#C0C0C0] mt-0.5 pointer-events-none">İlk görsel katalogda ana görsel olur</span>
            </div>
            <input type="file" accept="image/*" multiple x-ref="hGorselInput" @change="gorselSecCoklu($event)" style="display:none;">
          </div>

          <div>
            <label class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.07em] mb-1.5">Açıklama / Teknik Özellikler</label>
            <div id="h-quill-editor" style="background:white;"></div>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-[rgba(0,0,0,0.08)] flex gap-3 justify-end">
          <button @click="modalKapat()"
                  class="px-4 py-2.5 text-[11px] font-medium text-[#5A5A5A] border border-[rgba(0,0,0,0.15)] rounded-[7px] hover:bg-[#F5F5F5] cursor-pointer transition-colors">
            İptal
          </button>
          <button @click="kaydet()" :disabled="kaydediliyor"
                  class="px-5 py-2.5 bg-[#B8962E] text-[#0F0F0F] text-[11px] font-semibold tracking-[.1em] uppercase rounded-[7px] hover:bg-[#c8a84b] disabled:opacity-50 cursor-pointer transition-colors">
            <span x-text="kaydediliyor ? 'Kaydediliyor…' : (duzenlenenId ? 'Güncelle' : 'Kaydet')"></span>
          </button>
        </div>

      </div>
    </div>

  </div>
  @endif

</section>

@endsection

@push('styles')
<style>
.scrollbar-hide::-webkit-scrollbar{display:none;}
.scrollbar-hide{-ms-overflow-style:none;scrollbar-width:none;}
[x-cloak]{display:none!important;}
</style>
@if($user->isTeknik())
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.ql-toolbar.ql-snow{border-radius:7px 7px 0 0;border-color:rgba(0,0,0,0.15);background:#FAFAFA;}
.ql-container.ql-snow{border-radius:0 0 7px 7px;border-color:rgba(0,0,0,0.15);font-size:13px;min-height:120px;}
.ql-editor{min-height:120px;line-height:1.75;color:#0F0F0F;}
.ql-editor.ql-blank::before{color:#B0B0B0;font-style:normal;}
</style>
@endif
@endpush

@push('scripts')
@if($user->isTeknik())
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var _urunlerimHesabimInit = {!! json_encode($urunler, JSON_UNESCAPED_UNICODE) !!};

function urunlerimHesabim() {
    return {
        urunler: _urunlerimHesabimInit,
        modalAcik: false,
        duzenlenenId: null,
        form: { ad: '' },
        gorseller: [],
        dragUzerinde: false,
        kaydediliyor: false,
        hatalar: {},

        _initQuill: function() {
            if (!window._hQuill) {
                window._hQuill = new Quill('#h-quill-editor', {
                    theme: 'snow',
                    placeholder: 'Teknik özellikler, açıklama…',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['clean']
                        ]
                    }
                });
            }
        },

        yeniAc: function() {
            var self = this;
            this.form = { ad: '' };
            this.gorseller = [];
            this.duzenlenenId = null;
            this.hatalar = {};
            this.modalAcik = true;
            this.$nextTick(function() {
                self._initQuill();
                window._hQuill.setContents([]);
            });
        },

        duzenleAc: function(urun) {
            var self = this;
            this.form = { ad: urun.ad };
            this.gorseller = (urun.gorseller_yollar || []).map(function(yol, i) {
                return { url: (urun.gorseller || [])[i] || null, file: null, yol: yol };
            });
            this.duzenlenenId = urun.id;
            this.hatalar = {};
            this.modalAcik = true;
            this.$nextTick(function() {
                self._initQuill();
                window._hQuill.clipboard.dangerouslyPasteHTML(urun.aciklama || '');
            });
        },

        modalKapat: function() {
            this.modalAcik = false;
        },

        gorselIsle: function(file) {
            if (!file || !file.type.startsWith('image/')) return;
            if (this.gorseller.length >= 10) return;
            var reader = new FileReader();
            var self = this;
            reader.onload = function(e) {
                self.gorseller = self.gorseller.concat([{ url: e.target.result, file: file, yol: null }]);
            };
            reader.readAsDataURL(file);
        },

        gorselSil: function(idx) {
            this.gorseller = this.gorseller.filter(function(_, i) { return i !== idx; });
        },

        gorselSecCoklu: function(event) {
            var self = this;
            Array.from(event.target.files).forEach(function(f) { self.gorselIsle(f); });
            event.target.value = '';
        },

        gorselBirak: function(event) {
            this.dragUzerinde = false;
            var self = this;
            Array.from(event.dataTransfer.files).forEach(function(f) { self.gorselIsle(f); });
        },

        gorselYapistir: function(event) {
            var items = event.clipboardData && event.clipboardData.items;
            if (!items) return;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var file = items[i].getAsFile();
                    if (file) { this.gorselIsle(file); break; }
                }
            }
        },

        kaydet: async function() {
            var self = this;
            self.hatalar = {};
            if (!self.form.ad.trim()) {
                self.hatalar.ad = 'Ürün adı zorunludur.';
                return;
            }
            self.kaydediliyor = true;
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Yeni görselleri tek tek ayrı istekle yükle (max_file_uploads sınırını aşmak için)
            var yeniYollar = [];
            for (var i = 0; i < self.gorseller.length; i++) {
                if (self.gorseller[i].file !== null) {
                    try {
                        var ufd = new FormData();
                        ufd.append('gorsel', self.gorseller[i].file);
                        ufd.append('_token', csrfToken);
                        var ur = await fetch('{{ route("katalog.urunlerim.gorsel.yukle") }}', { method: 'POST', body: ufd });
                        var ud = await ur.json();
                        if (ud.path) yeniYollar.push(ud.path);
                        else { alert('Görsel yüklenemedi.'); self.kaydediliyor = false; return; }
                    } catch(e) { alert('Görsel yüklenirken hata oluştu.'); self.kaydediliyor = false; return; }
                }
            }

            var aciklama = window._hQuill ? window._hQuill.root.innerHTML : '';
            var fd = new FormData();
            fd.append('ad', self.form.ad.trim());
            fd.append('aciklama', aciklama);
            var korunan = self.gorseller.filter(function(g) { return g.yol !== null; }).map(function(g) { return g.yol; });
            fd.append('gorseller_koru', JSON.stringify(korunan.concat(yeniYollar)));
            fd.append('_token', csrfToken);

            var url = self.duzenlenenId
                ? '{{ route("katalog.urunlerim.update", ":id") }}'.replace(':id', self.duzenlenenId)
                : '{{ route("katalog.urunlerim.store") }}';

            fetch(url, { method: 'POST', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        if (self.duzenlenenId) {
                            var idx = self.urunler.findIndex(function(u) { return u.id === self.duzenlenenId; });
                            if (idx >= 0) { var arr = self.urunler.slice(); arr[idx] = data.urun; self.urunler = arr; }
                        } else {
                            self.urunler = [data.urun].concat(self.urunler);
                        }
                        self.modalKapat();
                    }
                })
                .catch(function() { alert('Kaydetme sırasında bir hata oluştu.'); })
                .finally(function() { self.kaydediliyor = false; });
        },

        sil: function(id) {
            if (!confirm('Bu ürünü silmek istediğinizden emin misiniz?')) return;
            var self = this;
            fetch('{{ route("katalog.urunlerim.destroy", ":id") }}'.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
            }).then(function(r) {
                if (r.ok) self.urunler = self.urunler.filter(function(u) { return u.id !== id; });
            });
        },
    };
}
</script>
@endif
@endpush
