@extends('admin.layouts.app')
@section('title', 'ERP Stok İçe Aktarma')
@section('page-title', 'ERP Stok İçe Aktarma')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">ERP Stok İçe Aktarma</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">

  {{-- Sonuç --}}
  @if(session('erp_yeniler') !== null || session('erp_guncellenenler') !== null || session('erp_hatalar'))
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6 space-y-4">

    @if(session('erp_yeniler') > 0)
    <div class="flex items-start gap-3 p-4 bg-green-50 border border-green-200 rounded-[8px]">
      <i class="ti ti-circle-check text-green-600 text-xl shrink-0 mt-0.5"></i>
      <p class="text-[13px] font-semibold text-green-800">{{ session('erp_yeniler') }} yeni ürün eklendi</p>
    </div>
    @endif

    @if(session('erp_guncellenenler') > 0)
    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-[8px]">
      <i class="ti ti-refresh text-blue-600 text-xl shrink-0 mt-0.5"></i>
      <p class="text-[13px] font-semibold text-blue-800">{{ session('erp_guncellenenler') }} ürün güncellendi (stok ve bilgiler)</p>
    </div>
    @endif

    @if(session('erp_yeniler') === 0 && session('erp_guncellenenler') === 0 && !session('erp_hatalar'))
    <div class="flex items-start gap-3 p-4 bg-yellow-50 border border-yellow-200 rounded-[8px]">
      <i class="ti ti-alert-triangle text-yellow-600 text-xl shrink-0 mt-0.5"></i>
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

  {{-- Bilgi: ERP format --}}
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-7 h-7 rounded-full bg-[#0F172A] flex items-center justify-center shrink-0">
        <span class="text-white text-[11px] font-bold">1</span>
      </div>
      <h2 class="text-[14px] font-semibold text-[#0F172A]">ERP'den Excel Export Al</h2>
    </div>
    <p class="text-[13px] text-[#64748B] mb-3 leading-relaxed">
      Hitit ERP'den stok listesini <strong>.xlsx</strong> veya <strong>.xls</strong> formatında export edin.
      Dosyanın aşağıdaki sütun sırasında olması gerekiyor:
    </p>
    <div class="overflow-x-auto">
      <table class="w-full text-[11px] border-collapse">
        <thead>
          <tr class="bg-[#0F172A] text-white">
            <th class="px-3 py-2 text-left font-semibold">Sütun</th>
            <th class="px-3 py-2 text-left font-semibold">ERP Alanı</th>
            <th class="px-3 py-2 text-left font-semibold">Sitede</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#F1F5F9]">
          <tr class="bg-[#F8FAFC]"><td class="px-3 py-1.5 font-mono font-bold text-[#0F172A]">A</td><td class="px-3 py-1.5 text-[#64748B]">Marka Açıklaması</td><td class="px-3 py-1.5 text-[#64748B]">Marka</td></tr>
          <tr><td class="px-3 py-1.5 font-mono font-bold text-[#CC2200]">B</td><td class="px-3 py-1.5 text-[#64748B]">Stok Kodu <span class="text-[#CC2200]">*</span></td><td class="px-3 py-1.5 text-[#64748B]">Stok Kodu (eşleştirme anahtarı)</td></tr>
          <tr class="bg-[#F8FAFC]"><td class="px-3 py-1.5 font-mono font-bold text-[#CC2200]">C</td><td class="px-3 py-1.5 text-[#64748B]">Stok Kodu Açıklama <span class="text-[#CC2200]">*</span></td><td class="px-3 py-1.5 text-[#64748B]">Ürün Adı</td></tr>
          <tr><td class="px-3 py-1.5 font-mono font-bold text-[#64748B]">D</td><td class="px-3 py-1.5 text-[#64748B]">Alt Stok Kodu</td><td class="px-3 py-1.5 text-[#64748B]">Özellik</td></tr>
          <tr class="bg-[#F8FAFC]"><td class="px-3 py-1.5 font-mono font-bold text-[#64748B]">E</td><td class="px-3 py-1.5 text-[#64748B]">Renk Açıklama</td><td class="px-3 py-1.5 text-[#64748B]">Alt Kategori / Özellik</td></tr>
          <tr><td class="px-3 py-1.5 font-mono font-bold text-[#64748B]">F</td><td class="px-3 py-1.5 text-[#64748B]">ANAGRUP</td><td class="px-3 py-1.5 text-[#64748B]">Kategori</td></tr>
          <tr class="bg-[#F8FAFC]"><td class="px-3 py-1.5 font-mono font-bold text-[#64748B]">G</td><td class="px-3 py-1.5 text-[#64748B]">Miktar</td><td class="px-3 py-1.5 text-[#64748B]">Stok Adedi</td></tr>
        </tbody>
      </table>
    </div>
    <p class="text-[11px] text-[#94A3B8] mt-3">
      <span class="text-[#CC2200] font-bold">*</span> Zorunlu sütunlar. İlk satır başlık olmalı (veriden önce).
    </p>
  </div>

  {{-- Yükle --}}
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-7 h-7 rounded-full bg-[#CC2200] flex items-center justify-center shrink-0">
        <span class="text-white text-[11px] font-bold">2</span>
      </div>
      <h2 class="text-[14px] font-semibold text-[#0F172A]">ERP Dosyasını Yükle</h2>
    </div>

    <div class="bg-[#FFF7ED] border border-orange-200 rounded-[8px] p-3 mb-4 text-[12px] text-orange-700 flex items-start gap-2">
      <i class="ti ti-info-circle text-sm shrink-0 mt-0.5"></i>
      <span>
        <strong>Upsert</strong> mantığı çalışır: Stok koduna göre mevcut ürün bulunursa güncellenir, bulunamazsa yeni ürün eklenir.
        Her yüklemede stok miktarı ERP'deki değerle güncellenir.
      </span>
    </div>

    @error('dosya')
    <p class="text-[12px] text-red-500 flex items-center gap-1 mb-3">
      <i class="ti ti-alert-circle"></i> {{ $message }}
    </p>
    @enderror

    <form action="{{ route('admin.erp-import.yukle') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] bg-[#F8FAFC] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors"
             id="erp-label">
        <i class="ti ti-file-spreadsheet text-3xl text-[#C0C0C0] mb-1" id="erp-icon"></i>
        <span class="text-[12px] text-[#94A3B8]" id="erp-txt">ERP dosyasını seç veya sürükle bırak</span>
        <span class="text-[10px] text-[#C0C0C0] mt-0.5">.xlsx / .xls · Maks. 20MB</span>
        <input type="file" name="dosya" accept=".xlsx,.xls" class="hidden"
               onchange="
                 const f=this.files[0];
                 if(f){
                   document.getElementById('erp-txt').textContent=f.name;
                   document.getElementById('erp-icon').className='ti ti-file-check text-3xl text-green-500 mb-1';
                 }
               ">
      </label>
      <button type="submit"
              class="mt-4 w-full bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3.5 rounded-[10px] hover:bg-[#a31b00] transition-colors">
        <i class="ti ti-upload text-sm mr-1.5"></i> ERP Stok Listesini Aktar
      </button>
    </form>
  </div>

  <p class="text-[12px] text-[#94A3B8] text-center">
    Sistemde <strong>{{ $toplamUrun }}</strong> ERP kökenli ürün kayıtlı.
    <a href="{{ route('admin.urunler.index') }}" class="underline hover:text-[#0F172A]">Ürün listesine git →</a>
  </p>

</div>
@endsection
