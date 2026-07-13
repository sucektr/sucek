@extends('admin.layouts.app')
@section('title', 'ERP Stok İçe Aktarma')
@section('page-title', 'ERP Stok İçe Aktarma')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">ERP Stok İçe Aktarma</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">

  {{-- Import sonucu --}}
  @if(session('erp_yeniler') !== null || session('erp_guncellenenler') !== null || session('erp_hatalar'))
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6 space-y-4">
    @if(session('erp_yeniler') > 0)
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-[8px]">
      <i class="ti ti-circle-check text-green-600 text-xl shrink-0"></i>
      <p class="text-[13px] font-semibold text-green-800">{{ session('erp_yeniler') }} yeni ürün eklendi</p>
    </div>
    @endif
    @if(session('erp_guncellenenler') > 0)
    <div class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded-[8px]">
      <i class="ti ti-refresh text-blue-600 text-xl shrink-0"></i>
      <p class="text-[13px] font-semibold text-blue-800">{{ session('erp_guncellenenler') }} ürünün stoğu güncellendi</p>
    </div>
    @endif
    @if(session('erp_yeniler') === 0 && session('erp_guncellenenler') === 0 && !session('erp_hatalar'))
    <div class="flex items-center gap-3 p-4 bg-yellow-50 border border-yellow-200 rounded-[8px]">
      <i class="ti ti-alert-triangle text-yellow-600 text-xl shrink-0"></i>
      <p class="text-[13px] text-yellow-800">Dosyada işlenebilecek satır bulunamadı.</p>
    </div>
    @endif
    @if(session('erp_hatalar') && count(session('erp_hatalar')) > 0)
    <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-[8px]">
      <i class="ti ti-alert-circle text-red-500 text-xl shrink-0 mt-0.5"></i>
      <div>
        <p class="text-[13px] font-semibold text-red-800">{{ count(session('erp_hatalar')) }} satır atlandı</p>
        <ul class="mt-2 space-y-0.5">
          @foreach(session('erp_hatalar') as $hata)
          <li class="text-[12px] text-red-700">{{ $hata }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif
    @if(session('erp_atlananlar') > 0)
    <p class="text-[12px] text-[#94A3B8]">{{ session('erp_atlananlar') }} boş satır atlandı.</p>
    @endif
  </div>
  @endif

  {{-- Kategori eşleştirme --}}
  @if($erpKategoriler->isNotEmpty())
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
    <div class="flex items-center justify-between mb-1">
      <h2 class="text-[14px] font-semibold text-[#0F172A]">Kategori Eşleştirme</h2>
      @if(session('harita_kaydedildi'))
      <span class="text-[11px] text-green-600 font-semibold flex items-center gap-1">
        <i class="ti ti-check"></i> Kaydedildi
      </span>
      @endif
    </div>
    <p class="text-[12px] text-[#64748B] mb-4">
      ERP'den gelen her kategoriyi mağaza kategorisiyle eşleştirin.
      Yeni ürünler eklenirken bu eşleştirme otomatik uygulanır.
    </p>

    <form action="{{ route('admin.erp-import.harita') }}" method="POST">
      @csrf
      <div class="space-y-2">
        @foreach($erpKategoriler as $erpKat)
        @php $secili = $harita[$erpKat] ?? ''; @endphp
        <div class="flex items-center gap-3 py-2 border-b border-[#F1F5F9] last:border-0">
          <span class="flex-1 text-[12px] text-[#0F172A] font-mono truncate" title="{{ $erpKat }}">{{ $erpKat }}</span>
          <i class="ti ti-arrow-right text-[#CBD5E1] text-sm shrink-0"></i>
          <select name="harita[{{ $erpKat }}]"
                  class="text-[12px] border border-[#E2E8F0] rounded-[6px] px-2 py-1.5 bg-white text-[#0F172A] focus:outline-none focus:border-[#CC2200] w-36 shrink-0">
            <option value="">— eşleştirilmemiş —</option>
            @foreach(['spor','dekorasyon','insaat','diger'] as $mkat)
            <option value="{{ $mkat }}" @selected($secili === $mkat)>{{ ucfirst($mkat) }}</option>
            @endforeach
          </select>
        </div>
        @endforeach
      </div>
      <button type="submit"
              class="mt-4 w-full bg-[#0F172A] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3 rounded-[10px] hover:bg-[#1e293b] transition-colors">
        <i class="ti ti-device-floppy text-sm mr-1.5"></i> Eşleştirmeyi Kaydet
      </button>
    </form>

    {{-- Mevcut ürünlere uygula --}}
    @if(session('harita_uygula_sonuc'))
    @php $sonuc = session('harita_uygula_sonuc'); @endphp
    <div class="mt-3 flex items-center gap-2 p-3 rounded-[8px] text-[12px]
      {{ $sonuc['tip'] === 'success' ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-yellow-50 border border-yellow-200 text-yellow-700' }}">
      <i class="ti ti-{{ $sonuc['tip'] === 'success' ? 'circle-check' : 'alert-triangle' }} text-sm shrink-0"></i>
      {{ $sonuc['mesaj'] }}
    </div>
    @endif

    <form action="{{ route('admin.erp-import.harita-uygula') }}" method="POST" class="mt-3">
      @csrf
      <button type="submit"
              class="w-full border border-[#0F172A] text-[#0F172A] text-[11px] font-semibold tracking-[1.5px] uppercase py-2.5 rounded-[10px] hover:bg-[#F8FAFC] transition-colors">
        <i class="ti ti-refresh text-sm mr-1.5"></i> Mevcut Ürünlere de Uygula
      </button>
    </form>
  </div>
  @endif

  {{-- ERP dosyası yükle --}}
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-7 h-7 rounded-full bg-[#CC2200] flex items-center justify-center shrink-0">
        <i class="ti ti-upload text-white text-xs"></i>
      </div>
      <h2 class="text-[14px] font-semibold text-[#0F172A]">ERP Dosyasını Yükle</h2>
    </div>

    <div class="bg-[#FFF7ED] border border-orange-200 rounded-[8px] p-3 mb-4 text-[12px] text-orange-700 flex items-start gap-2">
      <i class="ti ti-info-circle text-sm shrink-0 mt-0.5"></i>
      <span>
        Stok koduna göre: <strong>mevcut ürünlerde sadece stok güncellenir</strong>,
        yeni ürünler yukarıdaki eşleştirmeyle eklenir.
      </span>
    </div>

    @error('dosya')
    <p class="text-[12px] text-red-500 flex items-center gap-1 mb-3">
      <i class="ti ti-alert-circle"></i> {{ $message }}
    </p>
    @enderror

    <form action="{{ route('admin.erp-import.yukle') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] bg-[#F8FAFC] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors">
        <i class="ti ti-file-spreadsheet text-3xl text-[#C0C0C0] mb-1" id="erp-icon"></i>
        <span class="text-[12px] text-[#94A3B8]" id="erp-txt">ERP dosyasını seç veya sürükle bırak</span>
        <span class="text-[10px] text-[#C0C0C0] mt-0.5">.xlsx / .xls · Maks. 20MB</span>
        <input type="file" name="dosya" accept=".xlsx,.xls" class="hidden"
               onchange="const f=this.files[0];if(f){document.getElementById('erp-txt').textContent=f.name;document.getElementById('erp-icon').className='ti ti-file-check text-3xl text-green-500 mb-1';}">
      </label>
      <button type="submit"
              class="mt-4 w-full bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3.5 rounded-[10px] hover:bg-[#a31b00] transition-colors">
        <i class="ti ti-upload text-sm mr-1.5"></i> ERP Stok Listesini Aktar
      </button>
    </form>
  </div>

  {{-- Sütun formatı --}}
  <details class="bg-white rounded-[12px] border border-[#E2E8F0]">
    <summary class="px-6 py-4 text-[13px] font-semibold text-[#0F172A] cursor-pointer select-none">
      Beklenen Excel formatı
    </summary>
    <div class="px-6 pb-5 overflow-x-auto">
      <table class="w-full text-[11px] border-collapse">
        <thead>
          <tr class="bg-[#0F172A] text-white">
            <th class="px-3 py-2 text-left">Sütun</th>
            <th class="px-3 py-2 text-left">ERP Alanı</th>
            <th class="px-3 py-2 text-left">Sitede</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#F1F5F9]">
          <tr><td class="px-3 py-1.5 font-mono font-bold">A</td><td class="px-3 py-1.5 text-[#64748B]">Marka Açıklaması</td><td class="px-3 py-1.5 text-[#64748B]">Marka</td></tr>
          <tr class="bg-[#F8FAFC]"><td class="px-3 py-1.5 font-mono font-bold text-[#CC2200]">B</td><td class="px-3 py-1.5 text-[#64748B]">Stok Kodu *</td><td class="px-3 py-1.5 text-[#64748B]">Eşleştirme anahtarı</td></tr>
          <tr><td class="px-3 py-1.5 font-mono font-bold text-[#CC2200]">C</td><td class="px-3 py-1.5 text-[#64748B]">Stok Kodu Açıklama *</td><td class="px-3 py-1.5 text-[#64748B]">Ürün Adı</td></tr>
          <tr class="bg-[#F8FAFC]"><td class="px-3 py-1.5 font-mono font-bold">D</td><td class="px-3 py-1.5 text-[#64748B]">Alt Stok Kodu</td><td class="px-3 py-1.5 text-[#64748B]">Özellik</td></tr>
          <tr><td class="px-3 py-1.5 font-mono font-bold">E</td><td class="px-3 py-1.5 text-[#64748B]">Renk Açıklama</td><td class="px-3 py-1.5 text-[#64748B]">Alt Kategori</td></tr>
          <tr class="bg-[#F8FAFC]"><td class="px-3 py-1.5 font-mono font-bold">F</td><td class="px-3 py-1.5 text-[#64748B]">ANAGRUP</td><td class="px-3 py-1.5 text-[#64748B]">Kategori (eşleştirme uygulanır)</td></tr>
          <tr><td class="px-3 py-1.5 font-mono font-bold">G</td><td class="px-3 py-1.5 text-[#64748B]">Miktar</td><td class="px-3 py-1.5 text-[#64748B]">Stok Adedi</td></tr>
        </tbody>
      </table>
    </div>
  </details>

  <p class="text-[12px] text-[#94A3B8] text-center">
    Sistemde <strong>{{ $toplamUrun }}</strong> ERP kökenli ürün kayıtlı.
    <a href="{{ route('admin.urunler.index') }}" class="underline hover:text-[#0F172A]">Ürün listesine git →</a>
  </p>

</div>
@endsection
