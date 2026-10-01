@extends('admin.layouts.app')
@section('title', 'Nümizmatik Kataloğu')
@section('page-title', 'Nümizmatik Kataloğu')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&display=swap" rel="stylesheet">
<style>
.nk-builder{display:flex;height:calc(100vh - 71px);margin:-1.5rem;overflow:hidden;}
.nk-side{width:320px;flex-shrink:0;background:#0F172A;display:flex;flex-direction:column;overflow-y:auto;}
.nk-side::-webkit-scrollbar{width:4px;}
.nk-side::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.15);border-radius:2px;}
.nk-tabs{display:flex;border-bottom:1px solid rgba(255,255,255,0.1);flex-shrink:0;}
.nk-tab{flex:1;padding:10px 0;font-size:10px;font-weight:500;text-transform:uppercase;letter-spacing:.1em;background:transparent;border:none;border-bottom:2px solid transparent;cursor:pointer;color:rgba(255,255,255,0.4);font-family:inherit;transition:color .15s,border-color .15s;}
.nk-tab.active{color:#B8962E;border-bottom-color:#B8962E;}
.nk-tab:hover:not(.active){color:rgba(255,255,255,0.7);}
.nk-label{display:block;font-size:10px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px;}
.nk-input{width:100%;padding:7px 12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);border-radius:6px;font-size:13px;color:white;font-family:inherit;outline:none;transition:border-color .15s;}
.nk-input:focus{border-color:#B8962E;}
.nk-input::placeholder{color:rgba(255,255,255,0.3);}
.nk-pill{font-size:9px;font-weight:500;text-transform:uppercase;letter-spacing:.08em;padding:4px 10px;border-radius:99px;border:1px solid rgba(255,255,255,0.15);background:transparent;color:rgba(255,255,255,0.4);cursor:pointer;font-family:inherit;transition:all .15s;}
.nk-pill:hover{color:rgba(255,255,255,0.8);}
.nk-pill.active{background:rgba(184,150,46,0.2);border-color:rgba(184,150,46,0.6);color:#B8962E;}
.nk-kalem-row{display:flex;align-items:center;gap:10px;padding:8px 12px;border-bottom:1px solid rgba(255,255,255,0.05);transition:background .1s;}
.nk-kalem-row:hover{background:rgba(255,255,255,0.04);}
.nk-kalem-img{width:36px;height:36px;border-radius:4px;background:rgba(255,255,255,0.1);overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
.nk-kalem-img img{width:100%;height:100%;object-fit:cover;}
.nk-sel-row{display:flex;align-items:center;gap:6px;padding:7px 12px;border-bottom:1px solid rgba(255,255,255,0.05);}
.nk-sel-row:hover{background:rgba(255,255,255,0.03);}
.nk-saved-row{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,0.08);transition:background .1s;}
.nk-saved-row:hover{background:rgba(255,255,255,0.04);}
.nk-save-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:7px;padding:10px;background:#B8962E;color:#0F172A;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;border:none;border-radius:8px;cursor:pointer;font-family:inherit;transition:background .15s;}
.nk-save-btn:hover:not(:disabled){background:#c8a84b;}
.nk-save-btn:disabled{opacity:.4;cursor:not-allowed;}
.nk-print-btn{width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:transparent;border:1px solid rgba(255,255,255,0.2);color:rgba(255,255,255,0.5);border-radius:8px;cursor:pointer;font-size:16px;transition:all .15s;}
.nk-print-btn:hover:not(:disabled){color:white;border-color:rgba(255,255,255,0.4);}
.nk-print-btn:disabled{opacity:.3;cursor:not-allowed;}
.nk-toast-ok{margin:8px;padding:7px 10px;background:rgba(21,128,61,0.3);border:1px solid rgba(74,222,128,0.3);border-radius:6px;font-size:11px;color:#86efac;display:flex;align-items:center;gap:6px;}
.nk-toast-err{margin:8px;padding:7px 10px;background:rgba(127,29,29,0.4);border:1px solid rgba(252,165,165,0.3);border-radius:6px;font-size:11px;color:#fca5a5;display:flex;align-items:center;gap:6px;}
.nk-sec{font-size:10px;color:#B8962E;text-transform:uppercase;letter-spacing:.1em;}
.nk-muted{font-size:10px;color:rgba(255,255,255,0.3);}
.nk-ctrl{width:20px;height:20px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer;color:rgba(255,255,255,0.3);font-size:11px;border-radius:3px;transition:color .15s;}
.nk-ctrl:hover:not(:disabled){color:rgba(255,255,255,0.8);}
.nk-ctrl.del:hover{color:#f87171;}
.nk-ctrl:disabled{opacity:.2;cursor:default;}
.nk-icon-btn{width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer;color:rgba(255,255,255,0.4);font-size:14px;border-radius:4px;transition:color .15s;}
.nk-scroll{overflow-y:auto;}
.nk-scroll::-webkit-scrollbar{width:3px;}
.nk-scroll::-webkit-scrollbar-thumb{background:rgba(0,0,0,0.1);border-radius:2px;}
/* Katalog sayfaları */
.nk-preview{flex:1;overflow-y:auto;background:#EBEBEB;padding:2rem;height:100%;}
.nk-preview::-webkit-scrollbar{width:6px;}
.nk-preview::-webkit-scrollbar-thumb{background:rgba(0,0,0,0.15);border-radius:3px;}
.nk-sayfa{width:210mm;min-height:297mm;background:white;padding:16mm 16mm;box-shadow:0 4px 24px rgba(0,0,0,0.1);margin:0 auto 24px;display:flex;flex-direction:column;flex-shrink:0;}
.nk-kart{display:flex;gap:14px;align-items:stretch;flex:1;border-bottom:1px solid #F1F5F9;padding:14px 0;}
.nk-kart:last-child{border-bottom:none;}
/* Print */
@media print{
    *,-webkit-*{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
    .admin-sidebar,header{display:none!important;}
    .admin-content{margin-left:0!important;}
    main.p-6{padding:0!important;}
    .nk-builder{display:block!important;height:auto!important;overflow:visible!important;margin:0!important;}
    .nk-side{display:none!important;}
    .nk-preview{overflow:visible!important;height:auto!important;background:white!important;padding:0!important;}
    .nk-sayfa{width:210mm!important;min-height:297mm!important;margin:0!important;box-shadow:none!important;border-radius:0!important;page-break-after:always;break-after:page;}
    .nk-sayfa:last-child{page-break-after:avoid;break-after:avoid;}
    @page{size:A4 portrait;margin:0;}
}
</style>
@endpush

@php
$initialData = [
    'katalogId'    => $duzenlenenKatalog?->id,
    'baslik'       => $duzenlenenKatalog?->baslik ?? 'Nümizmatik Kataloğu',
    'altBaslik'    => $duzenlenenKatalog?->alt_baslik ?? '',
    'kapak'        => $kapakBirlestir,
    'seciliKalemler' => $duzenlenenKalemler->values()->toArray(),
    'kataloglar'   => $kataloglar->map(fn($k) => [
        'id'           => $k->id,
        'baslik'       => $k->baslik,
        'kalem_sayisi' => count($k->koleksiyon_idler ?? []),
        'tarih'        => $k->created_at->format('d.m.Y'),
    ])->values()->toArray(),
];
@endphp

@section('content')

<script>
window._nkInit = {!! json_encode($initialData, JSON_UNESCAPED_UNICODE) !!};

function numizmatikKatalogBuilder() {
    var init = window._nkInit || {};
    return {
        aktifTab:          'kalemler',
        katalogId:         init.katalogId  || null,
        baslik:            init.baslik      || 'Nümizmatik Kataloğu',
        altBaslik:         init.altBaslik   || '',
        kapak: {
            marka: (init.kapak && init.kapak.marka) || 'SUÇEK',
            logo:  (init.kapak && init.kapak.logo)  || '',
        },
        seciliKalemler:    init.seciliKalemler || [],
        kataloglar:        init.kataloglar     || [],
        tumKalemler:       [],
        aramaMetni:        '',
        seciliDurum:       '',
        yukleniyorKalemler:false,
        kaydediliyor:      false,
        logoYukleniyor:    false,
        basariMesaji:      '',
        hataMesaji:        '',
        aramaTimer:        null,

        get sayfalar() {
            var arr = this.seciliKalemler;
            var out = [];
            for (var i = 0; i < arr.length; i += 3) out.push(arr.slice(i, i + 3));
            return out;
        },

        secilmisMi: function(id) {
            return this.seciliKalemler.some(function(k){ return k.id === id; });
        },

        init: function() {
            this.kalemleriYukle();
            var self = this;
            this.$watch('aramaMetni', function(){ self.kalemleriYukleGecikmeli(); });
            this.$watch('seciliDurum', function(){ self.kalemleriYukle(); });
        },

        kalemleriYukleGecikmeli: function() {
            var self = this;
            clearTimeout(self.aramaTimer);
            self.aramaTimer = setTimeout(function(){ self.kalemleriYukle(); }, 300);
        },

        kalemleriYukle: function() {
            var self = this;
            self.yukleniyorKalemler = true;
            var params = new URLSearchParams();
            if (self.aramaMetni) params.set('ara', self.aramaMetni);
            if (self.seciliDurum) params.set('durum', self.seciliDurum);
            fetch('{{ route("admin.numizmatik-katalog.kalemler") }}?' + params.toString())
                .then(function(r){ return r.json(); })
                .then(function(data){ self.tumKalemler = data; })
                .catch(function(){
                    self.hataMesaji = 'Ürünler yüklenemedi.';
                    setTimeout(function(){ self.hataMesaji = ''; }, 4000);
                })
                .finally(function(){ self.yukleniyorKalemler = false; });
        },

        kalemEkle: function(kalem) {
            if (!this.secilmisMi(kalem.id)) {
                this.seciliKalemler = this.seciliKalemler.concat([Object.assign({}, kalem)]);
            }
        },

        kalemKaldir: function(index) {
            this.seciliKalemler = this.seciliKalemler.filter(function(_, i){ return i !== index; });
        },

        yukariTasi: function(index) {
            if (index === 0) return;
            var arr = this.seciliKalemler.slice();
            var tmp = arr[index - 1]; arr[index - 1] = arr[index]; arr[index] = tmp;
            this.seciliKalemler = arr;
        },

        asagiTasi: function(index) {
            if (index >= this.seciliKalemler.length - 1) return;
            var arr = this.seciliKalemler.slice();
            var tmp = arr[index + 1]; arr[index + 1] = arr[index]; arr[index] = tmp;
            this.seciliKalemler = arr;
        },

        kaydet: function() {
            var self = this;
            if (!self.baslik.trim()) {
                self.hataMesaji = 'Katalog başlığı gereklidir.';
                setTimeout(function(){ self.hataMesaji = ''; }, 3000);
                self.aktifTab = 'ayarlar';
                return;
            }
            if (self.seciliKalemler.length === 0) {
                self.hataMesaji = 'En az 1 ürün seçin.';
                setTimeout(function(){ self.hataMesaji = ''; }, 3000);
                return;
            }
            self.kaydediliyor = true;
            fetch('{{ route("admin.numizmatik-katalog.kaydet") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    id:                self.katalogId,
                    baslik:            self.baslik,
                    alt_baslik:        self.altBaslik,
                    kapak:             self.kapak,
                    koleksiyon_idler:  self.seciliKalemler.map(function(k){ return k.id; }),
                }),
            })
            .then(function(r){ return r.json(); })
            .then(function(data) {
                if (data.success) {
                    self.katalogId = data.id;
                    self.basariMesaji = 'Katalog kaydedildi!';
                    setTimeout(function(){ self.basariMesaji = ''; }, 3000);
                    var item = { id: data.id, baslik: self.baslik, kalem_sayisi: self.seciliKalemler.length, tarih: new Date().toLocaleDateString('tr-TR') };
                    var idx = self.kataloglar.findIndex(function(k){ return k.id === data.id; });
                    if (idx >= 0) { var arr = self.kataloglar.slice(); arr[idx] = item; self.kataloglar = arr; }
                    else self.kataloglar = [item].concat(self.kataloglar);
                }
            })
            .catch(function(){
                self.hataMesaji = 'Kaydetme başarısız.';
                setTimeout(function(){ self.hataMesaji = ''; }, 4000);
            })
            .finally(function(){ self.kaydediliyor = false; });
        },

        logoYukle: function(event) {
            var self = this;
            var file = event.target.files[0];
            if (!file) return;
            self.logoYukleniyor = true;
            var fd = new FormData();
            fd.append('logo', file);
            fetch('{{ route("admin.numizmatik-katalog.logo-yukle") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: fd,
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                if (data.url) self.kapak.logo = data.url;
                else { self.hataMesaji = 'Logo yüklenemedi.'; setTimeout(function(){ self.hataMesaji=''; }, 3000); }
            })
            .catch(function(){
                self.hataMesaji = 'Logo yüklenemedi.';
                setTimeout(function(){ self.hataMesaji=''; }, 3000);
            })
            .finally(function(){ self.logoYukleniyor = false; event.target.value = ''; });
        },

        yazdir: function() { window.print(); },

        katalogYazdir: function(id) {
            window.open('{{ url("admin/numizmatik-katalog") }}/' + id + '/yazdir', '_blank');
        },

        katalogYukle: function(k) {
            window.location.href = '{{ route("admin.numizmatik-katalog.index") }}?katalog=' + k.id;
        },

        katalogSil: function(id) {
            var self = this;
            if (!confirm('Bu kataloğu silmek istediğinizden emin misiniz?')) return;
            fetch('{{ url("admin/numizmatik-katalog") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            })
            .then(function(r){
                if (r.ok) {
                    self.kataloglar = self.kataloglar.filter(function(k){ return k.id !== id; });
                    if (self.katalogId === id) self.katalogId = null;
                    self.basariMesaji = 'Katalog silindi.';
                    setTimeout(function(){ self.basariMesaji = ''; }, 3000);
                }
            });
        },
    };
}
</script>

<div x-data="numizmatikKatalogBuilder()" class="nk-builder">

  {{-- ═══ SOL PANEL ═══ --}}
  <aside class="nk-side">

    <div style="padding:16px 20px 14px;border-bottom:1px solid rgba(255,255,255,0.1);flex-shrink:0;">
      <div style="font-size:9px;letter-spacing:.22em;color:#B8962E;text-transform:uppercase;margin-bottom:4px;">Katalog Oluşturucu</div>
      <div style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:600;color:white;letter-spacing:.05em;">Nümizmatik</div>
    </div>

    <div x-show="basariMesaji" class="nk-toast-ok">
      <i class="ti ti-circle-check"></i><span x-text="basariMesaji"></span>
    </div>
    <div x-show="hataMesaji" class="nk-toast-err">
      <i class="ti ti-alert-circle"></i><span x-text="hataMesaji"></span>
    </div>

    <div class="nk-tabs">
      <button class="nk-tab" :class="aktifTab==='ayarlar'?'active':''" @click="aktifTab='ayarlar'">Ayarlar</button>
      <button class="nk-tab" :class="aktifTab==='kalemler'?'active':''" @click="aktifTab='kalemler'">
        Ürünler<span x-show="seciliKalemler.length>0" x-text="' ('+seciliKalemler.length+')'"></span>
      </button>
      <button class="nk-tab" :class="aktifTab==='kayitli'?'active':''" @click="aktifTab='kayitli'">Kayıtlı</button>
    </div>

    {{-- AYARLAR --}}
    <div x-show="aktifTab==='ayarlar'" style="padding:16px;overflow-y:auto;flex:1;">
      <div style="margin-bottom:14px;">
        <label class="nk-label">Firma Adı</label>
        <input x-model="kapak.marka" type="text" placeholder="SUÇEK" class="nk-input">
      </div>
      <div style="margin-bottom:14px;">
        <label class="nk-label">Logo (Opsiyonel)</label>
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
        <label class="nk-label">Katalog Başlığı</label>
        <input x-model="baslik" type="text" placeholder="Nümizmatik Kataloğu" class="nk-input">
      </div>
      <div style="margin-bottom:14px;">
        <label class="nk-label">Alt Başlık</label>
        <input x-model="altBaslik" type="text" placeholder="Opsiyonel" class="nk-input">
      </div>
    </div>

    {{-- ÜRÜNLER --}}
    <div x-show="aktifTab==='kalemler'">

      <div style="padding:10px 12px;border-bottom:1px solid rgba(255,255,255,0.1);">
        <input x-model="aramaMetni" type="text" placeholder="Ad veya stok kodu ara…" class="nk-input" style="margin-bottom:8px;">
        <div style="display:flex;flex-wrap:wrap;gap:5px;">
          <button class="nk-pill" :class="seciliDurum===''?'active':''" @click="seciliDurum=''">Tümü</button>
          <button class="nk-pill" :class="seciliDurum==='satista'?'active':''" @click="seciliDurum='satista'">Satışta</button>
          <button class="nk-pill" :class="seciliDurum==='rezerve'?'active':''" @click="seciliDurum='rezerve'">Rezerve</button>
          <button class="nk-pill" :class="seciliDurum==='satildi'?'active':''" @click="seciliDurum='satildi'">Satıldı</button>
        </div>
      </div>

      <div class="nk-scroll" style="max-height:270px;border-bottom:1px solid rgba(255,255,255,0.1);">
        <div x-show="yukleniyorKalemler" style="padding:14px;text-align:center;font-size:12px;color:rgba(255,255,255,0.4);">
          <i class="ti ti-loader-2 animate-spin"></i> Yükleniyor…
        </div>
        <div x-show="!yukleniyorKalemler && tumKalemler.length===0"
             style="padding:14px;text-align:center;font-size:12px;color:rgba(255,255,255,0.3);">
          Ürün bulunamadı.
        </div>
        <template x-for="kalem in tumKalemler" :key="kalem.id">
          <div class="nk-kalem-row">
            <div class="nk-kalem-img">
              <img x-show="kalem.gorsel" :src="kalem.gorsel" :alt="kalem.ad">
              <i x-show="!kalem.gorsel" class="ti ti-photo" style="color:rgba(255,255,255,0.2);font-size:13px;"></i>
            </div>
            <div style="flex:1;min-width:0;">
              <div x-text="kalem.ad" style="font-size:12px;color:white;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></div>
              <div x-text="kalem.stok_kodu" style="font-size:10px;color:rgba(255,255,255,0.3);"></div>
            </div>
            <button @click="kalemEkle(kalem)" :disabled="secilmisMi(kalem.id)"
                    style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;background:transparent;border:none;cursor:pointer;font-size:14px;border-radius:4px;flex-shrink:0;transition:color .15s;"
                    :style="secilmisMi(kalem.id) ? 'color:#4ade80;cursor:default' : 'color:rgba(255,255,255,0.4)'">
              <i :class="secilmisMi(kalem.id) ? 'ti ti-check' : 'ti ti-plus'"></i>
            </button>
          </div>
        </template>
      </div>

      <div style="padding:7px 12px 4px;display:flex;align-items:center;justify-content:space-between;">
        <span class="nk-sec">Seçili Ürünler</span>
        <span class="nk-muted" x-text="seciliKalemler.length + ' ürün'"></span>
      </div>
      <div class="nk-scroll" style="max-height:200px;">
        <div x-show="seciliKalemler.length===0" style="padding:8px 12px;font-size:11px;color:rgba(255,255,255,0.25);font-style:italic;">
          Henüz ürün seçilmedi.
        </div>
        <template x-for="(kalem, i) in seciliKalemler" :key="kalem.id+'-'+i">
          <div class="nk-sel-row">
            <span x-text="i+1" style="font-size:11px;color:#B8962E;font-weight:700;width:18px;text-align:right;flex-shrink:0;"></span>
            <span x-text="kalem.ad" style="flex:1;min-width:0;font-size:11px;color:rgba(255,255,255,0.85);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
            <button class="nk-ctrl" @click="yukariTasi(i)" :disabled="i===0" title="Yukarı"><i class="ti ti-chevron-up"></i></button>
            <button class="nk-ctrl" @click="asagiTasi(i)" :disabled="i===seciliKalemler.length-1" title="Aşağı"><i class="ti ti-chevron-down"></i></button>
            <button class="nk-ctrl del" @click="kalemKaldir(i)" title="Kaldır"><i class="ti ti-x"></i></button>
          </div>
        </template>
      </div>
    </div>

    {{-- KAYITLI --}}
    <div x-show="aktifTab==='kayitli'" style="flex:1;overflow-y:auto;">
      <div x-show="kataloglar.length===0" style="padding:24px 16px;text-align:center;color:rgba(255,255,255,0.25);font-size:12px;">
        <i class="ti ti-book-2" style="font-size:28px;display:block;margin-bottom:8px;opacity:.2;"></i>
        Henüz kayıtlı katalog yok.
      </div>
      <template x-for="k in kataloglar" :key="k.id">
        <div class="nk-saved-row">
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
            <div style="min-width:0;flex:1;">
              <div x-text="k.baslik" style="font-size:12px;font-weight:500;color:white;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></div>
              <div style="font-size:10px;color:rgba(255,255,255,0.3);margin-top:2px;display:flex;gap:6px;">
                <span x-text="k.kalem_sayisi + ' ürün'"></span>
                <span>·</span>
                <span x-text="k.tarih"></span>
              </div>
              <div x-show="katalogId===k.id" style="font-size:10px;color:#B8962E;margin-top:3px;">
                <i class="ti ti-pencil" style="font-size:9px;"></i> Düzenleniyor
              </div>
            </div>
            <div style="display:flex;gap:2px;flex-shrink:0;">
              <button @click="katalogYazdir(k.id)" class="nk-icon-btn" title="Yazdır"
                      onmouseover="this.style.color='#B8962E'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                <i class="ti ti-printer"></i>
              </button>
              <button @click="katalogYukle(k)" class="nk-icon-btn" title="Düzenle"
                      onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                <i class="ti ti-edit"></i>
              </button>
              <button @click="katalogSil(k.id)" class="nk-icon-btn" title="Sil"
                      onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                <i class="ti ti-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>

    <div style="flex:1;min-height:8px;"></div>

    <div style="border-top:1px solid rgba(255,255,255,0.1);padding:12px;display:flex;gap:8px;flex-shrink:0;">
      <button class="nk-save-btn" @click="kaydet()" :disabled="kaydediliyor || seciliKalemler.length===0">
        <i class="ti ti-device-floppy" x-show="!kaydediliyor"></i>
        <i class="ti ti-loader-2 animate-spin" x-show="kaydediliyor"></i>
        <span x-text="kaydediliyor ? 'Kaydediliyor…' : 'Kaydet'"></span>
      </button>
      <button class="nk-print-btn" @click="yazdir()" :disabled="seciliKalemler.length===0" title="Yazdır / PDF">
        <i class="ti ti-printer"></i>
      </button>
    </div>

  </aside>

  {{-- ═══ ÖNİZLEME ═══ --}}
  <div class="nk-preview">

    {{-- ── KAPAK ── --}}
    <div class="nk-sayfa" style="align-items:center;justify-content:center;text-align:center;">
      <div style="width:110px;height:110px;border-radius:50%;border:3px solid #0F172A;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-bottom:24px;background:#F8FAFC;flex-shrink:0;">
        <img x-show="kapak.logo" :src="kapak.logo" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:10px;">
        <span x-show="!kapak.logo" x-text="(kapak.marka||'S').charAt(0)"
              style="font-size:40px;font-weight:700;color:#0F172A;font-family:'Inter',sans-serif;"></span>
      </div>
      <div style="font-size:14px;letter-spacing:.28em;text-transform:uppercase;color:#B8962E;margin-bottom:18px;font-weight:600;">NÜMİZMATİK KATALOĞU</div>
      <div x-text="baslik" style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:600;color:#0F172A;letter-spacing:.01em;margin-bottom:10px;max-width:140mm;"></div>
      <div x-show="altBaslik" x-text="altBaslik" style="font-size:13px;color:#94A3B8;letter-spacing:.04em;margin-bottom:26px;"></div>
      <div style="width:48px;height:2px;background:#B8962E;margin-bottom:26px;"></div>
      <div x-text="kapak.marka" style="font-size:16px;font-weight:600;color:#334155;letter-spacing:.06em;"></div>
      <div x-text="new Date().toLocaleDateString('tr-TR', {year:'numeric',month:'long'})" style="font-size:11px;color:#CBD5E1;margin-top:6px;letter-spacing:.04em;"></div>
    </div>

    {{-- ── ÜRÜN SAYFALARI: her sayfada 3 kalem ── --}}
    <template x-for="(sayfa, sIndex) in sayfalar" :key="sIndex">
      <div class="nk-sayfa">
        <div style="margin-bottom:12px;">
          <span x-text="baslik" style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:600;color:#0F172A;"></span>
        </div>
        <div style="height:2px;background:#B8962E;margin-bottom:1px;"></div>
        <div style="height:1px;background:#0F172A;margin-bottom:4px;"></div>

        <template x-for="(kalem, kIndex) in sayfa" :key="kalem.id+'-'+kIndex">
          <div class="nk-kart">
            <div style="width:260px;flex-shrink:0;display:flex;gap:6px;">
              <div style="flex:1;min-width:0;">
                <div style="border:1px solid #E2E8F0;border-radius:4px;overflow:hidden;background:#F8FAFC;display:flex;align-items:center;justify-content:center;height:118px;">
                  <img x-show="kalem.gorsel" :src="kalem.gorsel" :alt="kalem.ad" style="max-width:100%;max-height:100%;object-fit:contain;padding:6px;">
                  <div x-show="!kalem.gorsel" style="text-align:center;color:#CBD5E1;font-size:10px;padding:10px;">
                    <i class="ti ti-photo" style="font-size:18px;display:block;margin-bottom:3px;"></i>Yok
                  </div>
                </div>
                <div style="text-align:center;font-size:8px;letter-spacing:.14em;text-transform:uppercase;color:#94A3B8;margin-top:3px;">Ön Yüz</div>
              </div>
              <div style="flex:1;min-width:0;">
                <div style="border:1px solid #E2E8F0;border-radius:4px;overflow:hidden;background:#F8FAFC;display:flex;align-items:center;justify-content:center;height:118px;">
                  <img x-show="kalem.arka" :src="kalem.arka" :alt="kalem.ad" style="max-width:100%;max-height:100%;object-fit:contain;padding:6px;">
                  <div x-show="!kalem.arka" style="text-align:center;color:#CBD5E1;font-size:10px;padding:10px;">
                    <i class="ti ti-photo" style="font-size:18px;display:block;margin-bottom:3px;"></i>Yok
                  </div>
                </div>
                <div style="text-align:center;font-size:8px;letter-spacing:.14em;text-transform:uppercase;color:#94A3B8;margin-top:3px;">Arka Yüz</div>
              </div>
            </div>
            <div style="flex:1;min-width:0;display:flex;flex-direction:column;justify-content:center;">
              <span style="font-size:7px;letter-spacing:.14em;text-transform:uppercase;color:#B8962E;font-weight:600;margin-bottom:4px;" x-text="(sIndex*3)+kIndex+1"></span>
              <div x-text="kalem.ad" style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:600;color:#0F172A;line-height:1.2;margin-bottom:8px;"></div>
              <div style="display:flex;flex-direction:column;gap:4px;">
                <div x-show="kalem.ulke" style="display:flex;gap:8px;font-size:11px;">
                  <span style="color:#94A3B8;width:60px;flex-shrink:0;">Ülke</span>
                  <span x-text="kalem.ulke" style="color:#334155;font-weight:500;"></span>
                </div>
                <div x-show="kalem.stok_kodu" style="display:flex;gap:8px;font-size:11px;">
                  <span style="color:#94A3B8;width:60px;flex-shrink:0;">Stok Kodu</span>
                  <span x-text="kalem.stok_kodu" style="color:#334155;font-weight:500;font-family:monospace;"></span>
                </div>
              </div>
              <div x-text="kalem.fiyat ? (Number(kalem.fiyat).toLocaleString('tr-TR',{minimumFractionDigits:2,maximumFractionDigits:2}) + ' ₺') : ''"
                   style="font-size:16px;color:#0F172A;font-weight:700;margin-top:10px;"></div>
            </div>
          </div>
        </template>

        <div style="flex:1;"></div>
        <div style="margin:10px -16mm -16mm;">
          <div style="height:2px;background:#B8962E;"></div>
          <div style="height:6px;background:#0F172A;"></div>
        </div>
      </div>
    </template>

    <div x-show="seciliKalemler.length===0" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 0;color:#A8A8A8;">
      <i class="ti ti-coin" style="font-size:48px;margin-bottom:16px;opacity:.3;"></i>
      <p style="font-size:14px;">Katalog önizlemesi buraya gelecek</p>
      <p style="font-size:12px;margin-top:6px;opacity:.7;">Sol panelden ürün seçin</p>
    </div>

  </div>

</div>
@endsection
