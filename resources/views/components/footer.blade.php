{{-- ─── Footer ───────────────────────────────────────────────── --}}
<footer class="bg-[#0F172A]" style="border-top:1px solid rgba(255,255,255,0.06);">
  <div class="px-6 lg:px-10 max-w-[1280px] mx-auto">

    {{-- Üst Kısım --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pt-12 pb-10 border-b border-[rgba(255,255,255,0.07)]">

      {{-- Marka --}}
      <div class="lg:col-span-1">
        <div class="font-bold text-[20px] tracking-tight text-white mb-3">{{ icerik('site','sirket_adi','SUÇEK') }}</div>
        <p class="text-[13px] text-[rgba(255,255,255,0.40)] leading-relaxed max-w-[210px]">
          {{ icerik('site','footer_aciklama','Mimarlık, inşaat, antika koleksiyon ve mağaza hizmetlerinde güvenilir adresiniz.') }}
        </p>
        <div class="flex gap-2.5 mt-6">
          <a href="https://www.instagram.com/sucektr/" aria-label="Instagram" target="_blank" rel="noopener" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[rgba(255,255,255,0.10)] text-[rgba(255,255,255,0.40)] hover:border-[rgba(255,255,255,0.30)] hover:text-white transition-all duration-200">
            <i class="ti ti-brand-instagram text-[14px]"></i>
          </a>
          <a href="https://www.facebook.com/sucektr/" aria-label="Facebook" target="_blank" rel="noopener" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[rgba(255,255,255,0.10)] text-[rgba(255,255,255,0.40)] hover:border-[rgba(255,255,255,0.30)] hover:text-white transition-all duration-200">
            <i class="ti ti-brand-facebook text-[14px]"></i>
          </a>
          <a href="https://in.pinterest.com/sucektr/" aria-label="Pinterest" target="_blank" rel="noopener" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[rgba(255,255,255,0.10)] text-[rgba(255,255,255,0.40)] hover:border-[rgba(255,255,255,0.30)] hover:text-white transition-all duration-200">
            <i class="ti ti-brand-pinterest text-[14px]"></i>
          </a>
          <a href="https://wa.me/905442948402" aria-label="WhatsApp" target="_blank" rel="noopener" class="w-8 h-8 flex items-center justify-center rounded-lg border border-[rgba(255,255,255,0.10)] text-[rgba(255,255,255,0.40)] hover:border-[rgba(255,255,255,0.30)] hover:text-white transition-all duration-200">
            <i class="ti ti-brand-whatsapp text-[14px]"></i>
          </a>
        </div>
      </div>

      {{-- Hizmetler --}}
      <div>
        <div class="text-[11px] font-semibold tracking-wider uppercase text-[rgba(255,255,255,0.30)] mb-5">Hizmetler</div>
        <ul class="space-y-3">
          <li><a href="{{ route('mimarlik.index') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">Mimarlık</a></li>
          <li><a href="{{ route('mimarlik.index') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">İç Mimari</a></li>
          <li><a href="{{ route('mimarlik.belgeler') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">Ruhsat Takibi</a></li>
          <li><a href="{{ route('insaat.index') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">İnşaat</a></li>
          <li><a href="{{ route('insaat.hesaplama') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">Maliyet Hesaplama</a></li>
        </ul>
      </div>

      {{-- Yasal --}}
      <div>
        <div class="text-[11px] font-semibold tracking-wider uppercase text-[rgba(255,255,255,0.30)] mb-5">Yasal</div>
        <ul class="space-y-3">
          <li><a href="{{ route('yasal', 'kisisel-verilerin-korunmasi') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">Kişisel Verilerin Korunması</a></li>
          <li><a href="{{ route('yasal', 'gizlilik-politikasi') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">Gizlilik Politikası</a></li>
          <li><a href="{{ route('yasal', 'sss') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">SSS</a></li>
          <li><a href="{{ route('yasal', 'mesafeli-satis-sozlesmesi') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">Mesafeli Satış Sözleşmesi</a></li>
          <li><a href="{{ route('yasal', 'iade-degisim') }}" class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">İade & Değişim</a></li>
        </ul>
      </div>

      {{-- İletişim --}}
      <div>
        <div class="text-[11px] font-semibold tracking-wider uppercase text-[rgba(255,255,255,0.30)] mb-5">İletişim</div>
        <ul class="space-y-3.5">
          <li class="flex items-start gap-2.5">
            <i class="ti ti-map-pin text-sm text-[rgba(255,255,255,0.30)] mt-0.5 shrink-0"></i>
            <span class="text-[13px] text-[rgba(255,255,255,0.50)] leading-relaxed">{{ icerik('site','adres','Etimesgut, Ankara') }}</span>
          </li>
          <li class="flex items-center gap-2.5">
            <i class="ti ti-phone text-sm text-[rgba(255,255,255,0.30)] shrink-0"></i>
            <a href="tel:{{ preg_replace('/[^+\d]/', '', icerik('site','telefon','+905442948402')) }}"
               class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">
              {{ icerik('site','telefon','+90 (544) 294 84 02') }}
            </a>
          </li>
          <li class="flex items-center gap-2.5">
            <i class="ti ti-mail text-sm text-[rgba(255,255,255,0.30)] shrink-0"></i>
            <a href="mailto:{{ icerik('site','email','info@sucek.com.tr') }}"
               class="text-[13px] text-[rgba(255,255,255,0.50)] hover:text-white transition-colors">
              {{ icerik('site','email','info@sucek.com.tr') }}
            </a>
          </li>
          <li class="flex items-start gap-2.5 pt-1">
            <i class="ti ti-clock text-sm text-[rgba(255,255,255,0.30)] mt-0.5 shrink-0"></i>
            <span class="text-[13px] text-[rgba(255,255,255,0.50)] leading-relaxed">
              {{ icerik('site','calisma_hafta','Pzt–Cum 09:00–18:00') }}<br>
              {{ icerik('site','calisma_cumartesi','Cts 10:00–15:00') }}
            </span>
          </li>
        </ul>
      </div>
    </div>

    {{-- Alt Bar --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 py-5">
      <p class="text-[12px] text-[rgba(255,255,255,0.20)]">
        &copy; {{ date('Y') }} SUÇEK. Tüm hakları saklıdır.
      </p>
      <div class="flex items-center gap-5">
        {{-- ETBİS Rozeti --}}
        <a href="https://www.eticaret.gov.tr/" target="_blank" rel="noopener"
           class="flex items-center gap-2 border border-[rgba(255,255,255,0.10)] rounded px-2.5 py-1.5 hover:border-[rgba(255,255,255,0.25)] transition-colors group">
          <span class="text-[10px] font-bold tracking-widest text-[rgba(255,255,255,0.55)] group-hover:text-white transition-colors">ETBİS</span>
          <span class="w-px h-3 bg-[rgba(255,255,255,0.15)]"></span>
          <span class="text-[10px] text-[rgba(255,255,255,0.30)] group-hover:text-[rgba(255,255,255,0.55)] transition-colors font-mono">4785300354622668</span>
        </a>
      </div>
    </div>
  </div>
</footer>
