<!DOCTYPE html>
<html lang="tr" style="height:100%;">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Katalog Oluşturucu') — SUÇEK</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body
  style="height:100%;overflow:hidden;display:flex;flex-direction:column;"
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
  @include('components.nav')
  <div style="flex:1;overflow:hidden;display:flex;flex-direction:column;min-height:0;">
    @yield('content')
  </div>

  @stack('scripts')
</body>
</html>
