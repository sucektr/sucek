@extends('admin.layouts.app')
@section('title', 'Ürünler')
@section('page-title', 'Ürünler')

@section('header-actions')
<a href="{{ route('admin.urunler.create') }}"
   class="flex items-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1px] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
  <i class="ti ti-plus text-sm"></i> Yeni Ürün
</a>
@endsection

@section('content')

{{-- Filtre çubuğu --}}
<form method="GET" action="{{ route('admin.urunler.index') }}"
      class="flex flex-wrap items-center gap-2.5 mb-5">
  <div class="relative flex-1 min-w-[200px]">
    <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-[#B0B0B0] text-[15px] pointer-events-none"></i>
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="Ürün adı veya stok kodu..."
           class="w-full pl-9 pr-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
  </div>
  <select name="kategori"
          class="px-3 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
    <option value="">Tüm Kategoriler</option>
    @foreach(['spor' => 'Spor', 'dekorasyon' => 'Dekorasyon', 'insaat' => 'İnşaat', 'diger' => 'Diğer'] as $val => $lbl)
      <option value="{{ $val }}" {{ request('kategori') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
    @endforeach
  </select>
  <select name="durum"
          class="px-3 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors bg-white">
    <option value="">Tüm Durumlar</option>
    <option value="aktif"  {{ request('durum') === 'aktif'  ? 'selected' : '' }}>Aktif</option>
    <option value="pasif"  {{ request('durum') === 'pasif'  ? 'selected' : '' }}>Pasif</option>
  </select>
  <button type="submit"
          class="flex items-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[.08em] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
    <i class="ti ti-filter text-sm"></i> Filtrele
  </button>
  @if(request()->hasAny(['q','kategori','durum']))
  <a href="{{ route('admin.urunler.index') }}"
     class="text-[12px] text-[#94A3B8] hover:text-[#0F172A] transition-colors px-1">Temizle ×</a>
  @endif
</form>

<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Görsel</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ürün Adı</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Kategori</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Fiyat</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Stok</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Durum</th>
          <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
        @forelse($urunler as $urun)
        <tr class="hover:bg-[#F8FAFC] transition-colors">
          <td class="px-5 py-3">
            @if($urun->gorsel)
              <img src="{{ Storage::url($urun->gorsel) }}" alt="{{ $urun->ad }}" class="w-12 h-12 object-cover rounded-[6px]">
            @else
              <div class="w-12 h-12 rounded-[6px] bg-[#F1F5F9] flex items-center justify-center">
                <i class="ti ti-photo text-[#C0C0C0] text-lg"></i>
              </div>
            @endif
          </td>
          <td class="px-5 py-3">
            <p class="font-medium text-[#0F172A] text-[13px]">{{ $urun->ad }}</p>
            <p class="text-[11px] text-[#94A3B8]">{{ $urun->stok_kodu }}</p>
          </td>
          <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ ucfirst($urun->kategori) }}</td>
          <td class="px-5 py-3 text-[13px] font-medium text-[#0F172A]">{{ number_format($urun->fiyat, 2, ',', '.') }} ₺</td>
          <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $urun->stok ?? '—' }}</td>
          <td class="px-5 py-3">
            <div class="flex items-center gap-1.5">
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $urun->aktif ? 'bg-[#E6F4EC] text-[#1A5C3A]' : 'bg-[#F8FAFC] text-[#94A3B8]' }}">
                {{ $urun->aktif ? 'Aktif' : 'Pasif' }}
              </span>
              @if($urun->one_cikan)
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#FEF3C7] text-[#D97706]">Öne Çıkan</span>
              @endif
            </div>
          </td>
          <td class="px-5 py-3 text-right">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('admin.urunler.edit', $urun) }}"
                 class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors">
                <i class="ti ti-edit text-sm"></i>
              </a>
              <form action="{{ route('admin.urunler.destroy', $urun) }}" method="POST"
                    onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-transparent hover:bg-[#FEE2E2] hover:text-[#DC2626] text-[#C0C0C0] transition-colors">
                  <i class="ti ti-trash text-sm"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-5 py-16 text-center text-[13px] text-[#94A3B8]">
            <i class="ti ti-shopping-bag text-3xl text-[#D0D0D0] block mb-2"></i>
            Henüz ürün eklenmedi.
            <a href="{{ route('admin.urunler.create') }}" class="text-[#0F172A] underline ml-1">Ekleyin</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($urunler->hasPages())
  <div class="px-5 py-4 border-t border-[#E2E8F0]">{{ $urunler->links() }}</div>
  @endif
</div>
@endsection


