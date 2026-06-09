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
.ql-snow .ql-picker{color:#6B6B6B;}
.ql-snow .ql-picker-label:hover,.ql-snow .ql-picker-label.ql-active{color:#0F172A;}
</style>
@endpush
@section('title', $urun->exists ? 'Ürün Düzenle' : 'Yeni Ürün')
@section('page-title', $urun->exists ? 'Ürün Düzenle' : 'Yeni Ürün')

@section('breadcrumb')
<a href="{{ route('admin.urunler.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A]">Ürünler</a>
<span class="text-[#D0D0D0] text-xs">›</span>
<span class="text-[12px] text-[#64748B]">{{ $urun->exists ? $urun->ad : 'Yeni' }}</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.urunler.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">← Geri</a>
@endsection

@section('content')
<form id="urun-formu" action="{{ $urun->exists ? route('admin.urunler.update', $urun) : route('admin.urunler.store') }}"
      method="POST" enctype="multipart/form-data">
  @csrf
  @if($urun->exists) @method('PUT') @endif

  <div class="grid lg:grid-cols-3 gap-6">

    {{-- Sol: Ana Bilgiler --}}
    <div class="lg:col-span-2 space-y-5">

      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-5">Ürün Bilgileri</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Ürün Adı <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="ad" value="{{ old('ad', $urun->ad) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                   placeholder="Ürün adını girin">
            @error('ad')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Slug <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="slug" value="{{ old('slug', $urun->slug) }}" required
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
                  <option value="{{ $val }}" {{ old('kategori', $urun->kategori) === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              @error('kategori')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Alt Kategori</label>
              <input type="text" name="alt_kategori" value="{{ old('alt_kategori', $urun->alt_kategori) }}"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                     placeholder="Opsiyonel">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Fiyat (₺) <span class="text-[#CC2200]">*</span></label>
              <input type="number" name="fiyat" value="{{ old('fiyat', $urun->fiyat) }}" required min="0" step="0.01"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
              @error('fiyat')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Eski Fiyat (₺)</label>
              <input type="number" name="eski_fiyat" value="{{ old('eski_fiyat', $urun->eski_fiyat) }}" min="0" step="0.01"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Stok Kodu</label>
              <input type="text" name="stok_kodu" value="{{ old('stok_kodu', $urun->stok_kodu) }}"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono"
                     placeholder="SKU-001">
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Stok Adedi</label>
              <input type="number" name="stok" value="{{ old('stok', $urun->stok) }}" min="0"
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

      {{-- Kapak Görseli --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Kapak Görseli</h2>
        @if($urun->gorsel)
        <div class="mb-3">
          <img src="{{ Storage::url($urun->gorsel) }}" class="w-full h-40 object-cover rounded-[8px]" alt="{{ $urun->ad }}">
        </div>
        @endif
        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-upload text-2xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[12px] text-[#94A3B8]">{{ $urun->gorsel ? 'Değiştir' : 'Görsel Seç' }}</span>
          <span class="text-[10px] text-[#C0C0C0] mt-0.5">JPG, PNG, WebP — maks. 4MB</span>
          <input type="file" name="gorsel" accept="image/*" class="hidden">
        </label>
        @error('gorsel')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- Ek Görseller --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6"
           x-data="{ silinecekler: [], secilenSayi: 0 }">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-1">Ek Görseller</h2>
        <p class="text-[11px] text-[#94A3B8] mb-4">Ürün sayfasında slider olarak gösterilir.</p>

        {{-- Mevcut ek görseller --}}
        @if($urun->gorseller && count($urun->gorseller) > 0)
        <div class="grid grid-cols-3 gap-2 mb-4">
          @foreach($urun->gorseller as $g)
          <div class="relative" x-show="!silinecekler.includes('{{ $g }}')">
            <img src="{{ Storage::url($g) }}"
                 class="w-full aspect-square object-cover rounded-[8px] border border-[#E2E8F0]" alt="">
            <button type="button"
                    @click="silinecekler.push('{{ $g }}')"
                    class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#CC2200] text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow"
                    title="Sil">
              <i class="ti ti-x text-[10px]" aria-hidden="true"></i>
            </button>
          </div>
          <input type="checkbox" name="gorsel_sil[]" value="{{ $g }}" x-model="silinecekler" class="hidden">
          @endforeach
        </div>
        @endif

        {{-- Yeni görsel yükle --}}
        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-photo-plus text-2xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[12px] text-[#94A3B8]"
                x-text="secilenSayi > 0 ? secilenSayi + ' görsel seçildi' : 'Görsel ekle (çoklu seçim)'"></span>
          <span class="text-[10px] text-[#C0C0C0] mt-0.5">JPG, PNG, WebP — maks. 4MB/adet</span>
          <input type="file" name="gorseller[]" accept="image/*" multiple class="hidden"
                 @change="secilenSayi = $event.target.files.length">
        </label>
        @error('gorseller.*')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
      </div>

      {{-- İndirilebilir Öğeler --}}
      @include('admin.components.dosya-yukleme', ['model' => $urun])

      {{-- Vergi --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Vergi (KDV)</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">KDV Oranı</label>
            <select name="kdv_orani" class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
              @foreach([0 => '%0 — KDV Yok', 1 => '%1', 10 => '%10', 18 => '%18', 20 => '%20'] as $val => $label)
                <option value="{{ $val }}" {{ (int) old('kdv_orani', $urun->kdv_orani ?? 20) === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            @error('kdv_orani')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" name="kdv_dahil" value="1"
                   {{ old('kdv_dahil', $urun->exists ? ($urun->kdv_dahil ? '1' : '') : '1') ? 'checked' : '' }}
                   class="w-4 h-4 mt-0.5 accent-[#CC2200] cursor-pointer">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">KDV fiyata dahil</span>
              <p class="text-[11px] text-[#94A3B8] leading-snug">İşaretli → girilen fiyat zaten KDV içerir (müşteriye aynı fiyat gösterilir).<br>İşaretsiz → fiyat KDV hariçtir, sepette KDV eklenerek gösterilir.</p>
            </div>
          </label>
        </div>
      </div>

      {{-- Kargo --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6"
           x-data="{ kim: '{{ old('kargo_kim_oder', $urun->kargo_kim_oder ?? 'magaza') }}' }">
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
            <input type="number" name="kargo_bedeli" min="0" step="0.01"
                   value="{{ old('kargo_bedeli', $urun->kargo_bedeli ?? 0) }}"
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                   placeholder="0.00">
            @error('kargo_bedeli')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      {{-- Durum --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Durum</h2>
        <div class="space-y-3">
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $urun->aktif ?? true) ? 'checked' : '' }}
                   class="w-4 h-4 accent-[#CC2200] cursor-pointer">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">Aktif</span>
              <p class="text-[11px] text-[#94A3B8]">Sitede görüntülensin</p>
            </div>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="one_cikan" value="1" {{ old('one_cikan', $urun->one_cikan) ? 'checked' : '' }}
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
        <i class="ti ti-device-floppy text-sm mr-1"></i>
        {{ $urun->exists ? 'Güncelle' : 'Kaydet' }}
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

  document.getElementById('urun-formu').addEventListener('submit', function () {
    var html = quill.root.innerHTML;
    document.getElementById('aciklama-hidden').value = (html === '<p><br></p>') ? '' : html;
  });
})();
</script>
@endpush


