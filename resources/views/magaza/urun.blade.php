@extends('layouts.app')

@section('title', $urun->ad . ' — SUÇEK Mağaza')

@section('content')

{{-- ─── Breadcrumb ─────────────────────────────────────────────────────── --}}
<nav class="px-[9px] pt-5 pb-3" aria-label="İz izi">
  <ol class="flex items-center gap-1.5 text-[11px] text-[#A8A8A8]" role="list">
    <li><a href="{{ route('home') }}" class="hover:text-[#0F0F0F] transition-colors">Anasayfa</a></li>
    <li aria-hidden="true"><i class="ti ti-chevron-right text-[10px]"></i></li>
    <li><a href="{{ route('magaza.index') }}" class="hover:text-[#0F0F0F] transition-colors">Mağaza</a></li>
    <li aria-hidden="true"><i class="ti ti-chevron-right text-[10px]"></i></li>
    <li class="text-[#0F0F0F] font-medium" aria-current="page">{{ $urun->ad }}</li>
  </ol>
</nav>

@php
$gorsellerArray = [];
if ($urun->gorsel) $gorsellerArray[] = asset('storage/'.$urun->gorsel);
foreach ($urun->gorseller ?? [] as $g) {
    $gorsellerArray[] = asset('storage/'.$g);
}

$hasVaryant  = $urun->hasVaryant();
$seceneklerJs = [];
$varyantlarJs = [];
if ($hasVaryant) {
    $seceneklerJs = $urun->secenekler()->with('degerler')->get()->map(fn($s) => [
        'ad'      => $s->ad,
        'degerler' => $s->degerler->pluck('deger')->toArray(),
    ])->toArray();
    $varyantlarJs = $urun->varyantlar()->where('aktif', true)->get()->map(fn($v) => [
        'id'      => $v->id,
        'degerler' => $v->degerler,
        'stok'    => $v->stok,
    ])->toArray();
    $secimlerJs = collect($seceneklerJs)->pluck('ad')->mapWithKeys(fn($ad) => [$ad => null])->toArray();
}
@endphp

{{-- ─── Ürün Detay ──────────────────────────────────────────────────────── --}}
<section class="px-[9px] pb-10"
  x-data="{
    gorselIndex: 0,
    adet: 1,
    gorseller: {{ json_encode($gorsellerArray) }},
    hasVaryant: {{ $hasVaryant ? 'true' : 'false' }},
    secenekler: {!! json_encode($seceneklerJs) !!},
    varyantlar: {!! json_encode($varyantlarJs) !!},
    secimler: {!! json_encode($hasVaryant ? $secimlerJs : (object)[]) !!},
    get secilenVaryant() {
      if (!this.hasVaryant) return null;
      if (Object.values(this.secimler).some(v => !v)) return null;
      return this.varyantlar.find(v =>
        Object.keys(this.secimler).every(k => v.degerler[k] === this.secimler[k])
      ) || null;
    },
    get secimTamMi() {
      if (!this.hasVaryant) return true;
      return Object.values(this.secimler).every(v => !!v);
    },
    get aktifStok() {
      if (!this.hasVaryant) return {{ $urun->stok }};
      const v = this.secilenVaryant;
      return v ? v.stok : 0;
    },
    degerMevcut(secAd, deger) {
      const test = {...this.secimler, [secAd]: deger};
      return this.varyantlar.some(v =>
        Object.keys(test).every(k => !test[k] || v.degerler[k] === test[k]) && v.stok > 0
      );
    },
    get gorselSayisi() { return this.gorseller.length; },
    onceki() { this.gorselIndex = (this.gorselIndex - 1 + this.gorselSayisi) % this.gorselSayisi; },
    sonraki() { this.gorselIndex = (this.gorselIndex + 1) % this.gorselSayisi; }
  }"
  aria-label="Ürün detayı">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

    {{-- Görseller --}}
    <div class="flex flex-col gap-3">
      <div class="relative aspect-square bg-[#F5F5F5] rounded-[16px] overflow-hidden">
        @if($urun->gorsel)
        <img :src="gorseller[gorselIndex]" alt="{{ $urun->ad }}"
             class="w-full h-full object-contain transition-opacity duration-150"
             width="600" height="600">

        {{-- Ok butonları (birden fazla görsel varsa) --}}
        <template x-if="gorselSayisi > 1">
          <div>
            <button @click="onceki()"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white border border-[rgba(0,0,0,0.10)] rounded-full flex items-center justify-center hover:bg-[#F0F0F0] transition-colors shadow-sm z-10"
                    aria-label="Önceki görsel">
              <i class="ti ti-chevron-left text-sm" aria-hidden="true"></i>
            </button>
            <button @click="sonraki()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white border border-[rgba(0,0,0,0.10)] rounded-full flex items-center justify-center hover:bg-[#F0F0F0] transition-colors shadow-sm z-10"
                    aria-label="Sonraki görsel">
              <i class="ti ti-chevron-right text-sm" aria-hidden="true"></i>
            </button>
            <div class="absolute bottom-3 right-3 bg-[rgba(0,0,0,0.50)] text-white text-[10px] font-medium px-2.5 py-1 rounded-full"
                 x-text="(gorselIndex + 1) + ' / ' + gorselSayisi"
                 aria-live="polite"></div>
          </div>
        </template>
        @else
        <div class="w-full h-full flex items-center justify-center">
          <i class="ti ti-photo text-6xl text-[#D0D0D0]" aria-hidden="true"></i>
        </div>
        @endif
        @if($urun->indirim_yuzdesi)
        <div class="absolute top-4 left-4">
          <span class="bg-[#CC2200] text-white text-[11px] font-bold tracking-[1px] px-3 py-1 rounded-[6px]">-%{{ $urun->indirim_yuzdesi }}</span>
        </div>
        @endif
        <div class="absolute top-4 right-4">
          @if($hasVaryant)
          <template x-if="!secimTamMi">
            <span class="text-[10px] font-medium bg-[#F5F5F5] text-[#A8A8A8] px-2.5 py-1 rounded-[6px] border">Seçenek Seçin</span>
          </template>
          <template x-if="secimTamMi && aktifStok > 0">
            <span class="flex items-center gap-1 bg-green-50 border border-green-200 text-green-700 text-[10px] font-medium px-2.5 py-1 rounded-[6px]">
              <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Stokta
            </span>
          </template>
          <template x-if="secimTamMi && aktifStok <= 0">
            <span class="text-[10px] font-medium bg-[#F5F5F5] text-[#A8A8A8] px-2.5 py-1 rounded-[6px] border">Stok Yok</span>
          </template>
          @else
          @if($urun->stok > 0)
          <span class="flex items-center gap-1 bg-green-50 border border-green-200 text-green-700 text-[10px] font-medium px-2.5 py-1 rounded-[6px]">
            <span class="w-1.5 h-1.5 bg-green-500 rounded-full" aria-hidden="true"></span> Stokta
          </span>
          @else
          <span class="text-[10px] font-medium bg-[#F5F5F5] text-[#A8A8A8] px-2.5 py-1 rounded-[6px] border">Stok Yok</span>
          @endif
          @endif
        </div>
      </div>

      {{-- Thumbnail'lar (birden fazla görsel varsa) --}}
      @if(count($gorsellerArray) > 1)
      <div class="flex gap-2 overflow-x-auto scrollbar-hide">
        @foreach($gorsellerArray as $i => $gorselUrl)
        <button @click="gorselIndex = {{ $i }}"
                class="shrink-0 w-16 h-16 rounded-[8px] overflow-hidden border-2 transition-all duration-200"
                :class="gorselIndex === {{ $i }} ? 'border-[#141414] shadow-sm' : 'border-transparent opacity-70 hover:opacity-100 hover:border-[rgba(0,0,0,0.20)]'"
                aria-label="Görsel {{ $i + 1 }}">
          <img src="{{ $gorselUrl }}" alt="" class="w-full h-full object-cover" loading="lazy">
        </button>
        @endforeach
      </div>
      @endif
    </div>

    {{-- Bilgi --}}
    <div class="flex flex-col gap-5">
      <div>
        <p class="text-[10px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-1">{{ ucfirst($urun->kategori) }}</p>
        <h1 class="font-display text-[32px] lg:text-[38px] font-semibold text-[#0F0F0F] leading-[1.1] mb-2">{{ $urun->ad }}</h1>
        @if($urun->stok_kodu)
        <p class="text-[11px] text-[#A8A8A8]">Ürün Kodu: {{ $urun->stok_kodu }}</p>
        @endif
      </div>

      {{-- Fiyat --}}
      @php
        $isPremium = auth()->check() && auth()->user()->isPremium();
        $premiumFiyat = $isPremium ? $urun->premiumFiyat() : null;
        if ($premiumFiyat) {
            $karsilastirmaFiyat = ($urun->eski_fiyat && $urun->eski_fiyat > $premiumFiyat)
                ? $urun->eski_fiyat
                : $urun->fiyat;
        }
      @endphp
      <div class="py-4 border-y border-[rgba(0,0,0,0.08)]">
        @if($premiumFiyat)
        <div class="flex items-baseline gap-3 flex-wrap mb-1">
          <span class="font-display text-[36px] font-semibold" style="color:#6d28d9;">{{ number_format($premiumFiyat, 2, ',', '.') }} ₺</span>
          <span class="font-display text-[20px] text-[#A8A8A8] line-through">{{ number_format($karsilastirmaFiyat, 2, ',', '.') }} ₺</span>
        </div>
        <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-[1px] uppercase px-2.5 py-1 rounded-[6px]" style="background:#f5f3ff;color:#7c3aed;">
          <i class="ti ti-crown text-[10px]"></i> Premium — %15 İndirim
        </span>
        @else
        <div class="flex items-baseline gap-3">
          <span class="font-display text-[36px] font-semibold text-[#0F0F0F]">{{ number_format($urun->fiyat, 2, ',', '.') }} ₺</span>
          @if($urun->eski_fiyat)
          <span class="font-display text-[20px] text-[#A8A8A8] line-through">{{ number_format($urun->eski_fiyat, 2, ',', '.') }} ₺</span>
          @endif
        </div>
        @endif
      </div>

      {{-- Varyant Seçicileri --}}
      @if($hasVaryant)
      <div class="space-y-4">
        <template x-for="(sec, si) in secenekler" :key="si">
          <div>
            <p class="text-[10px] font-semibold tracking-[1.5px] uppercase text-[#A8A8A8] mb-2" x-text="sec.ad"></p>
            <div class="flex flex-wrap gap-2">
              <template x-for="(d, di) in sec.degerler" :key="di">
                <button type="button"
                        @click="secimler[sec.ad] = (secimler[sec.ad] === d ? null : d); adet = 1"
                        :disabled="!degerMevcut(sec.ad, d)"
                        :class="secimler[sec.ad] === d
                          ? 'bg-[#0F0F0F] text-white border-[#0F0F0F]'
                          : (!degerMevcut(sec.ad, d)
                            ? 'opacity-35 cursor-not-allowed line-through border-[#E2E2E2] text-[#A8A8A8]'
                            : 'border-[#E2E2E2] hover:border-[#0F0F0F] text-[#0F0F0F] cursor-pointer')"
                        class="px-4 py-2 border rounded-[8px] text-[12px] font-medium transition-colors"
                        x-text="d">
                </button>
              </template>
            </div>
          </div>
        </template>
      </div>
      @endif

      {{-- Adet --}}
      <div>
        <label class="form-label text-[10px]">Adet</label>
        <div class="flex items-center gap-0">
          <button @click="adet = Math.max(1, adet - 1)"
                  class="w-11 h-11 flex items-center justify-center border border-[rgba(0,0,0,0.15)] rounded-l-[8px] hover:bg-[#F0F0F0] transition-colors"
                  aria-label="Azalt">
            <i class="ti ti-minus text-sm" aria-hidden="true"></i>
          </button>
          <span class="w-12 h-11 flex items-center justify-center border-t border-b border-[rgba(0,0,0,0.15)] text-[14px] font-medium" x-text="adet" aria-live="polite"></span>
          <button @click="adet = Math.min(aktifStok, adet + 1)"
                  class="w-11 h-11 flex items-center justify-center border border-[rgba(0,0,0,0.15)] rounded-r-[8px] hover:bg-[#F0F0F0] transition-colors"
                  aria-label="Artır">
            <i class="ti ti-plus text-sm" aria-hidden="true"></i>
          </button>
        </div>
      </div>

      {{-- Butonlar --}}
      <div class="flex flex-col sm:flex-row gap-2.5">
        <button
          @click="
            if (hasVaryant && !secimTamMi) { alert('Lütfen tüm seçenekleri seçin.'); return; }
            if (aktifStok <= 0) return;
            fetch('{{ route('sepet.ekle') }}', {
              method: 'POST',
              headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
              body: JSON.stringify({
                urun_id: {{ $urun->id }},
                urun_tipi: 'urun',
                adet: adet,
                varyant_id: hasVaryant && secilenVaryant ? secilenVaryant.id : null
              })
            }).then(r=>r.json()).then(d=>{ $root.sepetAdet=d.adet; $root.bildirimiGoster('{{ $urun->ad }} sepete eklendi!'); })
          "
          :disabled="aktifStok <= 0 || (hasVaryant && !secimTamMi)"
          :class="(aktifStok <= 0 || (hasVaryant && !secimTamMi))
            ? 'bg-[#D0D0D0] cursor-not-allowed'
            : 'bg-[#141414] hover:bg-[#2a2a2a] active:scale-[0.98] cursor-pointer'"
          class="flex-1 flex items-center justify-center gap-2 text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] transition-all duration-200 min-h-[52px]">
          <i class="ti ti-shopping-cart text-sm" aria-hidden="true"></i>
          <span x-text="hasVaryant && !secimTamMi ? 'Seçenek Seçin' : (aktifStok <= 0 ? 'Stok Yok' : 'Sepete Ekle')">
            {{ $hasVaryant ? 'Seçenek Seçin' : ($urun->stok > 0 ? 'Sepete Ekle' : 'Stok Yok') }}
          </span>
        </button>
        <a href="{{ route('home') }}#iletisim"
           class="flex items-center justify-center gap-2 border border-[rgba(0,0,0,0.15)] text-[#5A5A5A] text-[11px] font-medium tracking-[1.5px] uppercase py-4 px-5 rounded-[10px] hover:bg-[#F0F0F0] hover:text-[#0F0F0F] transition-all duration-200 min-h-[52px]">
          <i class="ti ti-message text-sm" aria-hidden="true"></i> Soru Sor
        </a>
      </div>

      {{-- Özellikler --}}
      @if($urun->ozellikler && count($urun->ozellikler) > 0)
      <div class="border border-[rgba(0,0,0,0.08)] rounded-[12px] overflow-hidden">
        <div class="px-5 py-3.5 border-b border-[rgba(0,0,0,0.08)] bg-[#F9F9F9]">
          <span class="text-[11px] font-semibold tracking-[1.5px] uppercase text-[#5A5A5A]">Ürün Özellikleri</span>
        </div>
        <dl>
          @foreach($urun->ozellikler as $label => $deger)
          <div class="flex px-5 py-3 border-b border-[rgba(0,0,0,0.05)] last:border-0">
            <dt class="w-1/2 text-[12px] text-[#A8A8A8]">{{ $label }}</dt>
            <dd class="w-1/2 text-[12px] font-medium text-[#0F0F0F]">{{ $deger }}</dd>
          </div>
          @endforeach
        </dl>
      </div>
      @endif

      {{-- Açıklama --}}
      @if($urun->aciklama)
      <div class="text-[14px] text-[#5A5A5A] leading-relaxed urun-aciklama">{!! $urun->aciklama !!}</div>
      @endif

      {{-- İndirilebilir Öğeler --}}
      @if($urun->dosyalar && count($urun->dosyalar) > 0)
      <div class="border border-[rgba(0,0,0,0.08)] rounded-[12px] overflow-hidden">
        <div class="px-5 py-3.5 border-b border-[rgba(0,0,0,0.08)] bg-[#F9F9F9] flex items-center gap-2">
          <i class="ti ti-paperclip text-[#5A5A5A] text-sm"></i>
          <span class="text-[11px] font-semibold tracking-[1.5px] uppercase text-[#5A5A5A]">İndirilebilir Öğeler</span>
        </div>
        <ul class="divide-y divide-[rgba(0,0,0,0.05)]">
          @foreach($urun->dosyalar as $d)
          @php
            $ext  = strtolower(pathinfo($d['ad'], PATHINFO_EXTENSION));
            $ikon = match(true) {
              in_array($ext, ['pdf'])            => 'ti-file-type-pdf',
              in_array($ext, ['doc','docx'])     => 'ti-file-type-doc',
              in_array($ext, ['xls','xlsx'])     => 'ti-file-type-xls',
              in_array($ext, ['zip','rar','7z']) => 'ti-file-zip',
              default                            => 'ti-file',
            };
            $renk = match(true) {
              in_array($ext, ['pdf'])            => 'text-[#CC2200]',
              in_array($ext, ['doc','docx'])     => 'text-[#2B5EAB]',
              in_array($ext, ['xls','xlsx'])     => 'text-[#1A7A4A]',
              in_array($ext, ['zip','rar','7z']) => 'text-[#D97706]',
              default                            => 'text-[#5A5A5A]',
            };
          @endphp
          <li>
            <a href="{{ Storage::url($d['dosya']) }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-5 py-3 hover:bg-[#F9F9F9] transition-colors group">
              <i class="ti {{ $ikon }} {{ $renk }} text-xl shrink-0"></i>
              <span class="flex-1 text-[13px] text-[#0F0F0F] group-hover:underline truncate">{{ $d['ad'] }}</span>
              <i class="ti ti-download text-sm text-[#A8A8A8] group-hover:text-[#0F0F0F] transition-colors shrink-0"></i>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
      @endif
    </div>
  </div>
</section>

{{-- ─── İlgili Ürünler ────────────────────────────────────────────────── --}}
@if($ilgiliUrunler->count() > 0)
<section class="section" aria-labelledby="ilgili-baslik">
  <h2 id="ilgili-baslik" class="font-serif-sc text-[22px] font-bold text-[#0F0F0F] mb-5">Benzer Ürünler</h2>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3.5">
    @foreach($ilgiliUrunler as $u)
    <a href="{{ route('magaza.urun', $u->slug) }}" class="group bg-white border border-[rgba(0,0,0,0.07)] rounded-[12px] overflow-hidden hover:shadow-[0_4px_16px_rgba(0,0,0,0.08)] transition-all duration-200">
      <div class="aspect-square bg-[#F5F5F5] overflow-hidden">
        @if($u->gorsel)
        <img src="{{ asset('storage/'.$u->gorsel) }}" alt="{{ $u->ad }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             loading="lazy">
        @endif
      </div>
      <div class="p-3">
        <p class="text-[13px] font-medium text-[#0F0F0F] line-clamp-2 mb-1">{{ $u->ad }}</p>
        <p class="font-display text-[15px] font-semibold text-[#0F0F0F]">{{ number_format($u->fiyat, 2, ',', '.') }} ₺</p>
      </div>
    </a>
    @endforeach
  </div>
</section>
@endif

@endsection

@push('styles')
<style>.scrollbar-hide::-webkit-scrollbar{display:none}.scrollbar-hide{-ms-overflow-style:none;scrollbar-width:none}.urun-aciklama p{margin-bottom:.5em}.urun-aciklama p:last-child{margin-bottom:0}.urun-aciklama strong{font-weight:600;color:#0F0F0F}.urun-aciklama ul,.urun-aciklama ol{padding-left:1.4em;margin-bottom:.5em}.urun-aciklama li{margin-bottom:.2em}</style>
@endpush
