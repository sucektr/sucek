@extends('layouts.app')

@section('title', 'Maliyet Hesaplama — SUÇEK İnşaat')
@section('meta-description', 'İnşaat maliyetinizi hızlıca hesaplayın. Kaba, ince, dekorasyon veya anahtar teslim.')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden min-h-[220px] flex items-end" aria-label="Hesaplama hero">
  <div class="absolute inset-0 bg-[#141414]"></div>
  <div class="absolute inset-0 opacity-10"
       style="background-image:url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1280&q=80'); background-size:cover;"></div>
  <div class="relative z-10 px-9 lg:px-14 py-12 w-full">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[rgba(255,255,255,0.40)] mb-2">İNŞAAT · ARAÇLAR</p>
    <h1 class="font-display text-[36px] lg:text-[48px] font-semibold text-white leading-[1.1]">Maliyet Hesaplama</h1>
    <p class="text-[13px] text-[rgba(255,255,255,0.50)] mt-2">Tahmini inşaat maliyetinizi anında öğrenin.</p>
  </div>
</section>

{{-- ─── Hesaplama Formu ──────────────────────────────────────────────── --}}
<section class="section" aria-label="Maliyet hesaplama formu">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8"
       x-data="{
         tip: 'kaba',
         alan: '',
         sinif: 'standart',
         yukleniyor: false,
         sonuc: null,
         hesapla() {
           if (!this.alan || this.alan < 10) return;
           this.yukleniyor = true;
           this.sonuc = null;
           fetch('{{ route('insaat.hesapla') }}', {
             method: 'POST',
             headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
             body: JSON.stringify({ tip: this.tip, alan: this.alan, sinif: this.sinif })
           })
           .then(r => r.json())
           .then(d => { this.sonuc = d; this.yukleniyor = false; })
           .catch(() => { this.yukleniyor = false; });
         }
       }">

    {{-- Form Paneli --}}
    <div class="bg-white border border-[rgba(0,0,0,0.07)] rounded-[16px] p-8">
      <h2 class="font-display text-[24px] font-semibold text-[#0F0F0F] mb-6">Proje Detayları</h2>

      {{-- İnşaat Tipi --}}
      <fieldset class="mb-6">
        <legend class="form-label mb-3">İnşaat Türü</legend>
        <div class="grid grid-cols-2 gap-2" role="radiogroup">
          @php
          $tipler = ['kaba' => ['label' => 'Kaba İnşaat', 'ikon' => 'ti-wall'], 'ince' => ['label' => 'İnce İnşaat', 'ikon' => 'ti-paint'], 'dekorasyon' => ['label' => 'Dekorasyon', 'ikon' => 'ti-brush'], 'anahtar' => ['label' => 'Anahtar Teslim', 'ikon' => 'ti-key']];
          @endphp
          @foreach($tipler as $key => $t)
          <label class="flex items-center gap-2.5 p-3.5 border rounded-[10px] cursor-pointer transition-all duration-200"
                 :class="tip === '{{ $key }}' ? 'border-[#141414] bg-[#141414] text-white' : 'border-[rgba(0,0,0,0.12)] hover:border-[rgba(0,0,0,0.25)] text-[#5A5A5A]'">
            <input type="radio" name="tip" value="{{ $key }}" x-model="tip" class="sr-only">
            <i class="ti {{ $t['ikon'] }} text-[16px]" aria-hidden="true"></i>
            <span class="text-[11px] font-medium tracking-[0.5px]">{{ $t['label'] }}</span>
          </label>
          @endforeach
        </div>
      </fieldset>

      {{-- Alan --}}
      <div class="mb-6">
        <label for="alan" class="form-label">Toplam Alan (m²)</label>
        <div class="relative">
          <input id="alan" type="number" min="10" max="5000" step="1"
                 x-model="alan" placeholder="Örn: 150"
                 class="form-input pr-12 text-[15px]"
                 aria-describedby="alan-hint">
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[13px] font-medium text-[#A8A8A8]" aria-hidden="true">m²</span>
        </div>
        <p id="alan-hint" class="text-[11px] text-[#A8A8A8] mt-1.5">10 – 5.000 m² arasında giriş yapın.</p>
      </div>

      {{-- Sınıf --}}
      <fieldset class="mb-8">
        <legend class="form-label mb-3">Kalite Sınıfı</legend>
        <div class="flex flex-col gap-2" role="radiogroup">
          @php
          $siniflar = ['ekonomik' => ['label' => 'Ekonomik', 'aciklama' => 'Temel malzeme ve işçilik'], 'standart' => ['label' => 'Standart', 'aciklama' => 'Orta segment, dengeli kalite'], 'luks' => ['label' => 'Lüks', 'aciklama' => 'Üst segment malzeme'], 'ultra' => ['label' => 'Ultra Lüks', 'aciklama' => 'En üst kalite, özel işçilik']];
          @endphp
          @foreach($siniflar as $key => $s)
          <label class="flex items-center justify-between p-3.5 border rounded-[10px] cursor-pointer transition-all duration-200"
                 :class="sinif === '{{ $key }}' ? 'border-[#141414] bg-[rgba(0,0,0,0.03)]' : 'border-[rgba(0,0,0,0.10)] hover:border-[rgba(0,0,0,0.20)]'">
            <div class="flex items-center gap-3">
              <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center transition-all"
                   :class="sinif === '{{ $key }}' ? 'border-[#141414]' : 'border-[rgba(0,0,0,0.25)]'">
                <div class="w-2 h-2 rounded-full bg-[#141414] transition-all"
                     :class="sinif === '{{ $key }}' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
              </div>
              <div>
                <span class="text-[13px] font-medium text-[#0F0F0F]">{{ $s['label'] }}</span>
                <span class="block text-[11px] text-[#A8A8A8]">{{ $s['aciklama'] }}</span>
              </div>
            </div>
            <input type="radio" name="sinif" value="{{ $key }}" x-model="sinif" class="sr-only">
          </label>
          @endforeach
        </div>
      </fieldset>

      <button @click="hesapla()" :disabled="!alan || alan < 10 || yukleniyor"
              class="w-full flex items-center justify-center gap-2 bg-[#141414] text-white text-[12px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] disabled:opacity-40 disabled:cursor-not-allowed active:scale-[0.98] transition-all duration-200 min-h-[52px]">
        <template x-if="yukleniyor">
          <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
        </template>
        <template x-if="!yukleniyor">
          <i class="ti ti-calculator text-base" aria-hidden="true"></i>
        </template>
        <span x-text="yukleniyor ? 'Hesaplanıyor...' : 'Hesapla'"></span>
      </button>
    </div>

    {{-- Sonuç Paneli --}}
    <div>
      {{-- Boş state --}}
      <div x-show="!sonuc && !yukleniyor"
           class="h-full flex flex-col items-center justify-center text-center py-16 bg-[#F9F9F9] border border-[rgba(0,0,0,0.07)] rounded-[16px]">
        <i class="ti ti-calculator text-5xl text-[#D0D0D0] mb-4" aria-hidden="true"></i>
        <p class="text-[14px] font-medium text-[#5A5A5A] mb-1">Hesaplama sonucu burada görünecek</p>
        <p class="text-[12px] text-[#A8A8A8]">Sol taraftaki formu doldurun ve hesapla butonuna tıklayın.</p>
      </div>

      {{-- Yükleniyor --}}
      <div x-show="yukleniyor"
           class="h-full flex items-center justify-center bg-[#F9F9F9] border border-[rgba(0,0,0,0.07)] rounded-[16px] py-16"
           style="display:none;">
        <div class="text-center">
          <svg class="animate-spin w-8 h-8 text-[#141414] mx-auto mb-3" fill="none" viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          <p class="text-[13px] text-[#5A5A5A]">Hesaplanıyor...</p>
        </div>
      </div>

      {{-- Sonuç --}}
      <div x-show="sonuc && !yukleniyor"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-y-2"
           x-transition:enter-end="opacity-100 translate-y-0"
           class="bg-[#141414] rounded-[16px] p-8 text-white"
           style="display:none;" role="region" aria-live="polite" aria-label="Hesaplama sonucu">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-[rgba(255,255,255,0.08)]">
          <div>
            <p class="text-[9px] font-medium tracking-[2.5px] uppercase text-[rgba(255,255,255,0.40)] mb-1">TAHMİNİ MALİYET</p>
            <p class="text-[9px] font-medium tracking-[1.5px] text-[rgba(255,255,255,0.30)]">KDV Hariç</p>
          </div>
          <div class="text-right">
            <span class="font-display text-[42px] font-semibold text-white leading-none" x-text="sonuc?.toplam + ' ₺'"></span>
          </div>
        </div>
        <dl class="space-y-3.5">
          <div class="flex justify-between">
            <dt class="text-[12px] text-[rgba(255,255,255,0.50)]">Alan</dt>
            <dd class="text-[12px] font-medium" x-text="sonuc?.alan + ' m²'"></dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-[12px] text-[rgba(255,255,255,0.50)]">Birim Fiyat (m²)</dt>
            <dd class="text-[12px] font-medium" x-text="sonuc?.birim_fiyat + ' ₺'"></dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-[12px] text-[rgba(255,255,255,0.50)]">KDV (%20)</dt>
            <dd class="text-[12px] font-medium" x-text="sonuc?.kdv + ' ₺'"></dd>
          </div>
          <div class="flex justify-between pt-3.5 border-t border-[rgba(255,255,255,0.08)]">
            <dt class="text-[13px] font-semibold">Genel Toplam (KDV Dahil)</dt>
            <dd class="text-[15px] font-bold font-display" x-text="sonuc?.genel_toplam + ' ₺'"></dd>
          </div>
        </dl>
        <p class="text-[10px] text-[rgba(255,255,255,0.30)] leading-relaxed mt-6">
          * Bu hesaplama tahminidir. Gerçek maliyet, malzeme seçimi, bölge, işçilik koşulları ve
          projenin özelliklerine göre değişiklik gösterebilir.
        </p>
        <a href="{{ route('home') }}#iletisim"
           class="mt-5 flex items-center justify-center gap-2 border border-[rgba(255,255,255,0.20)] text-white text-[11px] font-medium tracking-[1.5px] uppercase py-3.5 rounded-[10px] hover:border-white hover:bg-[rgba(255,255,255,0.05)] transition-all duration-200 min-h-[48px]">
          <i class="ti ti-mail text-sm" aria-hidden="true"></i> Detaylı Teklif Al
        </a>
      </div>
    </div>

  </div>
</section>

@endsection
