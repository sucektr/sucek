@extends('admin.layouts.app')
@section('title', 'Projeler')
@section('page-title', 'Projeler')

@section('header-actions')
<a href="{{ route('admin.projeler.create') }}"
   class="flex items-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1px] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
  <i class="ti ti-plus text-sm"></i> Yeni Proje
</a>
@endsection

@section('content')
<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Görsel</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Başlık</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Kategori</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Konum / Yıl</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Durum</th>
          <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
        @forelse($projeler as $p)
        <tr class="hover:bg-[#F8FAFC] transition-colors">
          <td class="px-5 py-3">
            @if($p->kapak_gorsel)
              <img src="{{ Storage::url($p->kapak_gorsel) }}" alt="{{ $p->baslik }}" class="w-12 h-12 object-cover rounded-[6px]">
            @else
              <div class="w-12 h-12 rounded-[6px] bg-[#F1F5F9] flex items-center justify-center">
                <i class="ti ti-building-arch text-[#C0C0C0] text-lg"></i>
              </div>
            @endif
          </td>
          <td class="px-5 py-3">
            <p class="font-medium text-[#0F172A] text-[13px]">{{ $p->baslik }}</p>
            @if($p->alt_kategori)
            <p class="text-[11px] text-[#94A3B8]">
              {{ $p->alt_kategori === 'ruhsat' ? 'Ruhsat Süreci' : ($p->alt_kategori === 'ic-mimari' ? 'İç Mimari' : $p->alt_kategori) }}
            </p>
            @endif
          </td>
          <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $p->kategori === 'mimarlik' ? 'Mimarlık' : ($p->kategori === 'insaat' ? 'İnşaat' : ucfirst($p->kategori)) }}</td>
          <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $p->konum }}{{ $p->yil ? ' / '.$p->yil : '' }}</td>
          <td class="px-5 py-3">
            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $p->aktif ? 'bg-[#E6F4EC] text-[#1A5C3A]' : 'bg-[#F8FAFC] text-[#94A3B8]' }}">
              {{ $p->aktif ? 'Aktif' : 'Pasif' }}
            </span>
            @if($p->one_cikan)
            <span class="inline-flex ml-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#FEF3C7] text-[#D97706]">Öne Çıkan</span>
            @endif
          </td>
          <td class="px-5 py-3 text-right">
            <div class="flex items-center justify-end gap-1">
              <a href="{{ route('admin.projeler.edit', $p) }}"
                 class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors">
                <i class="ti ti-edit text-sm"></i>
              </a>
              <form action="{{ route('admin.projeler.destroy', $p) }}" method="POST"
                    onsubmit="return confirm('Bu projeyi silmek istediğinize emin misiniz?')">
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
            <i class="ti ti-building-arch text-3xl text-[#D0D0D0] block mb-2"></i>
            Henüz proje eklenmedi.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($projeler->hasPages())
  <div class="px-5 py-4 border-t border-[#E2E8F0]">{{ $projeler->links() }}</div>
  @endif
</div>
@endsection


