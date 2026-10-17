<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#141414">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="SUÇEK Cari">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
  <title>@yield('title', 'Cari Hesap') — SUÇEK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chart-vendor.js'])
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; background: #F8FAFC; padding-bottom: env(safe-area-inset-bottom); }
    .cari-topbar { background: #141414; padding-top: env(safe-area-inset-top); }
  </style>
  @stack('styles')
</head>
<body class="min-h-screen">

  <header class="cari-topbar sticky top-0 z-30 px-4 py-3 flex items-center gap-3">
    @hasSection('geri')
      <a href="@yield('geri')" class="w-8 h-8 flex items-center justify-center rounded-full bg-[rgba(255,255,255,0.08)] text-white shrink-0">
        <i class="ti ti-arrow-left text-[18px]"></i>
      </a>
    @else
      <div class="w-8 h-8 flex items-center justify-center rounded-full bg-[#CC2200] text-white font-bold text-[13px] shrink-0">S</div>
    @endif
    <div class="min-w-0 flex-1">
      <h1 class="text-[15px] font-semibold text-white truncate">@yield('page-title', 'Cari Hesap')</h1>
      <p class="text-[10px] text-[rgba(255,255,255,0.4)] uppercase tracking-wider">SUÇEK</p>
    </div>
    <div class="flex items-center gap-2">
      @yield('header-actions')
      <a href="{{ route('admin.dashboard') }}" title="Admin Panele Dön"
         class="w-8 h-8 flex items-center justify-center rounded-full bg-[rgba(255,255,255,0.08)] text-white shrink-0">
        <i class="ti ti-layout-dashboard text-[16px]"></i>
      </a>
    </div>
  </header>

  @if(session('basari'))
  <div class="mx-4 mt-3 flex items-center gap-2.5 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] rounded-[10px] px-3.5 py-2.5 text-[13px]" role="alert">
    <i class="ti ti-circle-check text-base shrink-0"></i>
    <span>{{ session('basari') }}</span>
  </div>
  @endif
  @if(session('hata') || (isset($errors) && $errors->any()))
  <div class="mx-4 mt-3 flex items-start gap-2.5 bg-[#FEE2E2] border border-[#FCA5A5] text-[#991B1B] rounded-[10px] px-3.5 py-2.5 text-[13px]" role="alert">
    <i class="ti ti-alert-circle text-base shrink-0 mt-0.5"></i>
    <div>
      @if(session('hata'))<p>{{ session('hata') }}</p>@endif
      @if(isset($errors)) @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach @endif
    </div>
  </div>
  @endif

  <main class="px-4 py-4 max-w-2xl mx-auto">
    @yield('content')
  </main>

  @stack('scripts')
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('{{ asset('sw.js') }}', { scope: '{{ url('/admin/cari') }}/' }).catch(() => {});
      });
    }
  </script>
</body>
</html>
