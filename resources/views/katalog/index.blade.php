@extends('layouts.katalog')
@section('title', 'Katalog Oluşturucu')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
#ks-quill .ql-toolbar.ql-snow{border-radius:4px 4px 0 0;border-color:rgba(255,255,255,0.15);background:rgba(255,255,255,0.06);padding:5px 8px;}
#ks-quill .ql-toolbar.ql-snow .ql-stroke{stroke:rgba(255,255,255,0.55);}
#ks-quill .ql-toolbar.ql-snow .ql-fill{fill:rgba(255,255,255,0.55);}
#ks-quill .ql-toolbar.ql-snow button:hover .ql-stroke,#ks-quill .ql-toolbar.ql-snow button.ql-active .ql-stroke{stroke:#B8962E;}
#ks-quill .ql-toolbar.ql-snow button:hover .ql-fill,#ks-quill .ql-toolbar.ql-snow button.ql-active .ql-fill{fill:#B8962E;}
#ks-quill .ql-toolbar.ql-snow .ql-picker-label{color:rgba(255,255,255,0.55);}
#ks-quill .ql-toolbar.ql-snow .ql-picker-label .ql-stroke{stroke:rgba(255,255,255,0.55);}
#ks-quill .ql-container.ql-snow{border-radius:0 0 4px 4px;border-color:rgba(255,255,255,0.15);background:rgba(255,255,255,0.06);min-height:80px;}
#ks-quill .ql-editor{color:rgba(255,255,255,0.9);min-height:80px;font-size:12px;line-height:1.7;padding:8px 10px;}
#ks-quill .ql-editor.ql-blank::before{color:rgba(255,255,255,0.25);font-style:normal;}
</style>
<style>
[x-cloak]{display:none!important;}
.katalog-builder{display:flex;height:100%;overflow:hidden;}
.kb-side{width:320px;flex-shrink:0;background:#0F172A;display:flex;flex-direction:column;overflow-y:auto;}
.kb-side::-webkit-scrollbar{width:4px;}
.kb-side::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.15);border-radius:2px;}
.kb-tabs{display:flex;border-bottom:1px solid rgba(255,255,255,0.1);flex-shrink:0;}
.kb-tab{flex:1;padding:10px 0;font-size:10px;font-weight:500;text-transform:uppercase;letter-spacing:.1em;background:transparent;border:none;border-bottom:2px solid transparent;cursor:pointer;color:rgba(255,255,255,0.4);font-family:inherit;transition:color .15s,border-color .15s;}
.kb-tab.active{color:#B8962E;border-bottom-color:#B8962E;}
.kb-tab:hover:not(.active){color:rgba(255,255,255,0.7);}
.kb-label{display:block;font-size:10px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px;}
.kb-input{width:100%;padding:7px 12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:6px;font-size:13px;color:white;font-family:inherit;outline:none;transition:border-color .15s;}
.kb-input:focus{border-color:#B8962E;}
.kb-input::placeholder{color:rgba(255,255,255,0.3);}
textarea.kb-input{resize:none;}
input[type=date].kb-input,input[type=time].kb-input{color-scheme:dark;}
.kb-pill{font-size:9px;font-weight:500;text-transform:uppercase;letter-spacing:.08em;padding:4px 10px;border-radius:99px;border:1px solid rgba(255,255,255,0.15);background:transparent;color:rgba(255,255,255,0.4);cursor:pointer;font-family:inherit;transition:all .15s;}
.kb-pill:hover{color:rgba(255,255,255,0.8);}
.kb-pill.active{background:rgba(184,150,46,0.2);border-color:rgba(184,150,46,0.6);color:#B8962E;}
.kb-urun-row{display:flex;align-items:center;gap:10px;padding:8px 12px;border-bottom:1px solid rgba(255,255,255,0.05);transition:background .1s;}
.kb-urun-row:hover{background:rgba(255,255,255,0.04);}
.kb-urun-img{width:36px;height:36px;border-radius:4px;background:rgba(255,255,255,0.1);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
.kb-urun-img img{width:100%;height:100%;object-fit:cover;}
.kb-sel-row{display:flex;align-items:center;gap:6px;padding:7px 12px;border-bottom:1px solid rgba(255,255,255,0.05);}
.kb-sel-row:hover{background:rgba(255,255,255,0.03);}
.kb-saved-row{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,0.08);transition:background .1s;}
.kb-saved-row:hover{background:rgba(255,255,255,0.04);}
.kb-save-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:7px;padding:10px;background:#B8962E;color:#0F172A;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;border:none;border-radius:8px;cursor:pointer;font-family:inherit;transition:background .15s;}
.kb-save-btn:hover:not(:disabled){background:#c8a84b;}
.kb-save-btn:disabled{opacity:.4;cursor:not-allowed;}
.kb-print-btn{width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:transparent;border:1px solid rgba(255,255,255,0.2);color:rgba(255,255,255,0.5);border-radius:8px;cursor:pointer;font-size:16px;transition:all .15s;}
.kb-print-btn:hover:not(:disabled){color:white;border-color:rgba(255,255,255,0.4);}
.kb-print-btn:disabled{opacity:.3;cursor:not-allowed;}
.kb-toast-ok{margin:8px;padding:7px 10px;background:rgba(21,128,61,0.3);border:1px solid rgba(74,222,128,0.3);border-radius:6px;font-size:11px;color:#86efac;display:flex;align-items:center;gap:6px;}
.kb-toast-err{margin:8px;padding:7px 10px;background:rgba(127,29,29,0.4);border:1px solid rgba(252,165,165,0.3);border-radius:6px;font-size:11px;color:#fca5a5;display:flex;align-items:center;gap:6px;}
.kb-sec{font-size:10px;color:#B8962E;text-transform:uppercase;letter-spacing:.1em;}
.kb-muted{font-size:10px;color:rgba(255,255,255,0.3);}
.kb-ctrl{width:20px;height:20px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer;color:rgba(255,255,255,0.3);font-size:11px;border-radius:3px;transition:color .15s;}
.kb-ctrl:hover:not(:disabled){color:rgba(255,255,255,0.8);}
.kb-ctrl.del:hover{color:#f87171;}
.kb-ctrl:disabled{opacity:.2;cursor:default;}
.kb-icon-btn{width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer;color:rgba(255,255,255,0.4);font-size:14px;border-radius:4px;transition:color .15s;}
.kb-scroll{overflow-y:auto;}
.kb-scroll::-webkit-scrollbar{width:3px;}
.kb-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.1);border-radius:2px;}
.kb-ekstra-row{display:flex;gap:6px;align-items:center;margin-bottom:8px;}
.kb-ekstra-row .kb-input{padding:5px 8px;font-size:12px;}
.katalog-preview{flex:1;overflow-y:auto;background:#EBEBEB;padding:2rem;height:100%;}
.katalog-preview::-webkit-scrollbar{width:6px;}
.katalog-preview::-webkit-scrollbar-thumb{background:rgba(0,0,0,0.15);border-radius:3px;}
.katalog-sayfa{width:210mm;min-height:297mm;background:white;padding:18mm 20mm;box-shadow:0 4px 24px rgba(0,0,0,0.1);margin:0 auto 24px;display:flex;flex-direction:column;flex-shrink:0;}
.kb-toc-entry{display:flex;align-items:baseline;padding:9px 0;border-bottom:1px solid #F1F5F9;}
.kb-toc-entry:first-child{border-top:1px solid #F1F5F9;}
[contenteditable]:focus{outline:2px solid rgba(184,150,46,0.3);outline-offset:2px;border-radius:2px;}
@media print{
    *,-webkit-*{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
    nav,header,footer{display:none!important;}
    body{overflow:visible!important;height:auto!important;}
    .katalog-builder{display:block!important;height:auto!important;overflow:visible!important;}
    .kb-side{display:none!important;}
    .katalog-preview{overflow:visible!important;height:auto!important;background:white!important;padding:0!important;}
    .katalog-sayfa{width:210mm!important;margin:0!important;box-shadow:none!important;page-break-after:always;break-after:page;}
    .katalog-sayfa:last-child{page-break-after:avoid;break-after:avoid;}
    @page{size:A4 portrait;margin:0;}
}
</style>
@endpush

@php
$initialData = [
    'katalogId'     => $duzenlenenKatalog?->id,
    'baslik'        => $duzenlenenKatalog?->baslik ?? 'Ürün Kataloğu',
    'altBaslik'     => $duzenlenenKatalog?->alt_baslik ?? '',
    'kapak'         => $kapakBirlestir,
    'icindekiler'   => empty($icindekiler) ? new \stdClass() : (object)$icindekiler,
    'seciliUrunler' => $duzenlenenUrunler->values()->toArray(),
    'kataloglar'    => $kataloglar->map(fn($k) => [
        'id'          => $k->id,
        'baslik'      => $k->baslik,
        'urun_sayisi' => count($k->urun_idler ?? []),
        'tarih'       => $k->created_at->format('d.m.Y'),
    ])->values()->toArray(),
];
@endphp

@section('content')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
window._kbInit = {!! json_encode($initialData, JSON_UNESCAPED_UNICODE) !!};

function katalogBuilder() {
    var init = window._kbInit || {};
    return {
        aktifTab:          'urunler',
        katalogId:         init.katalogId     || null,
        baslik:            init.baslik         || 'Ürün Kataloğu',
        altBaslik:         init.altBaslik      || '',
        kapak: {
            marka:      (init.kapak && init.kapak.marka)      || 'SUÇEK',
            marka2:     (init.kapak && init.kapak.marka2)     || '',
            logo:       (init.kapak && init.kapak.logo)       || '',
            kurum:      (init.kapak && init.kapak.kurum)      || '',
            ihale_no:   (init.kapak && init.kapak.ihale_no)   || '',
            ihale_tarih:(init.kapak && init.kapak.ihale_tarih)|| '',
            ihale_yeri: (init.kapak && init.kapak.ihale_yeri) || '',
            ihale_saat: (init.kapak && init.kapak.ihale_saat) || '',
            ekstralar:  (init.kapak && Array.isArray(init.kapak.ekstralar)) ? init.kapak.ekstralar : [],
            stiller: (function(){
                var s = (init.kapak && init.kapak.stiller) || {};
                return {
                    kurum_font:   s.kurum_font   || 'inter',
                    kurum_boyut:  s.kurum_boyut  || 52,
                    baslik_boyut: s.baslik_boyut || 19,
                    vurgu_renk:   s.vurgu_renk   || '#B8962E',
                };
            })(),
        },
        logoYukleniyor:    false,
        icindekiler:       (init.icindekiler && !Array.isArray(init.icindekiler)) ? init.icindekiler : {},
        seciliUrunler:     init.seciliUrunler  || [],
        kataloglar:        init.kataloglar     || [],
        tumUrunler:        [],
        aramaMetni:        '',
        seciliKategori:    '',
        yukleniyorUrunler: false,
        kaydediliyor:      false,
        basariMesaji:      '',
        hataMesaji:        '',

        // Kişisel ürün ekleme (inline modal)
        kisiselModal:          false,
        kisiselForm:           { ad: '', aciklama: '' },
        kisiselDosya:          null,
        kisiselOnizleme:       null,
        kisiselKaydediliyor:   false,

        get filtreliUrunler() {
            var self = this;
            return this.tumUrunler.filter(function(u) {
                if (self.aramaMetni && u.ad.toLowerCase().indexOf(self.aramaMetni.toLowerCase()) === -1) return false;
                if (self.seciliKategori && u.kategori !== self.seciliKategori) return false;
                return true;
            });
        },

        tocAdi: function(urun) { return this.icindekiler[urun.id] || urun.ad; },

        tocGuncelle: function(urunId, deger, varsayilanAd) {
            var obj = Object.assign({}, this.icindekiler);
            if (deger && deger !== varsayilanAd) { obj[urunId] = deger; }
            else { delete obj[urunId]; }
            this.icindekiler = obj;
        },

        secilmisMi: function(id) { return this.seciliUrunler.some(function(u){ return u.id === id; }); },

        init: function() { this.urunleriYukle(); },

        urunleriYukle: function() {
            var self = this;
            self.yukleniyorUrunler = true;
            fetch('{{ route("katalog.urunler") }}', { headers: { 'Accept': 'application/json' } })
                .then(function(r){
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(function(data){
                    if (Array.isArray(data)) { self.tumUrunler = data; }
                    else { throw new Error('invalid'); }
                })
                .catch(function(){
                    self.hataMesaji = 'Ürünler yüklenemedi.';
                    setTimeout(function(){ self.hataMesaji = ''; }, 4000);
                })
                .finally(function(){ self.yukleniyorUrunler = false; });
        },

        urunEkle: function(urun) {
            if (!this.secilmisMi(urun.id)) {
                this.seciliUrunler = this.seciliUrunler.concat([Object.assign({}, urun)]);
            }
        },

        urunKaldir: function(index) {
            this.seciliUrunler = this.seciliUrunler.filter(function(_, i){ return i !== index; });
        },

        yukariTasi: function(index) {
            if (index === 0) return;
            var arr = this.seciliUrunler.slice(); var tmp = arr[index-1]; arr[index-1]=arr[index]; arr[index]=tmp; this.seciliUrunler=arr;
        },

        asagiTasi: function(index) {
            if (index >= this.seciliUrunler.length - 1) return;
            var arr = this.seciliUrunler.slice(); var tmp = arr[index+1]; arr[index+1]=arr[index]; arr[index]=tmp; this.seciliUrunler=arr;
        },

        ekstraEkle: function() { this.kapak.ekstralar.push({ etiket: '', deger: '' }); },
        ekstraSil:  function(i) { this.kapak.ekstralar.splice(i, 1); },

        logoYukle: function(event) {
            var self = this;
            var file = event.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(e) { self.kapak.logo = e.target.result; };
            reader.readAsDataURL(file);
            self.logoYukleniyor = true;
            var fd = new FormData();
            fd.append('logo', file);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            fetch('{{ route("katalog.logo.yukle") }}', { method: 'POST', body: fd })
                .then(function(r){ return r.json(); })
                .then(function(data){ if (data.url) self.kapak.logo = data.url; })
                .catch(function(){ self.hataMesaji='Logo yüklenemedi.'; setTimeout(function(){ self.hataMesaji=''; }, 3000); })
                .finally(function(){ self.logoYukleniyor = false; event.target.value = ''; });
        },

        kaydet: function() {
            var self = this;
            if (!self.baslik.trim()) {
                self.hataMesaji = 'Katalog başlığı gereklidir.';
                setTimeout(function(){ self.hataMesaji = ''; }, 3000); self.aktifTab='ayarlar'; return;
            }
            if (self.seciliUrunler.length === 0) {
                self.hataMesaji = 'En az 1 ürün seçin.';
                setTimeout(function(){ self.hataMesaji = ''; }, 3000); return;
            }
            self.kaydediliyor = true;
            fetch('{{ route("katalog.kaydet") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({
                    id: self.katalogId, baslik: self.baslik, alt_baslik: self.altBaslik,
                    kapak: self.kapak, icindekiler: self.icindekiler,
                    urun_idler: self.seciliUrunler.map(function(u){ return u.id; }),
                }),
            })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                if (data.success) {
                    self.katalogId = data.id;
                    self.basariMesaji = 'Katalog kaydedildi!';
                    setTimeout(function(){ self.basariMesaji=''; }, 3000);
                    var item = { id: data.id, baslik: self.baslik, urun_sayisi: self.seciliUrunler.length, tarih: new Date().toLocaleDateString('tr-TR') };
                    var idx = self.kataloglar.findIndex(function(k){ return k.id===data.id; });
                    if (idx>=0){ var arr=self.kataloglar.slice(); arr[idx]=item; self.kataloglar=arr; } else self.kataloglar=[item].concat(self.kataloglar);
                }
            })
            .catch(function(){ self.hataMesaji='Kaydetme başarısız.'; setTimeout(function(){ self.hataMesaji=''; }, 4000); })
            .finally(function(){ self.kaydediliyor=false; });
        },

        yazdir: function() { window.print(); },

        katalogYazdir: function(id) { window.open('{{ url("katalog") }}/'+id+'/yazdir', '_blank'); },

        katalogYukle: function(k) { window.location.href='{{ route("katalog.index") }}?katalog='+k.id; },

        katalogSil: function(id) {
            var self = this;
            if (!confirm('Bu kataloğu silmek istediğinizden emin misiniz?')) return;
            fetch('{{ url("katalog") }}/'+id, {
                method:'DELETE', headers:{ 'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content, 'Accept':'application/json' }
            }).then(function(r){
                if(r.ok){ self.kataloglar=self.kataloglar.filter(function(k){ return k.id!==id; }); if(self.katalogId===id) self.katalogId=null; }
            });
        },

        // Kişisel ürün ekleme
        kisiselAc: function() {
            this.kisiselForm = { ad: '', aciklama: '' };
            this.kisiselDosya = null; this.kisiselOnizleme = null;
            this.kisiselModal = true;
            this.$nextTick(function() {
                if (!window._ksQuill) {
                    window._ksQuill = new Quill('#ks-quill', {
                        theme: 'snow',
                        placeholder: 'Teknik özellikler…',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                [{ 'align': [] }],
                                ['clean']
                            ]
                        }
                    });
                } else {
                    window._ksQuill.setContents([]);
                }
            });
        },

        kisiselGorselSec: function(event) {
            var file = event.target.files[0];
            if (!file) return;
            this.kisiselDosya = file;
            var reader = new FileReader();
            var self = this;
            reader.onload = function(e) { self.kisiselOnizleme = e.target.result; };
            reader.readAsDataURL(file);
        },

        kisiselKaydet: function() {
            var self = this;
            if (!self.kisiselForm.ad.trim()) {
                self.hataMesaji = 'Ürün adı zorunludur.';
                setTimeout(function(){ self.hataMesaji=''; }, 3000); return;
            }
            self.kisiselKaydediliyor = true;
            var fd = new FormData();
            fd.append('ad', self.kisiselForm.ad.trim());
            var aciklama = window._ksQuill ? window._ksQuill.root.innerHTML : (self.kisiselForm.aciklama || '');
            fd.append('aciklama', aciklama);
            if (self.kisiselDosya) fd.append('gorsel', self.kisiselDosya);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            fetch('{{ route("katalog.urunlerim.store") }}', { method: 'POST', body: fd })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    if (data.success && data.urun) {
                        var gorseller = (data.urun.gorseller && Array.isArray(data.urun.gorseller)) ? data.urun.gorseller : (data.urun.gorsel ? [data.urun.gorsel] : []);
                        var yeni = { id: 'u'+data.urun.id, ad: data.urun.ad, kategori: 'Kişisel', gorsel: data.urun.gorsel||null, gorseller: gorseller, aciklama: data.urun.aciklama||'', tip: 'kullanici' };
                        self.tumUrunler = self.tumUrunler.concat([yeni]);
                        self.kisiselModal = false;
                        self.basariMesaji = '"'+yeni.ad+'" ürün listesine eklendi.';
                        setTimeout(function(){ self.basariMesaji=''; }, 3000);
                    }
                })
                .catch(function(){ self.hataMesaji='Kaydedilemedi.'; setTimeout(function(){ self.hataMesaji=''; }, 3000); })
                .finally(function(){ self.kisiselKaydediliyor=false; });
        },
    };
}
</script>

<div x-data="katalogBuilder()" class="katalog-builder">

  {{-- ═══ SOL PANEL ═══ --}}
  <aside class="kb-side">

    <div style="padding:16px 20px 14px;border-bottom:1px solid rgba(255,255,255,0.1);flex-shrink:0;display:flex;align-items:center;justify-content:space-between;">
      <div>
        <div style="font-size:9px;letter-spacing:.22em;color:#B8962E;text-transform:uppercase;margin-bottom:4px;">Katalog Oluşturucu</div>
        <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:white;letter-spacing:.05em;">SUÇEK</div>
      </div>
      <a href="{{ route('hesabim.index') }}"
         style="display:flex;align-items:center;gap:5px;font-size:10px;color:rgba(255,255,255,0.35);text-decoration:none;padding:4px 8px;border-radius:5px;border:1px solid rgba(255,255,255,0.1);transition:color .15s,border-color .15s;"
         onmouseover="this.style.color='rgba(255,255,255,0.7)';this.style.borderColor='rgba(255,255,255,0.25)'"
         onmouseout="this.style.color='rgba(255,255,255,0.35)';this.style.borderColor='rgba(255,255,255,0.1)'">
        <i class="ti ti-arrow-left" style="font-size:11px;"></i> Geri
      </a>
    </div>

    <div x-show="basariMesaji" class="kb-toast-ok"><i class="ti ti-circle-check"></i><span x-text="basariMesaji"></span></div>
    <div x-show="hataMesaji"   class="kb-toast-err"><i class="ti ti-alert-circle"></i><span x-text="hataMesaji"></span></div>

    <div class="kb-tabs">
      <button class="kb-tab" :class="aktifTab==='ayarlar'?'active':''" @click="aktifTab='ayarlar'">Ayarlar</button>
      <button class="kb-tab" :class="aktifTab==='urunler'?'active':''" @click="aktifTab='urunler'">
        Ürünler<span x-show="seciliUrunler.length>0" x-text="' ('+seciliUrunler.length+')'"></span>
      </button>
      <button class="kb-tab" :class="aktifTab==='kayitli'?'active':''" @click="aktifTab='kayitli'">Kayıtlı</button>
    </div>

    {{-- ── AYARLAR ── --}}
    <div x-show="aktifTab==='ayarlar'" style="padding:16px;overflow-y:auto;flex:1;">

      <div style="margin-bottom:10px;">
        <label class="kb-label">Firma Adı — 1. Satır</label>
        <input x-model="kapak.marka" type="text" placeholder="SUÇEK" class="kb-input">
      </div>
      <div style="margin-bottom:14px;">
        <label class="kb-label">Firma Adı — 2. Satır (Opsiyonel)</label>
        <input x-model="kapak.marka2" type="text" placeholder="Şube / Alt başlık…" class="kb-input">
      </div>

      <div style="margin-bottom:14px;">
        <label class="kb-label">Logo (Opsiyonel)</label>
        <div x-show="kapak.logo" style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:6px;padding:6px 10px;margin-bottom:7px;">
          <img :src="kapak.logo" style="height:32px;max-width:80px;object-fit:contain;border-radius:3px;">
          <button @click="kapak.logo=''" title="Kaldır"
                  style="margin-left:auto;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.35);font-size:13px;line-height:1;padding:2px;"
                  onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">
            <i class="ti ti-x"></i>
          </button>
        </div>
        <label for="logoFileInput"
               style="display:flex;align-items:center;gap:7px;padding:7px 12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:6px;cursor:pointer;font-size:12px;color:rgba(255,255,255,0.55);transition:border-color .15s;font-family:inherit;"
               onmouseover="this.style.borderColor='#B8962E';this.style.color='rgba(255,255,255,0.85)'"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.15)';this.style.color='rgba(255,255,255,0.55)'">
          <i class="ti ti-upload" style="font-size:14px;"></i>
          <span x-text="logoYukleniyor ? 'Yükleniyor…' : (kapak.logo ? 'Logo Değiştir' : 'Dosya Seç')"></span>
        </label>
        <input id="logoFileInput" type="file" accept="image/*" style="display:none;" @change="logoYukle($event)">
      </div>

      <div style="margin-bottom:14px;">
        <label class="kb-label">Katalog Başlığı</label>
        <input x-model="baslik" type="text" placeholder="Ürün Kataloğu" class="kb-input">
      </div>
      <div style="margin-bottom:14px;">
        <label class="kb-label">Alt Başlık</label>
        <input x-model="altBaslik" type="text" placeholder="Opsiyonel" class="kb-input">
      </div>

      <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:14px;margin-top:4px;">
        <div class="kb-sec" style="margin-bottom:12px;">Kapak Bilgileri</div>
        <div style="margin-bottom:12px;">
          <label class="kb-label">Kurum Adı</label>
          <input x-model="kapak.kurum" type="text" placeholder="T.C. Kurum Adı…" class="kb-input">
        </div>
        <div style="margin-bottom:12px;">
          <label class="kb-label">İhale Kayıt No</label>
          <input x-model="kapak.ihale_no" type="text" placeholder="2025/…" class="kb-input">
        </div>
        <div style="margin-bottom:12px;">
          <label class="kb-label">İhale Tarihi</label>
          <input x-model="kapak.ihale_tarih" type="date" class="kb-input">
        </div>
        <div style="margin-bottom:12px;">
          <label class="kb-label">İhale Yeri</label>
          <input x-model="kapak.ihale_yeri" type="text" placeholder="Kurum adı, toplantı salonu…" class="kb-input">
        </div>
        <div style="margin-bottom:14px;">
          <label class="kb-label">İhale Saati</label>
          <input x-model="kapak.ihale_saat" type="time" class="kb-input">
        </div>

        <div style="border-top:1px solid rgba(255,255,255,0.08);padding-top:12px;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <span class="kb-sec">Ek Bilgiler</span>
            <button @click="ekstraEkle()" style="display:flex;align-items:center;gap:4px;font-size:10px;color:#B8962E;background:transparent;border:none;cursor:pointer;font-family:inherit;padding:0;">
              <i class="ti ti-plus" style="font-size:11px;"></i> Ekle
            </button>
          </div>
          <div x-show="kapak.ekstralar.length===0" style="font-size:11px;color:rgba(255,255,255,0.2);font-style:italic;padding:2px 0 8px;">Avans oranı, teklif süresi gibi ek bilgiler…</div>
          <template x-for="(ekstra, ei) in kapak.ekstralar" :key="ei">
            <div class="kb-ekstra-row">
              <input x-model="ekstra.etiket" type="text" placeholder="Etiket" class="kb-input" style="flex:2;">
              <input x-model="ekstra.deger"  type="text" placeholder="Değer"  class="kb-input" style="flex:2;">
              <button @click="ekstraSil(ei)" class="kb-ctrl del" style="flex-shrink:0;"><i class="ti ti-x"></i></button>
            </div>
          </template>
        </div>
      </div>

      {{-- Stil Ayarları --}}
      <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:14px;margin-top:4px;">
        <div class="kb-sec" style="margin-bottom:12px;">Stil Ayarları</div>

        <div style="margin-bottom:14px;">
          <label class="kb-label">Kurum Adı — Yazı Tipi</label>
          <div style="display:flex;gap:5px;margin-top:5px;">
            <button class="kb-pill" :class="kapak.stiller.kurum_font==='inter'?'active':''"     @click="kapak.stiller.kurum_font='inter'">Sans-Serif</button>
            <button class="kb-pill" :class="kapak.stiller.kurum_font==='cormorant'?'active':''" @click="kapak.stiller.kurum_font='cormorant'">Serif</button>
          </div>
        </div>

        <div style="margin-bottom:12px;">
          <label class="kb-label" style="display:flex;justify-content:space-between;">
            <span>Kurum Adı — Boyut</span>
            <span x-text="kapak.stiller.kurum_boyut+'px'" style="color:rgba(255,255,255,0.55);"></span>
          </label>
          <input type="range" min="20" max="80" step="1" x-model.number="kapak.stiller.kurum_boyut"
                 style="width:100%;margin-top:6px;accent-color:#B8962E;cursor:pointer;">
        </div>

        <div style="margin-bottom:12px;">
          <label class="kb-label" style="display:flex;justify-content:space-between;">
            <span>Katalog Başlığı — Boyut</span>
            <span x-text="kapak.stiller.baslik_boyut+'px'" style="color:rgba(255,255,255,0.55);"></span>
          </label>
          <input type="range" min="13" max="32" step="1" x-model.number="kapak.stiller.baslik_boyut"
                 style="width:100%;margin-top:6px;accent-color:#B8962E;cursor:pointer;">
        </div>

        <div style="margin-bottom:8px;">
          <label class="kb-label">Vurgu Rengi</label>
          <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
            <input type="color" x-model="kapak.stiller.vurgu_renk"
                   style="width:38px;height:30px;border:1px solid rgba(255,255,255,0.2);border-radius:6px;background:transparent;cursor:pointer;padding:2px;flex-shrink:0;">
            <span x-text="kapak.stiller.vurgu_renk" style="font-size:11px;color:rgba(255,255,255,0.35);font-family:monospace;flex:1;"></span>
            <button @click="kapak.stiller.vurgu_renk='#B8962E'"
                    style="font-size:10px;color:rgba(255,255,255,0.3);background:none;border:none;cursor:pointer;white-space:nowrap;font-family:inherit;"
                    onmouseover="this.style.color='#B8962E'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Sıfırla</button>
          </div>
        </div>
      </div>
    </div>

    {{-- ── ÜRÜNLER ── --}}
    <div x-show="aktifTab==='urunler'">

      <div style="padding:10px 12px;border-bottom:1px solid rgba(255,255,255,0.1);">
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
          <input x-model="aramaMetni" type="text" placeholder="Ürün ara…" class="kb-input" style="flex:1;">
          <button @click="kisiselAc()"
                  style="flex-shrink:0;display:flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid rgba(184,150,46,0.4);border-radius:6px;color:#B8962E;font-size:15px;background:transparent;cursor:pointer;transition:all .15s;"
                  title="Kişisel Ürün Ekle"
                  onmouseover="this.style.background='rgba(184,150,46,0.1)'" onmouseout="this.style.background='transparent'">
            <i class="ti ti-user-plus"></i>
          </button>
          <button @click="urunleriYukle()"
                  style="flex-shrink:0;display:flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid rgba(255,255,255,0.12);border-radius:6px;color:rgba(255,255,255,0.35);font-size:14px;background:transparent;cursor:pointer;transition:all .15s;"
                  title="Listeyi Yenile"
                  onmouseover="this.style.color='rgba(255,255,255,0.7)'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">
            <i class="ti ti-refresh"></i>
          </button>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:5px;">
          <button class="kb-pill" :class="seciliKategori===''?'active':''"          @click="seciliKategori=''">Tümü</button>
          <button class="kb-pill" :class="seciliKategori==='Kişisel'?'active':''"   @click="seciliKategori='Kişisel'">Kişisel</button>
          @foreach(['spor','dekorasyon','insaat','diger'] as $kat)
          <button class="kb-pill" :class="seciliKategori==='{{ $kat }}'?'active':''" @click="seciliKategori='{{ $kat }}'">{{ Str::ucfirst($kat) }}</button>
          @endforeach
        </div>
      </div>

      <div class="kb-scroll" style="max-height:270px;border-bottom:1px solid rgba(255,255,255,0.1);">
        <div x-show="yukleniyorUrunler" style="padding:14px;text-align:center;font-size:12px;color:rgba(255,255,255,0.4);">
          <i class="ti ti-loader-2 animate-spin"></i> Yükleniyor…
        </div>
        <div x-show="!yukleniyorUrunler && filtreliUrunler.length===0" style="padding:14px;text-align:center;font-size:12px;color:rgba(255,255,255,0.3);">
          Ürün bulunamadı.
        </div>
        <template x-for="urun in filtreliUrunler" :key="urun.id">
          <div class="kb-urun-row">
            <div class="kb-urun-img">
              <img x-show="urun.gorsel" :src="urun.gorsel" :alt="urun.ad">
              <i x-show="!urun.gorsel" class="ti ti-photo" style="color:rgba(255,255,255,0.2);font-size:13px;"></i>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="display:flex;align-items:center;gap:5px;">
                <div x-text="urun.ad" style="font-size:12px;color:white;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></div>
                <span x-show="urun.tip==='kullanici'"
                      style="flex-shrink:0;font-size:8px;background:rgba(184,150,46,0.2);color:#B8962E;padding:1px 5px;border-radius:99px;letter-spacing:.05em;">Ben</span>
              </div>
              <div x-text="urun.kategori" style="font-size:10px;color:rgba(255,255,255,0.3);"></div>
            </div>
            <button @click="urunEkle(urun)" :disabled="secilmisMi(urun.id)"
                    style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer;font-size:14px;border-radius:4px;flex-shrink:0;transition:color .15s;"
                    :style="secilmisMi(urun.id) ? 'color:#4ade80;cursor:default' : 'color:rgba(255,255,255,0.4)'">
              <i :class="secilmisMi(urun.id) ? 'ti ti-check' : 'ti ti-plus'"></i>
            </button>
          </div>
        </template>
      </div>

      <div style="padding:7px 12px 4px;display:flex;align-items:center;justify-content:space-between;">
        <span class="kb-sec">Seçili Ürünler</span>
        <span class="kb-muted" x-text="seciliUrunler.length + ' ürün'"></span>
      </div>
      <div class="kb-scroll" style="max-height:200px;">
        <div x-show="seciliUrunler.length===0" style="padding:8px 12px;font-size:11px;color:rgba(255,255,255,0.25);font-style:italic;">Henüz ürün seçilmedi.</div>
        <template x-for="(urun, i) in seciliUrunler" :key="urun.id+'-'+i">
          <div class="kb-sel-row">
            <span x-text="i+1" style="font-size:11px;color:#B8962E;font-weight:700;width:18px;text-align:right;flex-shrink:0;"></span>
            <span x-text="urun.ad" style="flex:1;min-width:0;font-size:11px;color:rgba(255,255,255,0.85);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
            <button class="kb-ctrl" @click="yukariTasi(i)"  :disabled="i===0"                          title="Yukarı"><i class="ti ti-chevron-up"></i></button>
            <button class="kb-ctrl" @click="asagiTasi(i)"  :disabled="i===seciliUrunler.length-1"     title="Aşağı"><i class="ti ti-chevron-down"></i></button>
            <button class="kb-ctrl del" @click="urunKaldir(i)" title="Kaldır"><i class="ti ti-x"></i></button>
          </div>
        </template>
      </div>
    </div>

    {{-- ── KAYITLI ── --}}
    <div x-show="aktifTab==='kayitli'" style="flex:1;overflow-y:auto;">
      <div x-show="kataloglar.length===0" style="padding:24px 16px;text-align:center;color:rgba(255,255,255,0.25);font-size:12px;">
        <i class="ti ti-book-2" style="font-size:28px;display:block;margin-bottom:8px;opacity:.2;"></i>
        Henüz kayıtlı katalog yok.
      </div>
      <template x-for="k in kataloglar" :key="k.id">
        <div class="kb-saved-row">
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
            <div style="min-width:0;flex:1;">
              <div x-text="k.baslik" style="font-size:12px;font-weight:500;color:white;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></div>
              <div style="font-size:10px;color:rgba(255,255,255,0.3);margin-top:2px;display:flex;gap:6px;">
                <span x-text="k.urun_sayisi + ' ürün'"></span><span>·</span><span x-text="k.tarih"></span>
              </div>
              <div x-show="katalogId===k.id" style="font-size:10px;color:#B8962E;margin-top:3px;">
                <i class="ti ti-pencil" style="font-size:9px;"></i> Düzenleniyor
              </div>
            </div>
            <div style="display:flex;gap:2px;flex-shrink:0;">
              <button @click="katalogYazdir(k.id)" class="kb-icon-btn" title="Yazdır"
                      onmouseover="this.style.color='#B8962E'" onmouseout="this.style.color='rgba(255,255,255,0.4)'"><i class="ti ti-printer"></i></button>
              <button @click="katalogYukle(k)"     class="kb-icon-btn" title="Düzenle"
                      onmouseover="this.style.color='white'"  onmouseout="this.style.color='rgba(255,255,255,0.4)'"><i class="ti ti-edit"></i></button>
              <button @click="katalogSil(k.id)"    class="kb-icon-btn" title="Sil"
                      onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='rgba(255,255,255,0.4)'"><i class="ti ti-trash"></i></button>
            </div>
          </div>
        </div>
      </template>
    </div>

    <div style="flex:1;min-height:8px;"></div>

    <div style="border-top:1px solid rgba(255,255,255,0.1);padding:12px;display:flex;gap:8px;flex-shrink:0;">
      <button class="kb-save-btn" @click="kaydet()" :disabled="kaydediliyor || seciliUrunler.length===0">
        <i class="ti ti-device-floppy" x-show="!kaydediliyor"></i>
        <i class="ti ti-loader-2 animate-spin" x-show="kaydediliyor"></i>
        <span x-text="kaydediliyor ? 'Kaydediliyor…' : 'Kaydet'"></span>
      </button>
      <button class="kb-print-btn" @click="yazdir()" :disabled="seciliUrunler.length===0" title="Yazdır / PDF">
        <i class="ti ti-printer"></i>
      </button>
    </div>

  </aside>

  {{-- ═══ KİŞİSEL ÜRÜN EKLEME MODAL ═══ --}}
  <div x-show="kisiselModal" x-cloak
       style="position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:300;display:flex;align-items:center;justify-content:center;padding:16px;"
       @click.self="kisiselModal=false">
    <div style="background:#1E293B;border:1px solid rgba(255,255,255,0.1);border-radius:12px;width:100%;max-width:460px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
      <div style="padding:16px 20px 12px;border-bottom:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:14px;font-weight:600;color:white;">Kişisel Ürün Ekle</span>
        <button @click="kisiselModal=false" style="background:transparent;border:none;cursor:pointer;font-size:18px;color:rgba(255,255,255,0.4);line-height:1;padding:2px;">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <div style="padding:18px 20px;">
        <div style="margin-bottom:14px;">
          <label class="kb-label">Ürün Adı *</label>
          <input x-model="kisiselForm.ad" type="text" class="kb-input" placeholder="Ürün adını girin…" @keydown.enter="kisiselKaydet()">
        </div>
        <div style="margin-bottom:14px;">
          <label class="kb-label">Görsel</label>
          <div @click="$refs.kisiselGorsel.click()"
               style="width:100%;height:90px;border:1px dashed rgba(255,255,255,0.2);border-radius:6px;display:flex;align-items:center;justify-content:center;overflow:hidden;cursor:pointer;transition:border-color .15s;"
               onmouseover="this.style.borderColor='rgba(184,150,46,0.5)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'">
            <img x-show="kisiselOnizleme" :src="kisiselOnizleme" style="width:100%;height:100%;object-fit:contain;">
            <div x-show="!kisiselOnizleme" style="text-align:center;color:rgba(255,255,255,0.25);">
              <i class="ti ti-upload" style="font-size:20px;display:block;margin-bottom:3px;"></i>
              <span style="font-size:10px;">Tıkla, görsel seç</span>
            </div>
          </div>
          <input type="file" accept="image/*" x-ref="kisiselGorsel" @change="kisiselGorselSec($event)" style="display:none;">
        </div>
        <div>
          <label class="kb-label">Açıklama / Özellikler</label>
          <div id="ks-quill"></div>
        </div>
      </div>
      <div style="padding:12px 20px;border-top:1px solid rgba(255,255,255,0.1);display:flex;gap:8px;justify-content:flex-end;">
        <button @click="kisiselModal=false"
                style="padding:8px 14px;background:transparent;color:rgba(255,255,255,0.5);border:1px solid rgba(255,255,255,0.15);border-radius:6px;font-size:12px;cursor:pointer;font-family:inherit;">
          İptal
        </button>
        <button @click="kisiselKaydet()" :disabled="kisiselKaydediliyor"
                :style="kisiselKaydediliyor ? 'opacity:.5;cursor:not-allowed' : ''"
                style="padding:8px 18px;background:#B8962E;color:#0F0F0F;border:none;border-radius:6px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;cursor:pointer;font-family:inherit;transition:opacity .15s;">
          <span x-text="kisiselKaydediliyor ? 'Kaydediliyor…' : 'Ekle'"></span>
        </button>
      </div>
    </div>
  </div>

  {{-- ═══ ÖNİZLEME ═══ --}}
  <div class="katalog-preview">

    {{-- ── KAPAK ── --}}
    <div class="katalog-sayfa">

      <div style="margin:-18mm -20mm 0;height:5px;background:#0F172A;"></div>
      <div style="margin:0 -20mm 0;height:3px;" :style="{background:kapak.stiller.vurgu_renk}"></div>
      <div style="height:22px;"></div>

      <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;text-align:center;padding:20mm 6mm 8mm;">

        <div style="width:110px;height:110px;border-radius:50%;border:3px solid #0F172A;overflow:hidden;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;background:#F8FAFC;flex-shrink:0;">
          <img x-show="kapak.logo" :src="kapak.logo" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:10px;">
          <span x-show="!kapak.logo" x-text="(kapak.marka||'S').charAt(0)"
                style="font-size:40px;font-weight:700;color:#0F172A;font-family:'Inter',sans-serif;"></span>
        </div>

        <div style="font-size:16px;letter-spacing:.28em;text-transform:uppercase;margin-bottom:14px;font-family:'Inter',sans-serif;font-weight:600;"
             :style="{color:kapak.stiller.vurgu_renk}">ÜRÜN KATALOĞU</div>

        <div x-text="kapak.kurum || '— Kurum Adı —'"
             :style="{color: kapak.kurum ? '#0F172A' : '#CBD5E1', fontFamily: kapak.stiller.kurum_font==='cormorant' ? '\'Cormorant Garamond\',serif' : '\'Inter\',sans-serif', fontSize: kapak.stiller.kurum_boyut+'px'}"
             style="font-weight:700;letter-spacing:-.02em;line-height:1.0;margin-bottom:16px;"></div>

        <div style="width:48px;height:2px;margin-bottom:16px;" :style="{background:kapak.stiller.vurgu_renk}"></div>

        <div x-text="baslik" style="font-weight:500;color:#334155;letter-spacing:.02em;margin-bottom:6px;font-family:'Inter',sans-serif;"
             :style="{fontSize:kapak.stiller.baslik_boyut+'px'}"></div>

        <div x-show="altBaslik" x-text="altBaslik"
             style="font-size:11px;color:#94A3B8;letter-spacing:.06em;text-transform:uppercase;margin-bottom:16px;"></div>

        <div style="flex:1;min-height:0;"></div>

        <div x-show="kapak.ihale_no || kapak.ihale_tarih || kapak.ihale_yeri || kapak.ihale_saat || kapak.ekstralar.length>0"
             style="width:100%;border:1.5px solid #E2E8F0;border-radius:8px;overflow:hidden;text-align:left;">
          <div style="background:#0F172A;padding:9px 16px;">
            <span style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,0.5);">İhale Bilgileri</span>
          </div>
          <div x-show="kapak.ihale_no" style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
            <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Kayıt No</span>
            <span x-text="kapak.ihale_no" style="flex:1;text-align:right;font-size:16px;font-weight:700;color:#0F172A;font-family:'Inter',sans-serif;"></span>
          </div>
          <div x-show="kapak.ihale_tarih" style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
            <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Tarihi</span>
            <span x-text="kapak.ihale_tarih ? new Date(kapak.ihale_tarih).toLocaleDateString('tr-TR') : ''"
                  style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;"></span>
          </div>
          <div x-show="kapak.ihale_yeri" style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
            <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Yeri</span>
            <span x-text="kapak.ihale_yeri" style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;"></span>
          </div>
          <div x-show="kapak.ihale_saat" style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
            <span style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;">İhale Saati</span>
            <span x-text="kapak.ihale_saat" style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;"></span>
          </div>
          <template x-for="(ekstra, ei) in kapak.ekstralar" :key="'ek-'+ei">
            <div x-show="ekstra.etiket || ekstra.deger" style="display:flex;align-items:center;padding:12px 16px;border-top:1px solid #F1F5F9;">
              <span x-text="ekstra.etiket" style="flex:0 0 44%;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#94A3B8;"></span>
              <span x-text="ekstra.deger"  style="flex:1;text-align:right;font-size:16px;font-weight:600;color:#0F172A;"></span>
            </div>
          </template>
        </div>

        <div style="flex:1;min-height:0;"></div>
      </div>

      <div style="height:3px;margin:0 -20mm;" :style="{background:kapak.stiller.vurgu_renk}"></div>

      <div style="margin:0 -20mm -18mm;background:#0F172A;padding:14mm 24mm 16mm;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;">
        <div style="position:absolute;bottom:-14mm;right:-10mm;width:64mm;height:64mm;border-radius:50%;background:rgba(184,150,46,0.06);pointer-events:none;"></div>
        <div style="font-size:9px;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,0.35);margin-bottom:10px;font-family:'Inter',sans-serif;font-weight:500;">Hazırlayan Firma</div>
        <div x-text="kapak.marka" style="font-size:28px;font-weight:700;color:white;letter-spacing:.02em;line-height:1.1;font-family:'Inter',sans-serif;"></div>
        <div x-show="kapak.marka2" x-text="kapak.marka2"
             style="font-size:14px;font-weight:400;color:rgba(255,255,255,0.48);margin-top:6px;letter-spacing:.04em;font-family:'Inter',sans-serif;"></div>
      </div>
    </div>

    {{-- ── İÇİNDEKİLER ── --}}
    <div class="katalog-sayfa">
      <div style="margin-bottom:5px;">
        <div style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:600;color:#0F172A;letter-spacing:.03em;">İçindekiler</div>
      </div>
      <div style="height:3px;background:#B8962E;margin-bottom:3px;"></div>
      <div style="height:1px;background:#0F172A;margin-bottom:24px;"></div>
      <div style="flex:1;">
        <div x-show="seciliUrunler.length===0" style="padding:32px;text-align:center;color:#CBD5E1;font-size:12px;font-style:italic;">
          Ürün eklendikçe içindekiler otomatik dolacaktır
        </div>
        <template x-for="(urun, i) in seciliUrunler" :key="urun.id+'-toc'">
          <div class="kb-toc-entry">
            <span x-text="(i+1)+'.'" style="min-width:26px;font-family:'Cormorant Garamond',serif;font-size:14px;color:#B8962E;font-weight:600;flex-shrink:0;"></span>
            <span contenteditable="true"
                  x-effect="if(document.activeElement!==$el) $el.textContent = icindekiler[urun.id] || urun.ad"
                  @blur="tocGuncelle(urun.id, $event.target.textContent.trim(), urun.ad)"
                  @keydown.enter.prevent="$event.target.blur()"
                  style="flex:1;font-size:13px;color:#0F172A;cursor:text;padding:2px 6px;border-radius:2px;min-width:0;line-height:1.4;"
                  title="Tıklayarak düzenleyin"></span>
            <span style="font-size:10px;color:#B8962E;font-weight:600;margin-left:10px;flex-shrink:0;font-family:'Cormorant Garamond',serif;min-width:20px;text-align:right;"
                  x-text="i+3"></span>
          </div>
        </template>
      </div>
      <div style="margin:0 -20mm -18mm;">
        <div style="height:3px;background:#B8962E;"></div>
        <div style="height:8px;background:#0F172A;"></div>
      </div>
    </div>

    {{-- ── ÜRÜN SAYFALARI ── --}}
    <template x-for="(urun, i) in seciliUrunler" :key="urun.id+'-'+i">
      <div class="katalog-sayfa">
        <div style="margin-bottom:5px;">
          <span x-text="(i+1)+'.'" style="font-family:'Cormorant Garamond',serif;font-size:15px;color:#B8962E;font-weight:600;margin-right:7px;"></span>
          <span x-text="urun.ad"   style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0F172A;"></span>
        </div>
        <div style="height:3px;background:#B8962E;margin-bottom:3px;"></div>
        <div style="height:1px;background:#0F172A;margin-bottom:18px;"></div>

        <div style="display:flex;flex:1;gap:18px;align-items:flex-start;">
          <div style="width:46%;flex-shrink:0;display:flex;flex-direction:column;gap:6px;">
            <template x-for="(gUrl, gi) in (urun.gorseller && urun.gorseller.length ? urun.gorseller : (urun.gorsel ? [urun.gorsel] : []))" :key="'g'+gi">
              <div style="flex:1;min-height:80px;border:1px solid #E2E8F0;border-radius:4px;overflow:hidden;background:#F8FAFC;display:flex;align-items:center;justify-content:center;">
                <img :src="gUrl" :alt="urun.ad" style="max-width:100%;max-height:100%;object-fit:contain;padding:8px;">
              </div>
            </template>
            <div x-show="!urun.gorsel && (!urun.gorseller || !urun.gorseller.length)" style="min-height:200px;border:1px solid #E2E8F0;border-radius:4px;background:#F8FAFC;display:flex;align-items:center;justify-content:center;">
              <div style="text-align:center;color:#CBD5E1;font-size:12px;padding:28px;">
                <i class="ti ti-photo" style="font-size:28px;display:block;margin-bottom:6px;"></i>Görsel yok
              </div>
            </div>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:8px;letter-spacing:.18em;text-transform:uppercase;color:#B8962E;margin-bottom:8px;font-weight:600;padding-bottom:6px;border-bottom:1.5px solid rgba(184,150,46,0.25);">Teknik Özellikler</div>
            <div x-html="urun.aciklama || ''" style="font-size:12px;color:#334155;line-height:1.85;"></div>
            <div x-show="!urun.aciklama" style="font-size:11px;color:#CBD5E1;font-style:italic;">Açıklama girilmemiş</div>
          </div>
        </div>

        <div style="margin-top:12px;padding-top:7px;border-top:1px solid #F1F5F9;">
          <span style="font-size:10px;color:#CBD5E1;" x-text="(i+1)+' / '+seciliUrunler.length"></span>
        </div>

        <div style="margin:0 -20mm -18mm;">
          <div style="height:3px;background:#B8962E;"></div>
          <div style="height:8px;background:#0F172A;"></div>
        </div>
      </div>
    </template>

    <div x-show="seciliUrunler.length===0" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 0;color:#A8A8A8;">
      <i class="ti ti-layout-grid" style="font-size:48px;margin-bottom:16px;opacity:.3;"></i>
      <p style="font-size:14px;">Katalog önizlemesi buraya gelecek</p>
      <p style="font-size:12px;margin-top:6px;opacity:.7;">Sol panelden ürün seçin</p>
    </div>

  </div>

</div>
@endsection
