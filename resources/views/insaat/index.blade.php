@extends('layouts.app')

@section('title', 'İnşaat — SUÇEK')
@section('meta-description', 'SUÇEK İnşaat hizmetleri: Anahtar teslim projeler, dekorasyon ve inşaat yönetimi.')

@section('banner')
  @include('components.banner', ['mesaj' => icerik('insaat','banner_metni','Maliyet hesaplama aracımızı kullanmak için tıklayın →')])
@endsection

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[360px] flex" aria-label="İnşaat hero">
  <div class="absolute inset-0"
       style="background-image:url('{{ icerik_gorsel('insaat','hero_gorsel','https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1280&q=80') }}'); background-size:cover; background-position:center;"></div>
  <div class="absolute inset-0 bg-gradient-to-r from-[rgba(15,15,15,0.88)] via-[rgba(15,15,15,0.55)] to-transparent"></div>
  <div class="relative z-10 flex flex-col lg:flex-row items-end lg:items-center gap-8 px-5 lg:px-14 py-12 lg:py-20 w-full">
    <div class="flex-1">
      <p class="text-[9px] font-medium tracking-[3px] uppercase text-[rgba(255,255,255,0.45)] mb-3">İNŞAAT HİZMETLERİ</p>
      <h1 class="font-display text-[42px] lg:text-[56px] font-semibold text-white leading-[1.05] mb-5">
        {{ icerik('insaat','hero_baslik','Sağlam Temeller, Mükemmel Sonuçlar') }}
      </h1>
      <p class="text-[13px] text-[rgba(255,255,255,0.60)] leading-relaxed max-w-[380px]">
        {{ icerik('insaat','hero_alt_baslik','Anahtar teslim projelerden dekorasyona, maliyet optimizasyonundan denetim hizmetine.') }}
      </p>
    </div>
    <div class="flex flex-col sm:flex-row lg:flex-col gap-2 shrink-0">
      <a href="{{ route('insaat.hesaplama') }}"
         class="btn btn-dark text-[10px] tracking-[1.5px] px-6 py-3 min-h-[44px] justify-center">
        <i class="ti ti-calculator text-sm" aria-hidden="true"></i> Maliyet Hesapla
      </a>
      <a href="{{ route('home') }}#iletisim"
         class="btn btn-outline-inv text-[10px] tracking-[1.5px] px-6 py-3 min-h-[44px] justify-center">
        <i class="ti ti-calendar text-sm" aria-hidden="true"></i> Teklif Al
      </a>
    </div>
  </div>
</section>

{{-- ─── Hizmetler ─────────────────────────────────────────────────────── --}}
<section class="section" id="hizmetler" aria-labelledby="ins-hizmetler-baslik">
  <div class="mb-6">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-1.5">HİZMET PAKETLERİ</p>
    <h2 id="ins-hizmetler-baslik" class="font-serif-sc text-[28px] font-bold text-[#0F0F0F]">İnşaat Hizmetlerimiz</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
    @php
    $hizmetler = [
      ['ikon'=>'ti-home','baslik'=>icerik('insaat','hizmet_1_baslik','Anahtar Teslim'),'aciklama'=>icerik('insaat','hizmet_1_aciklama','Projenizin başından sonuna, her detayıyla sizin adınıza yönetiyoruz. Teslim günü sadece anahtarı alıyorsunuz.'),'one_cikan'=>true],
      ['ikon'=>'ti-brush','baslik'=>icerik('insaat','hizmet_2_baslik','Dekorasyon'),'aciklama'=>icerik('insaat','hizmet_2_aciklama','İç mekan dekorasyonu ve özel mobilya tasarımıyla yaşam alanlarınızı kişiselleştiriyoruz.'),'one_cikan'=>false],
      ['ikon'=>'ti-tool','baslik'=>icerik('insaat','hizmet_3_baslik','Tadilat & Renovasyon'),'aciklama'=>icerik('insaat','hizmet_3_aciklama','Mevcut yapıların modernizasyonu ve tadilat çalışmalarında hızlı ve güvenilir hizmet sunuyoruz.'),'one_cikan'=>false],
      ['ikon'=>'ti-bolt','baslik'=>icerik('insaat','hizmet_4_baslik','Mekanik & Elektrik'),'aciklama'=>icerik('insaat','hizmet_4_aciklama','Tesisat, elektrik ve iklimlendirme sistemlerinin proje ve uygulamasını eksiksiz yapıyoruz.'),'one_cikan'=>false],
      ['ikon'=>'ti-wall','baslik'=>icerik('insaat','hizmet_5_baslik','Kaba İnşaat'),'aciklama'=>icerik('insaat','hizmet_5_aciklama','Temel, taşıyıcı sistem ve çatı dahil tüm kaba inşaat işlerini ustalarımızla gerçekleştiriyoruz.'),'one_cikan'=>false],
      ['ikon'=>'ti-report','baslik'=>icerik('insaat','hizmet_6_baslik','İnşaat Denetimi'),'aciklama'=>icerik('insaat','hizmet_6_aciklama','Yapım süreçlerinde kalite, güvenlik ve uygunluk denetimi sağlayarak riskleri minimize ediyoruz.'),'one_cikan'=>false],
    ];
    @endphp
    @foreach($hizmetler as $h)
    <article class="group bg-white border border-[rgba(0,0,0,0.07)] rounded-[12px] p-6 hover:shadow-[0_4px_20px_rgba(0,0,0,0.10)] hover:-translate-y-0.5 transition-all duration-200 relative">
      @if($h['one_cikan'])
      <span class="absolute top-4 right-4 text-[8px] font-bold tracking-[1.5px] uppercase bg-[#141414] text-white px-2 py-1 rounded-[4px]">Popüler</span>
      @endif
      <div class="w-11 h-11 bg-[#F0F0F0] rounded-[10px] flex items-center justify-center mb-4 group-hover:bg-[#141414] transition-colors duration-200">
        <i class="ti {{ $h['ikon'] }} text-[18px] text-[#5A5A5A] group-hover:text-white transition-colors duration-200" aria-hidden="true"></i>
      </div>
      <h3 class="font-display text-[20px] font-semibold text-[#0F0F0F] mb-2">{{ $h['baslik'] }}</h3>
      <p class="text-[13px] text-[#5A5A5A] leading-relaxed">{{ $h['aciklama'] }}</p>
    </article>
    @endforeach
  </div>
</section>

{{-- ─── Portfolio ──────────────────────────────────────────────────────── --}}
@if($projeler->count() > 0)
<section class="section" aria-labelledby="ins-portfolio-baslik">
  <div class="mb-6">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[#A8A8A8] mb-1.5">SON ÇALIŞMALAR</p>
    <h2 id="ins-portfolio-baslik" class="font-serif-sc text-[28px] font-bold text-[#0F0F0F]">Tamamlanan Projeler</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
    @foreach($projeler->take(6) as $proje)
    <article class="group relative rounded-[12px] overflow-hidden aspect-[4/3] shadow-[0_2px_6px_rgba(0,0,0,0.20)]">
      <div class="absolute inset-0 bg-[#E0E0E0]"
           style="{{ $proje->kapak_gorsel ? 'background-image:url('.asset('storage/'.$proje->kapak_gorsel).');background-size:cover;background-position:center;' : '' }}"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[rgba(0,0,0,0.75)] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
        <p class="text-[9px] uppercase tracking-[2px] text-[rgba(255,255,255,0.60)] mb-1">{{ $proje->konum ?? '' }}</p>
        <h3 class="font-display text-[18px] font-semibold text-white">{{ $proje->baslik }}</h3>
      </div>
    </article>
    @endforeach
  </div>
</section>
@endif

{{-- ─── CTA ────────────────────────────────────────────────────────────── --}}
<section class="section">
  <div class="bg-[#141414] rounded-[16px] px-6 lg:px-16 py-10 lg:py-12 flex flex-col lg:flex-row items-center justify-between gap-6 text-center lg:text-left">
    <div>
      <h2 class="font-display text-[28px] lg:text-[34px] font-semibold text-white mb-2">{{ icerik('insaat','cta_baslik','Projenizin Maliyetini Hesaplayın') }}</h2>
      <p class="text-[13px] text-[rgba(255,255,255,0.50)]">{{ icerik('insaat','cta_metin','Anlık maliyet tahmini için hesaplama aracımızı kullanın.') }}</p>
    </div>
    <a href="{{ route('insaat.hesaplama') }}"
       class="btn bg-white text-[#0F0F0F] text-[10px] tracking-[1.5px] px-8 py-3.5 min-h-[44px] justify-center hover:bg-[#F0F0F0] transition-colors rounded-[8px] shrink-0">
      <i class="ti ti-calculator text-sm mr-1" aria-hidden="true"></i> Hesaplama Aracı
    </a>
  </div>
</section>

@endsection
