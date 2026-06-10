@extends('admin.layouts.app')
@section('title', 'Toplu Fiyat Güncelle')
@section('page-title', 'Toplu Fiyat Güncelle')

@section('header-actions')
<a href="{{ route('admin.fiyat-guncelle.indir') }}"
   class="flex items-center gap-1.5 bg-[#0F172A] text-white text-[11px] font-semibold tracking-[1px] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#1e293b] transition-colors">
  <i class="ti ti-table-down text-sm"></i> Excel'i İndir
</a>
@endsection

@section('content')

{{-- Bilgi kutusu --}}
<div class="bg-[#EFF6FF] border border-[#BFDBFE] rounded-[10px] p-4 mb-6 flex gap-3">
  <i class="ti ti-info-circle text-[#3B82F6] text-[18px] shrink-0 mt-0.5"></i>
  <div class="text-[13px] text-[#1E40AF] leading-relaxed">
    <strong>Nasıl kullanılır?</strong><br>
    1. Sağ üstteki <strong>"Excel'i İndir"</strong> butonuna tıklayın — mevcut tüm fiyatları içeren dosyayı alın.<br>
    2. <strong>Sarı sütunları</strong> (Fiyat, Eski Fiyat) düzenleyin. ID ve ürün adı sütunlarına dokunmayın.<br>
    3. Kaydedin ve aşağıdan dosyayı yükleyin. Yalnızca değişen satırlar güncellenir.
  </div>
</div>

{{-- Sonuçlar --}}
@if(session('guncellenenler') !== null)
  @php $guncellenenler = session('guncellenenler'); $hatalar = session('hatalar', []); @endphp
  <div class="mb-6">
    @if(count($guncellenenler) > 0)
    <div class="bg-[#F0FDF4] border border-[#BBF7D0] rounded-[10px] p-4 mb-3 flex items-center gap-2">
      <i class="ti ti-circle-check text-[#16A34A] text-[18px]"></i>
      <span class="text-[13px] text-[#15803D] font-semibold">{{ count($guncellenenler) }} ürünün fiyatı güncellendi.</span>
    </div>
    <div class="bg-white border border-[#E2E8F0] rounded-[10px] overflow-hidden">
      <table class="w-full text-[12px]">
        <thead>
          <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
            <th class="text-left px-4 py-3 font-semibold text-[#64748B] uppercase tracking-[.06em]">ID</th>
            <th class="text-left px-4 py-3 font-semibold text-[#64748B] uppercase tracking-[.06em]">Ürün Adı</th>
            <th class="text-right px-4 py-3 font-semibold text-[#64748B] uppercase tracking-[.06em]">Fiyat (Eski)</th>
            <th class="text-right px-4 py-3 font-semibold text-[#64748B] uppercase tracking-[.06em]">Fiyat (Yeni)</th>
            <th class="text-right px-4 py-3 font-semibold text-[#64748B] uppercase tracking-[.06em]">Eski Fiyat (Eski)</th>
            <th class="text-right px-4 py-3 font-semibold text-[#64748B] uppercase tracking-[.06em]">Eski Fiyat (Yeni)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#F1F5F9]">
          @foreach($guncellenenler as $g)
          <tr class="hover:bg-[#FAFAFA]">
            <td class="px-4 py-2.5 text-[#94A3B8]">{{ $g['id'] }}</td>
            <td class="px-4 py-2.5 font-medium text-[#0F172A]">{{ $g['ad'] }}</td>
            <td class="px-4 py-2.5 text-right text-[#94A3B8] line-through">
              {{ $g['fiyat_eski'] ? number_format($g['fiyat_eski'], 2, ',', '.') . ' ₺' : '—' }}
            </td>
            <td class="px-4 py-2.5 text-right font-semibold text-[#16A34A]">
              {{ $g['fiyat_yeni'] ? number_format($g['fiyat_yeni'], 2, ',', '.') . ' ₺' : '—' }}
            </td>
            <td class="px-4 py-2.5 text-right text-[#94A3B8] line-through">
              {{ $g['eski_fiyat_eski'] ? number_format($g['eski_fiyat_eski'], 2, ',', '.') . ' ₺' : '—' }}
            </td>
            <td class="px-4 py-2.5 text-right text-[#0F172A]">
              {{ $g['eski_fiyat_yeni'] ? number_format($g['eski_fiyat_yeni'], 2, ',', '.') . ' ₺' : '—' }}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="bg-[#FFFBEB] border border-[#FDE68A] rounded-[10px] p-4 flex items-center gap-2">
      <i class="ti ti-alert-triangle text-[#D97706] text-[18px]"></i>
      <span class="text-[13px] text-[#92400E]">Dosyada hiçbir fiyat değişikliği tespit edilmedi.</span>
    </div>
    @endif

    @if(count($hatalar) > 0)
    <div class="mt-3 bg-[#FEF2F2] border border-[#FECACA] rounded-[10px] p-4">
      <p class="text-[12px] font-semibold text-[#DC2626] mb-1">Hatalı satırlar:</p>
      @foreach($hatalar as $h)
        <p class="text-[12px] text-[#DC2626]">— {{ $h }}</p>
      @endforeach
    </div>
    @endif
  </div>
@endif

@if($errors->any())
  <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[10px] p-4 mb-5 flex items-center gap-2">
    <i class="ti ti-alert-circle text-[#DC2626] text-[18px]"></i>
    <span class="text-[13px] text-[#DC2626]">{{ $errors->first() }}</span>
  </div>
@endif

{{-- Yükleme formu --}}
<div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden"
     x-data="{ dragOver: false, dosyaAdi: null }">

  <div class="px-5 py-4 border-b border-[#F1F5F9]">
    <h2 class="text-[14px] font-semibold text-[#0F172A]">Güncellenmiş Dosyayı Yükle</h2>
    <p class="text-[12px] text-[#94A3B8] mt-0.5">Yalnızca .xlsx formatı kabul edilir — toplam {{ $toplamUrun }} ürün</p>
  </div>

  <form method="POST" action="{{ route('admin.fiyat-guncelle.yukle') }}"
        enctype="multipart/form-data" class="p-5">
    @csrf

    {{-- Drop zone --}}
    <label
      @dragover.prevent="dragOver = true"
      @dragleave.prevent="dragOver = false"
      @drop.prevent="dragOver = false; dosyaAdi = $event.dataTransfer.files[0]?.name; $refs.fileInput.files = $event.dataTransfer.files"
      :class="dragOver ? 'border-[#3B82F6] bg-[#EFF6FF]' : 'border-[#E2E8F0] bg-[#F8FAFC] hover:border-[#94A3B8]'"
      class="flex flex-col items-center justify-center gap-2 border-2 border-dashed rounded-[10px] py-10 cursor-pointer transition-colors">

      <i class="ti ti-file-spreadsheet text-[40px]"
         :class="dosyaAdi ? 'text-[#16A34A]' : 'text-[#CBD5E1]'"></i>

      <span class="text-[13px] font-medium text-[#475569]" x-text="dosyaAdi || 'Dosyayı buraya sürükleyin veya tıklayın'"></span>
      <span class="text-[11px] text-[#94A3B8]" x-show="!dosyaAdi">Yalnızca .xlsx — maks. 10 MB</span>

      <input type="file" accept=".xlsx" x-ref="fileInput" name="dosya" class="hidden"
             @change="dosyaAdi = $event.target.files[0]?.name">
    </label>

    <div class="flex justify-end mt-4">
      <button type="submit"
              :disabled="!dosyaAdi"
              :class="dosyaAdi ? 'bg-[#CC2200] hover:bg-[#a31b00] cursor-pointer' : 'bg-[#E2E8F0] text-[#94A3B8] cursor-not-allowed'"
              class="flex items-center gap-2 text-white text-[11px] font-semibold tracking-[1px] uppercase px-5 py-2.5 rounded-[8px] transition-colors">
        <i class="ti ti-upload"></i> Yükle ve Güncelle
      </button>
    </div>
  </form>
</div>

@endsection
