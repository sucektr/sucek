<article class="group bg-white border border-[rgba(0,0,0,0.07)] rounded-[12px] overflow-hidden hover:shadow-[0_6px_24px_rgba(0,0,0,0.10)] hover:-translate-y-0.5 transition-all duration-200"
         role="listitem">
  <a href="{{ route('magaza.urun', $urun->slug) }}" class="block">
    <div class="relative aspect-square bg-[#F0F0F0] overflow-hidden">
      @if($urun->gorsel)
      <img src="{{ asset('storage/'.$urun->gorsel) }}" alt="{{ $urun->ad }}"
           class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
           loading="lazy" width="300" height="300">
      @else
      <div class="w-full h-full flex items-center justify-center">
        <i class="ti ti-photo text-3xl text-[#D0D0D0]" aria-hidden="true"></i>
      </div>
      @endif
      @if($urun->indirim_yuzdesi)
      <span class="absolute top-2 left-2 bg-[#CC2200] text-white text-[9px] font-bold px-2 py-0.5 rounded-[4px]">-%{{ $urun->indirim_yuzdesi }}</span>
      @endif
    </div>
    <div class="p-3.5">
      @php
        $isPremium = auth()->check() && auth()->user()->isPremium();
        $premiumFiyat = $isPremium ? $urun->premiumFiyat() : null;
      @endphp
      <p class="text-[10px] text-[#A8A8A8] uppercase tracking-[1px] mb-1">{{ ucfirst($urun->kategori) }}</p>
      <h3 class="font-display text-[15px] font-semibold text-[#0F0F0F] mb-2 line-clamp-2 leading-snug">{{ $urun->ad }}</h3>
      @if($premiumFiyat)
      <div class="flex items-baseline gap-2 flex-wrap">
        <span class="font-display text-[17px] font-semibold" style="color:#6d28d9;">{{ number_format($premiumFiyat, 2, ',', '.') }} ₺</span>
        <span class="text-[12px] text-[#A8A8A8] line-through">{{ number_format($urun->fiyat, 2, ',', '.') }} ₺</span>
        <span class="text-[8px] font-bold tracking-[1px] uppercase px-1.5 py-0.5 rounded" style="background:#f5f3ff;color:#7c3aed;">
          <i class="ti ti-crown text-[8px]"></i> %15
        </span>
      </div>
      @else
      <div class="flex items-baseline gap-2">
        <span class="font-display text-[17px] font-semibold text-[#0F0F0F]">{{ number_format($urun->fiyat, 2, ',', '.') }} ₺</span>
        @if($urun->eski_fiyat)
        <span class="text-[12px] text-[#A8A8A8] line-through">{{ number_format($urun->eski_fiyat, 2, ',', '.') }} ₺</span>
        @endif
      </div>
      @endif
    </div>
  </a>
  <div class="px-3.5 pb-3.5">
    <button
      @click="
        fetch('{{ route('sepet.ekle') }}', {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
          body: JSON.stringify({urun_id:{{ $urun->id }}, urun_tipi:'urun', adet:1})
        }).then(r=>r.json()).then(d=>{ $root.sepetAdet=d.adet; $root.bildirimiGoster('{{ $urun->ad }} sepete eklendi'); })
      "
      class="w-full flex items-center justify-center gap-1.5 text-[10px] font-medium tracking-[1.5px] uppercase text-white bg-[#141414] py-2.5 rounded-[8px] hover:bg-[#2a2a2a] active:scale-95 transition-all duration-200 min-h-[40px]"
      aria-label="{{ $urun->ad }} sepete ekle">
      <i class="ti ti-shopping-cart text-sm" aria-hidden="true"></i> Sepete Ekle
    </button>
  </div>
</article>
