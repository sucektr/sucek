{{-- İndirilebilir Öğeler kartı — $model (Urun|Koleksiyon) inject edilmeli --}}
<div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6"
     x-data="{ silinenler: [] }">
  <h2 class="text-[13px] font-semibold text-[#0F172A] mb-1">İndirilebilir Öğeler</h2>
  <p class="text-[11px] text-[#94A3B8] mb-4">PDF, Word, Excel, ZIP vb. — maks. 10 MB/dosya</p>

  {{-- Mevcut dosyalar --}}
  @if($model->dosyalar && count($model->dosyalar) > 0)
  <div class="space-y-2 mb-4">
    @foreach($model->dosyalar as $d)
    @php
      $ext  = strtolower(pathinfo($d['ad'], PATHINFO_EXTENSION));
      $ikon = match(true) {
        in_array($ext, ['pdf'])           => 'ti-file-type-pdf',
        in_array($ext, ['doc','docx'])    => 'ti-file-type-doc',
        in_array($ext, ['xls','xlsx'])    => 'ti-file-type-xls',
        in_array($ext, ['zip','rar','7z'])=> 'ti-file-zip',
        default                           => 'ti-file',
      };
      $renk = match(true) {
        in_array($ext, ['pdf'])           => 'text-[#CC2200]',
        in_array($ext, ['doc','docx'])    => 'text-[#2B5EAB]',
        in_array($ext, ['xls','xlsx'])    => 'text-[#1A7A4A]',
        in_array($ext, ['zip','rar','7z'])=> 'text-[#D97706]',
        default                           => 'text-[#64748B]',
      };
    @endphp
    <div class="flex items-center gap-3 p-2.5 border border-[#E2E8F0] rounded-[8px] transition-colors"
         :class="silinenler.includes('{{ $d['dosya'] }}') ? 'opacity-40 bg-[#FEE2E2] border-[#FCA5A5]' : 'bg-[#F8FAFC]'">
      <i class="ti {{ $ikon }} {{ $renk }} text-xl shrink-0"></i>
      <span class="flex-1 text-[12px] text-[#0F172A] truncate">{{ $d['ad'] }}</span>
      <a href="{{ Storage::url($d['dosya']) }}" target="_blank"
         class="text-[11px] text-[#64748B] hover:text-[#0F172A] transition-colors px-2 py-1 rounded hover:bg-[#F1F5F9] shrink-0">
        <i class="ti ti-download text-sm"></i>
      </a>
      <button type="button"
              @click="silinenler.includes('{{ $d['dosya'] }}') ? silinenler.splice(silinenler.indexOf('{{ $d['dosya'] }}'),1) : silinenler.push('{{ $d['dosya'] }}')"
              class="text-[11px] shrink-0 w-7 h-7 flex items-center justify-center rounded transition-colors"
              :class="silinenler.includes('{{ $d['dosya'] }}') ? 'bg-[#FEE2E2] text-[#CC2200]' : 'text-[#C0C0C0] hover:bg-[#FEE2E2] hover:text-[#CC2200]'"
              title="Sil / Geri Al">
        <i :class="silinenler.includes('{{ $d['dosya'] }}') ? 'ti ti-rotate-clockwise-2' : 'ti ti-trash'" class="text-sm"></i>
      </button>
      <input type="checkbox" name="dosya_sil[]" value="{{ $d['dosya'] }}" x-model="silinenler" class="hidden">
    </div>
    @endforeach
  </div>
  @endif

  {{-- Yeni dosya yükle --}}
  <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-[#E2E8F0] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors bg-[#F8FAFC]"
         x-data="{ sayi: 0 }">
    <i class="ti ti-cloud-upload text-2xl text-[#C0C0C0] mb-1"></i>
    <span class="text-[12px] text-[#94A3B8]"
          x-text="sayi > 0 ? sayi + ' dosya seçildi' : 'Dosya Ekle (çoklu seçim)'"></span>
    <span class="text-[10px] text-[#C0C0C0] mt-0.5">PDF, DOC, XLS, ZIP — maks. 10 MB/adet</span>
    <input type="file" name="dosyalar_yeni[]" multiple
           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.7z,.txt,.csv"
           class="hidden"
           @change="sayi = $event.target.files.length">
  </label>
</div>


