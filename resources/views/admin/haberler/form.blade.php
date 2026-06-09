@extends('admin.layouts.app')
@section('title', $haber ? 'Haberi Düzenle' : 'Yeni Haber')
@section('page-title', $haber ? 'Haberi Düzenle' : 'Yeni Haber')

@section('breadcrumb')
<a href="{{ route('admin.haberler.index') }}" class="text-[12px] text-[#64748B] hover:text-[#0F172A] transition-colors">Haberler</a>
<span class="text-[#C0C0C0] mx-1">/</span>
<span class="text-[12px] text-[#64748B]">{{ $haber ? 'Düzenle' : 'Yeni' }}</span>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css" rel="stylesheet">
<style>
  .ql-toolbar.ql-snow {
    background: #F5F5F5;
    border-color: rgba(0,0,0,0.12) !important;
    border-radius: 8px 8px 0 0;
    font-family: 'Inter', system-ui, sans-serif;
  }
  .ql-container.ql-snow {
    border-color: rgba(0,0,0,0.12) !important;
    border-radius: 0 0 8px 8px;
    font-family: 'Inter', system-ui, sans-serif;
    font-size: 14px;
  }
  .ql-editor { min-height: 360px; color: #0F172A; }
  .ql-snow.ql-toolbar button:hover,
  .ql-snow .ql-toolbar button:hover,
  .ql-snow.ql-toolbar button.ql-active,
  .ql-snow .ql-toolbar button.ql-active { color: #0F172A !important; }
  .ql-snow .ql-stroke { stroke: #5A5A5A; }
  .ql-snow .ql-fill { fill: #5A5A5A; }
  .ql-snow button:hover .ql-stroke,
  .ql-snow button.ql-active .ql-stroke { stroke: #0F172A !important; }
  .quill-focus .ql-container.ql-snow { border-color: #CC2200 !important; }
  .quill-focus .ql-toolbar.ql-snow { border-color: #CC2200 !important; }
</style>
@endpush

@section('content')

<form action="{{ $haber ? route('admin.haberler.update', $haber) : route('admin.haberler.store') }}"
      method="POST" enctype="multipart/form-data" id="haberForm">
  @csrf
  @if($haber) @method('PUT') @endif

  <div class="grid grid-cols-3 gap-6">

    {{-- Sol: Ana içerik --}}
    <div class="col-span-2 space-y-5">

      {{-- Başlık --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-2">Başlık <span class="text-[#CC2200]">*</span></label>
        <input type="text" name="baslik" value="{{ old('baslik', $haber?->baslik) }}"
               placeholder="Haber başlığını girin…"
               class="w-full px-4 py-3 bg-white text-[#0F172A] placeholder:text-[#C0C0C0] border border-[#E2E8F0] rounded-[8px] text-[15px] font-semibold focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
               required>
        @error('baslik')<p class="text-[#CC2200] text-[11px] mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Özet --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-2">Özet
          <span class="font-normal normal-case tracking-normal text-[#C0C0C0] ml-1">(liste sayfasında görünür, maks. 500 karakter)</span>
        </label>
        <textarea name="ozet" rows="3" maxlength="500"
                  placeholder="Haberin kısa özeti…"
                  class="w-full px-3 py-2.5 bg-white text-[#0F172A] placeholder:text-[#C0C0C0] border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors resize-none">{{ old('ozet', $haber?->ozet) }}</textarea>
        @error('ozet')<p class="text-[#CC2200] text-[11px] mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- İçerik (Quill) --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-3">İçerik <span class="text-[#CC2200]">*</span></label>
        <div id="quillWrapper">
          <div id="quillEditor" style="min-height:360px;">{!! old('icerik', $haber?->icerik) !!}</div>
        </div>
        <input type="hidden" name="icerik" id="icerikHidden" value="{{ old('icerik', $haber?->icerik) }}">
        @error('icerik')<p class="text-[#CC2200] text-[11px] mt-2">{{ $message }}</p>@enderror
      </div>

    </div>

    {{-- Sağ: Meta --}}
    <div class="col-span-1 space-y-5">

      {{-- Yayın Durumu --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5">
        <p class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-4">Yayın Durumu</p>
        <label class="flex items-center gap-3 cursor-pointer">
          <div class="relative" x-data="{ on: {{ old('yayinda', $haber?->yayinda ?? false) ? 'true' : 'false' }} }">
            <input type="checkbox" name="yayinda" value="1" class="sr-only"
                   :checked="on" @change="on = !on"
                   {{ old('yayinda', $haber?->yayinda) ? 'checked' : '' }}>
            <div @click="on = !on; $el.previousElementSibling.checked = on"
                 class="w-10 h-6 rounded-full transition-colors cursor-pointer"
                 :style="on ? 'background:#CC2200' : 'background:#D1D5DB'">
              <div class="w-4 h-4 bg-white rounded-full shadow transition-transform mt-1"
                   :style="on ? 'transform:translateX(20px)' : 'transform:translateX(4px)'"></div>
            </div>
          </div>
          <span class="text-[13px] font-medium text-[#0F172A]">Yayında</span>
        </label>
        <p class="text-[11px] text-[#94A3B8] mt-2">Kapalıysa taslak olarak kaydedilir.</p>
      </div>

      {{-- Kategori --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5">
        <label class="block text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-2">Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori', $haber?->kategori) }}"
               placeholder="Örn: Duyuru, Etkinlik…"
               class="w-full px-3 py-2.5 bg-white text-[#0F172A] placeholder:text-[#C0C0C0] border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
        @error('kategori')<p class="text-[#CC2200] text-[11px] mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Kapak Fotoğrafı --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5">
        <p class="text-[10px] font-bold text-[#94A3B8] uppercase tracking-[.08em] mb-3">Kapak Fotoğrafı</p>

        @if($haber?->kapak)
        <div class="mb-3 relative group">
          <img src="{{ Storage::url($haber->kapak) }}" alt="" class="w-full aspect-[16/9] object-cover rounded-[6px]">
          <label class="flex items-center gap-2 mt-2 cursor-pointer">
            <input type="checkbox" name="kapak_sil" value="1" class="rounded">
            <span class="text-[11px] text-[#CC2200]">Mevcut fotoğrafı sil</span>
          </label>
        </div>
        @endif

        <label class="flex flex-col items-center justify-center w-full aspect-[16/9] border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]" id="kapakLabel">
          <i class="ti ti-photo-up text-3xl text-[#C0C0C0] mb-2"></i>
          <span class="text-[12px] text-[#94A3B8]">Fotoğraf seçin</span>
          <span class="text-[10px] text-[#C0C0C0] mt-1">JPG, PNG, WebP — maks. 4 MB</span>
          <input type="file" name="kapak" accept="image/*" class="hidden" id="kapakInput"
                 onchange="previewKapak(this)">
        </label>
        <div id="kapakPreview" class="hidden mt-2">
          <img id="kapakPreviewImg" src="" alt="" class="w-full aspect-[16/9] object-cover rounded-[6px]">
          <button type="button" onclick="temizleKapak()"
                  class="mt-1 text-[11px] text-[#CC2200] hover:underline">Seçimi kaldır</button>
        </div>
        @error('kapak')<p class="text-[#CC2200] text-[11px] mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Kaydet --}}
      <button type="submit"
              class="w-full py-3 text-white text-[12px] font-semibold tracking-[2px] uppercase rounded-[8px] transition-colors"
              style="background:#CC2200;" onmouseover="this.style.background='#a31b00'" onmouseout="this.style.background='#CC2200'">
        {{ $haber ? 'Güncelle' : 'Kaydet' }}
      </button>

      @if($haber)
      <a href="{{ route('admin.haberler.index') }}"
         class="block w-full py-3 text-center border border-[#E2E8F0] text-[#64748B] text-[12px] font-semibold tracking-[2px] uppercase rounded-[8px] hover:bg-[#F8FAFC] transition-colors">
        İptal
      </a>
      @endif

    </div>
  </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
  const quill = new Quill('#quillEditor', {
    theme: 'snow',
    modules: {
      toolbar: [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'align': [] }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['blockquote', 'link'],
        ['clean']
      ]
    }
  });

  // Focus outline
  quill.on('selection-change', function(range) {
    const wrapper = document.getElementById('quillWrapper');
    wrapper.classList.toggle('quill-focus', !!range);
  });

  // Sync to hidden input on form submit
  document.getElementById('haberForm').addEventListener('submit', function () {
    document.getElementById('icerikHidden').value = quill.root.innerHTML;
  });

  // Kapak preview
  function previewKapak(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        document.getElementById('kapakLabel').classList.add('hidden');
        document.getElementById('kapakPreviewImg').src = e.target.result;
        document.getElementById('kapakPreview').classList.remove('hidden');
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function temizleKapak() {
    document.getElementById('kapakInput').value = '';
    document.getElementById('kapakPreview').classList.add('hidden');
    document.getElementById('kapakLabel').classList.remove('hidden');
  }
</script>
@endpush



