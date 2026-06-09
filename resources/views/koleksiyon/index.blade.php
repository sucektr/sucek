@extends('layouts.app')

@section('title', 'Koleksiyon — SUÇEK')
@section('meta-description', 'SUÇEK Koleksiyon: Antika saatler, nümizmatik ve antika eserler.')

@section('banner')
  @include('components.banner', ['mesaj' => icerik('koleksiyon','banner_metni','Koleksiyonunuzu değerletmek için randevu alın!')])
@endsection

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[280px] flex items-end" aria-label="Koleksiyon hero">
  <div class="absolute inset-0"
       style="background-image:url('{{ icerik_gorsel('koleksiyon','hero_gorsel','https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=1280&q=80') }}'); background-size:cover; background-position:center;"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.40)] to-transparent"></div>
  <div class="relative z-10 px-6 lg:px-12 py-12 w-full">
    <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">KOLEKSİYON</p>
    <h1 class="text-[36px] lg:text-[52px] font-bold text-white leading-tight tracking-tight">
      {{ icerik('koleksiyon','hero_baslik','Nadir Eserler, Eşsiz Değerler') }}
    </h1>
    @if(icerik('koleksiyon','hero_alt_baslik',''))
    <p class="text-[14px] text-[rgba(255,255,255,0.55)] mt-3 max-w-lg leading-relaxed">
      {{ icerik('koleksiyon','hero_alt_baslik','') }}
    </p>
    @endif
  </div>
</section>

{{-- ─── Filtreler + Grid ───────────────────────────────────────────────── --}}
<section class="section" x-data="{ aktif: '{{ $kategori }}' }" aria-label="Koleksiyon ürünleri">
  {{-- Filtre Bar --}}
  <div class="flex flex-wrap gap-2 mb-7" role="group" aria-label="Kategori filtresi">
    @php
    $kategoriler = ['tumu' => 'Tümü', 'saat' => 'Saatler', 'numizmatik' => 'Nümizmatik', 'antika' => 'Antika'];
    @endphp
    @foreach($kategoriler as $key => $label)
    <a href="{{ route('koleksiyon.index', $key !== 'tumu' ? ['kategori' => $key] : []) }}"
       class="px-4 py-1.5 text-[13px] font-medium rounded-full border transition-all duration-200 min-h-[36px] flex items-center
              {{ $kategori === $key
                ? 'bg-[#0F172A] text-white border-transparent'
                : 'bg-white text-[#64748B] border-[#E2E8F0] hover:border-[#CBD5E1] hover:text-[#0F172A]' }}"
       aria-current="{{ $kategori === $key ? 'true' : 'false' }}">
      {{ $label }}
    </a>
    @endforeach
  </div>

  {{-- Grid --}}
  @if($koleksiyonlar->count() > 0)
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach($koleksiyonlar as $item)
    <article class="group bg-white border border-[#E2E8F0] rounded-xl overflow-hidden hover:shadow-[0_6px_20px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all duration-200">
      <a href="{{ route('koleksiyon.show', $item->slug) }}" class="block relative aspect-square bg-[#F8FAFC] overflow-hidden" aria-label="{{ $item->ad }} detayları">
        @if($item->gorsel)
        <img src="{{ asset('storage/'.$item->gorsel) }}" alt="{{ $item->ad }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             loading="lazy" width="400" height="400">
        @else
        <div class="w-full h-full flex items-center justify-center">
          <i class="ti ti-photo text-4xl text-[#CBD5E1]" aria-hidden="true"></i>
        </div>
        @endif
        {{-- Durum badge --}}
        @if($item->durum === 'satildi')
        <div class="absolute inset-0 bg-[rgba(15,23,42,0.55)] flex items-center justify-center">
          <span class="text-[12px] font-semibold tracking-wider uppercase text-white bg-[rgba(15,23,42,0.80)] px-4 py-2 rounded-lg">Satıldı</span>
        </div>
        @elseif($item->durum === 'rezerve')
        <div class="absolute top-3 left-3">
          <span class="text-[10px] font-semibold tracking-wider uppercase text-white bg-[#CC2200] px-2.5 py-1 rounded-md">Rezerve</span>
        </div>
        @endif
        <div class="absolute top-3 right-3">
          <span class="text-[10px] font-medium text-[#64748B] bg-white px-2.5 py-1 rounded-md shadow-sm border border-[#E2E8F0]">{{ ucfirst($item->kategori) }}</span>
        </div>
      </a>
      <div class="p-4">
        <h3 class="text-[15px] font-semibold text-[#0F172A] mb-1 leading-snug tracking-tight">{{ $item->ad }}</h3>
        @if($item->aciklama)
        <p class="text-[12px] text-[#64748B] line-clamp-2 mb-3">{{ $item->aciklama }}</p>
        @endif
        @php
          $isPremium = auth()->check() && auth()->user()->isPremium();
          $premiumFiyat = $isPremium ? $item->premiumFiyat() : null;
        @endphp
        <div class="flex items-center justify-between">
          <div>
            @if($premiumFiyat)
            <div>
              <span class="text-[17px] font-bold" style="color:#6d28d9;">{{ number_format($premiumFiyat, 0, ',', '.') }} ₺</span>
              <span class="text-[12px] text-[#94A3B8] line-through ml-1.5">{{ number_format($item->fiyat, 0, ',', '.') }} ₺</span>
              <span class="ml-1 text-[9px] font-semibold px-1.5 py-0.5 rounded" style="background:#f5f3ff;color:#7c3aed;">
                <i class="ti ti-crown text-[9px]"></i> %15
              </span>
            </div>
            @elseif($item->fiyat)
            <span class="text-[17px] font-bold text-[#0F172A]">
              {{ number_format($item->fiyat, 0, ',', '.') }} ₺
            </span>
            @else
            <span class="text-[12px] text-[#94A3B8] tracking-wider uppercase">Fiyat Sorunuz</span>
            @endif
          </div>
          <a href="{{ route('koleksiyon.show', $item->slug) }}"
             class="text-[12px] font-medium text-[#64748B] hover:text-[#CC2200] flex items-center gap-1 transition-colors min-h-[44px] px-1"
             aria-label="{{ $item->ad }} detayları">
            Detay <i class="ti ti-arrow-right text-sm" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </article>
    @endforeach
  </div>
  @else
  <div class="flex flex-col items-center justify-center py-20 text-center">
    <div class="w-16 h-16 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center mb-4">
      <i class="ti ti-mood-empty text-2xl text-[#94A3B8]" aria-hidden="true"></i>
    </div>
    <p class="text-[15px] font-semibold text-[#0F172A]">Bu kategoride henüz ürün yok</p>
    <p class="text-[13px] text-[#64748B] mt-1">Diğer kategorilere göz atabilirsiniz.</p>
    <a href="{{ route('koleksiyon.index') }}" class="mt-5 btn btn-dark text-sm px-5 py-2.5">Tümünü Gör</a>
  </div>
  @endif
</section>

@endsection
