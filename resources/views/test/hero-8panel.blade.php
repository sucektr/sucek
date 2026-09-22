@extends('layouts.app')

@section('title', 'Hero Test — 8 Panel')
@section('meta-description', 'Test sayfası')

@section('content')

@php
$panelData = [
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
    'id' => 'koleksiyon', 'kicker' => 'KOLEKSİYON', 'title' => 'Koleksiyon',
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

<div class="mx-4 lg:mx-8 mt-4 flex items-center gap-3 bg-[#FEF2F0] border border-[#FECDC7] text-[#CC2200] rounded-[10px] px-5 py-4 text-[13px]" role="status">
  <i class="ti ti-flask text-lg shrink-0"></i>
  <span><b>Test sayfası</b> — bu, anasayfaya henüz uygulanmadı. 8 panelli hero önerisini burada inceleyebilirsiniz. Bu sayfa hiçbir menüye bağlı değil.</span>
</div>

{{-- Mobile: 2 sütunlu grid --}}
<section class="grid grid-cols-2 gap-2 p-2 bg-[#F8FAFC] md:hidden" aria-label="Hizmet alanları">
  @foreach($panelData as $p)
    <x-hero-panel-mobile :kicker="$p['kicker']" :title="$p['title']" :image="$p['image']" :href="$p['href']" />
  @endforeach
</section>

{{-- Desktop: Yatay Expanding Accordion (md+) --}}
<section class="hidden md:flex overflow-hidden gap-2 p-2 bg-[#F8FAFC]"
         style="height:520px;"
         x-data="{ aktif: null }"
         aria-label="Hizmet alanları">
  @foreach($panelData as $p)
    <x-hero-panel :id="$p['id']" :kicker="$p['kicker']" :title="$p['title']" :image="$p['image']" :href="$p['href']" :sub-links="$p['subLinks']" />
  @endforeach
</section>

@endsection
