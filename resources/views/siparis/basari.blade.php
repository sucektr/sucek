@extends('layouts.app')

@section('title', 'Ödeme Başarılı — SUÇEK')

@section('content')
<div class="section max-w-lg mx-auto text-center py-10">

  <div class="w-20 h-20 bg-green-50 border border-green-200 rounded-full flex items-center justify-center mx-auto mb-6">
    <i class="ti ti-check text-4xl text-green-600"></i>
  </div>

  <h1 class="font-serif-sc text-[28px] font-bold text-[#0F0F0F] mb-2">Ödemeniz Alındı!</h1>

  <div class="inline-block bg-[#F8F8F8] border border-[rgba(0,0,0,0.08)] rounded-[8px] px-5 py-3 mb-4">
    <p class="text-[10px] text-[#A8A8A8] uppercase tracking-[1.5px] mb-0.5">Sipariş Numaranız</p>
    <p class="text-[17px] font-bold text-[#0F0F0F]">#{{ $siparis->referans }}</p>
  </div>

  <p class="text-[13px] text-[#5A5A5A] leading-relaxed mb-8 max-w-sm mx-auto">
    Siparişiniz hazırlanmaya başlanacaktır. Kargo bilgileri e-posta adresinize (<strong>{{ $siparis->email }}</strong>) iletilecektir.
  </p>

  <div class="flex flex-col sm:flex-row gap-3 justify-center">
    @auth
    <a href="{{ route('hesabim.index') }}"
       class="inline-flex items-center justify-center gap-2 bg-[#0F0F0F] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 px-7 rounded-[10px] hover:bg-[#2a2a2a] transition-colors">
      <i class="ti ti-package text-sm"></i>Siparişlerim
    </a>
    @endauth
    <a href="{{ route('magaza.index') }}"
       class="inline-flex items-center justify-center gap-2 border border-[rgba(0,0,0,0.15)] text-[#5A5A5A] text-[11px] font-medium tracking-[1.5px] uppercase py-3 px-7 rounded-[10px] hover:bg-[#F0F0F0] hover:text-[#0F0F0F] transition-colors">
      <i class="ti ti-shopping-bag text-sm"></i>Alışverişe Devam
    </a>
  </div>

</div>
@endsection
