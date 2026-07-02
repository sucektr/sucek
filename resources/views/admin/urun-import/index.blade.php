@extends('admin.layouts.app')
@section('title', 'Toplu Ürün İçe Aktarma')
@section('page-title', 'Toplu Ürün İçe Aktarma')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">Toplu Ürün İçe Aktarma</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">

  {{-- Sonuç Mesajları --}}
  @if(session('import_yeniler') || session('import_guncellenenler') || session('import_hatalar'))
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6 space-y-4">

    @if(session('import_yeniler') && count(session('import_yeniler')) > 0)
    <div class="flex items-start gap-3 p-4 bg-green-50 border border-green-200 rounded-[8px]">
      <i class="ti ti-circle-check text-green-600 text-xl shrink-0 mt-0.5"></i>
      <div>
        <p class="text-[13px] font-semibold text-green-800">{{ count(session('import_yeniler')) }} yeni ürün eklendi</p>
        <ul class="mt-2 space-y-0.5">
          @foreach(session('import_yeniler') as $u)
          <li class="text-[12px] text-green-700">
            <a href="{{ route('admin.urunler.edit', $u['id']) }}" class="hover:underline">#{{ $u['id'] }} — {{ $u['ad'] }}</a>
            <span class="text-green-500 font-mono ml-1">/magaza/{{ $u['slug'] }}</span>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    @if(session('import_guncellenenler') && count(session('import_guncellenenler')) > 0)
    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-[8px]">
      <i class="ti ti-refresh text-blue-600 text-xl shrink-0 mt-0.5"></i>
      <div>
        <p class="text-[13px] font-semibold text-blue-800">{{ count(session('import_guncellenenler')) }} ürün güncellendi</p>
        <ul class="mt-2 space-y-0.5">
          @foreach(session('import_guncellenenler') as $u)
          <li class="text-[12px] text-blue-700">
            <a href="{{ route('admin.urunler.edit', $u['id']) }}" class="hover:underline">#{{ $u['id'] }} — {{ $u['ad'] }}</a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    @if(session('import_hatalar') && count(session('import_hatalar')) > 0)
    <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-[8px]">
      <i class="ti ti-alert-circle text-red-500 text-xl shrink-0 mt-0.5"></i>
      <div>
        <p class="text-[13px] font-semibold text-red-800">{{ count(session('import_hatalar')) }} hata</p>
        <ul class="mt-2 space-y-0.5">
          @foreach(session('import_hatalar') as $hata)
          <li class="text-[12px] text-red-700">{{ $hata }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    @if(session('import_atlananlar') > 0)
    <p class="text-[12px] text-[#94A3B8]">{{ session('import_atlananlar') }} boş satır atlandı.</p>
    @endif

  </div>
  @endif

  {{-- Adım 1: Şablon indir --}}
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-7 h-7 rounded-full bg-[#0F172A] flex items-center justify-center shrink-0">
        <span class="text-white text-[11px] font-bold">1</span>
      </div>
      <h2 class="text-[14px] font-semibold text-[#0F172A]">Excel Şablonunu İndir</h2>
    </div>
    <p class="text-[13px] text-[#64748B] mb-4 leading-relaxed">
      Şablon dosyasında <strong>Ürünler</strong> ve <strong>Açıklamalar</strong> olmak üzere iki sekme var.
      Sarı sütunlar zorunlu, gri sütunlar otomatik doldurulur. <strong>O</strong> sütununa Marka, Renk, Jant Ebatı gibi özellikler girilebilir.
    </p>
    <div class="bg-[#F8FAFC] rounded-[8px] border border-[#E2E8F0] p-3 mb-4 text-[12px] text-[#64748B] space-y-1">
      <p><span class="inline-block w-3 h-3 rounded-sm bg-[#FEF08A] border border-yellow-300 mr-1.5"></span><strong>Sarı</strong> — Zorunlu (Ürün Adı, Kategori, Fiyat)</p>
      <p><span class="inline-block w-3 h-3 rounded-sm bg-[#94A3B8] mr-1.5"></span><strong>Gri</strong> — Otomatik (ID, Slug)</p>
      <p><span class="inline-block w-3 h-3 rounded-sm bg-white border border-[#E2E8F0] mr-1.5"></span><strong>Beyaz</strong> — Opsiyonel</p>
    </div>
    <a href="{{ route('admin.urun-import.sablon') }}"
       class="inline-flex items-center gap-2 bg-[#0F172A] text-white text-[11px] font-semibold tracking-[1px] uppercase px-5 py-2.5 rounded-[8px] hover:bg-[#1e293b] transition-colors">
      <i class="ti ti-download text-sm"></i> Şablonu İndir (.xlsx)
    </a>
  </div>

  {{-- Adım 2: Doldur --}}
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-7 h-7 rounded-full bg-[#0F172A] flex items-center justify-center shrink-0">
        <span class="text-white text-[11px] font-bold">2</span>
      </div>
      <h2 class="text-[14px] font-semibold text-[#0F172A]">Dosyayı Doldur</h2>
    </div>
    <ul class="text-[13px] text-[#64748B] space-y-1.5 leading-relaxed">
      <li><i class="ti ti-point-filled text-[8px] mr-1.5"></i><strong>Yeni ürün:</strong> ID sütununu boş bırak, adı/kategoriyi/fiyatı doldur</li>
      <li><i class="ti ti-point-filled text-[8px] mr-1.5"></i><strong>Mevcut ürün güncelleme:</strong> ID sütununa ürün ID'sini yaz, değiştirmek istediğin alanları güncelle</li>
      <li><i class="ti ti-point-filled text-[8px] mr-1.5"></i>Slug boş bırakılırsa ürün adından otomatik oluşturulur</li>
      <li><i class="ti ti-point-filled text-[8px] mr-1.5"></i>Mevcut ürünlerde boş bırakılan alanlar değiştirilmez</li>
      <li><i class="ti ti-point-filled text-[8px] mr-1.5"></i>Görseller Excel'den yüklenemez — ürün sayfasından ayrıca eklenmelidir</li>
    </ul>
  </div>

  {{-- Adım 3: Yükle --}}
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-7 h-7 rounded-full bg-[#CC2200] flex items-center justify-center shrink-0">
        <span class="text-white text-[11px] font-bold">3</span>
      </div>
      <h2 class="text-[14px] font-semibold text-[#0F172A]">Doldurulmuş Dosyayı Yükle</h2>
    </div>

    @error('dosya')
    <p class="text-[12px] text-red-500 flex items-center gap-1 mb-3">
      <i class="ti ti-alert-circle"></i> {{ $message }}
    </p>
    @enderror

    <form action="{{ route('admin.urun-import.yukle') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#E2E8F0] bg-[#F8FAFC] rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors"
             id="import-label">
        <i class="ti ti-file-spreadsheet text-3xl text-[#C0C0C0] mb-1" id="import-icon"></i>
        <span class="text-[12px] text-[#94A3B8]" id="import-txt">Dosya seç veya sürükle bırak</span>
        <span class="text-[10px] text-[#C0C0C0] mt-0.5">.xlsx · Maks. 20MB</span>
        <input type="file" name="dosya" accept=".xlsx" class="hidden"
               onchange="
                 const f=this.files[0];
                 if(f){
                   document.getElementById('import-txt').textContent=f.name;
                   document.getElementById('import-icon').className='ti ti-file-check text-3xl text-green-500 mb-1';
                 }
               ">
      </label>
      <button type="submit"
              class="mt-4 w-full bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1.5px] uppercase py-3.5 rounded-[10px] hover:bg-[#a31b00] transition-colors">
        <i class="ti ti-upload text-sm mr-1.5"></i> İçe Aktar
      </button>
    </form>
  </div>

  {{-- Özet bilgi --}}
  <p class="text-[12px] text-[#94A3B8] text-center">
    Sistemde toplam <strong>{{ $toplamUrun }}</strong> ürün kayıtlı.
    <a href="{{ route('admin.urunler.index') }}" class="underline hover:text-[#0F172A]">Ürün listesine git →</a>
  </p>

</div>
@endsection
