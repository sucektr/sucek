@extends('admin.layouts.app')
@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.ql-toolbar.ql-snow{border-radius:8px 8px 0 0;border-color:rgba(0,0,0,0.12);background:#FAFAFA;}
.ql-container.ql-snow{border-radius:0 0 8px 8px;border-color:rgba(0,0,0,0.12);}
.ql-editor{min-height:140px;font-size:14px;font-family:'Inter',sans-serif;line-height:1.65;}
.ql-editor.ql-blank::before{color:#A0A0A0;font-style:normal;}
.ql-snow .ql-stroke{stroke:#6B6B6B;}
.ql-snow .ql-fill,.ql-snow .ql-stroke.ql-fill{fill:#6B6B6B;}
.ql-snow.ql-toolbar button:hover .ql-stroke,.ql-snow.ql-toolbar button.ql-active .ql-stroke{stroke:#0F172A;}
.ql-snow.ql-toolbar button:hover .ql-fill,.ql-snow.ql-toolbar button.ql-active .ql-fill{fill:#0F172A;}
.ql-snow.ql-toolbar button.ql-active,.ql-snow.ql-toolbar .ql-picker-label.ql-active,.ql-snow.ql-toolbar .ql-picker-item.ql-selected{color:#0F172A;}
</style>
@endpush

@section('title', 'Mağazaya Ekle')
@section('page-title', 'Mağazaya Ekle')

@section('breadcrumb')
<a href="{{ route('admin.uye-urunleri.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A]">Üye Ürünleri</a>
<span class="text-[#D0D0D0] text-xs">›</span>
<span class="text-[12px] text-[#64748B]">Mağazaya Ekle</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.uye-urunleri.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">← Geri</a>
@endsection

@section('content')

{{-- Kaynak Bilgi Kartı --}}
<div class="mb-6 bg-[#EFF6FF] border border-[#BFDBFE] rounded-xl p-5 flex gap-5">
  @if($kaynak->gorsel && \Illuminate\Support\Facades\Storage::disk('public')->exists($kaynak->gorsel))
  <img src="{{ Storage::url($kaynak->gorsel) }}"
       class="w-20 h-20 object-cover rounded-[10px] border border-[#BFDBFE] shrink-0" alt="{{ $kaynak->ad }}">
  @else
  <div class="w-20 h-20 rounded-[10px] bg-[#DBEAFE] flex items-center justify-center shrink-0">
    <i class="ti ti-photo text-[#93C5FD] text-3xl"></i>
  </div>
  @endif
  <div class="min-w-0">
    <div class="flex items-center gap-2 mb-1">
      <span class="text-[10px] font-semibold tracking-[.08em] uppercase text-[#3B82F6] bg-[#DBEAFE] px-2 py-0.5 rounded-full">Üye Gönderisi</span>
      <span class="text-[11px] text-[#93C5FD]">{{ $kaynak->created_at->format('d.m.Y H:i') }}</span>
    </div>
    <p class="text-[15px] font-semibold text-[#1E40AF]">{{ $kaynak->ad }}</p>
    <p class="text-[12px] text-[#3B82F6] mt-0.5">
      <i class="ti ti-user mr-1"></i>{{ $kaynak->user?->name ?? '—' }} — {{ $kaynak->user?->email ?? '' }}
    </p>
    @if($kaynak->aciklama)
    <p class="text-[12px] text-[#1D4ED8] mt-2 leading-relaxed line-clamp-2">{{ Str::limit(strip_tags($kaynak->aciklama), 150) }}</p>
    @endif
  </div>
</div>

<form id="donustur-formu" action="{{ route('admin.uye-urunleri.kaydet', $kaynak->id) }}"
      method="POST" enctype="multipart/form-data">
  @csrf

  <div class="grid lg:grid-cols-3 gap-6">

    {{-- Sol: Ana Bilgiler --}}
    <div class="lg:col-span-2 space-y-5">

      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-5">Ürün Bilgileri</h2>
        <div class="space-y-4">

          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Ürün Adı <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="ad" id="ad-input" value="{{ old('ad', $urun->ad) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                   placeholder="Ürün adını girin">
            @error('ad')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>

          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Slug <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="slug" id="slug-input" value="{{ old('slug') }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono"
                   placeholder="url-uyumlu-slug">
            @error('slug')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Kategori <span class="text-[#CC2200]">*</span></label>
              <select name="kategori" required class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
                <option value="">Seçin</option>
                @foreach(['spor' => 'Spor', 'dekorasyon' => 'Dekorasyon', 'insaat' => 'İnşaat', 'diger' => 'Diğer'] as $val => $label)
                  <option value="{{ $val }}" {{ old('kategori') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              @error('kategori')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Alt Kategori</label>
              <input type="text" name="alt_kategori" value="{{ old('alt_kategori') }}"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                     placeholder="Opsiyonel">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Fiyat (₺) <span class="text-[#CC2200]">*</span></label>
              <input type="number" name="fiyat" value="{{ old('fiyat') }}" required min="0" step="0.01"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
              @error('fiyat')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Eski Fiyat (₺)</label>
              <input type="number" name="eski_fiyat" value="{{ old('eski_fiyat') }}" min="0" step="0.01"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Stok Kodu</label>
              <input type="text" name="stok_kodu" value="{{ old('stok_kodu') }}"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono"
                     placeholder="SKU-001">
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Stok Adedi</label>
              <input type="number" name="stok" value="{{ old('stok') }}" min="0"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Açıklama / Bilgi</label>
            <div id="aciklama-editor"></div>
            <input type="hidden" name="aciklama" id="aciklama-hidden">
          </div>

        </div>
      </div>

    </div>

    {{-- Sağ: Görsel + Durum --}}
    <div class="space-y-5">

      {{-- Görsel --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6"
           x-data="{ kullanKaynak: {{ $kaynak->gorsel ? 'true' : 'false' }}, yeniSecildi: false }">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Kapak Görseli</h2>

        @if($kaynak->gorsel && \Illuminate\Support\Facades\Storage::disk('public')->exists($kaynak->gorsel))
        {{-- Üyenin görseli --}}
        <div class="mb-3 rounded-[8px] overflow-hidden border border-[#E2E8F0]" x-show="kullanKaynak && !yeniSecildi">
          <img src="{{ Storage::url($kaynak->gorsel) }}" class="w-full h-40 object-cover" alt="{{ $kaynak->ad }}">
        </div>
        <label class="flex items-center gap-2 mb-4 cursor-pointer select-none" x-show="!yeniSecildi">
          <input type="checkbox" name="kaynak_gorsel_kullan" value="1" x-model="kullanKaynak"
                 class="w-4 h-4 accent-[#0F172A] cursor-pointer">
          <span class="text-[12px] text-[#334155] font-medium">Üyenin gönderdiği görseli kullan</span>
        </label>
        @endif

        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-upload text-2xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[12px] text-[#94A3B8]" x-text="yeniSecildi ? 'Görsel seçildi ✓' : 'Farklı görsel yükle'"></span>
          <span class="text-[10px] text-[#C0C0C0] mt-0.5">JPG, PNG, WebP — maks. 4MB</span>
          <input type="file" name="gorsel" accept="image/*" class="hidden"
                 @change="yeniSecildi = $event.target.files.length > 0; if(yeniSecildi) kullanKaynak = false">
        </label>
        @error('gorsel')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- KDV --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Vergi (KDV)</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">KDV Oranı</label>
            <select name="kdv_orani" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
              @foreach([0 => '%0 — KDV Yok', 1 => '%1', 10 => '%10', 18 => '%18', 20 => '%20'] as $val => $label)
                <option value="{{ $val }}" {{ (int) old('kdv_orani', 20) === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="kdv_dahil" value="1" checked
                   class="w-4 h-4 mt-0.5 accent-[#CC2200] cursor-pointer">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">KDV fiyata dahil</span>
              <p class="text-[11px] text-[#94A3B8] leading-snug">İşaretli → girilen fiyat zaten KDV içerir.</p>
            </div>
          </label>
        </div>
      </div>

      {{-- Kargo --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6"
           x-data="{ kim: '{{ old('kargo_kim_oder', 'magaza') }}' }">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Kargo</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Kargo Kim Öder?</label>
            <select name="kargo_kim_oder" x-model="kim"
                    class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
              <option value="magaza">Mağaza Karşılar (müşteriye ücretsiz)</option>
              <option value="satici">Satıcı Karşılar (müşteriye ücretsiz)</option>
              <option value="musteri">Müşteri Öder</option>
            </select>
          </div>
          <div x-show="kim === 'musteri'" x-transition>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Kargo Bedeli (₺)</label>
            <input type="number" name="kargo_bedeli" min="0" step="0.01" value="{{ old('kargo_bedeli', 0) }}"
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                   placeholder="0.00">
          </div>
        </div>
      </div>

      {{-- Durum --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Durum</h2>
        <div class="space-y-3">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="aktif" value="1" checked
                   class="w-4 h-4 accent-[#CC2200] cursor-pointer">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">Aktif</span>
              <p class="text-[11px] text-[#94A3B8]">Sitede görüntülensin</p>
            </div>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="one_cikan" value="1"
                   class="w-4 h-4 accent-[#CC2200] cursor-pointer">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">Öne Çıkan</span>
              <p class="text-[11px] text-[#94A3B8]">Ana sayfada göster</p>
            </div>
          </label>
        </div>
      </div>

      {{-- Kaydet --}}
      <button type="submit"
              class="w-full bg-[#CC2200] text-white text-[12px] font-semibold tracking-[1.5px] uppercase py-4 rounded-[10px] hover:bg-[#a31b00] transition-colors min-h-[52px]">
        <i class="ti ti-plus text-sm mr-1"></i>
        Mağazaya Ekle
      </button>
    </div>

  </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
(function () {
  var quill = new Quill('#aciklama-editor', {
    theme: 'snow',
    placeholder: 'Ürün açıklaması...',
    modules: {
      toolbar: [
        [{ size: ['small', false, 'large', 'huge'] }],
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['clean']
      ]
    }
  });

  var existing = {!! json_encode(old('aciklama', $urun->aciklama)) !!};
  if (existing) { quill.root.innerHTML = existing; }

  document.getElementById('donustur-formu').addEventListener('submit', function () {
    var html = quill.root.innerHTML;
    document.getElementById('aciklama-hidden').value = (html === '<p><br></p>') ? '' : html;
  });

  // Slug otomatik üret (ad alanından)
  var adInput   = document.getElementById('ad-input');
  var slugInput = document.getElementById('slug-input');
  var slugEdited = slugInput.value.length > 0;

  function toSlug(str) {
    return str.toLowerCase()
      .replace(/ğ/g,'g').replace(/ü/g,'u').replace(/ş/g,'s')
      .replace(/ı/g,'i').replace(/ö/g,'o').replace(/ç/g,'c')
      .replace(/[^a-z0-9\s-]/g,'').trim()
      .replace(/[\s]+/g,'-').replace(/-+/g,'-');
  }

  if (adInput && slugInput) {
    adInput.addEventListener('input', function () {
      if (!slugEdited) { slugInput.value = toSlug(this.value); }
    });
    slugInput.addEventListener('input', function () {
      slugEdited = this.value.length > 0;
    });
  }
})();
</script>
@endpush
