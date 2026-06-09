@extends('layouts.app')

@section('title', '404 — Sayfa Bulunamadı — SUÇEK')

@section('content')

<section class="flex flex-col items-center justify-center text-center px-6 py-24 bg-white min-h-[60vh]" aria-label="404 Sayfa Bulunamadı">
  <div class="font-display text-[96px] lg:text-[140px] font-semibold text-[rgba(0,0,0,0.06)] leading-none select-none mb-2" aria-hidden="true">404</div>
  <h1 class="font-display text-[32px] lg:text-[40px] font-semibold text-[#0F0F0F] mb-3">Sayfa Bulunamadı</h1>
  <p class="text-[14px] text-[#5A5A5A] leading-relaxed max-w-[420px] mb-10">
    Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir.
    Ana sayfaya dönerek devam edebilirsiniz.
  </p>
  <div class="flex flex-col sm:flex-row gap-3">
    <a href="{{ route('home') }}"
       class="btn btn-dark text-[10px] tracking-[1.5px] px-8 py-3.5 min-h-[48px] justify-center">
      <i class="ti ti-home text-sm" aria-hidden="true"></i> Ana Sayfaya Dön
    </a>
    <a href="{{ route('home') }}#iletisim"
       class="btn btn-outline text-[10px] tracking-[1.5px] px-8 py-3.5 min-h-[48px] justify-center">
      <i class="ti ti-mail text-sm" aria-hidden="true"></i> İletişime Geç
    </a>
  </div>
</section>

@endsection
