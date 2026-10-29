<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#141414">
  <title>Giriş — SUÇEK Ürün Ekle</title>
  <link rel="manifest" href="/manifest-yardimci.json">
  <link rel="icon" type="image/png" href="/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>body { font-family: 'Inter', system-ui, sans-serif; background: #F8FAFC; }</style>
</head>
<body class="min-h-screen flex items-center justify-center px-5">
  <div class="w-full max-w-[380px]">
    <div class="text-center mb-8">
      <div class="font-bold text-[22px] tracking-tight text-[#0F0F0F]">SUÇEK</div>
      <div class="text-[12px] tracking-widest text-[#A8A8A8] mt-1 uppercase">Ürün Ekle</div>
    </div>

    @if($errors->any())
    <div class="mb-4 flex items-center gap-2 bg-[#FDECEC] border border-[#F5B5B5] text-[#8A1F1F] rounded-[10px] px-4 py-3 text-[13px]">
      <i class="ti ti-alert-triangle shrink-0"></i> {{ $errors->first() }}
    </div>
    @endif
    @if(session('hata'))
    <div class="mb-4 flex items-center gap-2 bg-[#FDECEC] border border-[#F5B5B5] text-[#8A1F1F] rounded-[10px] px-4 py-3 text-[13px]">
      <i class="ti ti-alert-triangle shrink-0"></i> {{ session('hata') }}
    </div>
    @endif

    <form action="{{ route('yardimci.giris.post') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">E-posta</label>
        <input type="email" name="email" required autofocus value="{{ old('email') }}"
               class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[15px] focus:outline-none focus:border-[#141414] transition-colors">
      </div>
      <div>
        <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Şifre</label>
        <input type="password" name="password" required
               class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[15px] focus:outline-none focus:border-[#141414] transition-colors">
      </div>
      <button type="submit"
              class="w-full flex items-center justify-center gap-2 bg-[#141414] text-white text-[13px] font-semibold tracking-[1px] uppercase py-3.5 rounded-[10px] hover:bg-[#2a2a2a] active:scale-[0.98] transition-all duration-200 min-h-[52px]">
        <i class="ti ti-login text-sm"></i> Giriş Yap
      </button>
    </form>
  </div>
</body>
</html>
