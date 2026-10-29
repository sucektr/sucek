<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#141414">
  <title>@yield('title', 'Ürün Ekle') — SUÇEK</title>
  <link rel="manifest" href="/manifest-yardimci.json">
  <link rel="icon" type="image/png" href="/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; background: #F8FAFC; }
  </style>
</head>
<body class="min-h-screen" style="padding-bottom: env(safe-area-inset-bottom);">

  <header class="sticky top-0 z-20 bg-[#141414] text-white px-4 py-3.5 flex items-center justify-between" style="padding-top: calc(0.875rem + env(safe-area-inset-top));">
    <div class="flex items-center gap-2">
      @hasSection('geri')
        <a href="@yield('geri')" class="w-8 h-8 -ml-1.5 flex items-center justify-center rounded-full hover:bg-white/10">
          <i class="ti ti-arrow-left text-lg"></i>
        </a>
      @endif
      <span class="font-semibold text-[15px]">@yield('title', 'Ürün Ekle')</span>
    </div>
    @auth
    <form action="{{ route('yardimci.cikis') }}" method="POST">
      @csrf
      <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10" aria-label="Çıkış Yap">
        <i class="ti ti-logout text-lg"></i>
      </button>
    </form>
    @endauth
  </header>

  @if(session('basari'))
  <div class="mx-4 mt-3 flex items-center gap-2 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] rounded-[10px] px-4 py-3 text-[13px]">
    <i class="ti ti-circle-check shrink-0"></i> {{ session('basari') }}
  </div>
  @endif
  @if(session('hata'))
  <div class="mx-4 mt-3 flex items-center gap-2 bg-[#FDECEC] border border-[#F5B5B5] text-[#8A1F1F] rounded-[10px] px-4 py-3 text-[13px]">
    <i class="ti ti-alert-triangle shrink-0"></i> {{ session('hata') }}
  </div>
  @endif

  <main class="px-4 py-4 max-w-[520px] mx-auto">
    @yield('content')
  </main>

  <script>
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/sw-yardimci.js', { scope: '/urun-ekle' }).catch(() => {});
    }
  </script>
</body>
</html>
