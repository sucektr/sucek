{{-- ─── Promosyon Banner (opsiyonel) ───────────────────────────── --}}
@if(isset($mesaj) && $mesaj)
@php $bannerLink = $link ?? null; @endphp
<div class="bg-[#FFF5F3] border-b border-[#FECACA]">
  <div class="flex items-center justify-center gap-2.5 px-4 py-2.5">
    <div class="w-1.5 h-1.5 rounded-full bg-[#CC2200] shrink-0"></div>
    @if($bannerLink)
    <a href="{{ $bannerLink }}"
       class="text-[13px] font-medium text-[#991B0A] hover:text-[#CC2200] transition-colors flex items-center gap-1"
       role="banner" aria-label="{{ $mesaj }}">
      {{ $mesaj }}<i class="ti ti-arrow-right text-xs ml-0.5"></i>
    </a>
    @else
    <p class="text-[13px] font-medium text-[#991B0A]" role="banner">{{ $mesaj }}</p>
    @endif
  </div>
</div>
@endif
