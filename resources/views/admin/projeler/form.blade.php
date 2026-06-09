@extends('admin.layouts.app')
@section('title', $proje->exists ? 'Proje Düzenle' : 'Yeni Proje')
@section('page-title', $proje->exists ? 'Proje Düzenle' : 'Yeni Proje')

@section('breadcrumb')
<a href="{{ route('admin.projeler.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A]">Projeler</a>
<span class="text-[#D0D0D0] text-xs">›</span>
<span class="text-[12px] text-[#64748B]">{{ $proje->exists ? $proje->baslik : 'Yeni' }}</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.projeler.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">← Geri</a>
@endsection

@section('content')
<form action="{{ $proje->exists ? route('admin.projeler.update', $proje) : route('admin.projeler.store') }}"
      method="POST" enctype="multipart/form-data">
  @csrf
  @if($proje->exists) @method('PUT') @endif

  <div class="grid lg:grid-cols-3 gap-6"
       x-data="{ kategori: '{{ old('kategori', $proje->kategori) }}', altKategori: '{{ old('alt_kategori', $proje->alt_kategori) }}' }">
    <div class="lg:col-span-2 space-y-5">
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-5">Proje Bilgileri</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Başlık <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="baslik" value="{{ old('baslik', $proje->baslik) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            @error('baslik')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Slug <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="slug" value="{{ old('slug', $proje->slug) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono">
            @error('slug')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Kategori <span class="text-[#CC2200]">*</span></label>
              <select name="kategori" required x-model="kategori" @change="altKategori = ''"
                      class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
                <option value="">Seçin</option>
                @foreach(['mimarlik' => 'Mimarlık', 'insaat' => 'İnşaat', 'diger' => 'Diğer'] as $val => $lbl)
                  <option value="{{ $val }}" {{ old('kategori', $proje->kategori) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Alt Kategori</label>
              {{-- Mimarlık: dropdown seçimi --}}
              <select x-show="kategori === 'mimarlik'" :disabled="kategori !== 'mimarlik'"
                      name="alt_kategori" x-model="altKategori"
                      class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
                <option value="">— Seçin —</option>
                <option value="ruhsat" {{ old('alt_kategori', $proje->alt_kategori) === 'ruhsat' ? 'selected' : '' }}>Ruhsat Süreci</option>
                <option value="ic-mimari" {{ old('alt_kategori', $proje->alt_kategori) === 'ic-mimari' ? 'selected' : '' }}>İç Mimari</option>
              </select>
              {{-- Diğer kategoriler: serbest metin --}}
              <input x-show="kategori !== 'mimarlik'" :disabled="kategori === 'mimarlik'"
                     type="text" name="alt_kategori" x-model="altKategori"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                     placeholder="Alt kategori...">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Konum</label>
              <input type="text" name="konum" value="{{ old('konum', $proje->konum) }}"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                     placeholder="İstanbul, Türkiye">
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Yıl</label>
              <input type="number" name="yil" value="{{ old('yil', $proje->yil) }}" min="1900" max="2100"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                     placeholder="{{ date('Y') }}">
            </div>
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Detaylar</label>
            <textarea name="detaylar" rows="5"
                      class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors resize-none"
                      placeholder="Proje hakkında açıklama...">{{ old('detaylar', $proje->detaylar) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="space-y-5">
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Kapak Görseli</h2>
        @if($proje->kapak_gorsel)
          <img src="{{ Storage::url($proje->kapak_gorsel) }}" class="w-full h-40 object-cover rounded-[8px] mb-3" alt="{{ $proje->baslik }}">
        @endif
        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-upload text-2xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[12px] text-[#94A3B8]">{{ $proje->kapak_gorsel ? 'Değiştir' : 'Görsel Seç' }}</span>
          <input type="file" name="kapak_gorsel" accept="image/*" class="hidden">
        </label>
      </div>
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Durum & Sıra</h2>
        <div class="space-y-3 mb-4">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $proje->aktif ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#CC2200]">
            <span class="text-[13px] font-medium text-[#0F172A]">Aktif</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="one_cikan" value="1" {{ old('one_cikan', $proje->one_cikan) ? 'checked' : '' }} class="w-4 h-4 accent-[#CC2200]">
            <span class="text-[13px] font-medium text-[#0F172A]">Öne Çıkan</span>
          </label>
        </div>
        <div>
          <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Sıra</label>
          <input type="number" name="sira" value="{{ old('sira', $proje->sira ?? 0) }}" min="0"
                 class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
        </div>
      </div>
      <button type="submit" class="w-full bg-[#CC2200] text-white text-[12px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#a31b00] transition-colors min-h-[52px]">
        <i class="ti ti-device-floppy text-sm mr-1"></i>
        {{ $proje->exists ? 'Güncelle' : 'Kaydet' }}
      </button>
    </div>
  </div>
</form>
@endsection


