@extends('admin.layouts.cari')

@section('title', $musteri->ad_soyad)
@section('page-title', $musteri->ad_soyad)
@section('geri', $musteri->tip === 'tedarikci' ? route('admin.cari.tedarikci.index') : route('admin.cari.index'))

@section('header-actions')
  <button type="button" onclick="document.getElementById('belge-modal').classList.remove('hidden')"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-[rgba(255,255,255,0.08)] text-white">
    <i class="ti ti-upload text-[16px]"></i>
  </button>
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
      @if($musteri->tip === 'tedarikci')
        {{ $bakiye > 0 ? 'Tedarikçiye borçlusunuz' : ($bakiye < 0 ? 'Tedarikçi size borçlu' : 'Hesap dengede') }}
      @else
        {{ $bakiye > 0 ? 'Müşteri size borçlu' : ($bakiye < 0 ? 'Siz müşteriye borçlusunuz' : 'Hesap dengede') }}
      @endif
    </p>
    <div class="mt-2">
      <span class="inline-block text-[9.5px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $musteri->tip === 'tedarikci' ? 'bg-[rgba(184,150,46,0.25)] text-[#D9B65C]' : 'bg-[rgba(204,34,0,0.2)] text-[#FF8A6B]' }}">
        {{ $musteri->tip === 'tedarikci' ? 'Tedarikçi' : 'Müşteri' }}
      </span>
    </div>
    @if($musteri->yetkili_kisi || $musteri->telefon || $musteri->email)
    <div class="flex flex-wrap items-center gap-3 mt-3 pt-3 border-t border-[rgba(255,255,255,0.1)] text-[12px] text-[rgba(255,255,255,0.7)]">
      @if($musteri->yetkili_kisi)<span><i class="ti ti-user"></i> {{ $musteri->yetkili_kisi }}</span>@endif
      @if($musteri->telefon)<span><i class="ti ti-phone"></i> {{ $musteri->telefon }}</span>@endif
      @if($musteri->email)<span><i class="ti ti-mail"></i> {{ $musteri->email }}</span>@endif
    </div>
    @endif
  </div>

  {{-- Ekstre aksiyonları --}}
  <div class="grid grid-cols-3 gap-2 mb-5">
    <a href="{{ route('admin.cari.ekstre', $musteri) }}" class="flex flex-col items-center gap-1.5 bg-white border border-[#E2E8F0] rounded-[10px] py-3 text-[#0F172A]">
      <i class="ti ti-file-download text-[18px]"></i>
      <span class="text-[10.5px] font-medium">PDF İndir</span>
    </a>
    <button type="button" onclick="document.getElementById('gonder-modal').classList.remove('hidden')" class="flex flex-col items-center gap-1.5 bg-white border border-[#E2E8F0] rounded-[10px] py-3 text-[#0F172A]">
      <i class="ti ti-send text-[18px]"></i>
      <span class="text-[10.5px] font-medium">Ekstre Gönder</span>
    </button>
    <button type="button" onclick="document.getElementById('duzenle-modal').classList.remove('hidden')" class="flex flex-col items-center gap-1.5 bg-white border border-[#E2E8F0] rounded-[10px] py-3 text-[#0F172A]">
      <i class="ti ti-edit text-[18px]"></i>
      <span class="text-[10.5px] font-medium">Düzenle</span>
    </button>
  </div>

  {{-- Hareket listesi --}}
  <p class="text-[12px] font-semibold text-[#64748B] uppercase tracking-wide mb-2">Hareketler</p>
  <div class="space-y-2">
    @forelse($hareketler as $h)
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] px-4 py-3 {{ $h->iptal ? 'opacity-50' : '' }}">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="text-[13.5px] font-medium text-[#0F172A] truncate {{ $h->iptal ? 'line-through' : '' }}">{{ $h->aciklama }}</p>
            <p class="text-[11px] text-[#94A3B8] mt-0.5">
              {{ $h->tarih->format('d.m.Y') }}
              @if($h->vade_tarihi)
                · Vade: <span class="{{ $h->vadesiGecti() ? 'text-[#CC2200] font-medium' : '' }}">{{ $h->vade_tarihi->format('d.m.Y') }}</span>
              @endif
              @if($h->iptal)
                · <span class="text-[#CC2200] font-semibold">İptal Edildi</span>
              @endif
            </p>
          </div>
          <div class="shrink-0 text-right">
            <p class="text-[14px] font-bold {{ $h->iptal ? 'line-through text-[#94A3B8]' : ($h->tur === 'borc' ? 'text-[#CC2200]' : 'text-[#1D7A4C]') }}">
              {{ $h->tur === 'borc' ? '+' : '−' }}{{ number_format($h->tutar, 2, ',', '.') }} ₺
            </p>
            <div class="flex items-center gap-2 justify-end mt-1">
              <button type="button" class="text-[10px] text-[#64748B]"
                      onclick="hareketDuzenleAc({{ $h->id }}, '{{ $h->tur }}', '{{ $h->tutar }}', {{ json_encode($h->aciklama) }}, '{{ $h->tarih->format('Y-m-d') }}', '{{ $h->vade_tarihi?->format('Y-m-d') }}')">
                Düzenle
              </button>
              <form action="{{ route('admin.cari.hareketler.guncelle', [$musteri, $h]) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="iptal" value="{{ $h->iptal ? 0 : 1 }}">
                <button type="submit" class="text-[10px] {{ $h->iptal ? 'text-[#1D7A4C]' : 'text-[#CC2200]' }}">
                  {{ $h->iptal ? 'İptali Geri Al' : 'İptal Et' }}
                </button>
              </form>
              <form action="{{ route('admin.cari.hareketler.sil', [$musteri, $h]) }}" method="POST" onsubmit="return confirm('Bu hareket silinsin mi?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-[10px] text-[#94A3B8]">Sil</button>
              </form>
            </div>
          </div>
        </div>
        @if(!$h->iptal && $h->tur === 'borc' && $h->vade_tarihi)
          <label class="flex items-center gap-2 mt-2.5 pt-2.5 border-t border-[#F1F5F9] text-[11px] text-[#64748B]">
            <form action="{{ route('admin.cari.hareketler.guncelle', [$musteri, $h]) }}" method="POST" class="flex items-center gap-2">
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

  {{-- Belgeler --}}
  <p class="text-[12px] font-semibold text-[#64748B] uppercase tracking-wide mb-2 mt-6">Belgeler</p>
  <div class="space-y-2">
    @forelse($belgeler as $b)
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] px-4 py-3 flex items-center gap-3">
        <div class="w-9 h-9 rounded-[8px] bg-[#F1F5F9] flex items-center justify-center shrink-0 text-[#64748B]">
          <i class="ti {{ in_array($b->uzanti(), ['jpg','jpeg','png']) ? 'ti-photo' : ($b->uzanti() === 'pdf' ? 'ti-file-type-pdf' : 'ti-file') }} text-[18px]"></i>
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-[13px] font-medium text-[#0F172A] truncate">{{ $b->dosya_adi }}</p>
          <p class="text-[11px] text-[#94A3B8] mt-0.5">{{ $b->boyutMetin() }} · {{ $b->created_at->format('d.m.Y') }}</p>
        </div>
        <div class="flex items-center gap-1 shrink-0">
          <form action="{{ route('admin.cari.belgeler.gonder', [$musteri, $b]) }}" method="POST" onsubmit="return confirm('Bu belge müşteriye gönderilsin mi?')">
            @csrf
            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#0F172A]" title="Gönder">
              <i class="ti ti-send text-[14px]"></i>
            </button>
          </form>
          <form action="{{ route('admin.cari.belgeler.sil', [$musteri, $b]) }}" method="POST" onsubmit="return confirm('Bu belge silinsin mi?')">
            @csrf @method('DELETE')
            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#CC2200]" title="Sil">
              <i class="ti ti-trash text-[14px]"></i>
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="text-center py-8 text-[#94A3B8]">
        <i class="ti ti-file-upload text-[26px]"></i>
        <p class="text-[13px] mt-2">Henüz belge yüklenmedi.</p>
      </div>
    @endforelse
  </div>

  {{-- Belge yükleme modalı --}}
  <div id="belge-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Belge Yükle</p>
        <button type="button" onclick="document.getElementById('belge-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.belgeler.yukle', $musteri) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
        @csrf
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Dosya (PDF, resim, Word, Excel — max 10MB) *</label>
          <input type="file" name="dosya" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                 class="w-full text-[13px] file:mr-3 file:py-2 file:px-3 file:rounded-[8px] file:border-0 file:bg-[#141414] file:text-white file:text-[12px]">
        </div>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Yükle</button>
      </form>
    </div>
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
      <form action="{{ route('admin.cari.hareketler.ekle', $musteri) }}" method="POST" class="space-y-3">
        @csrf
        <div class="grid grid-cols-2 gap-2">
          <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#FEE2E2] has-[:checked]:border-[#CC2200] has-[:checked]:text-[#CC2200]">
            <input type="radio" name="tur" value="borc" checked class="hidden"> {{ $musteri->tip === 'tedarikci' ? 'Borç (Aldım)' : 'Borç' }}
          </label>
          <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#E6F4EC] has-[:checked]:border-[#1D7A4C] has-[:checked]:text-[#1D7A4C]">
            <input type="radio" name="tur" value="tahsilat" class="hidden"> {{ $musteri->tip === 'tedarikci' ? 'Ödeme (Yaptım)' : 'Tahsilat' }}
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

  {{-- Hareket düzenleme modalı --}}
  <div id="hareket-duzenle-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Hareketi Düzenle</p>
        <button type="button" onclick="document.getElementById('hareket-duzenle-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form id="hareket-duzenle-form" method="POST" class="space-y-3">
        @csrf @method('PATCH')
        <div class="grid grid-cols-2 gap-2">
          <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#FEE2E2] has-[:checked]:border-[#CC2200] has-[:checked]:text-[#CC2200]">
            <input type="radio" name="tur" value="borc" id="hd-tur-borc" class="hidden"> {{ $musteri->tip === 'tedarikci' ? 'Borç (Aldım)' : 'Borç' }}
          </label>
          <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#E6F4EC] has-[:checked]:border-[#1D7A4C] has-[:checked]:text-[#1D7A4C]">
            <input type="radio" name="tur" value="tahsilat" id="hd-tur-tahsilat" class="hidden"> {{ $musteri->tip === 'tedarikci' ? 'Ödeme (Yaptım)' : 'Tahsilat' }}
          </label>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Tutar (₺) *</label>
          <input type="number" step="0.01" min="0.01" name="tutar" id="hd-tutar" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Açıklama *</label>
          <input type="text" name="aciklama" id="hd-aciklama" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Tarih *</label>
            <input type="date" name="tarih" id="hd-tarih" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vade (opsiyonel)</label>
            <input type="date" name="vade_tarihi" id="hd-vade" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Kaydet</button>
      </form>
    </div>
  </div>

  {{-- Ekstre gönder modalı --}}
  <div id="gonder-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Ekstre Gönder</p>
        <button type="button" onclick="document.getElementById('gonder-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.ekstre.gonder', $musteri) }}" method="POST" class="space-y-3">
        @csrf
        <label class="flex items-center gap-2.5 border border-[#E2E8F0] rounded-[8px] px-3.5 py-3 {{ !$musteri->email ? 'opacity-40' : '' }}">
          <input type="checkbox" name="mail_gonder" value="1" {{ $musteri->email ? 'checked' : 'disabled' }}>
          <span class="text-[13px]">E-posta ile gönder {{ $musteri->email ? '(' . $musteri->email . ')' : '(e-posta kayıtlı değil)' }}</span>
        </label>
        <label class="flex items-center gap-2.5 border border-[#E2E8F0] rounded-[8px] px-3.5 py-3 {{ !$musteri->telefon ? 'opacity-40' : '' }}">
          <input type="checkbox" name="sms_gonder" value="1" {{ $musteri->telefon ? '' : 'disabled' }}>
          <span class="text-[13px]">SMS ile bilgilendir {{ $musteri->telefon ? '(' . $musteri->telefon . ')' : '(telefon kayıtlı değil)' }}</span>
        </label>
        <button type="submit" class="w-full bg-[#141414] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Gönder</button>
      </form>
    </div>
  </div>

  {{-- Müşteri düzenle modalı --}}
  <div id="duzenle-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Kaydı Düzenle</p>
        <button type="button" onclick="document.getElementById('duzenle-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.update', $musteri) }}" method="POST" class="space-y-3">
        @csrf @method('PATCH')
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Kayıt Türü *</label>
          <div class="grid grid-cols-2 gap-2">
            <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#FEE2E2] has-[:checked]:border-[#CC2200] has-[:checked]:text-[#CC2200]">
              <input type="radio" name="tip" value="musteri" {{ $musteri->tip === 'musteri' ? 'checked' : '' }} class="hidden"> Müşteri
            </label>
            <label class="flex items-center justify-center gap-1.5 border border-[#E2E8F0] rounded-[8px] py-2.5 text-[13px] font-medium cursor-pointer has-[:checked]:bg-[#FEF3C7] has-[:checked]:border-[#B8962E] has-[:checked]:text-[#92720F]">
              <input type="radio" name="tip" value="tedarikci" {{ $musteri->tip === 'tedarikci' ? 'checked' : '' }} class="hidden"> Tedarikçi
            </label>
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Ad Soyad / Firma *</label>
          <input type="text" name="ad_soyad" required value="{{ $musteri->ad_soyad }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Yetkili Kişi</label>
          <input type="text" name="yetkili_kisi" value="{{ $musteri->yetkili_kisi }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Telefon</label>
            <input type="text" name="telefon" value="{{ $musteri->telefon }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">E-posta</label>
            <input type="email" name="email" value="{{ $musteri->email }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Adres</label>
          <textarea name="adres" rows="2" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">{{ $musteri->adres }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi Dairesi</label>
            <input type="text" name="vergi_dairesi" value="{{ $musteri->vergi_dairesi }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi No</label>
            <input type="text" name="vergi_no" value="{{ $musteri->vergi_no }}" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Notlar</label>
          <textarea name="notlar" rows="2" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">{{ $musteri->notlar }}</textarea>
        </div>
        <label class="flex items-center gap-2">
          <input type="checkbox" name="aktif" value="1" {{ $musteri->aktif ? 'checked' : '' }}>
          <span class="text-[13px]">Aktif kayıt</span>
        </label>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Kaydet</button>
      </form>

      <div class="mt-4 pt-4 border-t border-[#F1F5F9]">
        <form action="{{ route('admin.cari.destroy', $musteri) }}" method="POST"
              onsubmit="return confirm('\"{{ $musteri->ad_soyad }}\" cari hesabı ve tüm hareket/belgeleri kalıcı olarak silinecek. Emin misin?')">
          @csrf @method('DELETE')
          <button type="submit" class="w-full bg-white border border-[#FCA5A5] text-[#CC2200] text-[13px] font-semibold py-2.5 rounded-[10px] flex items-center justify-center gap-1.5">
            <i class="ti ti-trash text-[15px]"></i> Kaydı Sil
          </button>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
  const hareketGuncelleUrlSablonu = '{{ route('admin.cari.hareketler.guncelle', [$musteri, '__ID__']) }}';

  function hareketDuzenleAc(id, tur, tutar, aciklama, tarih, vade) {
    document.getElementById('hareket-duzenle-form').action = hareketGuncelleUrlSablonu.replace('__ID__', id);
    document.getElementById(tur === 'borc' ? 'hd-tur-borc' : 'hd-tur-tahsilat').checked = true;
    document.getElementById('hd-tutar').value = tutar;
    document.getElementById('hd-aciklama').value = aciklama;
    document.getElementById('hd-tarih').value = tarih;
    document.getElementById('hd-vade').value = (vade && vade !== '') ? vade : '';
    document.getElementById('hareket-duzenle-modal').classList.remove('hidden');
  }
</script>
@endpush
