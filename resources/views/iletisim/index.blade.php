@extends('layouts.app')

@section('title', 'İletişim — SUÇEK')
@section('meta-description', 'SUÇEK ile iletişime geçin. Mimarlık, inşaat ve koleksiyon hizmetlerimiz hakkında bilgi alın.')

@push('styles')
@endpush

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden min-h-[220px] flex items-end" aria-label="İletişim hero">
  <div class="absolute inset-0 bg-[#141414]"></div>
  <div class="absolute inset-0 opacity-[0.06]"
       style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%); background-size: 20px 20px;"></div>
  <div class="relative z-10 px-9 lg:px-14 py-12 w-full">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[rgba(255,255,255,0.40)] mb-2">İLETİŞİM</p>
    <h1 class="font-display text-[36px] lg:text-[48px] font-semibold text-white leading-[1.1]">
      {{ icerik('iletisim','hero_baslik','Bize Ulaşın') }}
    </h1>
    <p class="text-[13px] text-[rgba(255,255,255,0.45)] mt-2 max-w-md">
      {{ icerik('iletisim','hero_alt_baslik','Projeleriniz ve hizmetlerimiz hakkında konuşalım.') }}
    </p>
  </div>
</section>

{{-- Başarı Mesajı --}}
@if(session('basari'))
<div class="mx-9 lg:mx-14 mt-6 flex items-start gap-3 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] rounded-[10px] px-5 py-4 text-[13px]" role="alert">
  <i class="ti ti-circle-check text-lg mt-0.5 shrink-0"></i>
  <span>{{ session('basari') }}</span>
</div>
@endif

{{-- Bilgi Kartları + Harita --}}
<section class="section" aria-label="İletişim bilgileri">
  <div class="grid lg:grid-cols-5 gap-8">

    {{-- Sol: Bilgi Kartları --}}
    <div class="lg:col-span-2 flex flex-col gap-3">

      <div class="flex items-start gap-4 bg-white border border-[rgba(0,0,0,0.07)] rounded-[14px] p-5">
        <div class="w-10 h-10 rounded-[10px] bg-[#F0F0F0] flex items-center justify-center shrink-0">
          <i class="ti ti-map-pin text-[#0F0F0F] text-lg"></i>
        </div>
        <div>
          <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-1">ADRES</p>
          <p class="text-[14px] font-medium text-[#0F0F0F] leading-snug">{{ icerik('site','adres','Kazım Karabekir Mah. Misaki-i Milli Cad. No: 6/A Etimesgut, Ankara') }}</p>
        </div>
      </div>

      <div class="flex items-start gap-4 bg-white border border-[rgba(0,0,0,0.07)] rounded-[14px] p-5">
        <div class="w-10 h-10 rounded-[10px] bg-[#F0F0F0] flex items-center justify-center shrink-0">
          <i class="ti ti-phone text-[#0F0F0F] text-lg"></i>
        </div>
        <div>
          <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-1">TELEFON</p>
          <a href="tel:{{ preg_replace('/[^+\d]/', '', icerik('site','telefon','+905442948402')) }}"
             class="text-[14px] font-medium text-[#0F0F0F] hover:text-[#CC2200] transition-colors">
            {{ icerik('site','telefon','+90 (544) 294 84 02') }}
          </a>
        </div>
      </div>

      <div class="flex items-start gap-4 bg-white border border-[rgba(0,0,0,0.07)] rounded-[14px] p-5">
        <div class="w-10 h-10 rounded-[10px] bg-[#F0F0F0] flex items-center justify-center shrink-0">
          <i class="ti ti-mail text-[#0F0F0F] text-lg"></i>
        </div>
        <div>
          <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-1">E-POSTA</p>
          <a href="mailto:{{ icerik('site','email','info@sucek.com.tr') }}"
             class="text-[14px] font-medium text-[#0F0F0F] hover:text-[#CC2200] transition-colors">
            {{ icerik('site','email','info@sucek.com.tr') }}
          </a>
        </div>
      </div>

      <div class="flex items-start gap-4 bg-white border border-[rgba(0,0,0,0.07)] rounded-[14px] p-5">
        <div class="w-10 h-10 rounded-[10px] bg-[#F0F0F0] flex items-center justify-center shrink-0">
          <i class="ti ti-clock text-[#0F0F0F] text-lg"></i>
        </div>
        <div>
          <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-1">ÇALIŞMA SAATLERİ</p>
          <p class="text-[13px] font-medium text-[#0F0F0F]">{{ icerik('site','calisma_hafta','Pzt–Cum 09:00–18:00') }}</p>
          <p class="text-[12px] text-[#A8A8A8] mt-0.5">{{ icerik('site','calisma_cumartesi','Cts 10:00–15:00') }}</p>
          <p class="text-[12px] text-[#A8A8A8] mt-0.5">{{ icerik('site','calisma_pazar','Pazar: Kapalı') }}</p>
        </div>
      </div>

    </div>

    {{-- Sağ: OpenStreetMap --}}
    <div class="lg:col-span-3">
      <div id="sucek-harita" class="rounded-[16px] overflow-hidden border border-[rgba(0,0,0,0.07)]" style="height:420px;"></div>
    </div>

  </div>
</section>

{{-- İletişim Formu --}}
<section class="section border-t border-[rgba(0,0,0,0.06)]" aria-label="Mesaj formu">

  <div class="mb-8">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-1.5">MESAJ GÖNDERIN</p>
    <h2 class="font-display text-[28px] lg:text-[36px] font-semibold text-[#0F0F0F]">
      {{ icerik('iletisim','form_baslik','Nasıl Yardımcı Olabiliriz?') }}
    </h2>
  </div>

  <div class="grid lg:grid-cols-2 gap-12">

    {{-- Form --}}
    <div>
      <form id="iletisim-form" action="{{ route('iletisim.gonder') }}" method="POST" class="space-y-4" novalidate>
        @csrf
        <input type="hidden" name="kaynak" value="iletisim">

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label for="ad" class="form-label">Ad Soyad <span class="text-[#CC2200]">*</span></label>
            <input id="ad" name="ad" type="text" required
                   value="{{ old('ad') }}"
                   placeholder="Adınız Soyadınız"
                   class="form-input @error('ad') border-[#CC2200] @enderror"
                   autocomplete="name">
            @error('ad')<p class="text-[11px] text-[#CC2200] mt-1.5">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="email" class="form-label">E-posta <span class="text-[#CC2200]">*</span></label>
            <input id="email" name="email" type="email" required
                   value="{{ old('email') }}"
                   placeholder="ornek@email.com"
                   class="form-input @error('email') border-[#CC2200] @enderror"
                   autocomplete="email">
            @error('email')<p class="text-[11px] text-[#CC2200] mt-1.5">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label for="telefon" class="form-label">Telefon</label>
            <input id="telefon" name="telefon" type="tel"
                   value="{{ old('telefon') }}"
                   placeholder="+90 (___) ___ __ __"
                   class="form-input"
                   autocomplete="tel">
          </div>
          <div>
            <label for="konu" class="form-label">Konu</label>
            <input id="konu" name="konu" type="text"
                   value="{{ old('konu') }}"
                   placeholder="Mimarlık, İnşaat, Koleksiyon..."
                   class="form-input">
          </div>
        </div>

        <div>
          <label for="mesaj" class="form-label">Mesajınız <span class="text-[#CC2200]">*</span></label>
          <textarea id="mesaj" name="mesaj" rows="6" required
                    placeholder="Projeniz veya talebiniz hakkında bilgi verin..."
                    class="form-input resize-none @error('mesaj') border-[#CC2200] @enderror">{{ old('mesaj') }}</textarea>
          @error('mesaj')<p class="text-[11px] text-[#CC2200] mt-1.5">{{ $message }}</p>@enderror
        </div>

        @php $rcKey = app(\App\Services\RecaptchaService::class)->siteKey(); @endphp
        @if($rcKey)
        <div class="g-recaptcha" data-sitekey="{{ $rcKey }}" data-theme="light"></div>
        @error('recaptcha')<p class="text-[11px] text-[#CC2200]">{{ $message }}</p>@enderror
        @endif

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-[#141414] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] active:scale-[0.98] transition-all duration-200 min-h-[52px]">
          <i class="ti ti-send text-sm" aria-hidden="true"></i>
          Mesajı Gönder
        </button>

        <p class="text-[11px] text-[#A8A8A8] text-center">Genellikle 1 iş günü içinde dönüş yapıyoruz.</p>
      </form>
    </div>

    {{-- Yan Bilgi --}}
    <div class="flex flex-col gap-6 lg:pl-6 lg:border-l lg:border-[rgba(0,0,0,0.07)]">

      <div>
        <h3 class="font-display text-[20px] font-semibold text-[#0F0F0F] mb-3">
          {{ icerik('iletisim','yon_baslik','Projeleriniz İçin Buradayız') }}
        </h3>
        <p class="text-[14px] text-[#5A5A5A] leading-relaxed">
          {{ icerik('iletisim','yon_metin','Mimarlık, inşaat, antika koleksiyon ve mağaza hizmetlerimiz hakkında her türlü soru ve talebiniz için iletişim formunu doldurabilir ya da doğrudan bizimle iletişime geçebilirsiniz.') }}
        </p>
      </div>

      <div class="space-y-3">
        <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A8A8A8]">HİZMET ALANLARI</p>
        @foreach([
          ['ti-building', 'Mimari Tasarım & Ruhsat'],
          ['ti-home-2', 'Anahtar Teslim İnşaat'],
          ['ti-diamond', 'Antika & Koleksiyon'],
          ['ti-shopping-bag', 'Spor & Dekorasyon Mağazası'],
        ] as [$icon, $label])
        <div class="flex items-center gap-3 py-2.5 border-b border-[rgba(0,0,0,0.05)]">
          <i class="ti {{ $icon }} text-[#B8962E] text-base w-5 shrink-0"></i>
          <span class="text-[13px] font-medium text-[#3A3A3A]">{{ $label }}</span>
        </div>
        @endforeach
      </div>

      <div class="bg-[#141414] rounded-[14px] p-6">
        <p class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.40)] mb-2">SOSYAL MEDYA</p>
        @php
          $sosyalIletisim = [
            'Instagram' => ['ikon' => 'ti-brand-instagram', 'url' => icerik('site','sosyal_instagram','https://www.instagram.com/sucektr/')],
            'Facebook'  => ['ikon' => 'ti-brand-facebook',  'url' => icerik('site','sosyal_facebook','https://www.facebook.com/sucektr/')],
            'Pinterest' => ['ikon' => 'ti-brand-pinterest', 'url' => icerik('site','sosyal_pinterest','https://in.pinterest.com/sucektr/')],
            'WhatsApp'  => ['ikon' => 'ti-brand-whatsapp',  'url' => icerik('site','sosyal_whatsapp','https://wa.me/905442948402')],
            'YouTube'   => ['ikon' => 'ti-brand-youtube',   'url' => icerik('site','sosyal_youtube','')],
            'LinkedIn'  => ['ikon' => 'ti-brand-linkedin',  'url' => icerik('site','sosyal_linkedin','')],
            'TikTok'    => ['ikon' => 'ti-brand-tiktok',    'url' => icerik('site','sosyal_tiktok','')],
          ];
        @endphp
        <div class="flex flex-wrap gap-3 mt-3">
          @foreach($sosyalIletisim as $ad => $s)
            @if($s['url'])
            <a href="{{ $s['url'] }}" target="_blank" rel="noopener"
               class="w-10 h-10 rounded-[8px] border border-[rgba(255,255,255,0.15)] flex items-center justify-center text-[rgba(255,255,255,0.60)] hover:text-white hover:border-white transition-all"
               aria-label="{{ $ad }}">
              <i class="ti {{ $s['ikon'] }} text-base"></i>
            </a>
            @endif
          @endforeach
        </div>
      </div>

    </div>
  </div>
</section>

@endsection

@push('scripts')
@if(app(\App\Services\RecaptchaService::class)->aktif())
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
@php
  $mapsKey = icerik('sistem','google_maps_key', env('GOOGLE_MAPS_KEY',''));
  $haritaLat  = icerik('iletisim','harita_lat','39.9501');
  $haritaLng  = icerik('iletisim','harita_lng','32.7013');
  $haritaZoom = icerik('iletisim','harita_zoom','15');
  $sirketAdi  = icerik('site','sirket_adi','SUÇEK');
  $adres      = icerik('site','adres','Etimesgut, Ankara');
@endphp
@if($mapsKey)
<script>
(function () {
  window._sucekHaritaInit = function () {
    const lat  = parseFloat('{{ $haritaLat }}');
    const lng  = parseFloat('{{ $haritaLng }}');
    const zoom = parseInt('{{ $haritaZoom }}');

    const map = new google.maps.Map(document.getElementById('sucek-harita'), {
      center: { lat, lng },
      zoom: zoom,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: true,
      zoomControl: true,
      styles: [
        { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
        { featureType: 'transit', stylers: [{ visibility: 'off' }] },
      ],
    });

    const marker = new google.maps.Marker({
      position: { lat, lng },
      map,
      title: '{{ $sirketAdi }}',
    });

    const info = new google.maps.InfoWindow({
      content: '<div style="font-family:Inter,sans-serif;padding:4px 2px;min-width:160px">' +
               '<p style="font-weight:700;font-size:13px;margin:0 0 4px">{{ $sirketAdi }}</p>' +
               '<p style="font-size:12px;color:#5A5A5A;margin:0">{{ $adres }}</p>' +
               '<a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($adres) }}" ' +
               'target="_blank" style="display:inline-block;margin-top:8px;font-size:11px;font-weight:600;color:#CC2200;text-decoration:none;">Yol Tarifi Al →</a>' +
               '</div>',
    });

    marker.addListener('click', () => info.open(map, marker));
    info.open(map, marker);
  };
})();
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $mapsKey }}&callback=_sucekHaritaInit&loading=async" async defer></script>
@else
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script>
(function () {
  const lat  = parseFloat('{{ $haritaLat }}');
  const lng  = parseFloat('{{ $haritaLng }}');
  const zoom = parseInt('{{ $haritaZoom }}');
  const map = L.map('sucek-harita').setView([lat, lng], zoom);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap', maxZoom: 19
  }).addTo(map);
  L.marker([lat, lng]).addTo(map)
   .bindPopup('<strong>{{ $sirketAdi }}</strong><br>{{ $adres }}').openPopup();
})();
</script>
@endif
@endpush
