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

{{-- ─── Hero Accordion (8 panel, 2×4) ─────────────────────────────────── --}}

@php
$heroPaneller = [
  [
    'id' => 'mimarlik', 'kicker' => 'PROJELENDİRME', 'title' => 'Mimarlık',
    'image' => icerik_gorsel('anasayfa','mimarlik_gorsel','https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=800&q=80'),
    'href' => route('mimarlik.index'),
    'subLinks' => [
      ['icon' => 'ti-file-certificate', 'label' => 'Ruhsat Takibi', 'href' => route('mimarlik.ruhsat')],
      ['icon' => 'ti-building-arch',    'label' => 'Projelerimiz',  'href' => route('projeler.index')],
    ],
  ],
  [
    'id' => 'insaat', 'kicker' => 'UYGULAMA', 'title' => 'İnşaat',
    'image' => icerik_gorsel('anasayfa','insaat_gorsel','https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80'),
    'href' => route('insaat.index'),
    'subLinks' => [
      ['icon' => 'ti-calculator',      'label' => 'Maliyet Hesaplama', 'href' => route('insaat.hesaplama')],
      ['icon' => 'ti-ruler-measure',   'label' => 'Emsal Hesaplama',   'href' => route('insaat.emsal')],
    ],
  ],
  [
    'id' => 'icmimari', 'kicker' => 'TASARIM', 'title' => 'İç Mimari',
    'image' => icerik_gorsel('anasayfa','icmimari_gorsel','https://images.unsplash.com/photo-1615529162924-f8605388461d?w=800&q=80'),
    'href' => route('mimarlik.icmimari'),
    'subLinks' => [],
  ],
  [
    'id' => 'celikkonteyner', 'kicker' => 'İNŞAAT ÇÖZÜMLERİ', 'title' => 'Hafif Çelik & Konteyner',
    'image' => icerik_gorsel('hafif-celik-konteyner','hero_gorsel','https://images.unsplash.com/photo-1541976590-713941681591?w=800&q=80'),
    'href' => route('hafif-celik-konteyner.index'),
    'subLinks' => [],
  ],
  [
    'id' => 'magazainsaat', 'kicker' => 'ALIŞVERİŞ', 'title' => 'İnşaat Malzemeleri',
    'image' => icerik_gorsel('anasayfa','magaza_insaat_gorsel','https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80'),
    'href' => route('magaza.index', ['kategori' => 'insaat']),
    'subLinks' => [],
  ],
  [
    'id' => 'magazaspor', 'kicker' => 'ALIŞVERİŞ', 'title' => 'Spor Malzemeleri',
    'image' => icerik_gorsel('anasayfa','magaza_spor_gorsel','https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=800&q=80'),
    'href' => route('magaza.index', ['kategori' => 'spor']),
    'subLinks' => [],
  ],
  [
    'id' => 'koleksiyon', 'kicker' => 'ALIŞVERİŞ', 'title' => 'Koleksiyon',
    'image' => icerik_gorsel('anasayfa','koleksiyon_gorsel','https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=800&q=80'),
    'href' => route('koleksiyon.index'),
    'subLinks' => [
      ['icon' => 'ti-clock', 'label' => 'Saat',        'href' => route('koleksiyon.index', ['kategori' => 'saat'])],
      ['icon' => 'ti-coin',  'label' => 'Nümizmatik',  'href' => route('koleksiyon.index', ['kategori' => 'numizmatik'])],
    ],
  ],
  [
    'id' => 'celikag', 'kicker' => 'GÜVENLİK', 'title' => 'Çelik Güvenlik Ağı',
    'image' => icerik_gorsel('celik-guvenlik-agi','hero_gorsel','/images/mesh/hizmet-4.webp'),
    'href' => route('celik-guvenlik-agi.index'),
    'subLinks' => [],
  ],
];
@endphp

{{-- Mobile: 2 sütunlu grid --}}
<section class="grid grid-cols-2 gap-2 p-2 bg-[#F8FAFC] md:hidden" aria-label="Hizmet alanları">
  @foreach($heroPaneller as $p)
    <x-hero-panel-mobile :kicker="$p['kicker']" :title="$p['title']" :image="$p['image']" :href="$p['href']" />
  @endforeach
</section>

{{-- Desktop: Yatay Expanding Accordion, 2 sıra × 4 (md+) --}}
<section class="hidden md:block p-2 bg-[#F8FAFC]" x-data="{ aktif: null }" aria-label="Hizmet alanları">
  <div class="flex overflow-hidden gap-2 mb-2" style="height:396px;">
    @foreach(array_slice($heroPaneller, 0, 4) as $p)
      <x-hero-panel :id="$p['id']" :kicker="$p['kicker']" :title="$p['title']" :image="$p['image']" :href="$p['href']" :sub-links="$p['subLinks']" />
    @endforeach
  </div>
  <div class="flex overflow-hidden gap-2" style="height:396px;">
    @foreach(array_slice($heroPaneller, 4, 4) as $p)
      <x-hero-panel :id="$p['id']" :kicker="$p['kicker']" :title="$p['title']" :image="$p['image']" :href="$p['href']" :sub-links="$p['subLinks']" />
    @endforeach
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
