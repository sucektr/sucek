@extends('layouts.app')

@section('title', 'Sipariş Ver — SUÇEK')
@section('meta-description', 'Siparişinizi tamamlayın.')

@section('content')

<div class="section max-w-5xl mx-auto">

  {{-- Başlık --}}
  <div class="mb-8">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-1.5">ALIŞVERİŞ</p>
    <h1 class="font-serif-sc text-[28px] font-bold text-[#0F0F0F]">Siparişi Tamamla</h1>
  </div>

  @if($errors->any())
  <div class="mb-6 bg-[#FEE2E2] border border-[#FCA5A5] text-[#DC2626] text-[12px] px-5 py-4 rounded-[10px]">
    <ul class="space-y-1">
      @foreach($errors->all() as $err)
      <li class="flex items-center gap-2"><i class="ti ti-circle-x text-sm"></i>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('siparis.store') }}" method="POST" x-data="{ odeme: '{{ old('odeme_yontemi', 'havale') }}' }">
    @csrf
    <div class="grid lg:grid-cols-5 gap-6">

      {{-- ─── Sol: Adres Formu ─────────────────────────────────────── --}}
      <div class="lg:col-span-3 space-y-5">

        {{-- Kişisel Bilgiler --}}
        <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] p-6">
          <h2 class="text-[13px] font-semibold text-[#0F0F0F] mb-5 pb-4 border-b border-[rgba(0,0,0,0.06)]">
            <i class="ti ti-user text-sm mr-1.5 text-[#A0A0A0]"></i>Kişisel Bilgiler
          </h2>
          <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">Ad Soyad <span class="text-[#CC2200]">*</span></label>
                <input type="text" name="ad_soyad" required
                       value="{{ old('ad_soyad', auth()->user()?->name) }}"
                       class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors min-h-[44px]"
                       placeholder="Ad Soyad">
              </div>
              <div>
                <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">Telefon</label>
                <input type="tel" name="telefon"
                       value="{{ old('telefon', auth()->user()?->telefon) }}"
                       class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors min-h-[44px]"
                       placeholder="0555 000 00 00">
              </div>
            </div>
            <div>
              <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">E-posta <span class="text-[#CC2200]">*</span></label>
              <input type="email" name="email" required
                     value="{{ old('email', auth()->user()?->email) }}"
                     class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors min-h-[44px]"
                     placeholder="ornek@mail.com">
            </div>
          </div>
        </div>

        {{-- Teslimat Adresi --}}
        <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] p-6">
          <h2 class="text-[13px] font-semibold text-[#0F0F0F] mb-5 pb-4 border-b border-[rgba(0,0,0,0.06)]">
            <i class="ti ti-map-pin text-sm mr-1.5 text-[#A0A0A0]"></i>Teslimat Adresi
          </h2>
          <div class="space-y-4">
            <div>
              <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">Adres <span class="text-[#CC2200]">*</span></label>
              <textarea name="teslimat_adres" required rows="3"
                        class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors resize-none"
                        placeholder="Mahalle, sokak, kapı no...">{{ old('teslimat_adres', $kargoAdresi?->adres_satiri) }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">İlçe</label>
                <input type="text" name="teslimat_ilce"
                       value="{{ old('teslimat_ilce', $kargoAdresi?->ilce) }}"
                       class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors min-h-[44px]"
                       placeholder="İlçe">
              </div>
              <div>
                <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">Şehir <span class="text-[#CC2200]">*</span></label>
                <input type="text" name="teslimat_sehir" required
                       value="{{ old('teslimat_sehir', $kargoAdresi?->sehir) }}"
                       class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors min-h-[44px]"
                       placeholder="Şehir">
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">Posta Kodu</label>
                <input type="text" name="teslimat_posta"
                       value="{{ old('teslimat_posta', $kargoAdresi?->posta_kodu) }}"
                       class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors min-h-[44px]"
                       placeholder="06000">
              </div>
              <div>
                <label class="block text-[10px] font-medium uppercase tracking-[.06em] text-[#6B6B6B] mb-1.5">Ülke</label>
                <input type="text" value="Türkiye" disabled
                       class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.06)] rounded-[8px] text-[13px] bg-[#F8F8F8] text-[#A0A0A0] min-h-[44px]">
              </div>
            </div>
          </div>
        </div>

        {{-- Ödeme Yöntemi --}}
        <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] p-6">
          <h2 class="text-[13px] font-semibold text-[#0F0F0F] mb-5 pb-4 border-b border-[rgba(0,0,0,0.06)]">
            <i class="ti ti-credit-card text-sm mr-1.5 text-[#A0A0A0]"></i>Ödeme Yöntemi
          </h2>
          <div class="space-y-3">
            <label class="flex items-start gap-4 p-4 rounded-[10px] border-2 cursor-pointer transition-colors"
                   :class="odeme === 'kredi_karti' ? 'border-[#0F0F0F] bg-[#FAFAFA]' : 'border-[rgba(0,0,0,0.10)] bg-white hover:border-[rgba(0,0,0,0.20)]'">
              <input type="radio" name="odeme_yontemi" value="kredi_karti" x-model="odeme" class="mt-0.5 accent-[#0F0F0F] shrink-0">
              <div class="flex-1">
                <div class="flex items-center gap-2">
                  <p class="text-[13px] font-semibold text-[#0F0F0F]">Kredi / Banka Kartı</p>
                  <span class="text-[9px] font-bold tracking-[1px] bg-green-100 text-green-700 px-2 py-0.5 rounded-[4px] uppercase">Güvenli</span>
                </div>
                <p class="text-[11px] text-[#6B6B6B] mt-0.5 leading-relaxed">Tüm Visa, Mastercard ve Türk kartları ile 3D Secure güvencesiyle ödeme yapın. Taksit seçenekleri mevcuttur.</p>
              </div>
            </label>
            <label class="flex items-start gap-4 p-4 rounded-[10px] border-2 cursor-pointer transition-colors"
                   :class="odeme === 'havale' ? 'border-[#0F0F0F] bg-[#FAFAFA]' : 'border-[rgba(0,0,0,0.10)] bg-white hover:border-[rgba(0,0,0,0.20)]'">
              <input type="radio" name="odeme_yontemi" value="havale" x-model="odeme" class="mt-0.5 accent-[#0F0F0F] shrink-0">
              <div>
                <p class="text-[13px] font-semibold text-[#0F0F0F]">Havale / EFT</p>
                <p class="text-[11px] text-[#6B6B6B] mt-0.5 leading-relaxed">Banka havalesi ile ödeme yapın. Siparişiniz, ödemeniz onaylandıktan sonra hazırlanmaya başlanır.</p>
              </div>
            </label>
          </div>
        </div>

        {{-- Müşteri Notu --}}
        <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] p-6">
          <h2 class="text-[13px] font-semibold text-[#0F0F0F] mb-4">
            <i class="ti ti-note text-sm mr-1.5 text-[#A0A0A0]"></i>Not <span class="text-[#A0A0A0] font-normal">(isteğe bağlı)</span>
          </h2>
          <textarea name="musteri_notu" rows="3"
                    class="w-full px-4 py-2.5 border border-[rgba(0,0,0,0.12)] rounded-[8px] text-[13px] focus:outline-none focus:border-[#0F0F0F] transition-colors resize-none"
                    placeholder="Siparişinizle ilgili eklemek istediğiniz not...">{{ old('musteri_notu') }}</textarea>
        </div>

      </div>

      {{-- ─── Sağ: Sipariş Özeti ──────────────────────────────────────── --}}
      <div class="lg:col-span-2 space-y-4">

        <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] p-6 sticky top-6">
          <h2 class="text-[13px] font-semibold text-[#0F0F0F] mb-5 pb-4 border-b border-[rgba(0,0,0,0.06)]">
            <i class="ti ti-shopping-bag text-sm mr-1.5 text-[#A0A0A0]"></i>Sipariş Özeti
          </h2>

          <ul class="space-y-3 mb-5">
            @foreach($sepet as $item)
            <li class="flex items-center gap-3">
              @if($item['gorsel'])
              <img src="{{ asset('storage/'.$item['gorsel']) }}" alt="{{ $item['ad'] }}"
                   class="w-12 h-12 object-cover rounded-[6px] shrink-0 bg-[#F0F0F0]">
              @else
              <div class="w-12 h-12 rounded-[6px] bg-[#F0F0F0] flex items-center justify-center shrink-0">
                <i class="ti ti-photo text-[#C0C0C0]"></i>
              </div>
              @endif
              <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-[#0F0F0F] truncate">{{ $item['ad'] }}</p>
                <p class="text-[11px] text-[#A0A0A0]">{{ $item['adet'] }} adet × {{ number_format($item['fiyat'], 0, ',', '.') }} ₺</p>
              </div>
              <span class="text-[13px] font-semibold text-[#0F0F0F] shrink-0">
                {{ number_format($item['fiyat'] * $item['adet'], 0, ',', '.') }} ₺
              </span>
            </li>
            @endforeach
          </ul>

          <div class="border-t border-[rgba(0,0,0,0.06)] pt-4 space-y-2">
            <div class="flex justify-between text-[12px] text-[#5A5A5A]">
              <span>Ara Toplam (KDV hariç)</span>
              <span>{{ number_format($araToplam, 2, ',', '.') }} ₺</span>
            </div>
            @if($kdvToplam > 0)
            <div class="flex justify-between text-[12px] text-[#5A5A5A]">
              <span>KDV</span>
              <span>{{ number_format($kdvToplam, 2, ',', '.') }} ₺</span>
            </div>
            @endif
            <div class="flex justify-between text-[12px] text-[#5A5A5A]">
              <span>Kargo</span>
              @if($kargoUcreti > 0)
              <span class="font-medium">{{ number_format($kargoUcreti, 2, ',', '.') }} ₺</span>
              @else
              <span class="text-[#1A5C3A] font-medium">Ücretsiz</span>
              @endif
            </div>
            <div class="flex justify-between text-[15px] font-bold text-[#0F0F0F] pt-2 border-t border-[rgba(0,0,0,0.06)]">
              <span>Toplam</span>
              <span>{{ number_format($toplam, 2, ',', '.') }} ₺</span>
            </div>
          </div>

          {{-- Banka Bilgisi Önizleme (sadece havale seçiliyken) --}}
          @if(!empty($bankalar))
          <div x-show="odeme === 'havale'" x-transition class="mt-5 space-y-2">
            @foreach($bankalar as $banka)
            <div class="p-4 bg-[#F8F8F8] rounded-[8px] border border-[rgba(0,0,0,0.06)]">
              <p class="text-[9px] font-semibold tracking-[1.5px] uppercase text-[#A8A8A8] mb-2">Havale Hesabı</p>
              <p class="text-[12px] font-medium text-[#0F0F0F]">{{ $banka['banka'] }}</p>
              @if($banka['iban'])<p class="text-[11px] text-[#5A5A5A] font-mono mt-0.5">{{ $banka['iban'] }}</p>@endif
              @if($banka['alici'])<p class="text-[11px] text-[#5A5A5A] mt-0.5">{{ $banka['alici'] }}</p>@endif
            </div>
            @endforeach
          </div>
          @endif

          <button type="submit"
                  class="mt-5 w-full bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] transition-colors min-h-[52px] flex items-center justify-center gap-2">
            <template x-if="odeme === 'kredi_karti'">
              <span class="flex items-center gap-2"><i class="ti ti-credit-card text-sm"></i>Ödemeye Geç</span>
            </template>
            <template x-if="odeme === 'havale'">
              <span class="flex items-center gap-2"><i class="ti ti-check text-sm"></i>Siparişi Onayla</span>
            </template>
          </button>

          <a href="{{ route('sepet.index') }}"
             class="mt-3 w-full flex items-center justify-center gap-1.5 text-[10px] text-[#A0A0A0] hover:text-[#0F0F0F] transition-colors py-2">
            <i class="ti ti-arrow-left text-xs"></i>Sepete Dön
          </a>
        </div>

      </div>
    </div>
  </form>

</div>

@endsection
