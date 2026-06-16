@extends('layouts.app')

@push('styles')
<style>
  .urun-slider-track::-webkit-scrollbar { display: none; }
</style>
@endpush

@section('title', 'SUÇEK — Ana Sayfa')
@section('meta-description', 'SUÇEK — Mimarlık, İnşaat, Antika Koleksiyon ve Mağaza hizmetleri.')

@section('banner')
  @include('components.banner', [
    'mesaj' => icerik('anasayfa','banner_metni','Seçili ürünlerde %30 indirim ve ücretsiz kargo fırsatını kaçırma!'),
    'link'  => icerik('anasayfa','banner_link','') ?: route('magaza.index'),
  ])
@endsection

@section('content')

@if(session('basari'))
<div class="mx-4 lg:mx-8 mt-4 flex items-center gap-3 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] rounded-[10px] px-5 py-4 text-[13px]" role="alert">
  <i class="ti ti-circle-check text-lg shrink-0"></i>
  <span>{{ session('basari') }}</span>
</div>
@endif

{{-- ─── Hero Accordion ─────────────────────────────────────────────────── --}}

{{-- Mobile: 2×2 basit grid (md altı) --}}
<section class="grid grid-cols-2 gap-2 p-2 bg-[#F8FAFC] md:hidden" aria-label="Hizmet alanları">

  <div class="group relative rounded-xl overflow-hidden min-h-[200px] cursor-pointer">
    <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105" style="background-image:url('{{ icerik_gorsel('anasayfa','mimarlik_gorsel','https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=800&q=80') }}'); background-size:cover; background-position:center;"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.30)] to-transparent"></div>
    <a href="{{ route('mimarlik.index') }}" class="absolute inset-0 z-10 flex flex-col justify-end p-4" aria-label="Mimarlık">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1">PROJELENDİRME</p>
      <h2 class="text-[18px] font-bold text-white tracking-tight leading-tight">Mimarlık</h2>
    </a>
  </div>

  <div class="group relative rounded-xl overflow-hidden min-h-[200px] cursor-pointer">
    <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105" style="background-image:url('{{ icerik_gorsel('anasayfa','insaat_gorsel','https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80') }}'); background-size:cover; background-position:center;"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.30)] to-transparent"></div>
    <a href="{{ route('insaat.index') }}" class="absolute inset-0 z-10 flex flex-col justify-end p-4" aria-label="İnşaat">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1">UYGULAMA</p>
      <h2 class="text-[18px] font-bold text-white tracking-tight leading-tight">İnşaat</h2>
    </a>
  </div>

  <div class="group relative rounded-xl overflow-hidden min-h-[200px] cursor-pointer">
    <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105" style="background-image:url('{{ icerik_gorsel('anasayfa','magaza_gorsel','https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800&q=80') }}'); background-size:cover; background-position:center;"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.30)] to-transparent"></div>
    <a href="{{ route('magaza.index') }}" class="absolute inset-0 z-10 flex flex-col justify-end p-4" aria-label="Mağaza">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1">ALIŞVERİŞ</p>
      <h2 class="text-[18px] font-bold text-white tracking-tight leading-tight">Mağaza</h2>
    </a>
  </div>

  <div class="group relative rounded-xl overflow-hidden min-h-[200px] cursor-pointer">
    <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105" style="background-image:url('{{ icerik_gorsel('anasayfa','koleksiyon_gorsel','https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=800&q=80') }}'); background-size:cover; background-position:center;"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.30)] to-transparent"></div>
    <a href="{{ route('koleksiyon.index') }}" class="absolute inset-0 z-10 flex flex-col justify-end p-4" aria-label="Koleksiyon">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1">KOLEKSİYON</p>
      <h2 class="text-[18px] font-bold text-white tracking-tight leading-tight">Antika</h2>
    </a>
  </div>

</section>

{{-- Desktop: Yatay Expanding Accordion (md+) --}}
<section class="hidden md:flex overflow-hidden gap-2 p-2 bg-[#F8FAFC]"
         style="height:520px;"
         x-data="{ aktif: null }"
         aria-label="Hizmet alanları">

  {{-- ── MİMARLIK ── --}}
  <div class="relative overflow-hidden rounded-xl min-w-0 cursor-pointer transition-all duration-500 ease-in-out"
       :style="{ flex: aktif === 'mimarlik' ? '2.8' : aktif !== null ? '0.6' : '1' }"
       @mouseenter="aktif = 'mimarlik'"
       @mouseleave="aktif = null">

    {{-- Arka plan --}}
    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700"
         :class="aktif === 'mimarlik' ? 'scale-110' : 'scale-100'"
         style="background-image:url('{{ icerik_gorsel('anasayfa','mimarlik_gorsel','https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=800&q=80') }}');"></div>
    {{-- Gradient --}}
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.95)] via-[rgba(15,23,42,0.35)] to-transparent"></div>
    {{-- Yan karartma (diğer kartlar hover'da) --}}
    <div class="absolute inset-0 bg-[rgba(15,23,42,0.40)] transition-opacity duration-500"
         :class="aktif !== null && aktif !== 'mimarlik' ? 'opacity-100' : 'opacity-0'"></div>

    {{-- Varsayılan durum: alt başlık --}}
    <div class="absolute bottom-0 left-0 right-0 p-5 z-10 transition-all duration-300 pointer-events-none"
         :class="aktif !== null ? 'opacity-0 translate-y-2' : 'opacity-100 translate-y-0'">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1.5">PROJELENDİRME</p>
      <h2 class="text-[22px] font-bold text-white tracking-tight">Mimarlık</h2>
    </div>

    {{-- Daraltılmış durum: dikey etiket --}}
    <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none transition-all duration-300"
         :class="aktif !== null && aktif !== 'mimarlik' ? 'opacity-100' : 'opacity-0'">
      <span class="text-white/60 text-[11px] font-semibold tracking-[0.2em] uppercase select-none"
            style="writing-mode:vertical-rl; transform:rotate(180deg);">Mimarlık</span>
    </div>

    {{-- Aktif durum: ana içerik linki --}}
    <a href="{{ route('mimarlik.index') }}"
       class="absolute left-0 right-0 top-0 z-10 flex flex-col justify-end p-6 transition-all duration-300"
       style="bottom:52px;"
       :class="aktif === 'mimarlik' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5 pointer-events-none'"
       aria-label="Mimarlık hizmetleri">
      <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">PROJELENDİRME</p>
      <h2 class="text-[30px] font-bold text-white tracking-tight leading-tight">Mimarlık</h2>
    </a>

    {{-- Alt bar (aktifken yukarı kayar) --}}
    <div class="absolute bottom-0 left-0 right-0 flex bg-[rgba(15,23,42,0.92)] z-20 transition-all duration-500"
         style="height:52px;"
         :class="aktif === 'mimarlik' ? 'translate-y-0' : 'translate-y-full'">
      <a href="{{ route('mimarlik.ruhsat') }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-file-certificate text-sm shrink-0"></i><span class="truncate">Ruhsat Takibi</span>
      </a>
      <span class="w-px bg-white/10 shrink-0"></span>
      <a href="{{ route('mimarlik.icmimari') }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-sofa text-sm shrink-0"></i><span class="truncate">İç Mimari</span>
      </a>
    </div>

    {{-- Tıklama alanı (daraltılmış/varsayılan hâlde) --}}
    <a href="{{ route('mimarlik.index') }}"
       class="absolute inset-0 z-[5] transition-opacity duration-300"
       :class="aktif === 'mimarlik' ? 'opacity-0 pointer-events-none' : 'opacity-100'"
       aria-hidden="true" tabindex="-1"></a>
  </div>

  {{-- ── İNŞAAT ── --}}
  <div class="relative overflow-hidden rounded-xl min-w-0 cursor-pointer transition-all duration-500 ease-in-out"
       :style="{ flex: aktif === 'insaat' ? '2.8' : aktif !== null ? '0.6' : '1' }"
       @mouseenter="aktif = 'insaat'"
       @mouseleave="aktif = null">

    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700"
         :class="aktif === 'insaat' ? 'scale-110' : 'scale-100'"
         style="background-image:url('{{ icerik_gorsel('anasayfa','insaat_gorsel','https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.95)] via-[rgba(15,23,42,0.35)] to-transparent"></div>
    <div class="absolute inset-0 bg-[rgba(15,23,42,0.40)] transition-opacity duration-500"
         :class="aktif !== null && aktif !== 'insaat' ? 'opacity-100' : 'opacity-0'"></div>

    <div class="absolute bottom-0 left-0 right-0 p-5 z-10 transition-all duration-300 pointer-events-none"
         :class="aktif !== null ? 'opacity-0 translate-y-2' : 'opacity-100 translate-y-0'">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1.5">UYGULAMA</p>
      <h2 class="text-[22px] font-bold text-white tracking-tight">İnşaat</h2>
    </div>

    <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none transition-all duration-300"
         :class="aktif !== null && aktif !== 'insaat' ? 'opacity-100' : 'opacity-0'">
      <span class="text-white/60 text-[11px] font-semibold tracking-[0.2em] uppercase select-none"
            style="writing-mode:vertical-rl; transform:rotate(180deg);">İnşaat</span>
    </div>

    <a href="{{ route('insaat.index') }}"
       class="absolute left-0 right-0 top-0 z-10 flex flex-col justify-end p-6 transition-all duration-300"
       style="bottom:52px;"
       :class="aktif === 'insaat' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5 pointer-events-none'"
       aria-label="İnşaat hizmetleri">
      <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">UYGULAMA</p>
      <h2 class="text-[30px] font-bold text-white tracking-tight leading-tight">İnşaat</h2>
    </a>

    <div class="absolute bottom-0 left-0 right-0 flex bg-[rgba(15,23,42,0.92)] z-20 transition-all duration-500"
         style="height:52px;"
         :class="aktif === 'insaat' ? 'translate-y-0' : 'translate-y-full'">
      <a href="{{ route('insaat.hesaplama') }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-calculator text-sm shrink-0"></i><span class="truncate">Maliyet Hesaplama</span>
      </a>
      <span class="w-px bg-white/10 shrink-0"></span>
      <a href="{{ route('insaat.emsal') }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-ruler-measure text-sm shrink-0"></i><span class="truncate">Emsal Hesaplama</span>
      </a>
    </div>

    <a href="{{ route('insaat.index') }}"
       class="absolute inset-0 z-[5] transition-opacity duration-300"
       :class="aktif === 'insaat' ? 'opacity-0 pointer-events-none' : 'opacity-100'"
       aria-hidden="true" tabindex="-1"></a>
  </div>

  {{-- ── MAĞAZA ── --}}
  <div class="relative overflow-hidden rounded-xl min-w-0 cursor-pointer transition-all duration-500 ease-in-out"
       :style="{ flex: aktif === 'magaza' ? '2.8' : aktif !== null ? '0.6' : '1' }"
       @mouseenter="aktif = 'magaza'"
       @mouseleave="aktif = null">

    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700"
         :class="aktif === 'magaza' ? 'scale-110' : 'scale-100'"
         style="background-image:url('{{ icerik_gorsel('anasayfa','magaza_gorsel','https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800&q=80') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.95)] via-[rgba(15,23,42,0.35)] to-transparent"></div>
    <div class="absolute inset-0 bg-[rgba(15,23,42,0.40)] transition-opacity duration-500"
         :class="aktif !== null && aktif !== 'magaza' ? 'opacity-100' : 'opacity-0'"></div>

    <div class="absolute bottom-0 left-0 right-0 p-5 z-10 transition-all duration-300 pointer-events-none"
         :class="aktif !== null ? 'opacity-0 translate-y-2' : 'opacity-100 translate-y-0'">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1.5">ALIŞVERİŞ</p>
      <h2 class="text-[22px] font-bold text-white tracking-tight">Mağaza</h2>
    </div>

    <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none transition-all duration-300"
         :class="aktif !== null && aktif !== 'magaza' ? 'opacity-100' : 'opacity-0'">
      <span class="text-white/60 text-[11px] font-semibold tracking-[0.2em] uppercase select-none"
            style="writing-mode:vertical-rl; transform:rotate(180deg);">Mağaza</span>
    </div>

    <a href="{{ route('magaza.index') }}"
       class="absolute left-0 right-0 top-0 z-10 flex flex-col justify-end p-6 transition-all duration-300"
       style="bottom:52px;"
       :class="aktif === 'magaza' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5 pointer-events-none'"
       aria-label="Mağaza">
      <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">ALIŞVERİŞ</p>
      <h2 class="text-[30px] font-bold text-white tracking-tight leading-tight">Mağaza</h2>
    </a>

    <div class="absolute bottom-0 left-0 right-0 flex bg-[rgba(15,23,42,0.92)] z-20 transition-all duration-500"
         style="height:52px;"
         :class="aktif === 'magaza' ? 'translate-y-0' : 'translate-y-full'">
      <a href="{{ route('magaza.index', ['kategori' => 'spor']) }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-ball-football text-sm shrink-0"></i><span class="truncate">Spor</span>
      </a>
      <span class="w-px bg-white/10 shrink-0"></span>
      <a href="{{ route('magaza.index', ['kategori' => 'insaat']) }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-building text-sm shrink-0"></i><span class="truncate">İnşaat</span>
      </a>
    </div>

    <a href="{{ route('magaza.index') }}"
       class="absolute inset-0 z-[5] transition-opacity duration-300"
       :class="aktif === 'magaza' ? 'opacity-0 pointer-events-none' : 'opacity-100'"
       aria-hidden="true" tabindex="-1"></a>
  </div>

  {{-- ── KOLEKSİYON ── --}}
  <div class="relative overflow-hidden rounded-xl min-w-0 cursor-pointer transition-all duration-500 ease-in-out"
       :style="{ flex: aktif === 'koleksiyon' ? '2.8' : aktif !== null ? '0.6' : '1' }"
       @mouseenter="aktif = 'koleksiyon'"
       @mouseleave="aktif = null">

    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700"
         :class="aktif === 'koleksiyon' ? 'scale-110' : 'scale-100'"
         style="background-image:url('{{ icerik_gorsel('anasayfa','koleksiyon_gorsel','https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=800&q=80') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.95)] via-[rgba(15,23,42,0.35)] to-transparent"></div>
    <div class="absolute inset-0 bg-[rgba(15,23,42,0.40)] transition-opacity duration-500"
         :class="aktif !== null && aktif !== 'koleksiyon' ? 'opacity-100' : 'opacity-0'"></div>

    <div class="absolute bottom-0 left-0 right-0 p-5 z-10 transition-all duration-300 pointer-events-none"
         :class="aktif !== null ? 'opacity-0 translate-y-2' : 'opacity-100 translate-y-0'">
      <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1.5">KOLEKSİYON</p>
      <h2 class="text-[22px] font-bold text-white tracking-tight">Antika</h2>
    </div>

    <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none transition-all duration-300"
         :class="aktif !== null && aktif !== 'koleksiyon' ? 'opacity-100' : 'opacity-0'">
      <span class="text-white/60 text-[11px] font-semibold tracking-[0.2em] uppercase select-none"
            style="writing-mode:vertical-rl; transform:rotate(180deg);">Koleksiyon</span>
    </div>

    <a href="{{ route('koleksiyon.index') }}"
       class="absolute left-0 right-0 top-0 z-10 flex flex-col justify-end p-6 transition-all duration-300"
       style="bottom:52px;"
       :class="aktif === 'koleksiyon' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5 pointer-events-none'"
       aria-label="Koleksiyon">
      <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">KOLEKSİYON</p>
      <h2 class="text-[30px] font-bold text-white tracking-tight leading-tight">Antika</h2>
    </a>

    <div class="absolute bottom-0 left-0 right-0 flex bg-[rgba(15,23,42,0.92)] z-20 transition-all duration-500"
         style="height:52px;"
         :class="aktif === 'koleksiyon' ? 'translate-y-0' : 'translate-y-full'">
      <a href="{{ route('koleksiyon.index', ['kategori' => 'saat']) }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-clock text-sm shrink-0"></i><span class="truncate">Saat</span>
      </a>
      <span class="w-px bg-white/10 shrink-0"></span>
      <a href="{{ route('koleksiyon.index', ['kategori' => 'numizmatik']) }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti ti-coin text-sm shrink-0"></i><span class="truncate">Nümizmatik</span>
      </a>
    </div>

    <a href="{{ route('koleksiyon.index') }}"
       class="absolute inset-0 z-[5] transition-opacity duration-300"
       :class="aktif === 'koleksiyon' ? 'opacity-0 pointer-events-none' : 'opacity-100'"
       aria-hidden="true" tabindex="-1"></a>
  </div>

</section>

{{-- ─── Öne Çıkan & İndirimli Ürünler ─────────────────────────────────── --}}
@if(isset($slider_urunler) && $slider_urunler->count() > 0)
<div style="padding:30px 1.25rem 0;background:#F8FAFC;">
<section style="height:192px;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;"
         aria-label="Öne çıkan ürünler"
         x-data="{ kaydır(yön) { this.$refs.track.scrollBy({ left: yön * 170, behavior: 'smooth' }); } }">

  {{-- Başlık --}}
  <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 14px 6px;flex-shrink:0;">
    <div style="display:flex;align-items:center;gap:7px;">
      <div style="width:2px;height:13px;background:#CC2200;border-radius:1px;flex-shrink:0;"></div>
      <span style="font-size:11px;font-weight:700;color:#0F172A;letter-spacing:.02em;">Öne Çıkan Ürünler</span>
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
      <button @click="kaydır(-1)" aria-label="Önceki"
              style="width:22px;height:22px;border-radius:50%;background:white;border:1px solid #E2E8F0;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#94A3B8;"
              onmouseover="this.style.borderColor='#CC2200';this.style.color='#CC2200'"
              onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#94A3B8'">
        <i class="ti ti-chevron-left" style="font-size:11px;"></i>
      </button>
      <button @click="kaydır(1)" aria-label="Sonraki"
              style="width:22px;height:22px;border-radius:50%;background:white;border:1px solid #E2E8F0;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#94A3B8;"
              onmouseover="this.style.borderColor='#CC2200';this.style.color='#CC2200'"
              onmouseout="this.style.borderColor='#E2E8F0';this.style.color='#94A3B8'">
        <i class="ti ti-chevron-right" style="font-size:11px;"></i>
      </button>
      <a href="{{ route('magaza.index') }}"
         style="font-size:10px;color:#94A3B8;margin-left:2px;text-decoration:none;"
         onmouseover="this.style.color='#CC2200'" onmouseout="this.style.color='#94A3B8'">
        Tümü →
      </a>
    </div>
  </div>

  {{-- Kart şeridi --}}
  <div x-ref="track"
       class="urun-slider-track"
       style="flex:1;display:flex;gap:6px;overflow-x:auto;padding:0 10px 10px;-webkit-overflow-scrolling:touch;scrollbar-width:none;-ms-overflow-style:none;align-items:stretch;">
    @foreach($slider_urunler as $urun)
    <a href="{{ route('magaza.urun', $urun->slug) }}"
       class="group"
       style="flex-shrink:0;width:160px;border-radius:10px;overflow:hidden;position:relative;background:#1E293B;display:block;">
      @if($urun->gorsel)
      <img src="{{ asset('storage/'.$urun->gorsel) }}" alt="{{ $urun->ad }}"
           class="group-hover:scale-105 transition-transform duration-500"
           style="width:100%;height:100%;object-fit:cover;opacity:.88;display:block;"
           loading="lazy" width="160" height="160">
      @else
      <div style="width:100%;height:100%;background:#334155;display:flex;align-items:center;justify-content:center;">
        <i class="ti ti-photo" style="font-size:24px;color:#475569;"></i>
      </div>
      @endif
      <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(15,23,42,.85) 0%,rgba(15,23,42,.1) 50%,transparent 100%);"></div>
      @if($urun->indirim_yuzdesi)
      <span style="position:absolute;top:6px;left:6px;background:#CC2200;color:white;font-size:8px;font-weight:700;padding:1px 6px;border-radius:20px;line-height:15px;">
        -%{{ $urun->indirim_yuzdesi }}
      </span>
      @endif
      <div style="position:absolute;bottom:0;left:0;right:0;padding:8px 8px 7px;">
        <p style="color:white;font-size:11px;font-weight:600;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $urun->ad }}</p>
        <p style="color:#FF6B4A;font-size:11px;font-weight:700;margin-top:2px;">{{ number_format($urun->fiyat, 0, ',', '.') }} ₺
          @if($urun->eski_fiyat)<span style="color:rgba(255,255,255,.4);font-size:9px;font-weight:400;text-decoration:line-through;margin-left:3px;">{{ number_format($urun->eski_fiyat, 0, ',', '.') }} ₺</span>@endif
        </p>
      </div>
    </a>
    @endforeach
  </div>

</section>
</div>
@endif

{{-- ─── Instagram Feed ──────────────────────────────────────────────────── --}}
@if(isset($instagramPosts) && count($instagramPosts) > 0)
<section class="section" aria-label="Instagram gönderileri">
  <div class="flex items-center justify-between mb-6">
    <div>
      <p class="section-label mb-2">INSTAGRAM</p>
      <h2 class="text-[22px] font-bold text-[#0F172A] tracking-tight">Son Gönderiler</h2>
    </div>
    <a href="https://www.instagram.com/sucektr/" target="_blank" rel="noopener"
       class="flex items-center gap-1.5 text-[13px] font-medium text-[#64748B] hover:text-[#CC2200] transition-colors duration-200 min-h-[44px] px-1">
      @sucektr
      <i class="ti ti-arrow-right text-sm" aria-hidden="true"></i>
    </a>
  </div>
  <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
    @foreach($instagramPosts as $post)
    <a href="{{ $post['permalink'] }}" target="_blank" rel="noopener"
       class="group relative aspect-square rounded-xl overflow-hidden bg-[#F8FAFC] border border-[#E2E8F0] cursor-pointer"
       aria-label="Instagram gönderisini görüntüle">
      <img src="{{ $post['media_url'] ?? $post['thumbnail_url'] ?? '' }}"
           alt="{{ \Illuminate\Support\Str::limit($post['caption'] ?? 'Instagram', 60) }}"
           class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
           loading="lazy">
      <div class="absolute inset-0 bg-[rgba(15,23,42,0.55)] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
        <i class="ti ti-brand-instagram text-white text-2xl" aria-hidden="true"></i>
      </div>
    </a>
    @endforeach
  </div>
</section>
@endif

{{-- ─── Yaklaşım ──────────────────────────────────────────────────────── --}}
<section class="section" aria-labelledby="yaklasim-baslik">
  <div class="rounded-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-5 border border-[#E2E8F0] shadow-sm">
    <div class="lg:col-span-3 bg-[#F8FAFC] p-8 lg:p-12 flex flex-col justify-between">
      <div>
        <p class="section-label mb-3">YAKLAŞIMIMIZ</p>
        <h2 id="yaklasim-baslik" class="text-[26px] lg:text-[32px] font-bold text-[#0F172A] leading-tight tracking-tight mb-5">
          {{ icerik('anasayfa','yaklasim_baslik','Kalite, Güven ve Estetik') }}
        </h2>
        <p class="text-[15px] text-[#64748B] leading-relaxed">
          {{ icerik('anasayfa','yaklasim_metin','Her projede müşterilerimizin hayalini gerçeğe dönüştürüyoruz. Mimarlıktan inşaata, antika koleksiyondan mağazacılığa kadar uzanan geniş hizmet yelpazesiyle yanınızdayız.') }}
        </p>
      </div>
      <div class="mt-8 flex items-center gap-3 pt-6 border-t border-[#E2E8F0]">
        <div class="w-9 h-9 rounded-full bg-[#CC2200] flex items-center justify-center shrink-0">
          <i class="ti ti-building-community text-white text-base"></i>
        </div>
        <div>
          <span class="text-sm font-semibold text-[#0F172A]">SUÇEK EKİBİ</span>
          <span class="text-xs text-[#94A3B8] block mt-0.5 tracking-wider uppercase">Kurucu & Yönetim</span>
        </div>
      </div>
    </div>
    <div class="lg:col-span-2 min-h-[260px] lg:min-h-0"
         style="background-image:url('{{ icerik_gorsel('anasayfa','yaklasim_gorsel','https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=600&q=80') }}'); background-size:cover; background-position:center;">
    </div>
  </div>
</section>

{{-- ─── Referanslar ───────────────────────────────────────────────────── --}}
<section class="section" aria-labelledby="ref-baslik">
  <div class="mb-7">
    <p class="section-label mb-2">REFERANSLAR</p>
    <h2 id="ref-baslik" class="text-[26px] font-bold text-[#0F172A] tracking-tight">Müşterilerimiz Ne Diyor?</h2>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @php
    $referanslar = [
      ['metin' => icerik('anasayfa','ref_1_metin','SUÇEK ekibi, villam için hayalini kurduğum mimari projeyi en ince ayrıntısına kadar hayata geçirdi. Profesyonellik ve estetik anlayışları gerçekten üst düzey.'), 'ad' => icerik('anasayfa','ref_1_ad','Ahmet K.'), 'unvan' => icerik('anasayfa','ref_1_unvan','Villa Projesi, 2024')],
      ['metin' => icerik('anasayfa','ref_2_metin','Koleksiyon bölümünden aldığım antika saatin özgünlüğü ve değerlemesi konusunda son derece titiz davrandılar. Tam anlamıyla güvenilir bir adres.'), 'ad' => icerik('anasayfa','ref_2_ad','Nilüfer B.'), 'unvan' => icerik('anasayfa','ref_2_unvan','Antika Koleksiyon')],
      ['metin' => icerik('anasayfa','ref_3_metin','İnşaat projemiz zamanında, bütçe dahilinde ve vaat edilen kaliteyle teslim edildi. Bu üçlüyü bir arada bulmak artık nadiren mümkün.'), 'ad' => icerik('anasayfa','ref_3_ad','Murat T.'), 'unvan' => icerik('anasayfa','ref_3_unvan','Anahtar Teslim Proje, 2023')],
    ];
    @endphp
    @foreach($referanslar as $ref)
    <article class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl p-6 flex flex-col gap-4">
      <i class="ti ti-quote text-2xl text-[#E2E8F0]" aria-hidden="true"></i>
      <p class="text-[14px] text-[#475569] leading-relaxed flex-1">"{{ $ref['metin'] }}"</p>
      <div class="border-t border-[#E2E8F0] pt-4">
        <span class="text-sm font-semibold text-[#0F172A]">{{ $ref['ad'] }}</span>
        <span class="text-xs text-[#94A3B8] block mt-0.5">{{ $ref['unvan'] }}</span>
      </div>
    </article>
    @endforeach
  </div>
</section>


@endsection

@push('scripts')
@if(app(\App\Services\RecaptchaService::class)->aktif())
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
@endpush
