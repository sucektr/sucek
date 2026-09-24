{{-- ─── Navigasyon ───────────────────────────────────────────────── --}}
@php
  $mimarlikAktif   = request()->routeIs('mimarlik.*');
  $insaatAktif     = request()->routeIs('insaat.*');
  $magazaAktif     = request()->routeIs('magaza.*') || request()->routeIs('koleksiyon.*');
  $projelerAktif   = request()->routeIs('projeler.*');
@endphp
<nav class="sticky top-0 z-40 bg-white border-b border-[#E2E8F0]"
     x-data="{ menuOpen: false }"
     aria-label="{{ ceviri('Ana navigasyon') }}">

  <div class="flex items-center h-14 max-w-[1280px] mx-auto px-4 lg:px-6">

    {{-- Logo --}}
    <a href="{{ route('home') }}"
       class="flex items-center shrink-0 mr-6 lg:mr-8 gap-2"
       aria-label="{{ ceviri('SUÇEK Ana Sayfa') }}">
      @php $logoUrl = icerik_gorsel('site', 'logo', ''); @endphp
      @if($logoUrl)
      <img src="{{ $logoUrl }}" alt="SUÇEK" class="h-7 w-auto object-contain">
      @endif
      <span class="font-bold text-[17px] tracking-tight text-[#0F172A] leading-none">SUÇEK</span>
    </a>

    {{-- Desktop Nav Items --}}
    <div class="hidden lg:flex items-center gap-0.5 flex-1">

      <a href="{{ route('home') }}"
         class="text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150 {{ request()->routeIs('home') ? 'text-[#CC2200] bg-[rgba(204,34,0,0.06)]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
         @if(request()->routeIs('home')) aria-current="page" @endif>
        {{ ceviri('Anasayfa') }}
      </a>

      {{-- Mimarlık --}}
      <div class="nav-item group relative"
           x-data="{ open: false, isActive: {{ $mimarlikAktif ? 'true' : 'false' }} }"
           @mouseenter="open=true" @mouseleave="open=false"
           @focusin="open=true" @focusout="open=false">
        <a href="{{ route('mimarlik.index') }}"
           class="flex items-center gap-1 text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150"
           :class="(open || isActive) ? 'text-[#CC2200] bg-[rgba(204,34,0,0.06)]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-[#F8FAFC]'"
           aria-haspopup="true" :aria-expanded="open">
          {{ ceviri('Mimarlık') }} <i class="ti ti-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
        </a>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-end="opacity-0"
             class="absolute top-full left-0 pt-1.5 min-w-[190px] z-50"
             style="display:none;">
          <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-[0_8px_32px_rgba(15,23,42,0.12)] py-1.5" role="menu">
            <a href="{{ route('mimarlik.ruhsat') }}" class="block px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">{{ ceviri('Ruhsat Süreci') }}</a>
            <a href="{{ route('mimarlik.icmimari') }}" class="block px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">{{ ceviri('İç Mimari') }}</a>
            <div class="border-t border-[#F1F5F9] my-1"></div>
            <a href="{{ route('projeler.index') }}" class="block px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">{{ ceviri('Projelerimiz') }}</a>
            <a href="{{ route('mimarlik.belgeler') }}" class="block px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">{{ ceviri('Belgeler') }}</a>
          </div>
        </div>
      </div>

      {{-- İnşaat --}}
      <div class="nav-item group relative"
           x-data="{ open: false, isActive: {{ $insaatAktif ? 'true' : 'false' }} }"
           @mouseenter="open=true" @mouseleave="open=false"
           @focusin="open=true" @focusout="open=false">
        <a href="{{ route('insaat.index') }}"
           class="flex items-center gap-1 text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150"
           :class="(open || isActive) ? 'text-[#CC2200] bg-[rgba(204,34,0,0.06)]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-[#F8FAFC]'"
           aria-haspopup="true" :aria-expanded="open">
          {{ ceviri('İnşaat') }} <i class="ti ti-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
        </a>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-end="opacity-0"
             class="absolute top-full left-0 pt-1.5 min-w-[210px] z-50"
             style="display:none;">
          <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-[0_8px_32px_rgba(15,23,42,0.12)] py-1.5" role="menu">
            <a href="{{ route('insaat.hesaplama') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">
              <i class="ti ti-calculator text-base opacity-50"></i>{{ ceviri('Maliyet Hesaplama') }}
            </a>
            <a href="{{ route('insaat.emsal') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">
              <i class="ti ti-ruler-measure text-base opacity-50"></i>{{ ceviri('Emsal Hesaplama') }}
            </a>
          </div>
        </div>
      </div>

      {{-- Mağaza --}}
      <div class="nav-item group relative"
           x-data="{ open: false, isActive: {{ $magazaAktif ? 'true' : 'false' }} }"
           @mouseenter="open=true" @mouseleave="open=false"
           @focusin="open=true" @focusout="open=false">
        <a href="{{ route('magaza.index') }}"
           class="flex items-center gap-1 text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150"
           :class="(open || isActive) ? 'text-[#CC2200] bg-[rgba(204,34,0,0.06)]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-[#F8FAFC]'"
           aria-haspopup="true" :aria-expanded="open">
          {{ ceviri('Mağaza') }} <i class="ti ti-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
        </a>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-end="opacity-0"
             class="absolute top-full left-0 pt-1.5 min-w-[200px] z-50"
             style="display:none;">
          <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-[0_8px_32px_rgba(15,23,42,0.12)] py-1.5" role="menu">
            <a href="{{ route('magaza.index', ['kategori' => 'spor']) }}" class="block px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">{{ ceviri('Spor Malzemeleri') }}</a>
            <a href="{{ route('magaza.index', ['kategori' => 'insaat']) }}" class="block px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">{{ ceviri('İnşaat Malzemeleri') }}</a>
            <a href="{{ route('koleksiyon.index') }}" class="block px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">{{ ceviri('Koleksiyon') }}</a>
          </div>
        </div>
      </div>

      {{-- Çelik Güvenlik Ağı --}}
      <a href="{{ route('celik-guvenlik-agi.index') }}"
         class="text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150 {{ request()->routeIs('celik-guvenlik-agi.index') ? 'text-[#CC2200] bg-[rgba(204,34,0,0.06)]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
         @if(request()->routeIs('celik-guvenlik-agi.index')) aria-current="page" @endif>
        {{ ceviri('Güvenlik Ağı') }}
      </a>

      {{-- İletişim --}}
      <a href="{{ route('iletisim.index') }}"
         class="text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150 {{ request()->routeIs('iletisim.index') ? 'text-[#CC2200] bg-[rgba(204,34,0,0.06)]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
         @if(request()->routeIs('iletisim.index')) aria-current="page" @endif>
        {{ ceviri('İletişim') }}
      </a>

      {{-- Premium (sadece sucek üyeler) --}}
      @auth
      @if(auth()->user()->isSucek())
      <div class="nav-item group relative"
           x-data="{ open: false }"
           @mouseenter="open=true" @mouseleave="open=false"
           @focusin="open=true" @focusout="open=false">
        <button type="button"
                class="flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150"
                :class="open ? 'text-[#7c3aed] bg-[#f5f3ff]' : 'text-[#7c3aed] hover:bg-[#f5f3ff]'"
                aria-haspopup="true" :aria-expanded="open">
          <i class="ti ti-crown text-[13px]"></i> {{ ceviri('Premium') }}
          <i class="ti ti-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-end="opacity-0"
             class="absolute top-full left-0 pt-1.5 min-w-[190px] z-50"
             style="display:none;">
          <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-[0_8px_32px_rgba(15,23,42,0.12)] py-1.5" role="menu">
            <a href="{{ route('haberler.index') }}"
               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">
              <i class="ti ti-news text-base opacity-50"></i> {{ ceviri('Haberler') }}
            </a>
            <a href="{{ route('soy-agaci.index') }}"
               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">
              <i class="ti ti-topology-star-3 text-base opacity-50"></i> {{ ceviri('Soy Ağacı') }}
            </a>
            <a href="{{ route('admin.maliyet-teklif.index') }}"
               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors" role="menuitem">
              <i class="ti ti-calculator text-base opacity-50"></i> {{ ceviri('Yaklaşık Maliyet & Teklif') }}
            </a>
          </div>
        </div>
      </div>
      @elseif(auth()->user()->isTeknik())
      <a href="{{ route('katalog.index') }}"
         class="flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150 text-[#d97706] hover:bg-[#fffbeb]"
         @if(request()->routeIs('admin.katalog.*')) aria-current="page" @endif>
        <i class="ti ti-book-2 text-[13px]"></i> {{ ceviri('Katalog') }}
      </a>
      <a href="{{ route('admin.maliyet-teklif.index') }}"
         class="flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-md transition-colors duration-150 text-[#d97706] hover:bg-[#fffbeb]"
         @if(request()->routeIs('admin.maliyet-teklif.*')) aria-current="page" @endif>
        <i class="ti ti-calculator text-[13px]"></i> {{ ceviri('Maliyet & Teklif') }}
      </a>
      @endif
      @endauth
    </div>

    {{-- Dil Değiştirici --}}
    @php $aktifDil = app()->getLocale(); @endphp
    <div class="hidden lg:flex items-center ml-3">
      <div class="nav-item group relative"
           x-data="{ open: false }"
           @mouseenter="open=true" @mouseleave="open=false"
           @focusin="open=true" @focusout="open=false">
        <button type="button"
                class="flex items-center gap-1 text-[11px] font-medium px-2.5 py-1.5 rounded-md border transition-colors"
                :class="open ? 'text-[#CC2200] border-[#CC2200]' : 'text-[#64748B] border-[#E2E8F0] hover:border-[#CC2200] hover:text-[#CC2200]'"
                aria-haspopup="true" :aria-expanded="open"
                aria-label="{{ ceviri('Dil seçimi') }}">
          <i class="ti ti-language text-[13px]"></i>
          <span>{{ strtoupper($aktifDil) }}</span>
          <i class="ti ti-chevron-down text-[9px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-end="opacity-0"
             class="absolute top-full right-0 pt-1.5 min-w-[140px] z-50"
             style="display:none;">
          <div class="bg-white border border-[#E2E8F0] rounded-xl shadow-[0_8px_32px_rgba(15,23,42,0.12)] py-1.5" role="menu">
            <a href="{{ route('dil.degistir', 'tr') }}"
               class="flex items-center justify-between gap-3 px-4 py-2.5 text-sm transition-colors {{ $aktifDil === 'tr' ? 'text-[#CC2200] font-semibold' : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A]' }}"
               role="menuitem">
              Türkçe @if($aktifDil === 'tr')<i class="ti ti-check text-sm"></i>@endif
            </a>
            <a href="{{ route('dil.degistir', 'en') }}"
               class="flex items-center justify-between gap-3 px-4 py-2.5 text-sm transition-colors {{ $aktifDil === 'en' ? 'text-[#CC2200] font-semibold' : 'text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A]' }}"
               role="menuitem">
              English @if($aktifDil === 'en')<i class="ti ti-check text-sm"></i>@endif
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Sepet + Auth --}}
    <div class="hidden lg:flex items-center gap-1.5 ml-auto">
      <a href="{{ route('sepet.index') }}"
         class="relative flex items-center gap-1.5 text-sm font-medium text-[#64748B] px-3 py-1.5 rounded-md hover:text-[#0F172A] hover:bg-[#F8FAFC] transition-colors"
         aria-label="{{ ceviri('Sepet') }}">
        <i class="ti ti-shopping-cart text-base"></i>
        {{ ceviri('Sepet') }}
        <span class="cart-badge" x-text="sepetAdet" x-show="sepetAdet > 0" aria-label="{{ ceviri('Sepetteki ürün sayısı') }}"></span>
      </a>

      <div class="w-px h-5 bg-[#E2E8F0] mx-1"></div>

      @auth
        <a href="{{ route('hesabim.index') }}"
           class="flex items-center gap-2 text-sm font-medium text-[#0F172A] px-3 py-1.5 rounded-md border border-[#E2E8F0] hover:border-[#CBD5E1] hover:bg-[#F8FAFC] transition-all">
          <div class="w-5 h-5 rounded-full bg-[#CC2200] flex items-center justify-center shrink-0">
            <span class="text-[8px] font-bold text-white leading-none">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
          </div>
          {{ ceviri('Hesabım') }}
        </a>
      @else
        <a href="{{ route('giris') }}"
           class="text-sm font-medium text-[#64748B] px-3 py-1.5 rounded-md hover:text-[#0F172A] hover:bg-[#F8FAFC] transition-colors">
          {{ ceviri('Giriş Yap') }}
        </a>
        <a href="{{ route('uye-ol') }}"
           class="text-sm font-semibold text-white px-4 py-1.5 rounded-md bg-[#CC2200] hover:bg-[#a31b00] transition-colors">
          {{ ceviri('Üye Ol') }}
        </a>
      @endauth
    </div>

    {{-- Mobile: Sepet + Hamburger --}}
    <div class="lg:hidden ml-auto flex items-center gap-1">
      <a href="{{ route('sepet.index') }}"
         class="relative p-2 rounded-md text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors"
         aria-label="{{ ceviri('Sepet') }}">
        <i class="ti ti-shopping-cart text-xl"></i>
        <span class="cart-badge" x-text="sepetAdet" x-show="sepetAdet > 0" aria-label="{{ ceviri('Sepetteki ürün sayısı') }}"></span>
      </a>
      <button class="p-2 rounded-md text-[#64748B] hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors"
              @click="menuOpen = !menuOpen"
              :aria-expanded="menuOpen"
              aria-label="{{ ceviri('Menüyü aç/kapat') }}">
        <i class="ti ti-menu-2 text-xl" x-show="!menuOpen"></i>
        <i class="ti ti-x text-xl" x-show="menuOpen" style="display:none;"></i>
      </button>
    </div>
  </div>

  {{-- Mobile Menu --}}
  <div x-show="menuOpen"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       class="lg:hidden border-t border-[#E2E8F0] bg-white"
       style="display:none;">
    <div class="flex flex-col px-4 py-3 gap-0.5">
      <a href="{{ route('home') }}" class="text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">{{ ceviri('Anasayfa') }}</a>
      <a href="{{ route('mimarlik.index') }}" class="text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">{{ ceviri('Mimarlık') }}</a>
      <a href="{{ route('mimarlik.ruhsat') }}" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-file-certificate text-xs mr-1.5"></i>{{ ceviri('Ruhsat Süreci') }}
      </a>
      <a href="{{ route('mimarlik.icmimari') }}" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-sofa text-xs mr-1.5"></i>{{ ceviri('İç Mimari') }}
      </a>
      <a href="{{ route('projeler.index') }}" @click="menuOpen=false" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-building-arch text-xs mr-1.5"></i>{{ ceviri('Projelerimiz') }}
      </a>
      <a href="{{ route('mimarlik.belgeler') }}" @click="menuOpen=false" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-file-certificate text-xs mr-1.5"></i>{{ ceviri('Belgeler') }}
      </a>
      <a href="{{ route('insaat.index') }}" class="text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">{{ ceviri('İnşaat') }}</a>
      <a href="{{ route('insaat.hesaplama') }}" @click="menuOpen=false" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-calculator text-xs mr-1.5"></i>{{ ceviri('Maliyet Hesaplama') }}
      </a>
      <a href="{{ route('insaat.emsal') }}" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-ruler-measure text-xs mr-1.5"></i>{{ ceviri('Emsal Hesaplama') }}
      </a>
      <a href="{{ route('magaza.index') }}" class="text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">{{ ceviri('Mağaza') }}</a>
      <a href="{{ route('magaza.index', ['kategori' => 'spor']) }}" @click="menuOpen=false" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-ball-football text-xs mr-1.5"></i>{{ ceviri('Spor Malzemeleri') }}
      </a>
      <a href="{{ route('magaza.index', ['kategori' => 'insaat']) }}" @click="menuOpen=false" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-building text-xs mr-1.5"></i>{{ ceviri('İnşaat Malzemeleri') }}
      </a>
      <a href="{{ route('koleksiyon.index') }}" @click="menuOpen=false" class="text-sm text-[#94A3B8] px-3 py-2 pl-7 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-diamond text-xs mr-1.5"></i>{{ ceviri('Koleksiyon') }}
      </a>
      <a href="{{ route('celik-guvenlik-agi.index') }}" @click="menuOpen=false" class="text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-shield text-xs mr-1.5"></i>{{ ceviri('Güvenlik Ağı') }}
      </a>
      <a href="{{ route('iletisim.index') }}" @click="menuOpen=false" class="text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">{{ ceviri('İletişim') }}</a>
      <a href="{{ route('sepet.index') }}" @click="menuOpen=false" class="flex items-center gap-2 text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors">
        <i class="ti ti-shopping-cart text-base"></i>{{ ceviri('Sepetim') }}
        <span class="cart-badge relative top-0 right-0 ml-1" x-text="sepetAdet" x-show="sepetAdet > 0"></span>
      </a>

      @auth
      @if(auth()->user()->isSucek())
      <div class="mt-2 pt-2 border-t border-[#E2E8F0]">
        <p class="text-xs font-semibold tracking-wider uppercase px-3 py-1.5 text-[#7c3aed]">
          <i class="ti ti-crown text-[11px] mr-1"></i> {{ ceviri('Premium') }}
        </p>
        <a href="{{ route('haberler.index') }}" @click="menuOpen=false"
           class="flex items-center gap-2 text-sm font-medium px-3 py-2.5 rounded-md hover:bg-[#f5f3ff] transition-colors text-[#6d28d9]">
          <i class="ti ti-news text-base"></i> {{ ceviri('Haberler') }}
        </a>
        <a href="{{ route('soy-agaci.index') }}" @click="menuOpen=false"
           class="flex items-center gap-2 text-sm font-medium px-3 py-2.5 rounded-md hover:bg-[#f5f3ff] transition-colors text-[#6d28d9]">
          <i class="ti ti-topology-star-3 text-base"></i> {{ ceviri('Soy Ağacı') }}
        </a>
        <a href="{{ route('admin.maliyet-teklif.index') }}" @click="menuOpen=false"
           class="flex items-center gap-2 text-sm font-medium px-3 py-2.5 rounded-md hover:bg-[#f5f3ff] transition-colors text-[#6d28d9]">
          <i class="ti ti-calculator text-base"></i> {{ ceviri('Yaklaşık Maliyet & Teklif') }}
        </a>
      </div>
      @elseif(auth()->user()->isTeknik())
      <div class="mt-2 pt-2 border-t border-[#E2E8F0]">
        <a href="{{ route('katalog.index') }}" @click="menuOpen=false"
           class="flex items-center gap-2 text-sm font-medium px-3 py-2.5 rounded-md hover:bg-[#fffbeb] transition-colors text-[#d97706]">
          <i class="ti ti-book-2 text-base"></i> {{ ceviri('Katalog Oluşturucu') }}
        </a>
        <a href="{{ route('admin.maliyet-teklif.index') }}" @click="menuOpen=false"
           class="flex items-center gap-2 text-sm font-medium px-3 py-2.5 rounded-md hover:bg-[#fffbeb] transition-colors text-[#d97706]">
          <i class="ti ti-calculator text-base"></i> {{ ceviri('Yaklaşık Maliyet & Teklif') }}
        </a>
      </div>
      @endif
      @endauth

      <div class="mt-2 pt-2 border-t border-[#E2E8F0]" x-data="{ dilOpen: false }">
        @php $aktifDilMobil = app()->getLocale(); @endphp
        <button type="button" @click="dilOpen = !dilOpen"
                class="w-full flex items-center justify-between text-sm font-medium text-[#64748B] px-3 py-2.5 rounded-md hover:bg-[#F8FAFC] hover:text-[#0F172A] transition-colors"
                :aria-expanded="dilOpen">
          <span class="flex items-center gap-2"><i class="ti ti-language text-base"></i> {{ ceviri('Dil') }}</span>
          <span class="flex items-center gap-1 text-[#94A3B8]">
            {{ $aktifDilMobil === 'tr' ? 'Türkçe' : 'English' }}
            <i class="ti ti-chevron-down text-xs transition-transform duration-200" :class="dilOpen ? 'rotate-180' : ''"></i>
          </span>
        </button>
        <div x-show="dilOpen"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="pl-7"
             style="display:none;">
          <a href="{{ route('dil.degistir', 'tr') }}"
             class="flex items-center justify-between text-sm px-3 py-2 rounded-md hover:bg-[#F8FAFC] transition-colors {{ $aktifDilMobil === 'tr' ? 'text-[#CC2200] font-semibold' : 'text-[#94A3B8]' }}">
            Türkçe @if($aktifDilMobil === 'tr')<i class="ti ti-check text-xs"></i>@endif
          </a>
          <a href="{{ route('dil.degistir', 'en') }}"
             class="flex items-center justify-between text-sm px-3 py-2 rounded-md hover:bg-[#F8FAFC] transition-colors {{ $aktifDilMobil === 'en' ? 'text-[#CC2200] font-semibold' : 'text-[#94A3B8]' }}">
            English @if($aktifDilMobil === 'en')<i class="ti ti-check text-xs"></i>@endif
          </a>
        </div>
      </div>

      <div class="flex gap-2 mt-3 pt-3 border-t border-[#E2E8F0]">
        @auth
          <a href="{{ route('hesabim.index') }}" class="flex-1 text-center text-sm font-medium text-[#0F172A] py-2.5 rounded-md border border-[#E2E8F0] hover:bg-[#F8FAFC] transition-colors">{{ ceviri('Hesabım') }}</a>
          <form action="{{ route('cikis') }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full text-center text-sm font-medium text-white py-2.5 rounded-md bg-[#0F172A] hover:bg-[#1e293b] transition-colors">{{ ceviri('Çıkış Yap') }}</button>
          </form>
        @else
          <a href="{{ route('giris') }}" class="flex-1 text-center text-sm font-medium text-[#0F172A] py-2.5 rounded-md border border-[#E2E8F0] hover:bg-[#F8FAFC] transition-colors">{{ ceviri('Giriş Yap') }}</a>
          <a href="{{ route('uye-ol') }}" class="flex-1 text-center text-sm font-semibold text-white py-2.5 rounded-md bg-[#CC2200] hover:bg-[#a31b00] transition-colors">{{ ceviri('Üye Ol') }}</a>
        @endauth
      </div>
    </div>
  </div>
</nav>
