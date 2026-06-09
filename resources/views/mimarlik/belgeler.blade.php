@extends('layouts.app')

@section('title', 'Belgeler — SUÇEK Mimarlık')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden min-h-[220px] flex items-end bg-[#F5F5F5]" aria-label="Belgeler hero">
  <div class="px-9 lg:px-14 py-12 w-full">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-2">MİMARLIK · BELGELER</p>
    <h1 class="font-display text-[36px] lg:text-[48px] font-semibold text-[#0F0F0F] leading-[1.1]">Belgeler & Formlar</h1>
    <p class="text-[13px] text-[#5A5A5A] mt-2 max-w-[480px]">Ruhsat başvuruları, teknik şartnameler ve resmi formları buradan indirebilirsiniz.</p>
  </div>
</section>

<section class="section" aria-label="Belge listesi">
  @if($belgeler->isEmpty())
  <div class="flex flex-col items-center justify-center py-20 text-center">
    <i class="ti ti-files-off text-5xl text-[#D0D0D0] mb-4" aria-hidden="true"></i>
    <p class="text-[14px] font-medium text-[#5A5A5A]">Henüz belge yüklenmedi</p>
    <p class="text-[12px] text-[#A8A8A8] mt-1">Lütfen daha sonra tekrar kontrol edin.</p>
  </div>
  @else
  <div class="space-y-8">
    @foreach($belgeler as $kategori => $kategoriBelgeler)
    <div>
      <h2 class="font-display text-[20px] font-semibold text-[#0F0F0F] mb-3 capitalize">
        {{ str_replace('-', ' ', $kategori) }}
      </h2>
      <div class="space-y-2">
        @foreach($kategoriBelgeler as $belge)
        <article class="flex items-center gap-4 bg-white border border-[rgba(0,0,0,0.07)] rounded-[12px] px-5 py-4 hover:shadow-[0_2px_12px_rgba(0,0,0,0.08)] transition-all duration-200">
          <div class="w-10 h-10 bg-[#F0F0F0] rounded-[8px] flex items-center justify-center shrink-0">
            @php $tur = strtolower($belge->dosya_turu ?? 'pdf'); @endphp
            <i class="ti ti-file-{{ $tur === 'pdf' ? 'type-pdf' : ($tur === 'docx' ? 'word' : 'description') }} text-[18px] text-[#5A5A5A]" aria-hidden="true"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-medium text-[14px] text-[#0F0F0F] truncate">{{ $belge->baslik }}</h3>
            @if($belge->aciklama)
            <p class="text-[12px] text-[#A8A8A8] truncate mt-0.5">{{ $belge->aciklama }}</p>
            @endif
            <div class="flex items-center gap-3 mt-1">
              <span class="text-[10px] font-medium tracking-[0.5px] uppercase text-[#A8A8A8]">{{ strtoupper($belge->dosya_turu ?? '') }}</span>
              @if($belge->dosya_boyutu)
              <span class="text-[10px] text-[#A8A8A8]">{{ round($belge->dosya_boyutu / 1024) }} KB</span>
              @endif
            </div>
          </div>
          <a href="{{ route('mimarlik.belgeler.indir', $belge) }}"
             class="flex items-center gap-1.5 text-[10px] font-medium tracking-[1.5px] uppercase text-white bg-[#141414] px-4 py-2.5 rounded-[8px] hover:bg-[#2a2a2a] transition-colors shrink-0 min-h-[40px]"
             aria-label="{{ $belge->baslik }} indir">
            <i class="ti ti-download text-sm" aria-hidden="true"></i> İndir
          </a>
        </article>
        @endforeach
      </div>
    </div>
    @endforeach
  </div>
  @endif
</section>

{{-- CTA --}}
<section class="section">
  <div class="bg-[#F0F0F0] rounded-[16px] px-8 py-10 flex flex-col sm:flex-row items-center justify-between gap-5">
    <div>
      <h2 class="font-display text-[22px] font-semibold text-[#0F0F0F] mb-1">İhtiyacınız olan belge burada yok mu?</h2>
      <p class="text-[13px] text-[#5A5A5A]">Bize ulaşın, size özel belgeler hazırlayalım.</p>
    </div>
    <a href="{{ route('home') }}#iletisim"
       class="btn btn-dark text-[10px] tracking-[1.5px] px-7 py-3.5 min-h-[44px] shrink-0">
      <i class="ti ti-mail text-sm" aria-hidden="true"></i> İletişime Geç
    </a>
  </div>
</section>

@endsection
