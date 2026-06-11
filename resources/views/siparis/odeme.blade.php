@extends('layouts.app')

@section('title', 'Güvenli Ödeme — SUÇEK')

@section('content')
<div class="section max-w-2xl mx-auto">

  <div class="mb-6 text-center">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-1.5">ÖDEME</p>
    <h1 class="font-serif-sc text-[26px] font-bold text-[#0F0F0F]">Güvenli Ödeme</h1>
    <p class="text-[11px] text-[#A8A8A8] mt-1">Sipariş: <span class="font-medium text-[#5A5A5A]">#{{ $siparis->referans }}</span></p>
  </div>

  @if(session('hata'))
  <div class="mb-5 bg-[#FEE2E2] border border-[#FCA5A5] text-[#DC2626] text-[12px] px-5 py-4 rounded-[10px] flex items-center gap-2">
    <i class="ti ti-circle-x text-sm shrink-0"></i>{{ session('hata') }}
  </div>
  @endif

  <div class="bg-white rounded-[12px] border border-[rgba(0,0,0,0.07)] overflow-hidden shadow-sm">

    <div class="px-5 py-4 border-b border-[rgba(0,0,0,0.06)] flex items-center justify-between bg-[#FAFAFA]">
      <div class="flex items-center gap-2">
        <i class="ti ti-lock text-[#1A5C3A] text-sm"></i>
        <span class="text-[11px] font-semibold text-[#0F0F0F] tracking-[0.5px]">SSL Güvenli Ödeme</span>
      </div>
      <span class="font-display text-[15px] font-bold text-[#0F0F0F]">
        {{ number_format($siparis->toplam, 2, ',', '.') }} ₺
      </span>
    </div>

    <iframe src="https://www.paytr.com/odeme/guvenli/{{ $iframeToken }}"
            id="paytriframe"
            frameborder="0"
            scrolling="no"
            style="width:100%;height:600px;display:block;"></iframe>

  </div>

  <div class="mt-4 flex items-center justify-center gap-3 text-[11px] text-[#A8A8A8]">
    <span>Ödeme altyapısı:</span>
    <span class="font-semibold text-[#5A5A5A]">PayTR</span>
    <span>·</span>
    <i class="ti ti-shield-check text-xs"></i>
    <span>256-bit SSL şifreli bağlantı</span>
  </div>

</div>
@endsection

@push('scripts')
<script>
window.addEventListener('message', function (e) {
  if (!e.data) return;
  var iframe = document.getElementById('paytriframe');
  // PayTR sadece sayı gönderir (px cinsinden yükseklik)
  if (typeof e.data === 'number' || /^\d+$/.test(e.data)) {
    iframe.style.height = parseInt(e.data) + 'px';
    return;
  }
  // Alternatif format: URL query string içinde height
  if (typeof e.data === 'string' && e.data.indexOf('//paytr.com') !== -1) {
    var params = new URLSearchParams(e.data.split('?')[1] || '');
    var h = params.get('height');
    if (h) iframe.style.height = parseInt(h) + 'px';
  }
}, false);
</script>
@endpush
