@extends('admin.layouts.app')
@section('title', 'Sayfa İçerikleri')
@section('page-title', 'Sayfa İçerikleri')

@section('content')
<div class="mb-5">
  <p class="text-[13px] text-[#94A3B8]">Sitenin tüm sayfalarındaki metin ve görselleri buradan düzenleyebilirsiniz.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
  @foreach($tanimlar as $anahtar => $sayfa)
  <a href="{{ route('admin.icerik.sayfa', $anahtar) }}"
     class="group bg-white rounded-[14px] border border-[#E2E8F0] p-6 hover:shadow-[0_4px_20px_rgba(0,0,0,0.10)] hover:-translate-y-0.5 transition-all duration-200 flex items-start gap-4">
    <div class="w-11 h-11 rounded-[10px] bg-[#F1F5F9] flex items-center justify-center shrink-0 group-hover:bg-[#CC2200] transition-colors duration-200">
      <i class="ti {{ $sayfa['ikon'] }} text-xl text-[#64748B] group-hover:text-white transition-colors duration-200"></i>
    </div>
    <div class="flex-1 min-w-0">
      <h3 class="text-[14px] font-semibold text-[#0F172A] mb-1">{{ $sayfa['baslik'] }}</h3>
      <p class="text-[12px] text-[#94A3B8] leading-relaxed">{{ $sayfa['aciklama'] }}</p>
      <div class="flex items-center gap-1 mt-3">
        <span class="text-[10px] font-semibold tracking-[.06em] text-[#94A3B8] uppercase">{{ count($sayfa['alanlar']) }} alan</span>
        <i class="ti ti-arrow-right text-xs text-[#C0C0C0] group-hover:text-[#0F172A] transition-colors ml-auto"></i>
      </div>
    </div>
  </a>
  @endforeach
</div>
@endsection


