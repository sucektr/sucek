@extends('admin.layouts.app')
@section('title', $koleksiyon->exists ? 'Koleksiyon Düzenle' : 'Yeni Koleksiyon')
@section('page-title', $koleksiyon->exists ? 'Koleksiyon Düzenle' : 'Yeni Koleksiyon')

@section('breadcrumb')
<a href="{{ route('admin.koleksiyonlar.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A]">Koleksiyonlar</a>
<span class="text-[#D0D0D0] text-xs">›</span>
<span class="text-[12px] text-[#64748B]">{{ $koleksiyon->exists ? $koleksiyon->ad : 'Yeni' }}</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.koleksiyonlar.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">← Geri</a>
@endsection

@section('content')
<form action="{{ $koleksiyon->exists ? route('admin.koleksiyonlar.update', $koleksiyon) : route('admin.koleksiyonlar.store') }}"
      method="POST" enctype="multipart/form-data">
  @csrf
  @if($koleksiyon->exists) @method('PUT') @endif

  <div class="grid lg:grid-cols-3 gap-6">

    {{-- Sol: Bilgiler --}}
    <div class="lg:col-span-2 space-y-5">
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-5">Koleksiyon Bilgileri</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Ad <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="ad" value="{{ old('ad', $koleksiyon->ad) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
            @error('ad')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Slug <span class="text-[#CC2200]">*</span></label>
            <input type="text" name="slug" value="{{ old('slug', $koleksiyon->slug) }}" required
                   class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono">
            @error('slug')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Kategori <span class="text-[#CC2200]">*</span></label>
              <select name="kategori" required class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
                <option value="">Seçin</option>
                @foreach(['saat' => 'Saat', 'numizmatik' => 'Nümizmatik', 'antika' => 'Antika', 'diger' => 'Diğer'] as $val => $lbl)
                  <option value="{{ $val }}" {{ old('kategori', $koleksiyon->kategori) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
              @error('kategori')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Satış Durumu <span class="text-[#CC2200]">*</span></label>
              <select name="durum" required class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] bg-white">
                @foreach(['satista' => 'Satışta', 'satildi' => 'Satıldı', 'rezerve' => 'Rezerve'] as $val => $lbl)
                  <option value="{{ $val }}" {{ old('durum', $koleksiyon->durum ?? 'satista') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Fiyat (₺)</label>
              <input type="number" name="fiyat" value="{{ old('fiyat', $koleksiyon->fiyat) }}" min="0" step="0.01"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
                     placeholder="Boş → Teklif butonu gösterilir">
              <p class="text-[11px] text-[#94A3B8] mt-1">Boş bırakırsanız ürün sayfasında "Teklif Ver" butonu görünür.</p>
              @error('fiyat')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Stok Kodu</label>
              <input type="text" name="stok_kodu" value="{{ old('stok_kodu', $koleksiyon->stok_kodu) }}"
                     class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono">
            </div>
          </div>
          <div>
            <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Açıklama</label>
            <textarea name="aciklama" rows="4"
                      class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors resize-none"
                      placeholder="Ürün hakkında açıklama...">{{ old('aciklama', $koleksiyon->aciklama) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- Sağ: Görsel + Ek Görseller + Durum --}}
    <div class="space-y-5">

      {{-- Kapak Görseli --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
        <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Kapak Görseli</h2>
        @if($koleksiyon->gorsel)
          <img src="{{ Storage::url($koleksiyon->gorsel) }}" class="w-full h-40 object-cover rounded-[8px] mb-3" alt="{{ $koleksiyon->ad }}">
        @endif
        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-upload text-2xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[12px] text-[#94A3B8]">{{ $koleksiyon->gorsel ? 'Değiştir' : 'Görsel Seç' }}</span>
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

        @if($koleksiyon->gorseller && count($koleksiyon->gorseller) > 0)
        <div class="grid grid-cols-3 gap-2 mb-4">
          @foreach($koleksiyon->gorseller as $g)
          <div class="relative" x-show="!silinecekler.includes('{{ $g }}')">
            <img src="{{ Storage::url($g) }}"
                 class="w-full aspect-square object-cover rounded-[8px] border border-[#E2E8F0]" alt="">
            <button type="button"
                    @click="silinecekler.push('{{ $g }}')"
                    class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#CC2200] text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow"
                    title="Sil">
              <i class="ti ti-x text-[10px]"></i>
            </button>
          </div>
          <input type="checkbox" name="gorsel_sil[]" value="{{ $g }}" x-model="silinecekler" class="hidden">
          @endforeach
        </div>
        @endif

        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]">
          <i class="ti ti-photo-plus text-2xl text-[#C0C0C0] mb-1"></i>
          <span class="text-[12px] text-[#94A3B8]"
                x-text="secilenSayi > 0 ? secilenSayi + ' görsel seçildi' : 'Görsel ekle (çoklu seçim)'"></span>
          <span class="text-[10px] text-[#C0C0C0] mt-0.5">JPG, PNG, WebP — maks. 4MB/adet</span>
          <input type="file" name="gorseller[]" accept="image/*" multiple class="hidden"
                 @change="secilenSayi = $event.target.files.length">
        </label>
      </div>

      {{-- İndirilebilir Öğeler --}}
      @include('admin.components.dosya-yukleme', ['model' => $koleksiyon])

      {{-- Kargo --}}
      <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6"
           x-data="{ kim: '{{ old('kargo_kim_oder', $koleksiyon->kargo_kim_oder ?? 'magaza') }}' }">
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
                   value="{{ old('kargo_bedeli', $koleksiyon->kargo_bedeli ?? 0) }}"
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
            <input type="checkbox" name="aktif" value="1" {{ old('aktif', $koleksiyon->aktif ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-[#CC2200]">
            <div>
              <span class="text-[13px] font-medium text-[#0F172A]">Aktif</span>
              <p class="text-[11px] text-[#94A3B8]">Sitede görüntülensin</p>
            </div>
          </label>
          <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="one_cikan" value="1" {{ old('one_cikan', $koleksiyon->one_cikan) ? 'checked' : '' }} class="w-4 h-4 accent-[#CC2200]">
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
        {{ $koleksiyon->exists ? 'Güncelle' : 'Kaydet' }}
      </button>
    </div>

  </div>
</form>
@endsection


