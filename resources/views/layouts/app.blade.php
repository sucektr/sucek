@php
use App\Models\Icerik;
use Illuminate\Support\Facades\Route;
$_routeSayfa = [
    'home'                 => 'anasayfa',
    'mimarlik.index'       => 'mimarlik',
    'mimarlik.ruhsat'      => 'ruhsat',
    'mimarlik.ic-mimari'   => 'icmimari',
    'insaat.index'         => 'insaat',
    'koleksiyon.index'     => 'koleksiyon',
    'magaza.index'         => 'magaza',
    'iletisim.index'       => 'iletisim',
    'projeler.index'       => 'projeler',
    'haberler.index'       => 'haberler',
];
$_sayfa = $_routeSayfa[Route::currentRouteName() ?? ''] ?? null;
$_seoBaslik   = null;
$_seoAciklama = null;
if ($_sayfa) {
    $_seoRows = Icerik::where('sayfa', $_sayfa)->whereIn('alan', ['seo_baslik','seo_aciklama'])->get()->keyBy('alan');
    $_seoBaslik   = $_seoRows['seo_baslik']?->deger   ?: null;
    $_seoAciklama = $_seoRows['seo_aciklama']?->deger ?: null;
}
if (!$_seoBaslik) {
    $_siteRow = Icerik::where('sayfa', 'site')->where('alan', 'seo_baslik')->value('deger');
    $_seoBaslik = $_siteRow ?: null;
}
if (!$_seoAciklama) {
    $_siteRow2 = Icerik::where('sayfa', 'site')->where('alan', 'seo_aciklama')->value('deger');
    $_seoAciklama = $_siteRow2 ?: null;
}
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if($_seoBaslik)<title>{{ $_seoBaslik }}</title>
  @else<title>@yield('title', 'SUÇEK — Mimarlık · İnşaat · Koleksiyon · Mağaza')</title>
  @endif
  @if($_seoAciklama)<meta name="description" content="{{ $_seoAciklama }}">
  @else<meta name="description" content="@yield('meta-description', 'SUÇEK — Mimarlık, inşaat, antika koleksiyon ve mağaza hizmetleri.')">
  @endif

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400;1,14..32,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body
  x-data="{
    menuOpen: false,
    sepetAdet: {{ count(session('sepet', [])) > 0 ? collect(session('sepet', []))->sum('adet') : 0 }},
    bildirim: null,
    bildirimiGoster(mesaj) {
      this.bildirim = mesaj;
      setTimeout(() => this.bildirim = null, 3500);
    }
  }"
>

  {{-- Bildirim Toast --}}
  <div
    x-show="bildirim"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-6 right-6 z-[200] bg-[#141414] text-white text-[11px] tracking-[0.08rem] px-5 py-3 rounded-lg shadow-[0_8px_24px_rgba(0,0,0,0.35)] flex items-center gap-3"
    role="status" aria-live="polite"
    style="display:none;"
  >
    <i class="ti ti-circle-check text-green-400 text-base"></i>
    <span x-text="bildirim"></span>
  </div>

  <div class="page-wrapper">
    @include('components.nav')
    @yield('banner')
    <main id="main-content">
      @yield('content')
    </main>
    @include('components.footer')
  </div>

  @stack('scripts')
</body>
</html>
