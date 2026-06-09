@extends('layouts.app')

@section('title', 'Mağaza — SUÇEK')
@section('meta-description', 'SUÇEK Mağaza: Spor malzemeleri, dekorasyon ve inşaat malzemeleri.')

@section('banner')
  @include('components.banner', ['mesaj' => icerik('magaza','banner_metni','Seçili ürünlerde %30 indirim ve ücretsiz kargo fırsatını kaçırma!')])
@endsection

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[280px] flex items-end" aria-label="Mağaza hero">
  <div class="absolute inset-0"
       style="background-image:url('{{ icerik_gorsel('magaza','hero_gorsel','https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=1280&q=80') }}'); background-size:cover; background-position:center;"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.40)] to-transparent"></div>
  <div class="relative z-10 px-6 lg:px-12 py-12 w-full">
    <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">MAĞAZA</p>
    <h1 class="text-[36px] lg:text-[52px] font-bold text-white leading-tight tracking-tight">
      {{ icerik('magaza','hero_baslik','Kaliteli Ürünler, Uygun Fiyatlar') }}
    </h1>
  </div>
</section>

{{-- ─── Arama & Filtre Çubuğu ──────────────────────────────────────────── --}}
<section class="section pb-0" aria-label="Filtreler">
  @php
    $aktifKat  = $kategori ?? '';
    $aktifQ    = $q ?? '';
    $aktifSira = $siralama ?? 'yeni';
  @endphp

  {{-- Kategori pill'leri --}}
  <div class="flex flex-wrap gap-2 mb-5" role="group" aria-label="Kategori filtresi">
    @foreach(['' => 'Tümü', 'spor' => 'Spor', 'dekorasyon' => 'Dekorasyon', 'insaat' => 'İnşaat', 'diger' => 'Diğer'] as $val => $lbl)
    @php
      $href = route('magaza.index', array_filter(['q' => $aktifQ, 'kategori' => $val, 'siralama' => $aktifSira !== 'yeni' ? $aktifSira : null]));
    @endphp
    <a href="{{ $href }}"
       class="px-4 py-1.5 text-[13px] font-medium rounded-full border transition-all duration-200 min-h-[36px] flex items-center
              {{ $aktifKat === $val
                ? 'bg-[#0F172A] text-white border-transparent'
                : 'bg-white text-[#64748B] border-[#E2E8F0] hover:border-[#CBD5E1] hover:text-[#0F172A]' }}"
       aria-current="{{ $aktifKat === $val ? 'true' : 'false' }}">
      {{ $lbl }}
    </a>
    @endforeach
  </div>

  {{-- Arama + Sıralama --}}
  <form method="GET" action="{{ route('magaza.index') }}" class="flex flex-wrap items-center gap-3">
    @if($aktifKat !== '')
    <input type="hidden" name="kategori" value="{{ $aktifKat }}">
    @endif

    <div class="relative flex-1 min-w-[220px]">
      <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#94A3B8] text-[15px] pointer-events-none"></i>
      <input type="text" name="q" value="{{ $aktifQ }}"
             placeholder="Ürün ara..."
             class="w-full pl-10 pr-4 py-2.5 border border-[#E2E8F0] rounded-lg text-[14px] text-[#0F172A] placeholder:text-[#94A3B8] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-all bg-white min-h-[40px]">
    </div>

    <select name="siralama" onchange="this.form.submit()"
            class="px-3 py-2.5 border border-[#E2E8F0] rounded-lg text-[14px] text-[#0F172A] focus:outline-none focus:border-[#CC2200] transition-colors bg-white min-h-[40px]">
      <option value="yeni"         {{ $aktifSira === 'yeni'         ? 'selected' : '' }}>En Yeni</option>
      <option value="fiyat_artan"  {{ $aktifSira === 'fiyat_artan'  ? 'selected' : '' }}>Fiyat: Düşükten Yükseğe</option>
      <option value="fiyat_azalan" {{ $aktifSira === 'fiyat_azalan' ? 'selected' : '' }}>Fiyat: Yüksekten Düşüğe</option>
    </select>

    @if($aktifQ !== '' || $aktifKat !== '')
    <a href="{{ route('magaza.index') }}"
       class="text-[13px] text-[#94A3B8] hover:text-[#0F172A] transition-colors whitespace-nowrap min-h-[40px] flex items-center px-1">
      Temizle ×
    </a>
    @endif
  </form>
</section>

{{-- ─── İçerik ────────────────────────────────────────────────────────── --}}
@if($aramaAktif ?? false)

  {{-- Flat grid - arama/filtre aktif --}}
  <section class="section" aria-label="Arama sonuçları">
    @if(isset($urunler) && $urunler->count() > 0)
      <p class="text-[13px] text-[#94A3B8] mb-5">{{ $urunler->total() }} ürün bulundu</p>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" role="list">
        @foreach($urunler as $urun)
          @include('magaza._urun_karti', compact('urun'))
        @endforeach
      </div>
      @if($urunler->hasPages())
      <div class="mt-8">{{ $urunler->links() }}</div>
      @endif
    @else
      <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center mb-4">
          <i class="ti ti-search-off text-2xl text-[#94A3B8]" aria-hidden="true"></i>
        </div>
        <p class="text-[15px] font-semibold text-[#0F172A]">Sonuç bulunamadı</p>
        <p class="text-[13px] text-[#64748B] mt-1">Farklı bir arama terimi veya kategori deneyin.</p>
        <a href="{{ route('magaza.index') }}" class="mt-4 text-[13px] text-[#CC2200] hover:underline">Filtreyi temizle</a>
      </div>
    @endif
  </section>

@else

  {{-- Kategori şeritleri - normal görünüm --}}
  @php
  $kategoriler = [
    ['key' => 'spor',       'baslik' => 'Spor Malzemeleri', 'aciklama' => 'Fitness, outdoor ve takım sporları ekipmanları', 'ikon' => 'ti-ball-football', 'urunler' => $spor ?? collect()],
    ['key' => 'dekorasyon', 'baslik' => 'Dekorasyon',        'aciklama' => 'Ev ve ofis dekorasyon ürünleri',                 'ikon' => 'ti-lamp',          'urunler' => $dekorasyon ?? collect()],
    ['key' => 'insaat',     'baslik' => 'İnşaat Malzemeleri','aciklama' => 'Yapı ve tesisat malzemeleri',                    'ikon' => 'ti-hammer',        'urunler' => $insaat ?? collect()],
    ['key' => 'diger',      'baslik' => 'Diğer',             'aciklama' => 'Çeşitli ürünler',                                'ikon' => 'ti-package',       'urunler' => $diger ?? collect()],
  ];
  @endphp

  @foreach($kategoriler as $kat)
  @if($kat['urunler']->count() > 0)
  <section class="section" aria-labelledby="kat-{{ $kat['key'] }}-baslik">
    <div class="flex items-center justify-between mb-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-[#F8FAFC] border border-[#E2E8F0] rounded-xl flex items-center justify-center">
          <i class="ti {{ $kat['ikon'] }} text-[16px] text-[#64748B]" aria-hidden="true"></i>
        </div>
        <div>
          <h2 id="kat-{{ $kat['key'] }}-baslik" class="text-[18px] font-bold text-[#0F172A] tracking-tight">{{ $kat['baslik'] }}</h2>
          <p class="text-[12px] text-[#94A3B8]">{{ $kat['aciklama'] }}</p>
        </div>
      </div>
      <a href="{{ route('magaza.index') }}?kategori={{ $kat['key'] }}"
         class="hidden sm:flex items-center gap-1.5 text-[13px] font-medium text-[#64748B] hover:text-[#CC2200] transition-colors min-h-[44px]"
         aria-label="{{ $kat['baslik'] }} tümünü gör">
        Tümünü Gör <i class="ti ti-arrow-right text-sm" aria-hidden="true"></i>
      </a>
    </div>

    {{-- Yatay Scroll Slider --}}
    <div class="overflow-x-auto -mx-5 px-5 scrollbar-hide" role="list">
      <div class="flex gap-3.5 pb-2" style="width:max-content;">
        @foreach($kat['urunler'] as $urun)
        <article class="group bg-white border border-[#E2E8F0] rounded-xl overflow-hidden hover:shadow-[0_6px_20px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all duration-200 flex-shrink-0 w-[220px]"
                 role="listitem">
          <a href="{{ route('magaza.urun', $urun->slug) }}" class="block">
            <div class="relative aspect-square bg-[#F8FAFC] overflow-hidden">
              @if($urun->gorsel)
              <img src="{{ asset('storage/'.$urun->gorsel) }}" alt="{{ $urun->ad }}"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                   loading="lazy" width="220" height="220">
              @else
              <div class="w-full h-full flex items-center justify-center">
                <i class="ti ti-photo text-3xl text-[#CBD5E1]" aria-hidden="true"></i>
              </div>
              @endif
              @if($urun->indirim_yuzdesi)
              <span class="absolute top-2 left-2 bg-[#CC2200] text-white text-[10px] font-semibold px-2 py-0.5 rounded-md">-%{{ $urun->indirim_yuzdesi }}</span>
              @endif
            </div>
            <div class="p-3.5">
              <h3 class="text-[14px] font-semibold text-[#0F172A] mb-2 line-clamp-2 leading-snug tracking-tight">{{ $urun->ad }}</h3>
              <div class="flex items-baseline gap-2">
                <span class="text-[16px] font-bold text-[#0F172A]">{{ number_format($urun->fiyat, 2, ',', '.') }} ₺</span>
                @if($urun->eski_fiyat)
                <span class="text-[12px] text-[#94A3B8] line-through">{{ number_format($urun->eski_fiyat, 2, ',', '.') }} ₺</span>
                @endif
              </div>
            </div>
          </a>
          <div class="px-3.5 pb-3.5">
            <button
              @click="
                fetch('{{ route('sepet.ekle') }}', {
                  method: 'POST',
                  headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                  body: JSON.stringify({urun_id:{{ $urun->id }}, urun_tipi:'urun', adet:1})
                }).then(r=>r.json()).then(d=>{ $root.sepetAdet=d.adet; $root.bildirimiGoster('{{ $urun->ad }} sepete eklendi'); })
              "
              class="w-full flex items-center justify-center gap-1.5 text-[13px] font-medium text-white bg-[#0F172A] py-2.5 rounded-lg hover:bg-[#1e293b] active:scale-95 transition-all duration-200 min-h-[40px]"
              aria-label="{{ $urun->ad }} sepete ekle">
              <i class="ti ti-shopping-cart text-sm" aria-hidden="true"></i> Sepete Ekle
            </button>
          </div>
        </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif
  @endforeach

  @if(($spor ?? collect())->count() === 0 && ($dekorasyon ?? collect())->count() === 0 && ($insaat ?? collect())->count() === 0 && ($diger ?? collect())->count() === 0)
  <div class="section flex flex-col items-center justify-center py-20 text-center">
    <div class="w-16 h-16 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center mb-4">
      <i class="ti ti-package-off text-2xl text-[#94A3B8]" aria-hidden="true"></i>
    </div>
    <p class="text-[15px] font-semibold text-[#0F172A]">Mağaza henüz hazırlanıyor</p>
    <p class="text-[13px] text-[#64748B] mt-1">Yakında ürünlerimiz burada olacak.</p>
  </div>
  @endif

@endif

@endsection

@push('styles')
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
