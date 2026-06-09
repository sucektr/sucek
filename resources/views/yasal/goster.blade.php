@extends('layouts.app')

@section('title', $tanim['baslik'] . ' — SUÇEK')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden min-h-[180px] flex items-end" aria-label="{{ $tanim['breadcrumb'] }}">
  <div class="absolute inset-0 bg-[#141414]"></div>
  <div class="absolute inset-0 opacity-[0.05]"
       style="background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>
  <div class="relative z-10 px-9 lg:px-14 py-10 w-full">
    <nav class="flex items-center gap-2 mb-3" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.35)] hover:text-white transition-colors">Ana Sayfa</a>
      <span class="text-[rgba(255,255,255,0.20)] text-xs">›</span>
      <span class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.50)]">{{ $tanim['breadcrumb'] }}</span>
    </nav>
    <h1 class="font-display text-[32px] lg:text-[44px] font-semibold text-white leading-[1.1]">{{ $tanim['baslik'] }}</h1>
    @if($guncellenme)
    <p class="text-[11px] text-[rgba(255,255,255,0.35)] mt-2">Son güncelleme: {{ $guncellenme }}</p>
    @endif
  </div>
</section>

{{-- İçerik --}}
<section class="section">
  <div class="max-w-3xl">
    @if($icerik)
      <div class="yasal-icerik prose prose-sm max-w-none">
        {!! $icerik !!}
      </div>
    @else
      <div class="flex flex-col items-center justify-center py-20 text-center">
        <i class="ti ti-file-description text-5xl text-[#D0D0D0] mb-4" aria-hidden="true"></i>
        <p class="text-[14px] font-medium text-[#5A5A5A]">İçerik henüz hazırlanıyor</p>
        <p class="text-[12px] text-[#A8A8A8] mt-1">Admin panelinden bu sayfanın içeriğini ekleyebilirsiniz.</p>
      </div>
    @endif
  </div>
</section>

{{-- Diğer Yasal Sayfalar --}}
<section class="section border-t border-[rgba(0,0,0,0.06)]">
  <p class="text-[9px] font-medium tracking-[2px] uppercase text-[#A8A8A8] mb-4">DİĞER YASAL SAYFALAR</p>
  <div class="flex flex-wrap gap-2">
    @foreach([
      ['kisisel-verilerin-korunmasi', 'Kişisel Verilerin Korunması'],
      ['gizlilik-politikasi',         'Gizlilik Politikası'],
      ['sss',                         'SSS'],
      ['mesafeli-satis-sozlesmesi',   'Mesafeli Satış Sözleşmesi'],
      ['iade-degisim',                'İade & Değişim'],
    ] as [$slug, $label])
    @if($slug !== request()->route('sayfa'))
    <a href="{{ route('yasal', $slug) }}"
       class="px-4 py-2 text-[11px] font-medium text-[#5A5A5A] border border-[rgba(0,0,0,0.12)] rounded-[8px] hover:border-[#0F0F0F] hover:text-[#0F0F0F] transition-colors">
      {{ $label }}
    </a>
    @endif
    @endforeach
  </div>
</section>

@endsection

@push('styles')
<style>
.yasal-icerik { color: #3A3A3A; line-height: 1.8; font-size: 14px; }
.yasal-icerik h2 { font-size: 20px; font-weight: 600; color: #0F0F0F; margin: 2rem 0 0.75rem; }
.yasal-icerik h3 { font-size: 16px; font-weight: 600; color: #0F0F0F; margin: 1.5rem 0 0.5rem; }
.yasal-icerik p  { margin-bottom: 1rem; }
.yasal-icerik ul, .yasal-icerik ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.yasal-icerik li { margin-bottom: 0.4rem; }
.yasal-icerik strong { color: #0F0F0F; font-weight: 600; }
.yasal-icerik a  { color: #0F0F0F; text-decoration: underline; }
.yasal-icerik table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: 13px; }
.yasal-icerik th, .yasal-icerik td { border: 1px solid rgba(0,0,0,0.12); padding: 8px 12px; text-align: left; }
.yasal-icerik th { background: #F5F5F5; font-weight: 600; color: #0F0F0F; }
.yasal-icerik hr { border: none; border-top: 1px solid rgba(0,0,0,0.08); margin: 2rem 0; }
</style>
@endpush
