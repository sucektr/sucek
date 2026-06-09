@extends('layouts.app')
@section('title', 'Üyelik Gerekli')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
  <div class="text-center max-w-md mx-auto">

    {{-- İkon --}}
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"
         style="background:#f5f3ff;">
      <i class="ti ti-lock text-4xl" style="color:#7c3aed;"></i>
    </div>

    {{-- Başlık --}}
    <h1 class="font-display text-[28px] font-semibold text-[#141414] tracking-[1px] mb-3">
      Erişim Kısıtlı
    </h1>

    <p class="text-[14px] text-[#6B6B6B] leading-relaxed mb-8">
      Bu sayfaya erişim yalnızca <strong class="text-[#141414]">özel üyeler</strong> için açıktır.
      Üyelik başvurusu için yönetici ile iletişime geçebilirsiniz.
    </p>

    {{-- Üyelik Tipleri --}}
    <div class="bg-[#FAFAFA] border border-[rgba(0,0,0,0.08)] rounded-[12px] p-5 text-left mb-8">
      <p class="text-[11px] font-bold text-[#A0A0A0] uppercase tracking-[.08em] mb-4">Üyelik Tipleri</p>
      <div class="space-y-3">
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-[0.06em] uppercase px-2.5 py-1 rounded-full border shrink-0"
                style="background:#eff6ff;color:#2563eb;border-color:#bfdbfe;">
            <i class="ti ti-star text-[9px]"></i> Standart
          </span>
          <span class="text-[12px] text-[#5A5A5A]">Temel içerikler ve hizmetler</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-[0.06em] uppercase px-2.5 py-1 rounded-full border shrink-0"
                style="background:#f5f3ff;color:#7c3aed;border-color:#ddd6fe;">
            <i class="ti ti-crown text-[9px]"></i> Suçek
          </span>
          <span class="text-[12px] text-[#5A5A5A]">Soy ağacı, haberler ve aile özel içerikleri</span>
        </div>
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-1 text-[10px] font-bold tracking-[0.06em] uppercase px-2.5 py-1 rounded-full border shrink-0"
                style="background:#fffbeb;color:#b45309;border-color:#fde68a;">
            <i class="ti ti-tool text-[9px]"></i> Teknik
          </span>
          <span class="text-[12px] text-[#5A5A5A]">Teknik belgeler ve profesyonel araçlar</span>
        </div>
      </div>
    </div>

    {{-- Butonlar --}}
    <div class="flex items-center justify-center gap-3 flex-wrap">
      <a href="{{ route('iletisim.index') }}"
         class="inline-flex items-center gap-2 px-6 py-3 text-[12px] font-semibold tracking-[2px] uppercase transition-colors"
         style="background:#141414;color:#ffffff;border-radius:4px;"
         onmouseover="this.style.background='#333'" onmouseout="this.style.background='#141414'">
        <i class="ti ti-mail"></i> İletişime Geç
      </a>
      <a href="{{ route('home') }}"
         class="inline-flex items-center gap-2 px-6 py-3 border border-[rgba(0,0,0,0.15)] text-[12px] font-semibold tracking-[2px] uppercase text-[#5A5A5A] hover:bg-[#F5F5F5] transition-colors"
         style="border-radius:4px;">
        Ana Sayfa
      </a>
    </div>

  </div>
</div>
@endsection
