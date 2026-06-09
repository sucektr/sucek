@extends('layouts.app')

@section('title', 'Mimarlık — SUÇEK')
@section('meta-description', 'SUÇEK Mimarlık hizmetleri: Ruhsat takibi, iç mimari tasarım ve proje yönetimi.')

@section('banner')
  @include('components.banner', ['mesaj' => icerik('mimarlik','banner_metni','Ücretsiz ön görüşme için hemen randevu alın!')])
@endsection

@section('content')

{{-- ─── Hero ──────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden min-h-[380px] flex" aria-label="Mimarlık hero">
  <div class="absolute inset-0"
       style="background-image:url('{{ icerik_gorsel('mimarlik','hero_gorsel','https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=1280&q=80') }}'); background-size:cover; background-position:center;"></div>
  <div class="absolute inset-0 bg-gradient-to-r from-[rgba(15,23,42,0.90)] via-[rgba(15,23,42,0.60)] to-transparent"></div>

  <div class="relative z-10 flex flex-col lg:flex-row items-end lg:items-center gap-8 px-6 lg:px-12 py-16 lg:py-20 w-full">
    <div class="flex-1">
      <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-3">MİMARLIK HİZMETLERİ</p>
      <h1 class="text-[36px] lg:text-[52px] font-bold text-white leading-tight tracking-tight mb-5">
        {{ icerik('mimarlik','hero_baslik','Vizyonunuzu Gerçeğe Taşıyoruz') }}
      </h1>
      <p class="text-[14px] text-[rgba(255,255,255,0.60)] leading-relaxed max-w-[380px]">
        {{ icerik('mimarlik','hero_alt_baslik','Ruhsat takibinden iç mimari tasarıma kadar kapsamlı mimarlık hizmetleri.') }}
      </p>
    </div>
    <div class="flex flex-col sm:flex-row lg:flex-col gap-2.5 shrink-0">
      <a href="#hizmetler"
         class="btn btn-dark text-sm px-6 py-3 min-h-[44px] justify-center">
        <i class="ti ti-arrow-down text-sm" aria-hidden="true"></i> Hizmetleri İncele
      </a>
      <a href="#iletisim-cta"
         class="btn btn-outline-inv text-sm px-6 py-3 min-h-[44px] justify-center">
        <i class="ti ti-calendar text-sm" aria-hidden="true"></i> Randevu Al
      </a>
    </div>
  </div>
</section>

{{-- ─── Hizmetler ─────────────────────────────────────────────────────── --}}
<section class="section" id="hizmetler" aria-labelledby="hizmetler-baslik">
  <div class="mb-7">
    <p class="section-label mb-2">NE YAPIYORUZ</p>
    <h2 id="hizmetler-baslik" class="text-[26px] font-bold text-[#0F172A] tracking-tight">Mimarlık Hizmetleri</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @php
    $hizmetler = [
      ['ikon'=>'ti-file-certificate','baslik'=>icerik('mimarlik','hizmet_1_baslik','Ruhsat Takibi'),'aciklama'=>icerik('mimarlik','hizmet_1_aciklama','İmar ve inşaat ruhsatlarının başvuru, takip ve sonuçlandırma süreçlerini eksiksiz yönetiyoruz.')],
      ['ikon'=>'ti-sofa','baslik'=>icerik('mimarlik','hizmet_2_baslik','İç Mimari'),'aciklama'=>icerik('mimarlik','hizmet_2_aciklama','Yaşam alanlarınızı işlevsel ve estetik bir bütünlük içinde yeniden tasarlıyoruz.')],
      ['ikon'=>'ti-building-arch','baslik'=>icerik('mimarlik','hizmet_3_baslik','Proje Tasarımı'),'aciklama'=>icerik('mimarlik','hizmet_3_aciklama','Konsept geliştirmeden uygulama projelerine kadar tüm mimari süreçleri kapsayan hizmet.')],
      ['ikon'=>'ti-3d-cube-sphere','baslik'=>icerik('mimarlik','hizmet_4_baslik','3D Görselleştirme'),'aciklama'=>icerik('mimarlik','hizmet_4_aciklama','Projenizin inşaattan önce nasıl görüneceğini gerçekçi 3D render ve animasyonlarla sunuyoruz.')],
      ['ikon'=>'ti-rulers','baslik'=>icerik('mimarlik','hizmet_5_baslik','Statik Proje'),'aciklama'=>icerik('mimarlik','hizmet_5_aciklama','Yapı güvenliği ve dayanımı için gerekli statik hesap ve projelerin hazırlanması.')],
      ['ikon'=>'ti-tree','baslik'=>icerik('mimarlik','hizmet_6_baslik','Peyzaj Tasarımı'),'aciklama'=>icerik('mimarlik','hizmet_6_aciklama','Dış mekan düzenlemesi ve peyzaj projelerinde estetik ve sürdürülebilir çözümler.')],
    ];
    @endphp
    @foreach($hizmetler as $h)
    <article class="group bg-white border border-[#E2E8F0] rounded-xl p-6 hover:shadow-[0_6px_24px_rgba(15,23,42,0.08)] hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
      <div class="w-11 h-11 bg-[#F8FAFC] rounded-xl flex items-center justify-center mb-5 border border-[#E2E8F0] group-hover:bg-[#CC2200] group-hover:border-[#CC2200] transition-all duration-200">
        <i class="ti {{ $h['ikon'] }} text-[18px] text-[#64748B] group-hover:text-white transition-colors duration-200" aria-hidden="true"></i>
      </div>
      <h3 class="text-[17px] font-semibold text-[#0F172A] mb-2 tracking-tight">{{ $h['baslik'] }}</h3>
      <p class="text-[13px] text-[#64748B] leading-relaxed">{{ $h['aciklama'] }}</p>
    </article>
    @endforeach
  </div>
</section>

{{-- ─── Süreç Adımları ────────────────────────────────────────────────── --}}
<section class="section bg-[#F8FAFC]" aria-labelledby="surec-baslik">
  <div class="mb-8">
    <p class="section-label mb-2">NASIL ÇALIŞIYORUZ</p>
    <h2 id="surec-baslik" class="text-[26px] font-bold text-[#0F172A] tracking-tight">Çalışma Sürecimiz</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @php
    $adimlar = [
      ['no'=>'01','baslik'=>icerik('mimarlik','adim_1_baslik','Ön Görüşme'),'aciklama'=>icerik('mimarlik','adim_1_aciklama','İhtiyaçlarınızı ve beklentilerinizi dinliyor, projenizi tanıyoruz.')],
      ['no'=>'02','baslik'=>icerik('mimarlik','adim_2_baslik','Konsept Geliştirme'),'aciklama'=>icerik('mimarlik','adim_2_aciklama','Vizyonunuzu somutlaştıracak tasarım konseptleri hazırlıyoruz.')],
      ['no'=>'03','baslik'=>icerik('mimarlik','adim_3_baslik','Proje & Ruhsat'),'aciklama'=>icerik('mimarlik','adim_3_aciklama','Tüm mimari ve yasal süreçleri titizlikle yürütüyoruz.')],
      ['no'=>'04','baslik'=>icerik('mimarlik','adim_4_baslik','Uygulama'),'aciklama'=>icerik('mimarlik','adim_4_aciklama','Proje sürecini başından sonuna kadar denetliyor ve yönetiyoruz.')],
    ];
    @endphp
    @foreach($adimlar as $i => $adim)
    <div class="relative">
      <div class="text-[56px] font-bold leading-none mb-3 select-none text-[#E2E8F0]" aria-hidden="true">{{ $adim['no'] }}</div>
      <h3 class="text-[16px] font-semibold text-[#0F172A] mb-2 tracking-tight">{{ $adim['baslik'] }}</h3>
      <p class="text-[13px] text-[#64748B] leading-relaxed">{{ $adim['aciklama'] }}</p>
      @if($i < 3)
      <div class="hidden lg:block absolute top-7 right-0 w-8 h-px bg-[#E2E8F0]"></div>
      @endif
    </div>
    @endforeach
  </div>
</section>

{{-- ─── Ruhsat Süreci Galerisi ─────────────────────────────────────────── --}}
@if($ruhsatProjeler->count() > 0)
<section class="section" id="ruhsat-projeler" aria-labelledby="ruhsat-portfolio-baslik">
  <div class="flex items-end justify-between mb-7">
    <div>
      <p class="section-label mb-2">RUHSAT SÜRECİ</p>
      <h2 id="ruhsat-portfolio-baslik" class="text-[26px] font-bold text-[#0F172A] tracking-tight">Ruhsat Projeleri</h2>
    </div>
    <a href="{{ route('mimarlik.ruhsat') }}"
       class="hidden sm:flex items-center gap-1.5 text-sm font-medium text-[#64748B] hover:text-[#CC2200] transition-colors">
      Tümünü Gör <i class="ti ti-arrow-right text-sm" aria-hidden="true"></i>
    </a>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($ruhsatProjeler->take(6) as $proje)
    <article class="group relative rounded-xl overflow-hidden aspect-[4/3] cursor-pointer border border-[#E2E8F0]">
      <div class="absolute inset-0 bg-[#F1F5F9] transition-transform duration-500 group-hover:scale-105"
           style="{{ $proje->kapak_gorsel ? 'background-image:url('.asset('storage/'.$proje->kapak_gorsel).');background-size:cover;background-position:center;' : '' }}"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.80)] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
        <p class="text-[10px] font-medium tracking-widest uppercase text-white/60 mb-1">{{ $proje->yil ?? '' }}</p>
        <h3 class="text-[16px] font-semibold text-white tracking-tight">{{ $proje->baslik }}</h3>
      </div>
    </article>
    @endforeach
  </div>
</section>
@endif

{{-- ─── İç Mimari Galerisi ─────────────────────────────────────────────── --}}
@if($icMimariProjeler->count() > 0)
<section class="section" id="ic-mimari-projeler" aria-labelledby="icmimari-portfolio-baslik">
  <div class="flex items-end justify-between mb-7">
    <div>
      <p class="section-label mb-2">İÇ MİMARİ</p>
      <h2 id="icmimari-portfolio-baslik" class="text-[26px] font-bold text-[#0F172A] tracking-tight">İç Mimari Projeleri</h2>
    </div>
    <a href="{{ route('mimarlik.icmimari') }}"
       class="hidden sm:flex items-center gap-1.5 text-sm font-medium text-[#64748B] hover:text-[#CC2200] transition-colors">
      Tümünü Gör <i class="ti ti-arrow-right text-sm" aria-hidden="true"></i>
    </a>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($icMimariProjeler->take(6) as $proje)
    <article class="group relative rounded-xl overflow-hidden aspect-[4/3] cursor-pointer border border-[#E2E8F0]">
      <div class="absolute inset-0 bg-[#F1F5F9] transition-transform duration-500 group-hover:scale-105"
           style="{{ $proje->kapak_gorsel ? 'background-image:url('.asset('storage/'.$proje->kapak_gorsel).');background-size:cover;background-position:center;' : '' }}"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.80)] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
        <p class="text-[10px] font-medium tracking-widest uppercase text-white/60 mb-1">{{ $proje->yil ?? '' }}</p>
        <h3 class="text-[16px] font-semibold text-white tracking-tight">{{ $proje->baslik }}</h3>
      </div>
    </article>
    @endforeach
  </div>
</section>
@endif

{{-- ─── SSS ────────────────────────────────────────────────────────────── --}}
<section class="section bg-[#F8FAFC]" aria-labelledby="sss-baslik">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div>
      <p class="section-label mb-2">SIKÇA SORULANLAR</p>
      <h2 id="sss-baslik" class="text-[26px] font-bold text-[#0F172A] tracking-tight">Aklınızdaki Sorular</h2>
    </div>
    <div class="lg:col-span-2 space-y-0" x-data="{ acik: null }">
      @php
      $sorular = [
        ['soru'=>icerik('mimarlik','sss_1_soru','Ruhsat süreci ne kadar sürer?'),'cevap'=>icerik('mimarlik','sss_1_cevap','Projenin türü ve belediye yoğunluğuna göre değişmekle birlikte standart bir konut ruhsatı genellikle 3-6 ay sürmektedir.')],
        ['soru'=>icerik('mimarlik','sss_2_soru','İç mimari proje için minimum alan sınırı var mı?'),'cevap'=>icerik('mimarlik','sss_2_cevap','Hayır, her büyüklükteki alana hizmet veriyoruz. Stüdyo daireden villa projelerine kadar geniş bir yelpazeyi kapsıyoruz.')],
        ['soru'=>icerik('mimarlik','sss_3_soru','Proje maliyeti nasıl belirlenir?'),'cevap'=>icerik('mimarlik','sss_3_cevap','Maliyet; projenin kapsamı, alanı ve hizmet türüne göre belirlenir. Ücretsiz ön görüşmemizde size özel teklif sunuyoruz.')],
        ['soru'=>icerik('mimarlik','sss_4_soru','Türkiye genelinde hizmet veriyor musunuz?'),'cevap'=>icerik('mimarlik','sss_4_cevap','Evet, tüm Türkiye genelinde proje ve danışmanlık hizmeti sunmaktayız.')],
      ];
      @endphp
      @foreach($sorular as $i => $s)
      <div class="border-b border-[#E2E8F0]">
        <button
          class="w-full flex items-center justify-between py-4 text-left text-[14px] font-medium text-[#0F172A] hover:text-[#64748B] transition-colors"
          @click="acik = acik === {{ $i }} ? null : {{ $i }}"
          :aria-expanded="acik === {{ $i }}">
          {{ $s['soru'] }}
          <i class="ti ti-chevron-down text-sm text-[#94A3B8] shrink-0 transition-transform duration-200 ml-4"
             :class="acik === {{ $i }} ? 'rotate-180' : ''"
             aria-hidden="true"></i>
        </button>
        <div x-show="acik === {{ $i }}"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="pb-4 text-[13px] text-[#64748B] leading-relaxed"
             style="display:none;">
          {{ $s['cevap'] }}
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ─── Referanslar / Çalıştığımız Kurumlar ──────────────────────────── --}}
@php
$kurumlar = [];
for ($i = 1; $i <= 8; $i++) {
    $ad   = icerik('mimarlik', "ref_kurum_{$i}_ad", '');
    $logo = icerik_gorsel('mimarlik', "ref_kurum_{$i}_logo", '');
    if ($ad || $logo) {
        $kurumlar[] = ['ad' => $ad, 'logo' => $logo];
    }
}
@endphp

@if(count($kurumlar) > 0)
<section class="section" aria-labelledby="kurumlar-baslik">
  <div class="mb-8 text-center">
    <p class="section-label mb-2 justify-center">REFERANSLAR</p>
    <h2 id="kurumlar-baslik" class="text-[22px] font-bold text-[#0F172A] tracking-tight">
      {{ icerik('mimarlik','referanslar_baslik','Çalıştığımız Kurumlar') }}
    </h2>
  </div>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($kurumlar as $kurum)
    <div class="flex flex-col items-center gap-3 p-5 rounded-xl border border-[#E2E8F0] bg-white hover:shadow-[0_4px_16px_rgba(15,23,42,0.06)] transition-shadow cursor-default">
      @if($kurum['logo'])
      <img src="{{ $kurum['logo'] }}" alt="{{ $kurum['ad'] }}"
           class="h-14 w-auto max-w-[120px] object-contain grayscale hover:grayscale-0 transition-all duration-300">
      @else
      <div class="w-14 h-14 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-center">
        <i class="ti ti-building-community text-[#CBD5E1] text-2xl" aria-hidden="true"></i>
      </div>
      @endif
      @if($kurum['ad'])
      <span class="text-[12px] font-medium text-[#64748B] text-center leading-snug">{{ $kurum['ad'] }}</span>
      @endif
    </div>
    @endforeach
  </div>
</section>
@endif

{{-- ─── CTA ────────────────────────────────────────────────────────────── --}}
<section class="section" id="iletisim-cta">
  <div class="bg-[#0F172A] rounded-2xl px-8 lg:px-14 py-12 flex flex-col lg:flex-row items-center justify-between gap-6">
    <div>
      <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-3">HAREKETE GEÇ</p>
      <h2 class="text-[24px] lg:text-[32px] font-bold text-white tracking-tight mb-2">{{ icerik('mimarlik','cta_baslik','Projenizi Konuşalım') }}</h2>
      <p class="text-[13px] text-[rgba(255,255,255,0.45)]">{{ icerik('mimarlik','cta_metin','Ücretsiz ön görüşme için bugün bize ulaşın.') }}</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 shrink-0">
      <a href="{{ route('home') }}#iletisim"
         class="btn btn-outline-inv text-sm px-7 py-3 min-h-[44px] justify-center">
        <i class="ti ti-mail text-sm" aria-hidden="true"></i> Mesaj Gönder
      </a>
      <a href="tel:+90XXXXXXXXXX"
         class="btn bg-[#CC2200] text-white text-sm font-semibold px-7 py-3 min-h-[44px] justify-center rounded-lg hover:bg-[#a31b00] transition-colors">
        <i class="ti ti-phone text-sm" aria-hidden="true"></i> Hemen Ara
      </a>
    </div>
  </div>
</section>

@endsection
