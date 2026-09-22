@props(['kicker', 'title', 'image', 'href'])

<div class="group relative rounded-xl overflow-hidden min-h-[200px] cursor-pointer">
  <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105" style="background-image:url('{{ $image }}'); background-size:cover; background-position:center;"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-[rgba(15,23,42,0.92)] via-[rgba(15,23,42,0.30)] to-transparent"></div>
  <a href="{{ $href }}" class="absolute inset-0 z-10 flex flex-col justify-end p-4" aria-label="{{ $title }}">
    <p class="text-[10px] font-semibold tracking-widest uppercase text-[#CC2200] mb-1">{{ $kicker }}</p>
    <h2 class="text-[18px] font-bold text-white tracking-tight leading-tight">{{ $title }}</h2>
  </a>
</div>
