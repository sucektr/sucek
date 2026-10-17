@extends('admin.layouts.cari')

@section('title', $tedarikci->unvan)
@section('page-title', $tedarikci->unvan)
@section('geri', route('admin.cari.tedarikci.index'))

@section('header-actions')
  <button type="button" onclick="document.getElementById('hareket-modal').classList.remove('hidden')"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-[rgba(255,255,255,0.08)] text-white">
    <i class="ti ti-plus text-[18px]"></i>
  </button>
@endsection

@section('content')

  {{-- Bakiye kartı --}}
  <div class="bg-[#141414] rounded-[14px] px-5 py-5 mb-4 text-white">
    <p class="text-[10px] uppercase tracking-wider text-[rgba(255,255,255,0.4)] mb-1">Bakiye</p>
    <p class="text-[28px] font-bold {{ $bakiye > 0 ? 'text-[#CC2200]' : ($bakiye < 0 ? 'text-[#4ADE80]' : 'text-white') }}">
      {{ number_format($bakiye, 2, ',', '.') }} ₺
    </p>
    <p class="text-[11px] text-[rgba(255,255,255,0.5)] mt-1">
      {{ $bakiye > 0 ? 'Tedarikçiye borçlusunuz' : ($bakiye < 0 ? 'Tedarikçi size borçlu' : 'Hesap dengede') }}
    </p>
    @if($tedarikci->yetkili_kisi || $tedarikci->telefon || $tedarikci->email)
    <div class="flex flex-wrap items-center gap-3 mt-3 pt-3 border-t border-[rgba(255,255,255,0.1)] text-[12px] text-[rgba(255,255,255,0.7)]">
      @if($tedarikci->yetkili_kisi)<span><i class="ti ti-user"></i> {{ $tedarikci->yetkili_kisi }}</span>@endif
      @if($tedarikci->telefon)<span><i class="ti ti-phone"></i> {{ $tedarikci->telefon }}</span>@endif
      @if($tedarikci->email)<span><i class="ti ti-mail"></i> {{ $tedarikci->email }}</span>@endif
    </div>
    @endif
  </div>

  <button type="button" onclick="document.getElementById('duzenle-modal').classList.remove('hidden')"
          class="w-full flex items-center justify-center gap-1.5 bg-white border border-[#E2E8F0] rounded-[10px] py-2.5 text-[#0F172A] text-[13px] font-medium mb-5">
    <i class="ti ti-edit text-[16px]"></i> Tedarikçiyi Düzenle
  </button>

  {{-- Hareket listesi --}}
  <p class="text-[12px] font-semibold text-[#64748B] uppercase tracking-wide mb-2">Hareketler</p>
  <div class="space-y-2">
    @forelse($hareketler as $h)
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] px-4 py-3">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="text-[13.5px] font-medium text-[#0F172A] truncate">{{ $h->aciklama }}</p>
            <p class="text-[11px] text-[#94A3B8] mt-0.5">
              {{ $h->tarih->format('d.m.Y') }}
              @if($h->vade_tarihi)
                · Vade: <span class="{{ $h->vadesiGecti() ? 'text-[#CC2200] font-medium' : '' }}">{{ $h->vade_tarihi->format('d.m.Y') }}</span>
              @endif
            </p>
          </div>
          <div class="shrink-0 text-right">
            <p class="text-[14px] font-bold {{ $h->tur === 'borc' ? 'text-[#CC2200]' : 'text-[#1D7A4C]' }}">
              {{ $h->tur === 'borc' ? '+' : '−' }}{{ number_format($h->tutar, 2, ',', '.') }} ₺
            </p>
            <form action="{{ route('admin.cari.tedarikci.hareketler.sil', [$tedarikci, $h]) }}" method="POST" onsubmit="return confirm('Bu hareket silinsin mi?')" class="mt-1">
              @csrf @method('DELETE')
              <button type="submit" class="text-[10px] text-[#94A3B8]">Sil</button>
            </form>
          </div>
        </div>
        @if($h->tur === 'borc' && $h->vade_tarihi)
          <label class="flex items-center gap-2 mt-2.5 pt-2.5 border-t border-[#F1F5F9] text-[11px] text-[#64748B]">
            <form action="{{ route('admin.cari.tedarikci.hareketler.guncelle', [$tedarikci, $h]) }}" method="POST" class="flex items-center gap-2">
              @csrf @method('PATCH')
              <input type="hidden" name="odendi_mi" value="{{ $h->odendi_mi ? 0 : 1 }}">
              <button type="submit" class="flex items-center gap-1.5">
                <i class="ti {{ $h->odendi_mi ? 'ti-square-check text-[#1D7A4C]' : 'ti-square' }} text-[16px]"></i>
                {{ $h->odendi_mi ? 'Ödendi' : 'Ödenmedi olarak işaretli — ödendi yap' }}
              </button>
            </form>
          </label>
        @endif
      </div>
    @empty
      <div class="text-center py-10 text-[#94A3B8]">
        <i class="ti ti-receipt text-[28px]"></i>
        <p class="text-[13px] mt-2">Henüz hareket kaydı yok.</p>
      </div>
    @endforelse
  </div>

  {{-- Hareket ekleme modalı --}}
  <div id="hareket-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Yeni Hareket</p>
        <button type="button" onclick="document.getElementById('hareket-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.tedarikci.hareketler.ekle', $tedarikci) }}" method="POST" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-2">
          <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#FEE2E2] has-[:checked]:border-[#CC2200] has-[:checked]:text-[#CC2200]">
            <input type="radio" name="tur" value="borc" checked class="hidden"> Borç (Aldım)
          </label>
          <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#E6F4EC] has-[:checked]:border-[#1D7A4C] has-[:checked]:text-[#1D7A4C]">
            <input type="radio" name="tur" value="odeme" class="hidden"> Ödeme (Yaptım)
          </label>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Tutar (₺) *</label>
          <input type="number" step="0.01" min="0.01" name="tutar" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Açıklama *</label>
          <input type="text" name="aciklama" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Tarih *</label>
            <input type="date" name="tarih" required value="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vade (opsiyonel)</label>
            <input type="date" name="vade_tarihi" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Kaydet</button>
      </form>
    </div>
  </div>

  {{-- Tedarikçi düzenle modalı --}}
  <div id="duzenle-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Tedarikçiyi Düzenle</p>
        <button type="button" onclick="document.getElementById('duzenle-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.tedarikci.update', $tedarikci) }}" method="POST" class="space-y-3">
        @csrf @method('PATCH')
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Firma / Unvan *</label>
          <input type="text" name="unvan" required value="{{ $tedarikci->unvan }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Yetkili Kişi</label>
          <input type="text" name="yetkili_kisi" value="{{ $tedarikci->yetkili_kisi }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Telefon</label>
            <input type="text" name="telefon" value="{{ $tedarikci->telefon }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">E-posta</label>
            <input type="email" name="email" value="{{ $tedarikci->email }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Adres</label>
          <textarea name="adres" rows="2" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">{{ $tedarikci->adres }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi Dairesi</label>
            <input type="text" name="vergi_dairesi" value="{{ $tedarikci->vergi_dairesi }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi No</label>
            <input type="text" name="vergi_no" value="{{ $tedarikci->vergi_no }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Notlar</label>
          <textarea name="notlar" rows="2" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">{{ $tedarikci->notlar }}</textarea>
        </div>
        <label class="flex items-center gap-2">
          <input type="checkbox" name="aktif" value="1" {{ $tedarikci->aktif ? 'checked' : '' }}>
          <span class="text-[13px]">Aktif tedarikçi</span>
        </label>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Kaydet</button>
      </form>

      <div class="mt-4 pt-4 border-t border-[#F1F5F9]">
        <form action="{{ route('admin.cari.tedarikci.destroy', $tedarikci) }}" method="POST"
              onsubmit="return confirm('\"{{ $tedarikci->unvan }}\" tedarikçi kaydı ve tüm hareketleri kalıcı olarak silinecek. Emin misin?')">
          @csrf @method('DELETE')
          <button type="submit" class="w-full bg-white border border-[#FCA5A5] text-[#CC2200] text-[13px] font-semibold py-2.5 rounded-[10px] flex items-center justify-center gap-1.5">
            <i class="ti ti-trash text-[15px]"></i> Tedarikçiyi Sil
          </button>
        </form>
      </div>
    </div>
  </div>

@endsection
