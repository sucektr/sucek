@extends('admin.layouts.app')
@section('title', 'Belgeler')
@section('page-title', 'Belgeler')

@section('header-actions')
<a href="{{ route('admin.belgeler.create') }}"
   class="flex items-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1px] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
  <i class="ti ti-plus text-sm"></i> Yeni Belge
</a>
@endsection

@section('content')
<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Tür</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Başlık</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Kategori</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Boyut</th>
          <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Durum</th>
          <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
        @forelse($belgeler as $b)
        @php
          $ikonlar = ['pdf' => ['ti-file-type-pdf', '#DC2626'], 'doc' => ['ti-file-type-doc', '#1a4a6b'], 'docx' => ['ti-file-type-doc', '#1a4a6b'], 'xls' => ['ti-file-type-xls', '#1a5c3a'], 'xlsx' => ['ti-file-type-xls', '#1a5c3a']];
          [$ikon, $renk] = $ikonlar[$b->dosya_turu] ?? ['ti-file', '#A0A0A0'];
        @endphp
        <tr class="hover:bg-[#F8FAFC] transition-colors">
          <td class="px-5 py-3">
            <div class="w-10 h-10 rounded-[6px] bg-[#F8FAFC] flex items-center justify-center">
              <i class="ti {{ $ikon }} text-xl" style="color:{{ $renk }}"></i>
            </div>
          </td>
          <td class="px-5 py-3">
            <p class="font-medium text-[#0F172A] text-[13px]">{{ $b->baslik }}</p>
            <p class="text-[11px] text-[#94A3B8] uppercase">{{ $b->dosya_turu }}</p>
          </td>
          <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $b->kategori }}</td>
          <td class="px-5 py-3 text-[12px] text-[#94A3B8] font-mono">
            {{ $b->dosya_boyutu ? number_format($b->dosya_boyutu / 1024, 0) . ' KB' : '—' }}
          </td>
          <td class="px-5 py-3">
            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $b->aktif ? 'bg-[#E6F4EC] text-[#1A5C3A]' : 'bg-[#F8FAFC] text-[#94A3B8]' }}">
              {{ $b->aktif ? 'Aktif' : 'Pasif' }}
            </span>
            @if($b->herkese_acik)
            <span class="inline-flex ml-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#e8f1f7] text-[#1a4a6b]">Herkese Açık</span>
            @endif
          </td>
          <td class="px-5 py-3 text-right">
            <div class="flex items-center justify-end gap-1">
              @if($b->dosya_yolu)
              <a href="{{ Storage::url($b->dosya_yolu) }}" target="_blank"
                 class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors">
                <i class="ti ti-download text-sm"></i>
              </a>
              @endif
              <a href="{{ route('admin.belgeler.edit', $b) }}"
                 class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors">
                <i class="ti ti-edit text-sm"></i>
              </a>
              <form action="{{ route('admin.belgeler.destroy', $b) }}" method="POST"
                    onsubmit="return confirm('Bu belgeyi silmek istediğinize emin misiniz?')">
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
            <i class="ti ti-files text-3xl text-[#D0D0D0] block mb-2"></i>
            Henüz belge eklenmedi.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($belgeler->hasPages())
  <div class="px-5 py-4 border-t border-[#E2E8F0]">{{ $belgeler->links() }}</div>
  @endif
</div>
@endsection


