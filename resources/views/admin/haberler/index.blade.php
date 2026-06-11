@extends('admin.layouts.app')
@section('title', 'Haberler')
@section('page-title', 'Haberler')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">Premium İçerik</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.haberler.create') }}"
   class="flex items-center gap-2 px-4 py-2 text-white text-[12px] font-semibold tracking-[1px] uppercase rounded-[8px] transition-colors"
   style="background:#CC2200;" onmouseover="this.style.background='#a31b00'" onmouseout="this.style.background='#CC2200'">
  <i class="ti ti-plus text-sm"></i> Yeni Haber
</a>
@endsection

@section('content')

{{-- İstatistik --}}
<div class="grid grid-cols-2 gap-4 mb-6">
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#0F172A]">{{ $yayindaSayisi }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Yayında</p>
  </div>
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#D97706]">{{ $taslakSayisi }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Taslak</p>
  </div>
</div>

{{-- Tablo --}}
<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="px-6 py-4 border-b border-[#E2E8F0]">
    <h2 class="text-[13px] font-semibold text-[#0F172A]">Tüm Haberler</h2>
  </div>

  @if($haberler->isEmpty())
  <div class="px-6 py-12 text-center">
    <i class="ti ti-news text-4xl text-[#D0D0D0] mb-3 block"></i>
    <p class="text-[13px] text-[#94A3B8] mb-4">Henüz haber eklenmedi.</p>
    <a href="{{ route('admin.haberler.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-white text-[12px] font-semibold rounded-[8px]"
       style="background:#CC2200;">
      <i class="ti ti-plus text-sm"></i> İlk Haberi Ekle
    </a>
  </div>
  @else
  <div class="divide-y divide-[rgba(0,0,0,0.05)]">
    @foreach($haberler as $haber)
    <div class="px-6 py-4 flex items-center gap-4">

      {{-- Kapak küçük --}}
      <div class="w-14 h-14 rounded-[6px] overflow-hidden bg-[#F5F0EA] shrink-0">
        @if($haber->kapak)
        <img src="{{ Storage::url($haber->kapak) }}" alt="" class="w-full h-full object-cover">
        @else
        <div class="w-full h-full flex items-center justify-center">
          <i class="ti ti-news text-xl text-[#C8B89A]"></i>
        </div>
        @endif
      </div>

      {{-- Bilgi --}}
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 flex-wrap mb-0.5">
          <span class="text-[13px] font-semibold text-[#0F172A] truncate">{{ $haber->baslik }}</span>
          @if($haber->kategori)
          <span class="text-[9px] font-bold tracking-[2px] uppercase px-1.5 py-0.5 rounded bg-[#F5F0EA] text-[#A07850]">
            {{ $haber->kategori }}
          </span>
          @endif
        </div>
        @if($haber->ozet)
        <p class="text-[11px] text-[#94A3B8] truncate">{{ $haber->ozet }}</p>
        @endif
        <p class="text-[10px] text-[#C0C0C0] mt-0.5">{{ $haber->created_at->format('d.m.Y H:i') }}</p>
      </div>

      {{-- Durum badge --}}
      @if($haber->yayinda)
      <span class="shrink-0 inline-flex items-center gap-1 bg-[#E6F4EC] border border-[#9DD4B5] text-[#1A5C3A] text-[10px] font-bold px-2 py-1 rounded-full">
        <i class="ti ti-eye text-[9px]"></i> Yayında
      </span>
      @else
      <span class="shrink-0 inline-flex items-center gap-1 bg-[#FEF3C7] border border-yellow-300 text-[#92400E] text-[10px] font-bold px-2 py-1 rounded-full">
        <i class="ti ti-pencil text-[9px]"></i> Taslak
      </span>
      @endif

      {{-- Aksiyonlar --}}
      <div class="shrink-0 flex items-center gap-2">
        {{-- Yayin toggle --}}
        <form action="{{ route('admin.haberler.yayin-toggle', $haber) }}" method="POST">
          @csrf @method('PATCH')
          <button type="submit" title="{{ $haber->yayinda ? 'Yayından Kaldır' : 'Yayına Al' }}"
                  class="w-8 h-8 flex items-center justify-center border border-[#E2E8F0] rounded-[6px] text-[#94A3B8] hover:text-[#0F172A] hover:border-[#CC2200] transition-colors bg-white">
            <i class="ti {{ $haber->yayinda ? 'ti-eye-off' : 'ti-eye' }} text-sm"></i>
          </button>
        </form>

        {{-- SMS + E-posta Gönder (sadece yayında olan haberler) --}}
        @if($haber->yayinda)
        <div x-data="{ ac: false }" class="relative">
          <button @click="ac=!ac" title="SMS ile Duyur"
                  class="w-8 h-8 flex items-center justify-center border border-[#E2E8F0] rounded-[6px] text-[#94A3B8] hover:text-[#7C3AED] hover:border-purple-300 transition-colors bg-white">
            <i class="ti ti-message-2 text-sm"></i>
          </button>
          <div x-show="ac" @click.outside="ac=false" x-cloak
               class="absolute right-0 top-10 z-50 bg-white border border-[#E2E8F0] rounded-[10px] shadow-lg p-4 w-56 space-y-3">
            <div>
              <p class="text-[11px] font-semibold text-[#0F172A] mb-2">SMS ile Duyur</p>
              <form action="{{ route('admin.sms.haber', $haber) }}" method="POST"
                    onsubmit="return confirm('Bu haber için SMS gönderilecek. Emin misiniz?')">
                @csrf
                <select name="grup" class="w-full border border-[#E2E8F0] rounded-[6px] px-2 py-1.5 text-[11px] text-[#0F172A] mb-2 focus:outline-none focus:border-[#7C3AED]">
                  <option value="sucek">Suçek Aile Üyeleri</option>
                  <option value="tum_premium">Tüm Premium Üyeler</option>
                  <option value="sms_izinli">SMS İzni Olanlar</option>
                </select>
                <button type="submit"
                        class="w-full py-1.5 text-white text-[11px] font-semibold rounded-[6px] transition-colors"
                        style="background:#7C3AED;" onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7C3AED'">
                  <i class="ti ti-send text-xs mr-1"></i> SMS Gönder
                </button>
              </form>
            </div>
            <hr class="border-[#E2E8F0]">
            <div>
              <p class="text-[11px] font-semibold text-[#0F172A] mb-2">E-posta ile Duyur</p>
              <form action="{{ route('admin.mail.haber', $haber) }}" method="POST"
                    onsubmit="return confirm('Bu haber için e-posta gönderilecek. Emin misiniz?')">
                @csrf
                <select name="grup" class="w-full border border-[#E2E8F0] rounded-[6px] px-2 py-1.5 text-[11px] text-[#0F172A] mb-2 focus:outline-none focus:border-[#CC2200]">
                  <option value="sucek">Suçek Aile Üyeleri</option>
                  <option value="tum_premium">Tüm Premium Üyeler</option>
                  <option value="tum_uyeler">Tüm Üyeler</option>
                </select>
                <button type="submit"
                        class="w-full py-1.5 text-white text-[11px] font-semibold rounded-[6px] transition-colors"
                        style="background:#CC2200;" onmouseover="this.style.background='#A31B00'" onmouseout="this.style.background='#CC2200'">
                  <i class="ti ti-mail text-xs mr-1"></i> Mail Gönder
                </button>
              </form>
            </div>
          </div>
        </div>
        @endif

        {{-- Düzenle --}}
        <a href="{{ route('admin.haberler.edit', $haber) }}"
           class="w-8 h-8 flex items-center justify-center border border-[#E2E8F0] rounded-[6px] text-[#94A3B8] hover:text-[#0F172A] hover:border-[#CC2200] transition-colors bg-white">
          <i class="ti ti-pencil text-sm"></i>
        </a>

        {{-- Sil --}}
        <form action="{{ route('admin.haberler.destroy', $haber) }}" method="POST"
              onsubmit="return confirm('Bu haberi silmek istediğinizden emin misiniz?')">
          @csrf @method('DELETE')
          <button type="submit"
                  class="w-8 h-8 flex items-center justify-center border border-[#E2E8F0] rounded-[6px] text-[#94A3B8] hover:text-[#CC2200] hover:border-red-300 transition-colors bg-white">
            <i class="ti ti-trash text-sm"></i>
          </button>
        </form>
      </div>

    </div>
    @endforeach
  </div>

  @if($haberler->hasPages())
  <div class="px-6 py-4 border-t border-[#E2E8F0]">
    {{ $haberler->links() }}
  </div>
  @endif
  @endif
</div>
@endsection


