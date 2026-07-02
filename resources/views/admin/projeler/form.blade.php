@extends('admin.layouts.app')
@section('title', $proje->exists ? 'Proje Düzenle' : 'Yeni Proje')
@section('page-title', $proje->exists ? 'Proje Düzenle' : 'Yeni Proje')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
  .ql-toolbar { border-radius: 8px 8px 0 0 !important; border-color: #E2E8F0 !important; background: #F8FAFC; }
  .ql-container { border-radius: 0 0 8px 8px !important; border-color: #E2E8F0 !important; font-size: 14px; min-height: 180px; }
  .ql-editor { min-height: 180px; }
</style>
@endpush

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
      method="POST" enctype="multipart/form-data" id="proje-form">
  @csrf
  @if($proje->exists) @method('PUT') @endif

  <div class="grid lg:grid-cols-3 gap-6"
       x-data="{ kategori: '{{ old('kategori', $proje->kategori) }}', altKategori: '{{ old('alt_kategori', $proje->alt_kategori) }}' }">

    {{-- Sol: Ana Bilgiler --}}
    <div class="lg:col-span-2 space-y-5">

      {{-- Temel Bilgiler --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-5">Proje Bilgileri</h2>
        <div class="space-y-4">

          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Başlık <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="baslik" id="slug-kaynak" value="{{ old('baslik', $proje->baslik) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            @error('baslik')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>

          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em]">Slug <span class="text-[#CC2200]">*</span></label>
              <span id="slug-mod-badge" class="text-[10px] px-2 py-0.5 rounded-full bg-green-50 text-green-600 border border-green-200">Otomatik</span>
            </div>
            <input type="text" name="slug" id="slug-hedef" value="{{ old('slug', $proje->slug) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono">
            <p class="text-[11px] text-[#94A3B8] mt-1">Başlıktan otomatik oluşturulur, istediğinizde düzenleyebilirsiniz.</p>
            @error('slug')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Kategori <span class="text-[#CC2200]">*</span></label>
              <select name="kategori" required x-model="kategori" @change="altKategori = ''"
                      class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
                <option value="">Seçin</option>
                @foreach(['mimarlik' => 'Mimarlık', 'insaat' => 'İnşaat', 'diger' => 'Diğer Projeler'] as $val => $lbl)
                  <option value="{{ $val }}" {{ old('kategori', $proje->kategori) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Alt Kategori</label>
              <select x-show="kategori === 'mimarlik'" :disabled="kategori !== 'mimarlik'"
                      name="alt_kategori" x-model="altKategori"
                      class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
                <option value="">— Seçin —</option>
                <option value="ruhsat" {{ old('alt_kategori', $proje->alt_kategori) === 'ruhsat' ? 'selected' : '' }}>Ruhsat Süreci</option>
                <option value="ic-mimari" {{ old('alt_kategori', $proje->alt_kategori) === 'ic-mimari' ? 'selected' : '' }}>İç Mimari</option>
              </select>
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

          {{-- Açıklama (Quill Rich Text) --}}
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Açıklama</label>
            <div id="quill-editor"></div>
            <input type="hidden" name="detaylar" id="detaylar-hidden" value="{{ old('detaylar', $proje->detaylar) }}">
          </div>

        </div>
      </div>

      {{-- Proje Görselleri --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-[13px] font-semibold text-[#0F172A]">Proje Görselleri</h2>
          <span class="text-[11px] text-[#94A3B8]">Birden fazla görsel yükleyebilirsiniz</span>
        </div>

        {{-- Mevcut Görseller --}}
        <div id="mevcut-gorseller" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-4 @if(empty($proje->gorseller)) hidden @endif">
          @foreach($proje->gorseller ?? [] as $gorsel)
          <div class="relative group gorsel-item" data-path="{{ $gorsel }}">
            <img src="{{ Storage::url($gorsel) }}"
                 alt="Proje görseli"
                 class="w-full aspect-square object-cover rounded-[8px] border border-[#E2E8F0]">
            <input type="hidden" name="mevcut_gorseller[]" value="{{ $gorsel }}">
            <button type="button"
                    onclick="gorselSil(this)"
                    class="absolute top-1.5 right-1.5 w-6 h-6 bg-[#CC2200] text-white rounded-full text-xs items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity flex cursor-pointer shadow-md"
                    title="Görseli sil">
              <i class="ti ti-x text-[11px]"></i>
            </button>
          </div>
          @endforeach
        </div>

        {{-- Yeni Görsel Önizleme --}}
        <div id="yeni-gorsel-preview" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-4 hidden"></div>

        {{-- Upload Alanı --}}
        <label id="upload-label"
               class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] hover:bg-[rgba(204,34,0,0.02)] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-photo-plus text-2xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[12px] text-[#94A3B8]">Görsel seç veya sürükle</span>
          <span class="text-[10px] text-[#C0C0C0] mt-0.5">JPG, PNG, WebP — max 8MB — çoklu seçim</span>
          <input type="file" name="yeni_gorseller[]" id="gorsel-input" accept="image/*" multiple class="hidden">
        </label>

      </div>

    </div>

    {{-- Sağ: Kapak + Ayarlar --}}
    <div class="space-y-5">

      {{-- Kapak Görseli --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Kapak Görseli</h2>
        @if($proje->kapak_gorsel)
          <img src="{{ Storage::url($proje->kapak_gorsel) }}"
               class="w-full h-40 object-cover rounded-[8px] mb-3 border border-[#E2E8F0]"
               alt="{{ $proje->baslik }}"
               id="kapak-preview">
        @else
          <img id="kapak-preview" class="w-full h-40 object-cover rounded-[8px] mb-3 border border-[#E2E8F0] hidden" alt="">
        @endif
        <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-upload text-xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[11px] text-[#94A3B8]">{{ $proje->kapak_gorsel ? 'Değiştir' : 'Kapak Seç' }}</span>
          <input type="file" name="kapak_gorsel" id="kapak-input" accept="image/*" class="hidden">
        </label>
      </div>

      {{-- Durum & Sıra --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Durum & Sıra</h2>
        <div class="space-y-3 mb-4">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="aktif" value="1"
                   {{ old('aktif', $proje->aktif ?? true) ? 'checked' : '' }}
                   class="w-4 h-4 accent-[#CC2200]">
            <span class="text-[13px] font-medium text-[#0F172A]">Aktif</span>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="one_cikan" value="1"
                   {{ old('one_cikan', $proje->one_cikan) ? 'checked' : '' }}
                   class="w-4 h-4 accent-[#CC2200]">
            <span class="text-[13px] font-medium text-[#0F172A]">Öne Çıkan</span>
          </label>
        </div>
        <div>
          <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Sıra</label>
          <input type="number" name="sira" value="{{ old('sira', $proje->sira ?? 0) }}" min="0"
                 class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
        </div>
      </div>

      <button type="submit"
              class="w-full bg-[#CC2200] text-white text-[12px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#a31b00] transition-colors min-h-[52px] cursor-pointer">
        <i class="ti ti-device-floppy text-sm mr-1"></i>
        {{ $proje->exists ? 'Güncelle' : 'Kaydet' }}
      </button>

    </div>
  </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
// ─── Quill Rich Text Editor ───────────────────────────────────────────────
var quill = new Quill('#quill-editor', {
  theme: 'snow',
  placeholder: 'Proje hakkında açıklama yazın...',
  modules: {
    toolbar: [
      [{ 'header': [2, 3, false] }],
      ['bold', 'italic', 'underline'],
      [{ 'list': 'ordered' }, { 'list': 'bullet' }],
      ['blockquote'],
      ['clean']
    ]
  }
});

// Mevcut içeriği yükle
var mevcutIcerik = document.getElementById('detaylar-hidden').value;
if (mevcutIcerik) {
  quill.root.innerHTML = mevcutIcerik;
}

// Form gönderilirken HTML'i hidden input'a kopyala
document.getElementById('proje-form').addEventListener('submit', function () {
  var html = quill.root.innerHTML;
  document.getElementById('detaylar-hidden').value = (html === '<p><br></p>') ? '' : html;
});

// ─── Kapak Görseli Önizleme ──────────────────────────────────────────────
document.getElementById('kapak-input').addEventListener('change', function () {
  var file = this.files[0];
  if (!file) return;
  var preview = document.getElementById('kapak-preview');
  preview.src = URL.createObjectURL(file);
  preview.classList.remove('hidden');
});

// ─── Çoklu Görsel Yükleme ────────────────────────────────────────────────
document.getElementById('gorsel-input').addEventListener('change', function () {
  var preview = document.getElementById('yeni-gorsel-preview');
  preview.innerHTML = '';
  if (this.files.length === 0) {
    preview.classList.add('hidden');
    return;
  }
  preview.classList.remove('hidden');
  Array.from(this.files).forEach(function (file, i) {
    var div = document.createElement('div');
    div.className = 'relative group';
    var img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.className = 'w-full aspect-square object-cover rounded-[8px] border border-[#E2E8F0]';
    div.appendChild(img);
    var badge = document.createElement('span');
    badge.className = 'absolute bottom-1.5 left-1.5 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded font-medium';
    badge.textContent = 'Yeni';
    div.appendChild(badge);
    preview.appendChild(div);
  });
});

// ─── Mevcut Görsel Silme ─────────────────────────────────────────────────
function gorselSil(btn) {
  var item = btn.closest('.gorsel-item');
  var container = document.getElementById('mevcut-gorseller');
  item.remove();
  if (container.querySelectorAll('.gorsel-item').length === 0) {
    container.classList.add('hidden');
  }
}
</script>
<script>
(function () {
  var kaynak = document.getElementById('slug-kaynak');
  var hedef  = document.getElementById('slug-hedef');
  var badge  = document.getElementById('slug-mod-badge');
  var otomatik = hedef.value === '';

  function slugify(str) {
    var map = {'ğ':'g','ü':'u','ş':'s','ı':'i','ö':'o','ç':'c','Ğ':'g','Ü':'u','Ş':'s','İ':'i','Ö':'o','Ç':'c'};
    return str.replace(/[ğüşıöçĞÜŞİÖÇ]/g, function(m){ return map[m]; })
              .toLowerCase().replace(/[^a-z0-9\s-]/g, '').trim()
              .replace(/\s+/g, '-').replace(/-+/g, '-');
  }

  function setBadge(auto) {
    if (auto) {
      badge.textContent = 'Otomatik';
      badge.className = 'text-[10px] px-2 py-0.5 rounded-full bg-green-50 text-green-600 border border-green-200';
    } else {
      badge.textContent = 'Manuel';
      badge.className = 'text-[10px] px-2 py-0.5 rounded-full bg-[#F8FAFC] text-[#64748B] border border-[#E2E8F0]';
    }
  }

  setBadge(otomatik);

  kaynak.addEventListener('input', function () {
    if (otomatik) hedef.value = slugify(this.value);
  });

  hedef.addEventListener('input', function () {
    otomatik = this.value === '';
    setBadge(otomatik);
  });

  hedef.addEventListener('blur', function () {
    if (this.value) this.value = slugify(this.value);
  });
})();
</script>
@endpush
