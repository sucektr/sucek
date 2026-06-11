<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Yönetim') — SUÇEK Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; background: #F8FAFC; }
    .admin-sidebar { width: 240px; min-height: 100vh; background: #0F172A; position: fixed; top: 0; left: 0; bottom: 0; display: flex; flex-direction: column; z-index: 50; }
    .admin-content { margin-left: 240px; min-height: 100vh; }
    .nav-link { display: flex; align-items: center; gap: 10px; padding: 9px 16px; font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.45); border-radius: 8px; transition: all .15s; margin: 1px 8px; text-decoration: none; }
    .nav-link:hover { color: #fff; background: rgba(255,255,255,0.07); }
    .nav-link.active { color: #fff; background: rgba(204,34,0,0.20); }
    .nav-link .ti { font-size: 16px; width: 20px; flex-shrink: 0; }
    .nav-section { font-size: 10px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,0.22); padding: 14px 24px 6px; }
    .badge-red { background: #CC2200; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 99px; margin-left: auto; }
  </style>
  @stack('styles')
</head>
<body>

<div class="admin-sidebar">
  {{-- Logo --}}
  <div class="px-6 py-5 border-b border-[rgba(255,255,255,0.07)]">
    <a href="{{ route('admin.dashboard') }}" class="text-[18px] font-bold tracking-tight text-white leading-none">SUÇEK</a>
    <div class="text-[10px] tracking-widest text-[rgba(255,255,255,0.25)] mt-1 uppercase">Yönetim</div>
  </div>

  {{-- Nav --}}
  <nav class="flex-1 py-3 overflow-y-auto">
    <div class="nav-section">Genel</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <i class="ti ti-layout-dashboard"></i> Dashboard
    </a>

    <div class="nav-section mt-2">İçerik</div>
    <a href="{{ route('admin.urunler.index') }}" class="nav-link {{ request()->routeIs('admin.urunler.*') ? 'active' : '' }}">
      <i class="ti ti-shopping-bag"></i> Ürünler
    </a>
    <a href="{{ route('admin.koleksiyonlar.index') }}" class="nav-link {{ request()->routeIs('admin.koleksiyonlar.*') ? 'active' : '' }}">
      <i class="ti ti-diamond"></i> Koleksiyonlar
    </a>
    <a href="{{ route('admin.projeler.index') }}" class="nav-link {{ request()->routeIs('admin.projeler.*') ? 'active' : '' }}">
      <i class="ti ti-building-arch"></i> Projeler
    </a>
    <a href="{{ route('admin.belgeler.index') }}" class="nav-link {{ request()->routeIs('admin.belgeler.*') ? 'active' : '' }}">
      <i class="ti ti-files"></i> Belgeler
    </a>

    <div class="nav-section mt-2">Site</div>
    <a href="{{ route('admin.icerik.index') }}" class="nav-link {{ request()->routeIs('admin.icerik.*') ? 'active' : '' }}">
      <i class="ti ti-layout-2"></i> Sayfa İçerikleri
    </a>

    <div class="nav-section mt-2">Premium</div>
    <a href="{{ route('admin.haberler.index') }}" class="nav-link {{ request()->routeIs('admin.haberler.*') ? 'active' : '' }}">
      <i class="ti ti-news"></i> Haberler
    </a>
    <a href="{{ route('admin.soy-agaci.index') }}" class="nav-link {{ request()->routeIs('admin.soy-agaci.*') ? 'active' : '' }}">
      <i class="ti ti-topology-star-3"></i> Soy Ağacı
      @php $bekleyenSoyAgaci = \App\Models\SoyAgaciDegisiklik::where('durum','beklemede')->count(); @endphp
      @if($bekleyenSoyAgaci > 0)
        <span class="badge-red">{{ $bekleyenSoyAgaci }}</span>
      @endif
    </a>

    <div class="nav-section mt-2">Üyeler</div>
    <a href="{{ route('admin.uyeler.index') }}" class="nav-link {{ request()->routeIs('admin.uyeler.*') ? 'active' : '' }}">
      <i class="ti ti-users"></i> Üyeler
    </a>
    <a href="{{ route('admin.erisim.index') }}" class="nav-link {{ request()->routeIs('admin.erisim.*') ? 'active' : '' }}">
      <i class="ti ti-shield-lock"></i> Erişim Yetkileri
    </a>
    @php $bekleyenUrunSayisi = \App\Models\UserUrun::count(); @endphp
    <a href="{{ route('admin.uye-urunleri.index') }}" class="nav-link {{ request()->routeIs('admin.uye-urunleri.*') ? 'active' : '' }}">
      <i class="ti ti-box"></i> Üye Ürünleri
      @if($bekleyenUrunSayisi > 0)
        <span class="badge-red">{{ $bekleyenUrunSayisi }}</span>
      @endif
    </a>

    <div class="nav-section mt-2">Araçlar</div>
    <a href="{{ route('admin.katalog.index') }}" class="nav-link {{ request()->routeIs('admin.katalog.*') ? 'active' : '' }}">
      <i class="ti ti-book-2"></i> Katalog Oluşturucu
    </a>
    <a href="{{ route('admin.fiyat-guncelle.index') }}" class="nav-link {{ request()->routeIs('admin.fiyat-guncelle.*') ? 'active' : '' }}">
      <i class="ti ti-table-options"></i> Toplu Fiyat
    </a>
    <a href="{{ route('admin.sms.index') }}" class="nav-link {{ request()->routeIs('admin.sms.*') ? 'active' : '' }}">
      <i class="ti ti-message-2"></i> SMS
    </a>
    <a href="{{ route('admin.deploy.index') }}" class="nav-link {{ request()->routeIs('admin.deploy.*') ? 'active' : '' }}">
      <i class="ti ti-cloud-download"></i> Güncelle
    </a>

    <div class="nav-section mt-2">Mesajlar</div>
    @php
      $okunmamis = \App\Models\IletisimMesaji::where('okundu', false)->count();
      $bekleyenTeklif = \App\Models\Teklif::where('durum', 'beklemede')->count();
    @endphp
    <a href="{{ route('admin.iletisim.index') }}" class="nav-link {{ request()->routeIs('admin.iletisim.*') ? 'active' : '' }}">
      <i class="ti ti-mail"></i> İletişim
      @if($okunmamis > 0)
        <span class="badge-red">{{ $okunmamis }}</span>
      @endif
    </a>
    <a href="{{ route('admin.siparisler.index') }}" class="nav-link {{ request()->routeIs('admin.siparisler.*') ? 'active' : '' }}">
      <i class="ti ti-shopping-bag"></i> Siparişler
    </a>
    <a href="{{ route('admin.teklifler.index') }}" class="nav-link {{ request()->routeIs('admin.teklifler.*') ? 'active' : '' }}">
      <i class="ti ti-tag"></i> Teklifler
      @if($bekleyenTeklif > 0)
        <span class="badge-red">{{ $bekleyenTeklif }}</span>
      @endif
    </a>
  </nav>

  {{-- Bottom --}}
  <div class="border-t border-[rgba(255,255,255,0.07)] p-3 space-y-0.5">
    <a href="{{ route('home') }}" target="_blank" class="nav-link">
      <i class="ti ti-external-link"></i> Siteye Git
    </a>
    <form action="{{ route('admin.cikis') }}" method="POST">
      @csrf
      <button type="submit" class="nav-link w-full text-left hover:text-red-400">
        <i class="ti ti-logout"></i> Çıkış Yap
      </button>
    </form>
    <div class="flex items-center gap-2.5 px-3 py-2.5 mt-1 rounded-lg bg-[rgba(255,255,255,0.05)] mx-1">
      <div class="w-7 h-7 rounded-full bg-[#CC2200] flex items-center justify-center shrink-0 text-[11px] font-bold text-white">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
      </div>
      <div class="min-w-0">
        <p class="text-[11px] font-semibold text-white truncate">{{ auth()->user()->name ?? '' }}</p>
        <p class="text-[10px] text-[rgba(255,255,255,0.35)] truncate">{{ auth()->user()->email ?? '' }}</p>
      </div>
    </div>
  </div>
</div>

{{-- Ana İçerik --}}
<div class="admin-content">

  {{-- Top Bar --}}
  <header class="bg-white border-b border-[#E2E8F0] px-6 py-4 flex items-center justify-between sticky top-0 z-30">
    <div>
      <h1 class="text-[16px] font-semibold text-[#0F172A]">@yield('page-title', 'Dashboard')</h1>
      @hasSection('breadcrumb')
      <div class="flex items-center gap-1.5 mt-0.5">
        @yield('breadcrumb')
      </div>
      @endif
    </div>
    <div class="flex items-center gap-3">
      @yield('header-actions')
    </div>
  </header>

  {{-- Flash Mesajlar --}}
  @if(session('basari'))
  <div class="mx-6 mt-4 flex items-center gap-3 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] rounded-[8px] px-4 py-3 text-[13px]" role="alert">
    <i class="ti ti-circle-check text-base shrink-0"></i>
    <span>{{ session('basari') }}</span>
  </div>
  @endif
  @if(session('hata') || (isset($errors) && $errors->any()))
  <div class="mx-6 mt-4 flex items-start gap-3 bg-[#FEE2E2] border border-[#FCA5A5] text-[#991B1B] rounded-[8px] px-4 py-3 text-[13px]" role="alert">
    <i class="ti ti-alert-circle text-base shrink-0 mt-0.5"></i>
    <div>
      @if(session('hata'))<p>{{ session('hata') }}</p>@endif
      @if(isset($errors)) @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach @endif
    </div>
  </div>
  @endif

  {{-- Sayfa İçeriği --}}
  <main class="p-6">
    @yield('content')
  </main>
</div>

@stack('scripts')
</body>
</html>
