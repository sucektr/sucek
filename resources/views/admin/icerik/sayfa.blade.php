@extends('admin.layouts.app')
@section('title', $tanim['baslik'] . ' — İçerik')
@section('page-title', $tanim['baslik'])

@section('breadcrumb')
<a href="{{ route('admin.icerik.index') }}" class="text-[12px] text-[#94A3B8] hover:text-[#0F172A]">Sayfa İçerikleri</a>
<span class="text-[#D0D0D0] text-xs">›</span>
<span class="text-[12px] text-[#64748B]">{{ $tanim['baslik'] }}</span>
@endsection

@section('header-actions')
<a href="{{ route('admin.icerik.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">← Geri</a>
@endsection

@section('content')
<form action="{{ route('admin.icerik.guncelle', $sayfa) }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="space-y-4 max-w-3xl">
    @foreach($tanim['alanlar'] as $alan_tanim)
    @php
      $alan = $alan_tanim['alan'];
      $tip  = $alan_tanim['tip'];
      $row  = $mevcut[$alan] ?? null;
    @endphp

    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-6">
      <label class="block text-[11px] font-semibold text-[#64748B] uppercase tracking-[.06em] mb-3">
        {{ $alan_tanim['baslik'] }}
        <span class="ml-2 font-normal normal-case tracking-normal text-[#C0C0C0]">({{ $tip }})</span>
      </label>

      @if($tip === 'gorsel')
        {{-- Mevcut görsel --}}
        @if($row && $row->gorsel)
        <div class="mb-3 flex items-start gap-3" id="gorsel-wrap-{{ $alan }}">
          <div class="relative inline-block">
            <img src="{{ url('/uploads/' . $row->gorsel) }}" alt="{{ $alan_tanim['baslik'] }}"
                 class="h-40 w-auto rounded-[8px] object-cover border border-[#E2E8F0]">
            <span class="absolute top-1 left-1 bg-[rgba(0,0,0,0.55)] text-white text-[9px] px-2 py-0.5 rounded-full">Mevcut</span>
          </div>
          <div class="flex flex-col gap-2 pt-1">
            <input type="hidden" name="f_{{ $alan }}_sil" value="0" id="sil-flag-{{ $alan }}">
            <button type="button"
                    onclick="
                      document.getElementById('sil-flag-{{ $alan }}').value='1';
                      document.getElementById('gorsel-wrap-{{ $alan }}').style.display='none';
                      document.getElementById('gorsel-yeni-{{ $alan }}').style.display='flex';
                      document.getElementById('gorsel-info-{{ $alan }}').style.display='none';
                    "
                    class="flex items-center gap-1.5 text-[11px] text-red-500 border border-red-200 rounded-[6px] px-3 py-1.5 hover:bg-red-50 transition-colors">
              <i class="ti ti-trash text-sm"></i> Görseli Kaldır
            </button>
          </div>
        </div>
        <p class="text-[11px] text-[#94A3B8] mb-2" id="gorsel-info-{{ $alan }}">Yeni görsel yükleyerek değiştirebilirsiniz:</p>
        @endif
        @error('f_' . $alan)
        <p class="text-[12px] text-red-500 flex items-center gap-1 mb-2">
          <i class="ti ti-alert-circle"></i> {{ $message }}
        </p>
        @enderror
        <div id="gorsel-yeni-{{ $alan }}" style="{{ ($row && $row->gorsel) ? 'display:none' : '' }}">
        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed {{ $errors->has('f_'.$alan) ? 'border-red-400 bg-red-50' : 'border-[#E2E8F0] bg-[#F8FAFC]' }} rounded-[8px] cursor-pointer hover:border-[#CC2200] transition-colors"
               id="label-{{ $alan }}">
          <i class="ti ti-upload text-2xl text-[#C0C0C0] mb-1" id="icon-{{ $alan }}"></i>
          <span class="text-[12px] text-[#94A3B8]" id="txt-{{ $alan }}">
            {{ ($row && $row->gorsel) ? 'Değiştir' : 'Görsel Seç' }}
          </span>
          <span class="text-[10px] text-[#C0C0C0] mt-0.5">JPG, PNG, WEBP · Maks. 5MB</span>
          <input type="file" name="f_{{ $alan }}" accept="image/*" class="hidden"
                 onchange="
                   const f=this.files[0];
                   if(!f) return;
                   const lbl=document.getElementById('label-{{ $alan }}');
                   const ico=document.getElementById('icon-{{ $alan }}');
                   const txt=document.getElementById('txt-{{ $alan }}');
                   if(f.size > 5*1024*1024){
                     txt.textContent='Dosya çok büyük! Maks. 5MB';
                     ico.className='ti ti-alert-circle text-2xl text-red-500 mb-1';
                     lbl.classList.add('border-red-400','bg-red-50');
                     lbl.classList.remove('border-[#E2E8F0]','bg-[#F8FAFC]');
                     this.value='';
                   } else {
                     txt.textContent=f.name;
                     ico.className='ti ti-check text-2xl text-green-500 mb-1';
                     lbl.classList.remove('border-red-400','bg-red-50');
                     lbl.classList.add('border-[#E2E8F0]','bg-[#F8FAFC]');
                   }
                 ">
        </label>
        </div>

      @elseif($tip === 'html')
        <div class="quill-wrapper rounded-[8px] overflow-hidden border border-[#E2E8F0]">
          <div class="quill-editor"
               id="editor-{{ $alan }}"
               data-alan="{{ $alan }}"
               style="min-height:260px; font-size:14px;">{!! old("f_{$alan}", $row?->deger ?? '') !!}</div>
        </div>
        <input type="hidden" name="f_{{ $alan }}" id="hidden-{{ $alan }}"
               value="{{ old("f_{$alan}", $row?->deger ?? '') }}">

      @elseif($tip === 'textarea')
        <textarea name="f_{{ $alan }}" rows="4"
                  class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors resize-none leading-relaxed"
                  placeholder="{{ $alan_tanim['baslik'] }}">{{ old("f_{$alan}", $row?->deger ?? '') }}</textarea>

      @elseif($tip === 'url')
        <input type="url" name="f_{{ $alan }}"
               value="{{ old("f_{$alan}", $row?->deger ?? '') }}"
               class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors font-mono"
               placeholder="https://...">
        @if($row && $row->deger)
        <p class="text-[11px] text-[#94A3B8] mt-1.5 flex items-center gap-1">
          <i class="ti ti-info-circle"></i>
          Mevcut URL kaydedildi. Haritayı güncellemek için yeni URL girin.
        </p>
        @endif

      @elseif($tip === 'bankaListesi')
        @php
          $jsonDeger = old("f_{$alan}", $row?->deger ?? '');
          $mevcutBankalar = [];
          if ($jsonDeger) {
              $decoded = json_decode($jsonDeger, true);
              if (is_array($decoded) && !empty($decoded)) $mevcutBankalar = $decoded;
          }
          if (empty($mevcutBankalar)) {
              $mevcutBankalar = [['banka_adi' => '', 'iban' => '', 'alici_adi' => '']];
          }
        @endphp

        <div id="bankaContainer">
          @foreach($mevcutBankalar as $banka)
          <div class="banka-satir flex gap-3 items-start p-4 bg-[#F9F9F9] rounded-[10px] border border-[#E2E8F0] mb-3">
            <div class="flex-1 grid grid-cols-3 gap-3">
              <div>
                <label class="block text-[10px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1">Banka Adı</label>
                <input type="text" data-field="banka_adi" value="{{ $banka['banka_adi'] ?? '' }}" placeholder="Ziraat Bankası"
                       class="w-full px-3 py-2 border border-[#E2E8F0] rounded-[6px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
              </div>
              <div>
                <label class="block text-[10px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1">IBAN</label>
                <input type="text" data-field="iban" value="{{ $banka['iban'] ?? '' }}" placeholder="TR00 0000 0000 0000 0000 0000 00"
                       class="w-full px-3 py-2 border border-[#E2E8F0] rounded-[6px] text-[13px] font-mono focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
              </div>
              <div>
                <label class="block text-[10px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1">Alıcı Adı</label>
                <input type="text" data-field="alici_adi" value="{{ $banka['alici_adi'] ?? '' }}" placeholder="Ad Soyad / Firma"
                       class="w-full px-3 py-2 border border-[#E2E8F0] rounded-[6px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
              </div>
            </div>
            <button type="button" class="banka-kaldir mt-6 w-8 h-8 flex items-center justify-center rounded-[6px] text-[#94A3B8] hover:bg-red-50 hover:text-red-500 transition-all shrink-0" title="Bu bankayı kaldır">
              <i class="ti ti-trash text-sm"></i>
            </button>
          </div>
          @endforeach
        </div>

        <button type="button" id="bankaEkleBtn"
                class="mt-3 flex items-center gap-2 text-[12px] font-medium text-[#0F172A] border border-[#E2E8F0] px-4 py-2 rounded-[8px] hover:bg-[#F8FAFC] transition-colors">
          <i class="ti ti-plus text-sm"></i>Banka Ekle
        </button>

        <input type="hidden" id="bankaHidden" name="f_{{ $alan }}" value="{{ old("f_{$alan}", $row?->deger ?? '') }}">

        @push('scripts')
        <script>
        (function () {
          var container   = document.getElementById('bankaContainer');
          var ekleBtn     = document.getElementById('bankaEkleBtn');
          var hiddenInput = document.getElementById('bankaHidden');

          function satirHtml() {
            var row = document.createElement('div');
            row.className = 'banka-satir flex gap-3 items-start p-4 bg-[#F9F9F9] rounded-[10px] border border-[#E2E8F0] mb-3';
            row.innerHTML =
              '<div class="flex-1 grid grid-cols-3 gap-3">' +
                '<div>' +
                  '<label class="block text-[10px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1">Banka Adı</label>' +
                  '<input type="text" data-field="banka_adi" placeholder="Ziraat Bankası" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-[6px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">' +
                '</div>' +
                '<div>' +
                  '<label class="block text-[10px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1">IBAN</label>' +
                  '<input type="text" data-field="iban" placeholder="TR00 0000 0000 0000 0000 0000 00" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-[6px] text-[13px] font-mono focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">' +
                '</div>' +
                '<div>' +
                  '<label class="block text-[10px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1">Alıcı Adı</label>' +
                  '<input type="text" data-field="alici_adi" placeholder="Ad Soyad / Firma" class="w-full px-3 py-2 border border-[#E2E8F0] rounded-[6px] text-[13px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">' +
                '</div>' +
              '</div>' +
              '<button type="button" class="banka-kaldir mt-6 w-8 h-8 flex items-center justify-center rounded-[6px] text-[#94A3B8] hover:bg-red-50 hover:text-red-500 transition-all shrink-0" title="Bu bankayı kaldır">' +
                '<i class="ti ti-trash text-sm"></i>' +
              '</button>';
            return row;
          }

          function serialize() {
            var rows = container.querySelectorAll('.banka-satir');
            var data = [];
            rows.forEach(function (row) {
              data.push({
                banka_adi: row.querySelector('[data-field="banka_adi"]').value,
                iban:      row.querySelector('[data-field="iban"]').value,
                alici_adi: row.querySelector('[data-field="alici_adi"]').value,
              });
            });
            hiddenInput.value = JSON.stringify(data);
          }

          function refreshKaldirBtnler() {
            var rows = container.querySelectorAll('.banka-satir');
            rows.forEach(function (row) {
              var btn = row.querySelector('.banka-kaldir');
              if (rows.length === 1) {
                btn.disabled = true;
                btn.classList.add('opacity-30', 'cursor-not-allowed');
                btn.classList.remove('hover:bg-red-50', 'hover:text-red-500');
              } else {
                btn.disabled = false;
                btn.classList.remove('opacity-30', 'cursor-not-allowed');
                btn.classList.add('hover:bg-red-50', 'hover:text-red-500');
              }
            });
          }

          ekleBtn.addEventListener('click', function () {
            container.appendChild(satirHtml());
            refreshKaldirBtnler();
            serialize();
          });

          container.addEventListener('click', function (e) {
            var btn = e.target.closest('.banka-kaldir');
            if (!btn) return;
            var rows = container.querySelectorAll('.banka-satir');
            if (rows.length > 1) {
              btn.closest('.banka-satir').remove();
              refreshKaldirBtnler();
              serialize();
            }
          });

          container.addEventListener('input', serialize);

          document.querySelector('form').addEventListener('submit', serialize, true);

          refreshKaldirBtnler();
          serialize();
        })();
        </script>
        @endpush

      @else
        {{-- metin --}}
        <input type="text" name="f_{{ $alan }}"
               value="{{ old("f_{$alan}", $row?->deger ?? '') }}"
               class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors"
               placeholder="{{ $alan_tanim['baslik'] }}">
      @endif
    </div>
    @endforeach

    <div class="flex items-center gap-3 pt-2">
      <button type="submit"
              class="bg-[#CC2200] text-white text-[12px] font-semibold tracking-[1.5px] uppercase px-8 py-3.5 rounded-[10px] hover:bg-[#a31b00] transition-colors">
        <i class="ti ti-device-floppy text-sm mr-1.5"></i> Kaydet
      </button>
      <a href="{{ route('admin.icerik.index') }}"
         class="text-[12px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">İptal</a>
    </div>
  </div>
</form>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
  .ql-toolbar.ql-snow {
    border: none;
    border-bottom: 1px solid rgba(0,0,0,0.10);
    background: #F9F9F9;
    padding: 8px 12px;
  }
  .ql-container.ql-snow {
    border: none;
    font-family: inherit;
  }
  .ql-editor {
    padding: 14px 16px;
    min-height: 260px;
    font-size: 14px;
    line-height: 1.65;
    color: #0F172A;
  }
  .ql-editor p { margin-bottom: 8px; }
  .ql-editor h1 { font-size: 26px; font-weight: 700; margin-bottom: 10px; }
  .ql-editor h2 { font-size: 22px; font-weight: 600; margin-bottom: 8px; }
  .ql-editor h3 { font-size: 18px; font-weight: 600; margin-bottom: 6px; }
  .ql-editor ul, .ql-editor ol { padding-left: 20px; margin-bottom: 8px; }
  .ql-editor a { color: #0F172A; text-decoration: underline; }
  .ql-snow .ql-stroke { stroke: #5A5A5A; }
  .ql-snow .ql-fill { fill: #5A5A5A; }
  .ql-snow.ql-toolbar button:hover .ql-stroke,
  .ql-snow.ql-toolbar button.ql-active .ql-stroke { stroke: #0F172A; }
  .ql-snow.ql-toolbar button:hover .ql-fill,
  .ql-snow.ql-toolbar button.ql-active .ql-fill { fill: #0F172A; }
  .quill-wrapper:focus-within { border-color: #CC2200 !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var toolbarOptions = [
    [{ 'header': [1, 2, 3, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'align': [] }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    ['link'],
    ['clean']
  ];

  document.querySelectorAll('.quill-editor').forEach(function (el) {
    var alan    = el.dataset.alan;
    var hidden  = document.getElementById('hidden-' + alan);
    var initial = hidden ? hidden.value : '';

    var quill = new Quill(el, {
      theme: 'snow',
      modules: { toolbar: toolbarOptions },
      placeholder: 'İçeriği buraya yazın…'
    });

    if (initial.trim()) {
      quill.root.innerHTML = initial;
    }

    quill.on('text-change', function () {
      if (hidden) {
        hidden.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
      }
    });
  });

  var form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function () {
      document.querySelectorAll('.quill-editor').forEach(function (el) {
        var alan   = el.dataset.alan;
        var hidden = document.getElementById('hidden-' + alan);
        var quill  = Quill.find(el);
        if (quill && hidden) {
          hidden.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
        }
      });
    });
  }
});
</script>
@endpush



