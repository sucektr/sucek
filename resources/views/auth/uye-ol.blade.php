@extends('layouts.app')
@section('title', 'Üye Ol — SUÇEK')

@section('content')
<div class="min-h-[calc(100vh-80px)] grid grid-cols-1 lg:grid-cols-5">

  {{-- ─── Sol: Marka Paneli ─────────────────────────────────────────── --}}
  <div class="hidden lg:flex lg:col-span-2 bg-[#0F0F0F] flex-col justify-between px-10 py-14 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:24px 24px;"></div>
    <div class="absolute top-0 left-0 w-1 h-full bg-[#B8962E] opacity-60"></div>

    <div class="relative z-10">
      <a href="{{ route('home') }}" class="font-display text-[30px] font-semibold tracking-[8px] text-white leading-none">SUÇEK</a>
      <p class="text-[10px] tracking-[3px] uppercase text-[rgba(255,255,255,0.30)] mt-1">Mimarlık · İnşaat · Koleksiyon</p>
    </div>

    <div class="relative z-10 space-y-8">
      <div>
        <p class="font-serif-sc text-[28px] text-white leading-[1.3]">Nadir eserlere <em>özel erişim</em> için üye olun.</p>
        <p class="text-[13px] text-[rgba(255,255,255,0.45)] mt-3 leading-relaxed">
          Koleksiyon ürünlerimize teklif verin, süreçleri takip edin.
        </p>
      </div>

      <div class="space-y-5">
        @foreach([
          ['ti-tag', 'Teklif Ver', 'Koleksiyon eserlerine doğrudan teklif gönderin'],
          ['ti-history', 'Geçmiş', 'Tüm tekliflerinizi ve durumlarını görün'],
          ['ti-shield-check', 'Güvenli', 'Verileriniz şifreli olarak korunur'],
        ] as [$ikon, $baslik, $aciklama])
        <div class="flex items-start gap-4">
          <div class="w-9 h-9 rounded-[8px] bg-[rgba(184,150,46,0.15)] border border-[rgba(184,150,46,0.30)] flex items-center justify-center shrink-0 mt-0.5">
            <i class="ti {{ $ikon }} text-[#B8962E] text-[15px]"></i>
          </div>
          <div>
            <p class="text-[13px] font-medium text-white">{{ $baslik }}</p>
            <p class="text-[11px] text-[rgba(255,255,255,0.40)] mt-0.5">{{ $aciklama }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div class="relative z-10">
      <p class="text-[10px] text-[rgba(255,255,255,0.20)] tracking-[1px]">&copy; {{ date('Y') }} SUÇEK. Tüm hakları saklıdır.</p>
    </div>
  </div>

  {{-- ─── Sağ: Form ──────────────────────────────────────────────────── --}}
  <div class="lg:col-span-3 flex items-center justify-center px-6 py-14 bg-[#FAFAFA]">
    <div class="w-full max-w-md">

      <div class="lg:hidden text-center mb-8">
        <a href="{{ route('home') }}" class="font-display text-[26px] font-semibold tracking-[6px] text-[#0F0F0F]">SUÇEK</a>
      </div>

      <div class="mb-8">
        <h1 class="font-display text-[28px] font-semibold text-[#0F0F0F]">Üye Olun</h1>
        <p class="text-[13px] text-[#A8A8A8] mt-1">Birkaç adımda hesabınızı oluşturun.</p>
      </div>

      @if($errors->any())
      <div class="mb-5 flex items-start gap-3 bg-[#FEE2E2] border border-[#FCA5A5] text-[#991B1B] rounded-[10px] px-4 py-3.5 text-[13px]" role="alert">
        <i class="ti ti-alert-circle text-base shrink-0 mt-0.5"></i>
        <div class="space-y-0.5">
          @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
      </div>
      @endif

      <form id="uye-ol-form" action="{{ route('uye-ol.post') }}" method="POST" class="space-y-5"
            x-data="{ showPass: false, showPass2: false }">
        @csrf

        <div>
          <label for="name" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.08em] mb-2">Ad Soyad</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                 class="w-full px-4 py-3.5 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[14px] bg-white focus:outline-none focus:border-[#0F0F0F] focus:ring-1 focus:ring-[#0F0F0F] transition-colors min-h-[48px] @error('name') border-[#CC2200] @enderror"
                 placeholder="Adınız Soyadınız">
        </div>

        <div>
          <label for="email" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.08em] mb-2">E-posta Adresi</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                 class="w-full px-4 py-3.5 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[14px] bg-white focus:outline-none focus:border-[#0F0F0F] focus:ring-1 focus:ring-[#0F0F0F] transition-colors min-h-[48px] @error('email') border-[#CC2200] @enderror"
                 placeholder="ornek@email.com">
        </div>

        <div>
          <label for="password" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.08em] mb-2">Şifre</label>
          <div class="relative">
            <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password" minlength="8"
                   class="w-full px-4 py-3.5 pr-12 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[14px] bg-white focus:outline-none focus:border-[#0F0F0F] focus:ring-1 focus:ring-[#0F0F0F] transition-colors min-h-[48px]"
                   placeholder="En az 8 karakter">
            <button type="button" @click="showPass = !showPass"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#A8A8A8] hover:text-[#0F0F0F] transition-colors p-1">
              <i :class="showPass ? 'ti ti-eye-off' : 'ti ti-eye'" class="text-[16px]"></i>
            </button>
          </div>
        </div>

        <div>
          <label for="password_confirmation" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.08em] mb-2">Şifre Tekrar</label>
          <div class="relative">
            <input id="password_confirmation" :type="showPass2 ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                   class="w-full px-4 py-3.5 pr-12 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[14px] bg-white focus:outline-none focus:border-[#0F0F0F] focus:ring-1 focus:ring-[#0F0F0F] transition-colors min-h-[48px]"
                   placeholder="Şifrenizi tekrar girin">
            <button type="button" @click="showPass2 = !showPass2"
                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#A8A8A8] hover:text-[#0F0F0F] transition-colors p-1">
              <i :class="showPass2 ? 'ti ti-eye-off' : 'ti ti-eye'" class="text-[16px]"></i>
            </button>
          </div>
        </div>

        @php $rcKey = app(\App\Services\RecaptchaService::class)->siteKey(); @endphp
        @if($rcKey)
        <div class="g-recaptcha" data-sitekey="{{ $rcKey }}"></div>
        @error('recaptcha')<p class="text-[11px] text-[#CC2200]">{{ $message }}</p>@enderror
        @endif

        <button type="submit"
                class="w-full bg-[#0F0F0F] text-white text-[12px] font-semibold tracking-[2px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] active:scale-[0.98] transition-all duration-200 min-h-[52px]">
          Hesap Oluştur
        </button>

        <p class="text-[11px] text-[#A8A8A8] text-center leading-relaxed">
          Üye olarak
          <a href="{{ route('yasal', 'kisisel-verilerin-korunmasi') }}" class="underline hover:text-[#0F0F0F]">Kişisel Verilerin Korunması</a> ve
          <a href="{{ route('yasal', 'gizlilik-politikasi') }}" class="underline hover:text-[#0F0F0F]">Gizlilik Politikası</a>'nı kabul etmiş olursunuz.
          <br>Bu site Google reCAPTCHA ile korunmaktadır.
        </p>
      </form>

      <div class="mt-8 pt-6 border-t border-[rgba(0,0,0,0.07)] text-center">
        <p class="text-[13px] text-[#A8A8A8]">
          Zaten hesabınız var mı?
          <a href="{{ route('giris') }}" class="text-[#0F0F0F] font-semibold hover:underline ml-1">Giriş Yapın →</a>
        </p>
      </div>

    </div>
  </div>

</div>
@endsection

@push('scripts')
@if(app(\App\Services\RecaptchaService::class)->aktif())
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
@endpush
