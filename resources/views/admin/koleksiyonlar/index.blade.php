@extends('admin.layouts.app')
@section('title', 'Koleksiyonlar')
@section('page-title', 'Koleksiyonlar')

@section('header-actions')
<a href="{{ route('admin.koleksiyonlar.create') }}"
   class="flex items-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1px] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
  <i class="ti ti-plus text-sm"></i> Yeni Koleksiyon
</a>
@endsection

@section('content')

{{-- Filtre çubuğu --}}
<form method="GET" action="{{ route('admin.koleksiyonlar.index') }}"
      class="flex flex-wrap items-center gap-2.5 mb-5">
  <div class="relative flex-1 min-w-[200px]">
    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-[#B0B0B0] text-[15px] pointer-events-none"></i>
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="Koleksiyon adı veya stok kodu..."
           class="w-full pl-9 pr-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
  </div>
  <select name="kategori"
          class="px-3 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
    <option value="">Tüm Kategoriler</option>
    @foreach(['antika' => 'Antika', 'saat' => 'Saat', 'numizmatik' => 'Nümizmatik', 'diger' => 'Diğer'] as $val => $lbl)
      <option value="{{ $val }}" {{ request('kategori') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
    @endforeach
  </select>
  <select name="durum"
          class="px-3 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
    <option value="">Tüm Durumlar</option>
    <option value="satista" {{ request('durum') === 'satista' ? 'selected' : '' }}>Satışta</option>
    <option value="satildi" {{ request('durum') === 'satildi' ? 'selected' : '' }}>Satıldı</option>
    <option value="rezerve" {{ request('durum') === 'rezerve' ? 'selected' : '' }}>Rezerve</option>
  </select>
  <button type="submit"
          class="flex items-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[.08em] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
    <i class="ti ti-filter text-sm"></i> Filtrele
  </button>
  @if(request()->hasAny(['q','kategori','durum']))
  <a href="{{ route('admin.koleksiyonlar.index') }}"
     class="text-[12px] text-[#94A3B8] hover:text-[#0F172A] transition-colors px-1">Temizle ×</a>
  @endif
</form>

{{-- Toplam bedel özeti --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
  @php
  $ozetKartlar = [
    ['label' => 'Toplam Ürün', 'value' => number_format($stats['adet'], 0, ',', '.'), 'icon' => 'ti-diamond', 'color' => '#3d2d7a', 'bg' => '#eeecfa'],
    ['label' => 'Toplam Değer', 'value' => number_format($stats['toplam_deger'], 2, ',', '.').' ₺', 'icon' => 'ti-sum', 'color' => '#0F172A', 'bg' => '#F1F5F9'],
    ['label' => 'Satıştaki Değer', 'value' => number_format($stats['satista_deger'], 2, ',', '.').' ₺', 'icon' => 'ti-tag', 'color' => '#1A5C3A', 'bg' => '#E6F4EC'],
    ['label' => 'Satılan Değer', 'value' => number_format($stats['satildi_deger'], 2, ',', '.').' ₺', 'icon' => 'ti-circle-check', 'color' => '#64748B', 'bg' => '#F8FAFC'],
  ];
  @endphp
  @foreach($ozetKartlar as $kart)
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5">
    <div class="w-9 h-9 rounded-[8px] flex items-center justify-center mb-3"
         style="background:{{ $kart['bg'] }}">
      <i class="ti {{ $kart['icon'] }}" style="color:{{ $kart['color'] }};font-size:18px;"></i>
    </div>
    <div class="text-[20px] font-semibold text-[#0F172A] leading-none mb-1">{{ $kart['value'] }}</div>
    <div class="text-[11px] font-medium text-[#94A3B8] uppercase tracking-[.05em]">{{ $kart['label'] }}</div>
  </div>
  @endforeach
</div>

<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Görsel</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ad</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Kategori</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Fiyat</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Durum</th>
          <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
        @forelse($koleksiyonlar as $k)
        <tr class="hover:bg-[#F8FAFC] transition-colors">
          <td class="px-5 py-3">
            @if($k->gorsel)
              <img src="{{ Storage::url($k->gorsel) }}" alt="{{ $k->ad }}" class="w-12 h-12 object-cover rounded-[6px]">
            @else
              <div class="w-12 h-12 rounded-[6px] bg-[#F1F5F9] flex items-center justify-center">
                <i class="ti ti-diamond text-[#C0C0C0] text-lg"></i>
              </div>
            @endif
          </td>
          <td class="px-5 py-3">
            <p class="font-medium text-[#0F172A] text-[13px]">{{ $k->ad }}</p>
            <p class="text-[11px] text-[#94A3B8]">{{ $k->stok_kodu }}</p>
            @if($k->kategori === 'numizmatik' && $k->para_turu)
            <span class="inline-flex items-center gap-1 text-[10px] text-[#94A3B8] mt-1">
              <i class="ti ti-{{ $k->para_turu === 'madeni' ? 'coin' : 'cash' }} text-[11px]"></i>
              {{ $k->para_turu === 'madeni' ? 'Madeni Para' : 'Kağıt Para' }}
            </span>
            @endif
          </td>
          <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ ucfirst($k->kategori) }}</td>
          <td class="px-5 py-3 text-[13px] font-medium text-[#0F172A]"
              x-data="{
                duzenleniyor: false,
                kaydediliyor: false,
                deger: {{ $k->fiyat ?? 0 }},
                duzenle() {
                  this.duzenleniyor = true;
                  this.$nextTick(() => this.$refs.fiyatInput.focus());
                },
                kaydet() {
                  if (!this.duzenleniyor) return;
                  this.kaydediliyor = true;
                  fetch('{{ route('admin.koleksiyonlar.fiyat', $k) }}', {
                    method: 'PATCH',
                    headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                      'Accept': 'application/json',
                    },
                    body: JSON.stringify({ fiyat: this.deger }),
                  })
                  .then(r => r.json())
                  .then(data => {
                    if (data.success) { window.location.reload(); }
                    else { this.kaydediliyor = false; }
                  })
                  .catch(() => { this.kaydediliyor = false; });
                },
              }">
            <span x-show="!duzenleniyor" @click="duzenle()" class="cursor-pointer border-b border-dotted border-transparent hover:border-[#CC2200] hover:text-[#CC2200] transition-colors"
                  title="Düzenlemek için tıklayın"
                  x-text="Number(deger || 0).toLocaleString('tr-TR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' ₺'"></span>
            <span x-show="duzenleniyor" x-cloak class="inline-flex items-center gap-1.5">
              <input type="number" x-ref="fiyatInput" x-model="deger" step="0.01" min="0"
                     :disabled="kaydediliyor"
                     @keydown.enter="kaydet()" @keydown.escape="duzenleniyor = false" @blur="kaydet()"
                     class="w-24 px-2 py-1 border border-[#CC2200] rounded-[6px] text-[13px] focus:outline-none focus:ring-2 focus:ring-[rgba(204,34,0,0.12)]">
              <i class="ti ti-loader-2 animate-spin text-sm text-[#94A3B8]" x-show="kaydediliyor"></i>
            </span>
          </td>
          <td class="px-5 py-3">
            <div class="flex items-center gap-1.5 flex-wrap">
              @php
                $durumRenk = ['satista' => 'bg-[#E6F4EC] text-[#1A5C3A]', 'satildi' => 'bg-[#F8FAFC] text-[#94A3B8]', 'rezerve' => 'bg-[#FEF3C7] text-[#D97706]'];
                $durumLabel = ['satista' => 'Satışta', 'satildi' => 'Satıldı', 'rezerve' => 'Rezerve'];
              @endphp
              <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $durumRenk[$k->durum] ?? '' }}">
                {{ $durumLabel[$k->durum] ?? $k->durum }}
              </span>
              @if($k->one_cikan)
              <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#eeecfa] text-[#3d2d7a]">Öne Çıkan</span>
              @endif
            </div>
          </td>
          <td class="px-5 py-3 text-right">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('admin.koleksiyonlar.edit', $k) }}"
                 class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors">
                <i class="ti ti-edit text-sm"></i>
              </a>
              <form action="{{ route('admin.koleksiyonlar.destroy', $k) }}" method="POST"
                    onsubmit="return confirm('Bu koleksiyonu silmek istediğinize emin misiniz?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-transparent hover:bg-[#FEE2E2] hover:text-[#DC2626] text-[#C0C0C0] transition-colors">
                  <i class="ti ti-trash text-sm"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-5 py-16 text-center text-[13px] text-[#94A3B8]">
            <i class="ti ti-diamond text-3xl text-[#D0D0D0] block mb-2"></i>
            Henüz koleksiyon eklenmedi.
            <a href="{{ route('admin.koleksiyonlar.create') }}" class="text-[#0F172A] underline ml-1">Ekleyin</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($koleksiyonlar->hasPages())
  <div class="px-5 py-4 border-t border-[#E2E8F0]">{{ $koleksiyonlar->links() }}</div>
  @endif
</div>
@endsection


