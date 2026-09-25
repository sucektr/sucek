@extends('layouts.app')

@section('title', ceviri('Koleksiyon') . ' — SUÇEK')
@section('meta-description', ceviri('SUÇEK Koleksiyon: Antika saatler, nümizmatik ve antika eserler.'))

@section('banner')
  @include('components.banner', ['mesaj' => icerik_metin('koleksiyon','banner_metni','Koleksiyonunuzu değerletmek için randevu alın!')])
@endsection

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[280px] flex items-end" aria-label="{{ ceviri('Koleksiyon hero') }}">
  <div class="absolute inset-0"
       style="background-image:url('{{ icerik_gorsel('koleksiyon','hero_gorsel','https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=1280&q=80') }}'); background-size:cover; background-position:center;"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.40)] to-transparent"></div>
  <div class="relative z-10 px-6 lg:px-12 py-12 w-full">
    <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">{{ ceviri('KOLEKSİYON') }}</p>
    <h1 class="text-[36px] lg:text-[52px] font-bold text-white leading-tight tracking-tight">
      {{ icerik_metin('koleksiyon','hero_baslik','Nadir Eserler, Eşsiz Değerler') }}
    </h1>
    @if(icerik('koleksiyon','hero_alt_baslik',''))
    <p class="text-[14px] text-[rgba(255,255,255,0.55)] mt-3 max-w-lg leading-relaxed">
      {{ icerik_metin('koleksiyon','hero_alt_baslik','') }}
    </p>
    @endif
  </div>
</section>

{{-- ─── Filtreler + Grid ───────────────────────────────────────────────── --}}
<section class="section" x-data="{ aktif: '{{ $kategori }}' }" aria-label="{{ ceviri('Koleksiyon ürünleri') }}">
  {{-- Filtre Bar --}}
  <div class="flex flex-wrap gap-2 mb-7" role="group" aria-label="{{ ceviri('Kategori filtresi') }}">
    @php
    $kategoriler = ['tumu' => ceviri('Tümü'), 'saat' => ceviri('Saatler'), 'numizmatik' => ceviri('Nümizmatik'), 'antika' => ceviri('Antika')];
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

  {{-- Ülke Filtresi (sadece Nümizmatik) — aranabilir açılır liste --}}
  @if($kategori === 'numizmatik' && $ulkeler->count() > 0)
  <div class="relative max-w-xs mb-7"
       x-data="{
         open: false,
         q: '',
         ulkeler: @js($ulkeler),
         ulkeEtiketleri: @js($ulkeler->mapWithKeys(fn($u) => [$u => ceviri($u)])),
         secili: @js($ulke),
         get filtered() {
           return this.q === '' ? this.ulkeler : this.ulkeler.filter(u => u.toLowerCase().includes(this.q.toLowerCase()));
         },
         git(u) {
           window.location = '{{ route('koleksiyon.index', ['kategori' => 'numizmatik']) }}' + (u ? '?ulke=' + encodeURIComponent(u) : '');
         }
       }"
       @click.outside="open = false">
    <label class="block text-[11px] font-medium text-[#94A3B8] uppercase tracking-wide mb-1.5">{{ ceviri('Ülke') }}</label>
    <div class="relative">
      <input type="text" x-model="q" @focus="open = true"
             placeholder="{{ $ulke ? ceviri($ulke) : ceviri('Tüm ülkeler') }}"
             class="w-full pl-4 pr-9 py-2.5 text-[13px] border border-[#E2E8F0] rounded-lg focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
             aria-label="{{ ceviri('Ülke ara') }}">
      <i class="ti ti-search text-[14px] text-[#94A3B8] absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" aria-hidden="true"></i>
      <div x-show="open" x-transition style="display:none;"
           class="absolute z-20 mt-1.5 w-full max-h-64 overflow-y-auto bg-white border border-[#E2E8F0] rounded-lg shadow-[0_8px_24px_rgba(15,23,42,0.10)] py-1">
        <button type="button" @click="git(''); open = false"
                class="w-full text-left px-4 py-2 text-[13px] hover:bg-[#F8FAFC] transition-colors"
                :class="!secili ? 'text-[#CC2200] font-semibold' : 'text-[#64748B]'">
          {{ ceviri('Tüm ülkeler') }}
        </button>
        <template x-for="u in filtered" :key="u">
          <button type="button" @click="git(u); open = false"
                  class="w-full text-left px-4 py-2 text-[13px] hover:bg-[#F8FAFC] transition-colors"
                  :class="u === secili ? 'text-[#CC2200] font-semibold' : 'text-[#64748B]'"
                  x-text="ulkeEtiketleri[u] ?? u"></button>
        </template>
        <p x-show="filtered.length === 0" style="display:none;" class="px-4 py-2 text-[12px] text-[#94A3B8]">{{ ceviri('Eşleşen ülke yok') }}</p>
      </div>
    </div>
  </div>
  @endif

  {{-- Grid --}}
  @if($koleksiyonlar->count() > 0)
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach($koleksiyonlar as $item)
    <article class="group bg-white border border-[#E2E8F0] rounded-xl overflow-hidden hover:shadow-[0_6px_20px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all duration-200">
      @php
        $itemKatLabel = match($item->kategori) {
          'saat' => ceviri('Saatler'), 'numizmatik' => ceviri('Nümizmatik'), 'antika' => ceviri('Antika'),
          default => ucfirst($item->kategori),
        };
      @endphp
      <a href="{{ route('koleksiyon.show', $item->slug) }}" class="block relative aspect-square bg-[#F8FAFC] overflow-hidden" aria-label="{{ $item->ad }} {{ ceviri('detayları') }}">
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
          <span class="text-[12px] font-semibold tracking-wider uppercase text-white bg-[rgba(15,23,42,0.80)] px-4 py-2 rounded-lg">{{ ceviri('Satıldı') }}</span>
        </div>
        @elseif($item->durum === 'rezerve')
        <div class="absolute top-3 left-3">
          <span class="text-[10px] font-semibold tracking-wider uppercase text-white bg-[#CC2200] px-2.5 py-1 rounded-md">{{ ceviri('Rezerve') }}</span>
        </div>
        @endif
        <div class="absolute top-3 right-3">
          <span class="text-[10px] font-medium text-[#64748B] bg-white px-2.5 py-1 rounded-md shadow-sm border border-[#E2E8F0]">{{ $itemKatLabel }}</span>
        </div>
      </a>
      <div class="p-4">
        <h3 class="text-[15px] font-semibold text-[#0F172A] mb-1 leading-snug tracking-tight">{{ $item->ad }}</h3>
        @if($item->ulke)
        <p class="text-[11px] text-[#94A3B8] mb-1.5 flex items-center gap-1"><i class="ti ti-map-pin text-xs"></i>{{ ceviri($item->ulke) }}</p>
        @endif
        @if($item->aciklama)
        <div class="text-[12px] text-[#64748B] line-clamp-2 mb-3 urun-aciklama">{!! $item->aciklama !!}</div>
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
            <span class="text-[12px] text-[#94A3B8] tracking-wider uppercase">{{ ceviri('Fiyat Sorunuz') }}</span>
            @endif
          </div>
          <a href="{{ route('koleksiyon.show', $item->slug) }}"
             class="text-[12px] font-medium text-[#64748B] hover:text-[#CC2200] flex items-center gap-1 transition-colors min-h-[44px] px-1"
             aria-label="{{ $item->ad }} {{ ceviri('detayları') }}">
            {{ ceviri('Detay') }} <i class="ti ti-arrow-right text-sm" aria-hidden="true"></i>
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
    <p class="text-[15px] font-semibold text-[#0F172A]">{{ ceviri('Bu kategoride henüz ürün yok') }}</p>
    <p class="text-[13px] text-[#64748B] mt-1">{{ ceviri('Diğer kategorilere göz atabilirsiniz.') }}</p>
    <a href="{{ route('koleksiyon.index') }}" class="mt-5 btn btn-dark text-sm px-5 py-2.5">{{ ceviri('Tümünü Gör') }}</a>
  </div>
  @endif
</section>

@endsection

@push('styles')
<style>
.urun-aciklama p{margin-bottom:0}
.urun-aciklama strong{font-weight:600;color:#0F172A}
.urun-aciklama ul,.urun-aciklama ol{padding-left:1.2em;margin:0}
</style>
@endpush
