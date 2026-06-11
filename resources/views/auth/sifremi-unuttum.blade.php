@extends('layouts.app')
@section('title', 'Şifremi Unuttum — SUÇEK')

@section('content')
<div class="min-h-[calc(100vh-80px)] grid grid-cols-1 lg:grid-cols-5">

  {{-- Sol: Marka Paneli --}}
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
        <p class="font-serif-sc text-[28px] text-white leading-[1.3]">Şifrenizi <em>güvenle</em> sıfırlayın.</p>
        <p class="text-[13px] text-[rgba(255,255,255,0.45)] mt-3 leading-relaxed">
          E-posta adresinize sıfırlama bağlantısı göndereceğiz. Birkaç dakika içinde yeni şifrenizi belirleyebilirsiniz.
        </p>
      </div>
    </div>

    <div class="relative z-10">
      <p class="text-[10px] text-[rgba(255,255,255,0.20)] tracking-[1px]">&copy; {{ date('Y') }} SUÇEK. Tüm hakları saklıdır.</p>
    </div>
  </div>

  {{-- Sağ: Form --}}
  <div class="lg:col-span-3 flex items-center justify-center px-6 py-14 bg-[#FAFAFA]">
    <div class="w-full max-w-md">

      <div class="lg:hidden text-center mb-8">
        <a href="{{ route('home') }}" class="font-display text-[26px] font-semibold tracking-[6px] text-[#0F0F0F]">SUÇEK</a>
      </div>

      <div class="mb-8">
        <h1 class="font-display text-[28px] font-semibold text-[#0F0F0F]">Şifremi Unuttum</h1>
        <p class="text-[13px] text-[#A8A8A8] mt-1">Kayıtlı e-posta adresinizi girin, sıfırlama bağlantısı gönderelim.</p>
      </div>

      @if($errors->any())
      <div class="mb-5 flex items-start gap-3 bg-[#FEE2E2] border border-[#FCA5A5] text-[#991B1B] rounded-[10px] px-4 py-3.5 text-[13px]" role="alert">
        <i class="ti ti-alert-circle text-base shrink-0 mt-0.5"></i>
        <span>{{ $errors->first() }}</span>
      </div>
      @endif

      @if(session('basari'))
      <div class="mb-5 flex items-start gap-3 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] rounded-[10px] px-4 py-3.5 text-[13px]" role="alert">
        <i class="ti ti-circle-check text-base shrink-0 mt-0.5"></i>
        <div>
          <p class="font-semibold mb-0.5">E-posta gönderildi</p>
          <p>{{ session('basari') }}</p>
        </div>
      </div>
      @else
      <form action="{{ route('sifremi-unuttum.gonder') }}" method="POST" class="space-y-5">
        @csrf

        <div>
          <label for="email" class="block text-[11px] font-semibold text-[#5A5A5A] uppercase tracking-[.08em] mb-2">E-posta Adresi</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                 class="w-full px-4 py-3.5 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[14px] bg-white focus:outline-none focus:border-[#0F0F0F] focus:ring-1 focus:ring-[#0F0F0F] transition-colors min-h-[48px] @error('email') border-[#CC2200] @enderror"
                 placeholder="ornek@email.com">
        </div>

        <button type="submit"
                class="w-full bg-[#0F0F0F] text-white text-[12px] font-semibold tracking-[2px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] active:scale-[0.98] transition-all duration-200 min-h-[52px]">
          Sıfırlama Bağlantısı Gönder
        </button>
      </form>
      @endif

      <div class="mt-8 pt-6 border-t border-[rgba(0,0,0,0.07)] text-center space-y-2">
        <p class="text-[13px] text-[#A8A8A8]">
          Şifrenizi hatırladınız mı?
          <a href="{{ route('giris') }}" class="text-[#0F0F0F] font-semibold hover:underline ml-1">Giriş Yapın →</a>
        </p>
        <p class="text-[13px] text-[#A8A8A8]">
          Hesabınız yok mu?
          <a href="{{ route('uye-ol') }}" class="text-[#0F0F0F] font-semibold hover:underline ml-1">Üye Olun →</a>
        </p>
      </div>

    </div>
  </div>

</div>
@endsection
