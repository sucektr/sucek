@extends('yardimci.layouts.app')

@section('title', 'Koleksiyon Ürünü Ekle')
@section('geri', route('yardimci.index'))

@section('content')
<form action="{{ route('yardimci.koleksiyon.kaydet') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
  @csrf

  {{-- Ana Fotoğraf --}}
  <div x-data="{ onizleme: null }">
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Ürün Fotoğrafı <span class="text-[#CC2200]">*</span></label>
    <label class="relative flex flex-col items-center justify-center w-full aspect-square bg-[#F5F5F5] border-2 border-dashed border-[rgba(0,0,0,0.12)] rounded-[14px] overflow-hidden cursor-pointer">
      <template x-if="!onizleme">
        <div class="flex flex-col items-center gap-2 text-[#A8A8A8]">
          <i class="ti ti-camera text-[32px]"></i>
          <span class="text-[12px] font-medium">Fotoğraf Çek / Seç</span>
        </div>
      </template>
      <img :src="onizleme" x-show="onizleme" class="absolute inset-0 w-full h-full object-cover">
      <input type="file" name="gorsel" accept="image/*" required class="hidden"
             @change="onizleme = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
    </label>
    @error('gorsel')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
  </div>

  {{-- Ek Fotoğraflar --}}
  <div x-data="{ sayi: 0 }">
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Ek Fotoğraflar (opsiyonel)</label>
    <label class="flex items-center justify-center gap-2 w-full border border-dashed border-[rgba(0,0,0,0.15)] rounded-[10px] py-3 text-[13px] text-[#5A5A5A] cursor-pointer">
      <i class="ti ti-plus"></i>
      <span x-text="sayi > 0 ? sayi + ' fotoğraf seçildi' : 'Fotoğraf Ekle'"></span>
      <input type="file" name="gorseller[]" accept="image/*" multiple class="hidden"
             @change="sayi = $event.target.files.length">
    </label>
  </div>

  {{-- Ürün Adı --}}
  <div>
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Ürün Adı <span class="text-[#CC2200]">*</span></label>
    <input type="text" name="ad" required value="{{ old('ad') }}"
           class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[15px] focus:outline-none focus:border-[#141414] transition-colors">
    @error('ad')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
  </div>

  {{-- Kategori --}}
  <div>
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Kategori <span class="text-[#CC2200]">*</span></label>
    <select name="kategori" required class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[15px] focus:outline-none focus:border-[#141414] transition-colors bg-white">
      <option value="">Seçin</option>
      @foreach(['saat' => 'Saat', 'numizmatik' => 'Nümizmatik', 'antika' => 'Antika', 'diger' => 'Diğer'] as $val => $label)
        <option value="{{ $val }}" {{ old('kategori') === $val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    @error('kategori')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
  </div>

  {{-- Ülke --}}
  <div>
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Ülke (opsiyonel)</label>
    <input type="text" name="ulke" value="{{ old('ulke') }}" placeholder="Örn: İsviçre"
           class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[15px] focus:outline-none focus:border-[#141414] transition-colors">
  </div>

  {{-- Fiyat --}}
  <div>
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Fiyat (₺) — bilinmiyorsa boş bırakın</label>
    <input type="number" name="fiyat" step="0.01" min="0" value="{{ old('fiyat') }}"
           class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[15px] focus:outline-none focus:border-[#141414] transition-colors">
    @error('fiyat')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
  </div>

  {{-- Stok Kodu --}}
  <div>
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Ref/Stok Kodu (opsiyonel)</label>
    <input type="text" name="stok_kodu" value="{{ old('stok_kodu') }}"
           class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[15px] focus:outline-none focus:border-[#141414] transition-colors">
  </div>

  {{-- Açıklama --}}
  <div>
    <label class="block text-[11px] font-medium text-[#6B6B6B] uppercase tracking-[.06em] mb-1.5">Açıklama (opsiyonel)</label>
    <textarea name="aciklama" rows="4"
              class="w-full px-4 py-3 border border-[rgba(0,0,0,0.12)] rounded-[10px] text-[14px] focus:outline-none focus:border-[#141414] transition-colors resize-none">{{ old('aciklama') }}</textarea>
  </div>

  <button type="submit"
          class="w-full flex items-center justify-center gap-2 bg-[#141414] text-white text-[13px] font-semibold tracking-[1px] uppercase py-4 rounded-[10px] hover:bg-[#2a2a2a] active:scale-[0.98] transition-all duration-200 min-h-[52px]">
    <i class="ti ti-send text-sm"></i> Onaya Gönder
  </button>
  <p class="text-[11px] text-[#A8A8A8] text-center">Ürün önce yönetici onayına düşer, onaylandıktan sonra sitede yayınlanır.</p>
</form>
@endsection
