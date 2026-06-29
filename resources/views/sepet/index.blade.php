@extends('layouts.app')

@section('title', 'Sepetim — SUÇEK')

@section('content')

<section class="section min-h-[50vh]" aria-label="Alışveriş sepeti">
  <div class="mb-6">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-1.5">ALIŞVERİŞ</p>
    <h1 class="font-serif-sc text-[28px] font-bold text-[#0F0F0F]">Sepetim</h1>
  </div>

  @if(count($sepet) === 0)
  <div class="flex flex-col items-center justify-center py-20 text-center bg-[#F9F9F9] rounded-[16px]">
    <i class="ti ti-shopping-cart-off text-5xl text-[#D0D0D0] mb-4" aria-hidden="true"></i>
    <p class="text-[14px] font-medium text-[#5A5A5A] mb-1">Sepetiniz boş</p>
    <p class="text-[12px] text-[#A8A8A8] mb-6">Ürünlerimizi keşfetmek için mağazamıza göz atın.</p>
    <div class="flex gap-3">
      <a href="{{ route('magaza.index') }}" class="btn btn-dark text-[10px] tracking-[1.5px] px-6 py-2.5 min-h-[44px]">Mağazaya Git</a>
      <a href="{{ route('koleksiyon.index') }}" class="btn btn-outline text-[10px] tracking-[1.5px] px-6 py-2.5 min-h-[44px]">Koleksiyon</a>
    </div>
  </div>
  @else

  @php
  $alpineItems = collect($sepet)->map(fn($item, $key) => [
      'key'          => $key,
      'fiyat'        => (float) ($item['fiyat'] ?? 0),
      'fiyat_net'    => (float) ($item['fiyat_net'] ?? $item['fiyat'] ?? 0),
      'kdv_tutari'   => (float) ($item['kdv_tutari'] ?? 0),
      'kargo_ucreti' => (float) ($item['kargo_ucreti'] ?? 0),
      'adet'         => (int)   $item['adet'],
  ])->values()->toArray();
  @endphp

  <script>
  function cartData() {
      return {
          items: @json($alpineItems),
          ucretsizEsik: {{ (float) $ucretsizEsik }},

          get araToplam() {
              return this.items.reduce((s, i) => s + i.fiyat_net * i.adet, 0);
          },
          get kdvToplam() {
              return this.items.reduce((s, i) => s + i.kdv_tutari * i.adet, 0);
          },
          get urunToplam() {
              return this.araToplam + this.kdvToplam;
          },
          get kargoUcreti() {
              if (this.ucretsizEsik > 0 && this.urunToplam >= this.ucretsizEsik) return 0;
              if (this.items.length === 0) return 0;
              return Math.max(...this.items.map(i => i.kargo_ucreti));
          },
          get toplam() {
              return this.urunToplam + this.kargoUcreti;
          },
          get eksik() {
              if (this.ucretsizEsik <= 0 || this.kargoUcreti === 0) return 0;
              return Math.max(0, this.ucretsizEsik - this.urunToplam);
          },

          fmt(n) {
              return n.toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          },

          increment(idx) {
              this.items[idx].adet++;
              this.sync(idx);
          },
          decrement(idx) {
              if (this.items[idx].adet > 1) {
                  this.items[idx].adet--;
                  this.sync(idx);
              }
          },
          async sync(idx) {
              const item = this.items[idx];
              await fetch('{{ route('sepet.guncelle') }}', {
                  method: 'PATCH',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                      'X-Requested-With': 'XMLHttpRequest',
                  },
                  body: JSON.stringify({ key: item.key, adet: item.adet }),
              });
          },
      };
  }
  </script>

  {{-- Mobil üst checkout butonu --}}
  <div class="lg:hidden mb-4" x-data="cartData()">
    <a href="{{ route('siparis.checkout') }}"
       class="w-full flex items-center justify-center bg-[#141414] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] min-h-[52px]">
      <i class="ti ti-lock text-sm mr-1.5" aria-hidden="true"></i>Siparişi Tamamla
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="cartData()">

    {{-- Ürün listesi --}}
    <div class="lg:col-span-2 space-y-3">
      @foreach($sepet as $key => $item)
      @php $idx = $loop->index; @endphp
      <article class="bg-white border border-[rgba(0,0,0,0.07)] rounded-[12px] p-4">
        <div class="flex gap-3">
          {{-- Görsel --}}
          <div class="w-16 h-16 bg-[#F0F0F0] rounded-[8px] overflow-hidden shrink-0">
            @if($item['gorsel'])
            <img src="{{ asset('storage/'.$item['gorsel']) }}" alt="{{ $item['ad'] }}" class="w-full h-full object-cover" loading="lazy">
            @endif
          </div>

          {{-- Bilgi --}}
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <p class="text-[14px] font-medium text-[#0F0F0F] leading-snug">{{ $item['ad'] }}</p>
              {{-- Sil --}}
              <form action="{{ route('sepet.kaldir', $key) }}" method="POST"
                    onsubmit="return confirm('Bu ürünü sepetten kaldırmak istediğinize emin misiniz?')"
                    class="shrink-0">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-7 h-7 flex items-center justify-center rounded-[6px] text-[#A8A8A8] hover:text-red-500 hover:bg-red-50 transition-all"
                        aria-label="{{ $item['ad'] }} sepetten kaldır">
                  <i class="ti ti-trash text-sm" aria-hidden="true"></i>
                </button>
              </form>
            </div>

            <p class="text-[12px] text-[#A8A8A8] mt-0.5">{{ number_format($item['fiyat'], 2, ',', '.') }} ₺ / adet</p>

            @if(($item['kargo_ucreti'] ?? 0) > 0)
            <p class="flex items-center gap-1 text-[11px] text-[#A8A8A8] mt-0.5">
              <i class="ti ti-truck text-[11px]" aria-hidden="true"></i>
              {{ number_format($item['kargo_ucreti'], 2, ',', '.') }} ₺ kargo
            </p>
            @else
            <p class="flex items-center gap-1 text-[11px] text-green-600 mt-0.5">
              <i class="ti ti-truck text-[11px]" aria-hidden="true"></i>
              Ücretsiz kargo
            </p>
            @endif

            {{-- Adet + Toplam --}}
            <div class="flex items-center justify-between mt-3">
              <div class="flex items-center gap-1">
                <button @click="decrement({{ $idx }})"
                        class="w-8 h-8 flex items-center justify-center border border-[rgba(0,0,0,0.15)] rounded-l-[6px] hover:bg-[#F0F0F0] transition-colors"
                        aria-label="Azalt">
                  <i class="ti ti-minus text-xs" aria-hidden="true"></i>
                </button>
                <span class="w-9 h-8 flex items-center justify-center border-t border-b border-[rgba(0,0,0,0.15)] text-[13px] font-medium"
                      x-text="items[{{ $idx }}].adet"
                      aria-live="polite">{{ $item['adet'] }}</span>
                <button @click="increment({{ $idx }})"
                        class="w-8 h-8 flex items-center justify-center border border-[rgba(0,0,0,0.15)] rounded-r-[6px] hover:bg-[#F0F0F0] transition-colors"
                        aria-label="Artır">
                  <i class="ti ti-plus text-xs" aria-hidden="true"></i>
                </button>
              </div>
              <p class="text-[15px] font-semibold text-[#0F0F0F]">
                <span x-text="fmt(items[{{ $idx }}].fiyat * items[{{ $idx }}].adet) + ' ₺'">
                  {{ number_format($item['fiyat'] * $item['adet'], 2, ',', '.') }} ₺
                </span>
              </p>
            </div>
          </div>
        </div>
      </article>
      @endforeach
    </div>

    {{-- Sipariş özeti --}}
    <div class="lg:col-span-1">
      <div class="bg-white border border-[rgba(0,0,0,0.07)] rounded-[16px] p-6 sticky top-24">
        <h2 class="font-display text-[20px] font-semibold text-[#0F0F0F] mb-5 pb-4 border-b border-[rgba(0,0,0,0.08)]">Sipariş Özeti</h2>
        <dl class="space-y-3 mb-5">

          <div class="flex justify-between text-[13px]">
            <dt class="text-[#5A5A5A]">Ara Toplam (KDV hariç)</dt>
            <dd class="font-medium" x-text="fmt(araToplam) + ' ₺'">{{ number_format($araToplam, 2, ',', '.') }} ₺</dd>
          </div>

          <div class="flex justify-between text-[13px]" x-show="kdvToplam > 0" x-cloak>
            <dt class="text-[#5A5A5A]">KDV</dt>
            <dd class="font-medium" x-text="fmt(kdvToplam) + ' ₺'">{{ number_format($kdvToplam, 2, ',', '.') }} ₺</dd>
          </div>

          <div class="flex justify-between text-[13px]">
            <dt class="text-[#5A5A5A]">Kargo</dt>
            <dd class="font-medium"
                :class="kargoUcreti > 0 ? 'text-[#0F0F0F]' : 'text-green-600'"
                x-text="kargoUcreti > 0 ? fmt(kargoUcreti) + ' ₺' : 'Ücretsiz'">
              @if($kargoUcreti > 0){{ number_format($kargoUcreti, 2, ',', '.') }} ₺@else<span class="text-green-600">Ücretsiz</span>@endif
            </dd>
          </div>

          <div class="flex items-center gap-1 text-[11px] text-[#A8A8A8] -mt-1" x-show="eksik > 0" x-cloak>
            <i class="ti ti-info-circle text-xs" aria-hidden="true"></i>
            <span x-text="fmt(eksik) + ' ₺ daha ekleyerek ücretsiz kargoya ulaşın'"></span>
          </div>

          <div class="flex justify-between pt-3 border-t border-[rgba(0,0,0,0.08)]">
            <dt class="font-semibold text-[14px]">Toplam</dt>
            <dd class="font-display text-[20px] font-semibold" x-text="fmt(toplam) + ' ₺'">{{ number_format($toplam, 2, ',', '.') }} ₺</dd>
          </div>

        </dl>
        <a href="{{ route('siparis.checkout') }}"
           class="w-full flex items-center justify-center bg-[#141414] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] transition-colors min-h-[52px]">
          <i class="ti ti-lock text-sm mr-1.5" aria-hidden="true"></i>Siparişi Tamamla
        </a>
        <a href="{{ route('magaza.index') }}" class="block text-center text-[11px] text-[#A8A8A8] hover:text-[#0F0F0F] mt-3 transition-colors py-2">
          Alışverişe Devam Et
        </a>
      </div>
    </div>

  </div>
  @endif
</section>

@endsection
