<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yönetici Girişi — SUÇEK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  @vite(['resources/css/app.css'])
</head>
<body class="bg-[#0F172A] min-h-screen flex items-center justify-center p-4" style="font-family:'Inter',system-ui,sans-serif;">

<div class="w-full max-w-[380px]">

  {{-- Logo --}}
  <div class="text-center mb-8">
    <div class="text-[28px] font-bold tracking-tight text-white">SUÇEK</div>
    <p class="text-[10px] tracking-widest uppercase text-[rgba(255,255,255,0.30)] mt-1.5">Yönetim Paneli</p>
  </div>

  {{-- Kart --}}
  <div class="bg-white rounded-[16px] p-8 shadow-[0_24px_64px_rgba(0,0,0,0.50)]">
    <h1 class="text-[18px] font-semibold text-[#0F172A] mb-1">Giriş Yap</h1>
    <p class="text-[13px] text-[#94A3B8] mb-6">Yönetici hesabınızla devam edin.</p>

    @if($errors->any())
    <div class="flex items-center gap-2.5 bg-[#FEE2E2] border border-[#FCA5A5] text-[#991B1B] rounded-[8px] px-4 py-3 text-[13px] mb-5">
      <i class="ti ti-alert-circle text-base shrink-0"></i>
      <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <form action="{{ route('admin.giris.post') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label for="email" class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">E-posta</label>
        <input id="email" name="email" type="email" required autocomplete="email"
               value="{{ old('email') }}"
               placeholder="admin@sucek.com.tr"
               class="w-full px-4 py-3 border border-[#E2E8F0] rounded-[8px] text-[14px] text-[#0F172A] placeholder-[#CBD5E1] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-all bg-[#F8FAFC]">
      </div>
      <div>
        <label for="password" class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Şifre</label>
        <input id="password" name="password" type="password" required autocomplete="current-password"
               placeholder="••••••••"
               class="w-full px-4 py-3 border border-[#E2E8F0] rounded-[8px] text-[14px] text-[#0F172A] placeholder-[#CBD5E1] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-all bg-[#F8FAFC]">
      </div>
      <div class="flex items-center gap-2 pt-1">
        <input type="checkbox" id="beni_hatirla" name="beni_hatirla" class="w-4 h-4 accent-[#CC2200] cursor-pointer">
        <label for="beni_hatirla" class="text-[13px] text-[#64748B] cursor-pointer">Beni hatırla</label>
      </div>
      <button type="submit"
              class="w-full bg-[#0F172A] text-white text-[12px] font-semibold tracking-[1.5px] uppercase py-3.5 rounded-[8px] hover:bg-[#1e293b] transition-colors mt-2 min-h-[48px]">
        Giriş Yap
      </button>
    </form>
  </div>

  <p class="text-center text-[11px] text-[rgba(255,255,255,0.25)] mt-6">
    <a href="{{ route('home') }}" class="hover:text-[rgba(255,255,255,0.55)] transition-colors">← Siteye Dön</a>
  </p>
</div>

</body>
</html>
