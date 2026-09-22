@props([
  'id',          // Alpine state anahtarı (benzersiz, örn. 'mimarlik')
  'kicker',      // küçük üst etiket, örn. "PROJELENDİRME"
  'title',       // başlık, örn. "Mimarlık"
  'image',       // arkaplan görseli URL
  'href',        // ana link
  'subLinks' => [],  // [['icon'=>'ti-...', 'label'=>'...', 'href'=>'...'], ...] — boşsa alt bar gizlenir
])

<div class="relative overflow-hidden rounded-xl min-w-0 cursor-pointer transition-all duration-500 ease-in-out"
     :style="{ flex: aktif === '{{ $id }}' ? '2.8' : aktif !== null ? '0.6' : '1' }"
     @mouseenter="aktif = '{{ $id }}'"
     @mouseleave="aktif = null">

  {{-- Arka plan --}}
  <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700"
       :class="aktif === '{{ $id }}' ? 'scale-110' : 'scale-100'"
       style="background-image:url('{{ $image }}');"></div>
  {{-- Gradient --}}
  <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.95)] via-[rgba(15,23,42,0.35)] to-transparent"></div>
  {{-- Yan karartma (diğer kartlar hover'da) --}}
  <div class="absolute inset-0 bg-[rgba(15,23,42,0.40)] transition-opacity duration-500"
       :class="aktif !== null && aktif !== '{{ $id }}' ? 'opacity-100' : 'opacity-0'"></div>

  {{-- Varsayılan durum: alt başlık --}}
  <div class="absolute bottom-0 left-0 right-0 p-5 z-10 transition-all duration-300 pointer-events-none"
       :class="aktif !== null ? 'opacity-0 translate-y-2' : 'opacity-100 translate-y-0'">
    <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1.5">{{ $kicker }}</p>
    <h2 class="text-[22px] font-bold text-white tracking-tight">{{ $title }}</h2>
  </div>

  {{-- Daraltılmış durum: dikey etiket --}}
  <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none transition-all duration-300"
       :class="aktif !== null && aktif !== '{{ $id }}' ? 'opacity-100' : 'opacity-0'">
    <span class="text-white/60 text-[11px] font-semibold tracking-[0.2em] uppercase select-none"
          style="writing-mode:vertical-rl; transform:rotate(180deg);">{{ $title }}</span>
  </div>

  {{-- Aktif durum: ana içerik linki --}}
  <a href="{{ $href }}"
     class="absolute left-0 right-0 top-0 z-10 flex flex-col justify-end p-6 transition-all duration-300"
     style="bottom:{{ count($subLinks) ? '52px' : '0' }};"
     :class="aktif === '{{ $id }}' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5 pointer-events-none'"
     aria-label="{{ $title }}">
    <p class="text-[11px] font-semibold tracking-widest uppercase text-[#CC2200] mb-2">{{ $kicker }}</p>
    <h2 class="text-[30px] font-bold text-white tracking-tight leading-tight">{{ $title }}</h2>
  </a>

  @if(count($subLinks))
  {{-- Alt bar (aktifken yukarı kayar) --}}
  <div class="absolute bottom-0 left-0 right-0 flex bg-[rgba(15,23,42,0.92)] z-20 transition-all duration-500"
       style="height:52px;"
       :class="aktif === '{{ $id }}' ? 'translate-y-0' : 'translate-y-full'">
    @foreach($subLinks as $i => $link)
      @if($i > 0)<span class="w-px bg-white/10 shrink-0"></span>@endif
      <a href="{{ $link['href'] }}"
         class="flex-1 flex items-center gap-1.5 px-4 text-[12px] font-medium text-white/60 hover:bg-white/5 hover:text-white transition-colors overflow-hidden">
        <i class="ti {{ $link['icon'] }} text-sm shrink-0"></i><span class="truncate">{{ $link['label'] }}</span>
      </a>
    @endforeach
  </div>
  @endif

  {{-- Tıklama alanı (daraltılmış/varsayılan hâlde) --}}
  <a href="{{ $href }}"
     class="absolute inset-0 z-[5] transition-opacity duration-300"
     :class="aktif === '{{ $id }}' ? 'opacity-0 pointer-events-none' : 'opacity-100'"
     aria-hidden="true" tabindex="-1"></a>
</div>
