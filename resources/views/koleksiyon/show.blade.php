@extends('layouts.app')

@section('title', $urun->ad . ' — SUÇEK Koleksiyon')

@section('content')

{{-- Breadcrumb --}}
<nav class="px-[9px] pt-5 pb-3" aria-label="İz izi">
  <ol class="flex items-center gap-1.5 text-[11px] text-[#A8A8A8]" role="list">
    <li><a href="{{ route('home') }}" class="hover:text-[#0F0F0F] transition-colors">Anasayfa</a></li>
    <li aria-hidden="true"><i class="ti ti-chevron-right text-[10px]"></i></li>
    <li><a href="{{ route('koleksiyon.index') }}" class="hover:text-[#0F0F0F] transition-colors">Koleksiyon</a></li>
    <li aria-hidden="true"><i class="ti ti-chevron-right text-[10px]"></i></li>
    <li class="text-[#0F0F0F] font-medium" aria-current="page">{{ $urun->ad }}</li>
  </ol>
</nav>

@php
  $tumGorseller = array_filter(array_merge(
    $urun->gorsel ? [$urun->gorsel] : [],
    $urun->gorseller ?? []
  ));
  $tumGorseller = array_values($tumGorseller);
  $teklifVar    = !$urun->fiyat && $urun->durum === 'satista';
@endphp

<section class="px-[9px] pb-10"
         x-data="{ aktifIndex: 0, teklifAcik: {{ session('teklif_basari') ? 'false' : 'false' }} }"
         aria-label="Koleksiyon ürün detayı">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

    {{-- ─── Görsel Slider ──────────────────────────────────────────── --}}
    <div>
      {{-- Ana görsel --}}
      <div class="relative aspect-square bg-[#F5F5F5] rounded-[16px] overflow-hidden flex items-center justify-center mb-3">
        @if(count($tumGorseller) > 0)
          @foreach($tumGorseller as $i => $g)
          <img src="{{ asset('storage/'.$g) }}" alt="{{ $urun->ad }}"
               class="w-full h-full object-contain p-6 transition-opacity duration-300"
               x-show="aktifIndex === {{ $i }}"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100"
               style="{{ $i > 0 ? 'display:none;' : '' }}"
               width="600" height="600">
          @endforeach

          {{-- Önceki/sonraki butonları --}}
          @if(count($tumGorseller) > 1)
          <button @click="aktifIndex = (aktifIndex - 1 + {{ count($tumGorseller) }}) % {{ count($tumGorseller) }}"
                  class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 rounded-full flex items-center justify-center shadow-md hover:bg-white transition-colors">
            <i class="ti ti-chevron-left text-[#0F0F0F] text-sm"></i>
          </button>
          <button @click="aktifIndex = (aktifIndex + 1) % {{ count($tumGorseller) }}"
                  class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white/90 rounded-full flex items-center justify-center shadow-md hover:bg-white transition-colors">
            <i class="ti ti-chevron-right text-[#0F0F0F] text-sm"></i>
          </button>
          {{-- Sayaç --}}
          <div class="absolute bottom-3 right-3 bg-[rgba(0,0,0,0.55)] text-white text-[10px] font-medium px-2.5 py-1 rounded-full">
            <span x-text="aktifIndex + 1"></span> / {{ count($tumGorseller) }}
          </div>
          @endif
        @else
          <i class="ti ti-photo text-6xl text-[#D0D0D0]" aria-hidden="true"></i>
        @endif

        @if($urun->durum === 'satildi')
        <div class="absolute inset-0 bg-[rgba(0,0,0,0.50)] flex items-center justify-center rounded-[16px]">
          <span class="text-[14px] font-bold tracking-[3px] uppercase text-white bg-[rgba(0,0,0,0.70)] px-6 py-3 rounded-[8px]">Satıldı</span>
        </div>
        @endif
      </div>

      {{-- Thumbnail şeridi --}}
      @if(count($tumGorseller) > 1)
      <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
        @foreach($tumGorseller as $i => $g)
        <button @click="aktifIndex = {{ $i }}"
                class="shrink-0 w-16 h-16 rounded-[8px] overflow-hidden border-2 transition-colors"
                :class="aktifIndex === {{ $i }} ? 'border-[#0F0F0F]' : 'border-transparent hover:border-[rgba(0,0,0,0.20)]'">
          <img src="{{ asset('storage/'.$g) }}" alt="Görsel {{ $i+1 }}" class="w-full h-full object-cover">
        </button>
        @endforeach
      </div>
      @endif
    </div>

    {{-- ─── Bilgi & Teklif ────────────────────────────────────────── --}}
    <div class="flex flex-col gap-5">
      <div>
        <p class="text-[10px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-1">{{ ucfirst($urun->kategori) }}</p>
        <h1 class="font-display text-[32px] lg:text-[38px] font-semibold text-[#0F0F0F] leading-[1.1] mb-2">{{ $urun->ad }}</h1>
        @if($urun->stok_kodu)
        <p class="text-[11px] text-[#A8A8A8]">Ref: {{ $urun->stok_kodu }}</p>
        @endif
      </div>

      {{-- Fiyat / Durum --}}
      @php
        $isPremium = auth()->check() && auth()->user()->isPremium();
        $premiumFiyat = $isPremium ? $urun->premiumFiyat() : null;
      @endphp
      <div class="py-4 border-y border-[rgba(0,0,0,0.08)]">
        @if($premiumFiyat)
          <div class="flex items-baseline gap-3 flex-wrap mb-1">
            <span class="font-display text-[36px] font-semibold" style="color:#6d28d9;">{{ number_format($premiumFiyat, 0, ',', '.') }} ₺</span>
            <span class="font-display text-[20px] text-[#A8A8A8] line-through">{{ number_format($urun->fiyat, 0, ',', '.') }} ₺</span>
          </div>
          <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-[1px] uppercase px-2.5 py-1 rounded-[6px]" style="background:#f5f3ff;color:#7c3aed;">
            <i class="ti ti-crown text-[10px]"></i> Premium — %15 İndirim
          </span>
        @elseif($urun->fiyat)
          <span class="font-display text-[36px] font-semibold text-[#0F0F0F]">{{ number_format($urun->fiyat, 0, ',', '.') }} ₺</span>
        @elseif($urun->durum === 'satista')
          <span class="text-[15px] font-medium text-[#5A5A5A]">Fiyat belirtilmemiş</span>
          <p class="text-[12px] text-[#A8A8A8] mt-0.5">Üye olup teklif gönderebilirsiniz.</p>
        @else
          <span class="text-[14px] text-[#5A5A5A] tracking-[1px]">Fiyat için iletişime geçin</span>
        @endif
        @if($urun->durum === 'rezerve')
        <span class="ml-3 text-[10px] font-bold tracking-[1.5px] uppercase bg-[#B8962E] text-white px-3 py-1 rounded-[6px]">Rezerve</span>
        @endif
      </div>

      {{-- Açıklama --}}
      @if($urun->aciklama)
      <p class="text-[14px] text-[#5A5A5A] leading-relaxed">{{ $urun->aciklama }}</p>
      @endif

      {{-- Özellikler --}}
      @if($urun->ozellikler && count($urun->ozellikler) > 0)
      <div class="border border-[rgba(0,0,0,0.08)] rounded-[12px] overflow-hidden">
        <div class="px-5 py-3.5 bg-[#F9F9F9] border-b border-[rgba(0,0,0,0.08)]">
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

      {{-- CTA Butonları --}}
      <div class="flex flex-col gap-2.5 mt-2">
        @if($urun->durum === 'satista' && $urun->fiyat)
        {{-- Normal satın alma --}}
        <button
          @click="
            fetch('{{ route('sepet.ekle') }}', {
              method: 'POST',
              headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
              body: JSON.stringify({urun_id:{{ $urun->id }}, urun_tipi:'koleksiyon', adet:1})
            }).then(r=>r.json()).then(d=>{ $root.sepetAdet=d.adet; $root.bildirimiGoster('{{ $urun->ad }} sepete eklendi!'); })
          "
          class="flex items-center justify-center gap-2 bg-[#141414] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] active:scale-[0.98] transition-all duration-200 min-h-[52px]">
          <i class="ti ti-shopping-cart text-sm" aria-hidden="true"></i> Sepete Ekle
        </button>
        @endif

        @if($teklifVar)
        {{-- Teklif Ver butonu --}}
        <button @click="teklifAcik = !teklifAcik"
                class="flex items-center justify-center gap-2 bg-[#B8962E] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#9a7a24] active:scale-[0.98] transition-all duration-200 min-h-[52px]">
          <i class="ti ti-tag text-sm" aria-hidden="true"></i>
          <span x-text="teklifAcik ? 'Formu Kapat' : 'Teklif Ver'"></span>
        </button>

        {{-- Teklif Formu --}}
        <div x-show="teklifAcik"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="border border-[rgba(0,0,0,0.10)] rounded-[12px] p-5 bg-[#FAFAFA]"
             style="display:none;">
          @if(session('teklif_basari'))
          <div class="flex items-start gap-3 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] rounded-[8px] px-4 py-3 text-[13px]" role="alert">
            <i class="ti ti-circle-check shrink-0 mt-0.5"></i>
            <span>{{ session('teklif_basari') }}</span>
          </div>
          @elseif(auth()->check())
          {{-- Giriş yapmış: form göster --}}
          <p class="text-[12px] font-medium text-[#0F0F0F] mb-3">
            <span class="text-[#A8A8A8]">Teklif veren:</span> {{ auth()->user()->name }}
          </p>
          @if($errors->has('miktar'))
          <p class="text-[11px] text-[#CC2200] mb-2">{{ $errors->first('miktar') }}</p>
          @endif
          <form action="{{ route('koleksiyon.teklif', $urun->slug) }}" method="POST" class="space-y-3">
            @csrf
            <div>
              <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Teklif Tutarı (₺) <span class="text-[#CC2200]">*</span></label>
              <div class="relative">
                <input type="number" name="miktar" min="1" step="1" required
                       value="{{ old('miktar') }}"
                       placeholder="0"
                       class="w-full px-4 py-2.5 pr-8 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[14px] focus:outline-none focus:border-[#0F0F0F] transition-colors">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[13px] text-[#A8A8A8]">₺</span>
              </div>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Mesaj (opsiyonel)</label>
              <textarea name="mesaj" rows="3" maxlength="500"
                        class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors resize-none"
                        placeholder="Teklifiniz hakkında not ekleyebilirsiniz...">{{ old('mesaj') }}</textarea>
            </div>
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[10px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
              <i class="ti ti-send text-sm"></i> Teklifi Gönder
            </button>
          </form>
          @else
          {{-- Giriş yapmamış: yönlendirme --}}
          <p class="text-[13px] text-[#5A5A5A] mb-4 leading-relaxed">
            Teklif vermek için hesabınıza giriş yapmanız veya üye olmanız gerekiyor.
          </p>
          <div class="flex gap-2">
            <a href="{{ route('giris') }}?redirect={{ urlencode(request()->url()) }}"
               class="flex-1 flex items-center justify-center gap-1.5 bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[8px] hover:bg-[#2a2a2a] transition-colors min-h-[44px]">
              <i class="ti ti-login text-sm"></i> Giriş Yap
            </a>
            <a href="{{ route('uye-ol') }}"
               class="flex-1 flex items-center justify-center gap-1.5 border border-[rgba(0,0,0,0.15)] text-[#0F0F0F] text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[8px] hover:bg-[#F0F0F0] transition-colors min-h-[44px]">
              <i class="ti ti-user-plus text-sm"></i> Üye Ol
            </a>
          </div>
          @endif
        </div>
        @endif

        <a href="{{ route('iletisim.index') }}"
           class="flex items-center justify-center gap-2 border border-[rgba(0,0,0,0.15)] text-[#5A5A5A] text-[11px] font-medium tracking-[1.5px] uppercase py-3.5 rounded-[10px] hover:bg-[#F0F0F0] transition-all duration-200 min-h-[44px]">
          <i class="ti ti-message text-sm" aria-hidden="true"></i> Bilgi Al
        </a>
      </div>

    </div>
  </div>
</section>

@endsection

@push('styles')
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush
