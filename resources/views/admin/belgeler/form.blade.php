@extends('admin.layouts.app')
@section('title', $belge->exists ? 'Belge Düzenle' : 'Yeni Belge')
@section('page-title', $belge->exists ? 'Belge Düzenle' : 'Yeni Belge')

@section('breadcrumb')
<a href="{{ route('admin.belgeler.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A]">Belgeler</a>
<span class="text-[#D0D0D0] text-xs">›</span>
<span class="text-[12px] text-[#64748B]">{{ $belge->exists ? $belge->baslik : 'Yeni' }}</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.belgeler.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">← Geri</a>
@endsection

@section('content')
<form action="{{ $belge->exists ? route('admin.belgeler.update', $belge) : route('admin.belgeler.store') }}"
      method="POST" enctype="multipart/form-data">
  @csrf
  @if($belge->exists) @method('PUT') @endif

  <div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-5">Belge Bilgileri</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Başlık <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="baslik" value="{{ old('baslik', $belge->baslik) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            @error('baslik')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Slug <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="slug" value="{{ old('slug', $belge->slug) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono">
            @error('slug')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Kategori <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="kategori" value="{{ old('kategori', $belge->kategori) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                   placeholder="örn: mimarlık, inşaat, teknik">
            @error('kategori')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">
              Dosya {{ $belge->exists ? '' : '*' }}
            </label>
            @if($belge->dosya_yolu)
            <div class="flex items-center gap-3 p-3 bg-[#F8FAFC] rounded-[8px] mb-2">
              <i class="ti ti-file text-[#94A3B8] text-lg"></i>
              <div class="flex-1 min-w-0">
                <p class="text-[12px] font-medium text-[#334155] truncate">{{ basename($belge->dosya_yolu) }}</p>
                <p class="text-[11px] text-[#94A3B8]">{{ strtoupper($belge->dosya_turu) }} · {{ number_format($belge->dosya_boyutu / 1024, 0) }} KB</p>
              </div>
              <a href="{{ Storage::url($belge->dosya_yolu) }}" target="_blank"
                 class="text-[11px] text-[#1a4a6b] hover:underline">İndir</a>
            </div>
            <p class="text-[11px] text-[#94A3B8] mb-2">Yeni dosya yükleyerek değiştirin:</p>
            @endif
            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
              <i class="ti ti-upload text-2xl text-[#C0C0C0] mb-1"></i>
              <span class="text-[12px] text-[#94A3B8]">PDF, Word, Excel, PNG, JPG</span>
              <span class="text-[10px] text-[#C0C0C0] mt-0.5">Maks. 20MB</span>
              <input type="file" name="dosya" class="hidden" {{ $belge->exists ? '' : 'required' }}>
            </label>
            @error('dosya')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>
    </div>

    <div class="space-y-5">
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Durum & Sıra</h2>
        <div class="space-y-3 mb-4">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $belge->aktif ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#CC2200]">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">Aktif</span>
              <p class="text-[11px] text-[#94A3B8]">Sitede listelenir</p>
            </div>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="herkese_acik" value="1" {{ old('herkese_acik', $belge->herkese_acik) ? 'checked' : '' }} class="w-4 h-4 accent-[#CC2200]">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">Herkese Açık</span>
              <p class="text-[11px] text-[#94A3B8]">Giriş yapmadan indirilebilir</p>
            </div>
          </label>
        </div>
        <div>
          <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Sıra</label>
          <input type="number" name="sira" value="{{ old('sira', $belge->sira ?? 0) }}" min="0"
                 class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
        </div>
      </div>
      <button type="submit" class="w-full bg-[#CC2200] text-white text-[12px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#a31b00] transition-colors min-h-[52px]">
        <i class="ti ti-device-floppy text-sm mr-1"></i>
        {{ $belge->exists ? 'Güncelle' : 'Kaydet' }}
      </button>
    </div>
  </div>
</form>
@endsection


