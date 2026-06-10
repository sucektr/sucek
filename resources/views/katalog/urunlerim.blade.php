@extends('layouts.katalog')
@section('title', 'Kendi Ürünlerim')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.um-wrap{max-width:900px;margin:0 auto;padding:24px 16px;}
.um-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;}
.um-btn-add{display:flex;align-items:center;gap:7px;padding:9px 18px;background:#B8962E;color:#0F0F0F;border:none;border-radius:8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;cursor:pointer;font-family:inherit;transition:background .15s;}
.um-btn-add:hover{background:#c8a84b;}
.um-card{background:white;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,0.08);margin-bottom:14px;display:flex;align-items:center;gap:14px;padding:14px 16px;}
.um-card-img{width:72px;height:72px;border-radius:6px;background:#F0F0F0;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;}
.um-card-img img{width:100%;height:100%;object-fit:cover;}
.um-card-info{flex:1;min-width:0;}
.um-card-ad{font-size:14px;font-weight:600;color:#0F0F0F;margin-bottom:3px;}
.um-card-aciklama{font-size:12px;color:#888;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:480px;}
.um-card-actions{display:flex;gap:6px;flex-shrink:0;}
.um-btn-icon{width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:transparent;border:1px solid rgba(0,0,0,0.12);border-radius:6px;cursor:pointer;color:#666;font-size:14px;transition:all .15s;}
.um-btn-icon:hover{color:#0F0F0F;border-color:rgba(0,0,0,0.3);}
.um-btn-icon.del:hover{color:#dc2626;border-color:#dc2626;}
/* Modal */
.um-modal-bg{position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:200;display:flex;align-items:center;justify-content:center;padding:16px;}
.um-modal{background:white;border-radius:12px;width:100%;max-width:620px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.3);}
.um-modal-head{padding:18px 20px 14px;border-bottom:1px solid rgba(0,0,0,0.08);display:flex;align-items:center;justify-content:space-between;}
.um-modal-body{padding:20px;}
.um-label{display:block;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:#666;margin-bottom:6px;}
.um-input{width:100%;padding:9px 12px;border:1px solid rgba(0,0,0,0.2);border-radius:7px;font-size:13px;font-family:inherit;outline:none;transition:border-color .15s;color:#0F0F0F;}
.um-input:focus{border-color:#B8962E;}
.um-gorsel-preview{width:100%;height:140px;border:2px dashed rgba(0,0,0,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#FAFAFA;cursor:pointer;transition:border-color .15s;}
.um-gorsel-preview:hover{border-color:#B8962E;}
.um-gorsel-preview img{width:100%;height:100%;object-fit:contain;}
.um-modal-foot{padding:14px 20px;border-top:1px solid rgba(0,0,0,0.08);display:flex;gap:8px;justify-content:flex-end;}
.um-btn-kaydet{padding:9px 20px;background:#B8962E;color:#0F0F0F;border:none;border-radius:7px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;cursor:pointer;font-family:inherit;}
.um-btn-kaydet:hover{background:#c8a84b;}
.um-btn-iptal{padding:9px 16px;background:transparent;color:#666;border:1px solid rgba(0,0,0,0.15);border-radius:7px;font-size:12px;cursor:pointer;font-family:inherit;}
.um-btn-iptal:hover{background:#F5F5F5;}
.ql-toolbar.ql-snow{border-radius:7px 7px 0 0;border-color:rgba(0,0,0,0.2);background:#FAFAFA;}
.ql-container.ql-snow{border-radius:0 0 7px 7px;border-color:rgba(0,0,0,0.2);font-size:13px;min-height:160px;}
.ql-editor{min-height:160px;line-height:1.8;color:#0F0F0F;}
.ql-editor.ql-blank::before{color:#B0B0B0;font-style:normal;}
.ql-snow .ql-picker.ql-header .ql-picker-label::before,.ql-snow .ql-picker.ql-header .ql-picker-item::before{content:'Normal';}
.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="1"]::before,.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="1"]::before{content:'Başlık 1';}
.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="2"]::before,.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="2"]::before{content:'Başlık 2';}
.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="3"]::before,.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="3"]::before{content:'Başlık 3';}
</style>
@endpush

@section('content')
<div style="flex:1;overflow-y:auto;background:#F5F5F5;" x-data="urunlerim()" @paste.window="modalAcik && gorselYapistir($event)">

  <div class="um-wrap">

    <div class="um-header">
      <div>
        <a href="{{ route('katalog.index') }}"
           style="font-size:11px;color:#888;text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:6px;">
          <i class="ti ti-arrow-left" style="font-size:10px;"></i> Katalog Oluşturucuya Dön
        </a>
        <h1 style="font-size:22px;font-weight:700;color:#0F0F0F;margin:0;">Kendi Ürünlerim</h1>
        <p style="font-size:12px;color:#888;margin:4px 0 0;">Kataloğa ekleyebileceğin kişisel ürün listesi</p>
      </div>
      <button class="um-btn-add" @click="yeniAc()">
        <i class="ti ti-plus"></i> Ürün Ekle
      </button>
    </div>

    <div x-show="urunler.length===0 && !yukleniyor"
         style="text-align:center;padding:60px 0;color:#C0C0C0;">
      <i class="ti ti-box-off" style="font-size:48px;display:block;margin-bottom:12px;opacity:.3;"></i>
      <p style="font-size:14px;margin:0;">Henüz ürün eklemediniz.</p>
      <p style="font-size:12px;margin:6px 0 0;">Yukarıdaki butona tıklayarak başlayın.</p>
    </div>

    <template x-for="urun in urunler" :key="urun.id">
      <div class="um-card">
        <div class="um-card-img">
          <img x-show="urun.gorsel" :src="urun.gorsel" :alt="urun.ad">
          <i x-show="!urun.gorsel" class="ti ti-photo" style="font-size:22px;color:#C0C0C0;"></i>
        </div>
        <div class="um-card-info">
          <div class="um-card-ad" x-text="urun.ad"></div>
          <div class="um-card-aciklama" x-html="urun.aciklama ? urun.aciklama.replace(/<[^>]+>/g,'').substring(0,120) : '—'"></div>
        </div>
        <div class="um-card-actions">
          <button class="um-btn-icon" @click="duzenleAc(urun)" title="Düzenle"><i class="ti ti-edit"></i></button>
          <button class="um-btn-icon del" @click="sil(urun.id)" title="Sil"><i class="ti ti-trash"></i></button>
        </div>
      </div>
    </template>

  </div>

  {{-- Modal --}}
  <div x-show="modalAcik" class="um-modal-bg" @click.self="modalKapat()" x-cloak>
    <div class="um-modal">

      <div class="um-modal-head">
        <span style="font-size:15px;font-weight:700;color:#0F0F0F;" x-text="duzenlenenId ? 'Ürünü Düzenle' : 'Yeni Ürün Ekle'"></span>
        <button @click="modalKapat()" style="background:transparent;border:none;cursor:pointer;font-size:18px;color:#888;line-height:1;">
          <i class="ti ti-x"></i>
        </button>
      </div>

      <div class="um-modal-body">
        <div style="margin-bottom:16px;">
          <label class="um-label">Ürün Adı *</label>
          <input x-model="form.ad" type="text" class="um-input" placeholder="Ürün adını girin…">
          <p x-show="hatalar.ad" x-text="hatalar.ad" style="font-size:11px;color:#dc2626;margin:4px 0 0;"></p>
        </div>

        <div style="margin-bottom:16px;">
          <label class="um-label">Görseller <span style="font-size:10px;color:#C0C0C0;font-weight:400;text-transform:none;letter-spacing:0;">en fazla 6</span></label>
          {{-- Mevcut görseller --}}
          <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px;" x-show="gorseller.length > 0">
            <template x-for="(g, idx) in gorseller" :key="idx">
              <div style="position:relative;width:68px;height:68px;border-radius:7px;overflow:hidden;border:1px solid rgba(0,0,0,0.1);background:#F5F5F5;flex-shrink:0;">
                <img :src="g.url" style="width:100%;height:100%;object-fit:cover;">
                <button @click.stop="gorselSil(idx)"
                        style="position:absolute;top:2px;right:2px;width:18px;height:18px;background:rgba(0,0,0,0.6);border:none;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;padding:0;">
                  <i class="ti ti-x" style="font-size:8px;color:white;line-height:1;"></i>
                </button>
                <div x-show="idx === 0"
                     style="position:absolute;bottom:0;left:0;right:0;text-align:center;background:rgba(184,150,46,0.85);font-size:7px;font-weight:700;padding:1px 0;color:#0F0F0F;">ANA</div>
              </div>
            </template>
          </div>
          {{-- Drop zone --}}
          <div x-show="gorseller.length < 6"
               @click="$refs.gorselInput.click()"
               @dragover.prevent="dragUzerinde=true"
               @dragleave.prevent="dragUzerinde=false"
               @drop.prevent="gorselBirak($event)"
               :class="dragUzerinde ? 'border-[#B8962E] !bg-[#FEFBF0]' : ''"
               class="um-gorsel-preview" style="height:80px;">
            <div style="text-align:center;color:#B0B0B0;pointer-events:none;">
              <i class="ti ti-upload" style="font-size:24px;display:block;margin-bottom:4px;"></i>
              <span style="font-size:12px;">Tıklayın, sürükleyin veya Ctrl+V</span>
            </div>
          </div>
          <input type="file" accept="image/*" multiple x-ref="gorselInput" @change="gorselSecCoklu($event)" style="display:none;">
        </div>

        <div style="margin-bottom:4px;">
          <label class="um-label">Açıklama / Teknik Özellikler</label>
          <div id="um-quill-editor" style="background:white;"></div>
        </div>
      </div>

      <div class="um-modal-foot">
        <button class="um-btn-iptal" @click="modalKapat()">İptal</button>
        <button class="um-btn-kaydet" @click="kaydet()" :disabled="kaydediliyor">
          <span x-text="kaydediliyor ? 'Kaydediliyor…' : (duzenlenenId ? 'Güncelle' : 'Kaydet')"></span>
        </button>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var _urunlerInit = {!! json_encode($urunler->values(), JSON_UNESCAPED_UNICODE) !!};

function urunlerim() {
    return {
        urunler: _urunlerInit,
        modalAcik: false,
        duzenlenenId: null,
        form: { ad: '', aciklama: '' },
        gorseller: [],
        dragUzerinde: false,
        kaydediliyor: false,
        hatalar: {},
        quill: null,

        init: function() {
            this.$nextTick(() => {
                this.quill = new Quill('#um-quill-editor', {
                    theme: 'snow',
                    placeholder: 'Teknik özellikler, açıklama…',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'align': [] }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'indent': '-1' }, { 'indent': '+1' }],
                            ['blockquote', 'clean']
                        ]
                    }
                });
            });
        },

        yeniAc: function() {
            this.form = { ad: '', aciklama: '' };
            this.gorseller = [];
            this.duzenlenenId = null;
            this.hatalar = {};
            this.modalAcik = true;
            this.$nextTick(() => { if (this.quill) this.quill.setContents([]); });
        },

        duzenleAc: function(urun) {
            this.form = { ad: urun.ad, aciklama: urun.aciklama };
            this.gorseller = (urun.gorseller_yollar || []).map(function(yol, i) {
                return { url: (urun.gorseller || [])[i] || null, file: null, yol: yol };
            });
            this.duzenlenenId = urun.id;
            this.hatalar = {};
            this.modalAcik = true;
            this.$nextTick(() => {
                if (this.quill) this.quill.clipboard.dangerouslyPasteHTML(urun.aciklama || '');
            });
        },

        modalKapat: function() {
            this.modalAcik = false;
        },

        gorselIsle: function(file) {
            if (!file || !file.type.startsWith('image/')) return;
            if (this.gorseller.length >= 6) return;
            var reader = new FileReader();
            var self = this;
            reader.onload = function(e) {
                self.gorseller = self.gorseller.concat([{ url: e.target.result, file: file, yol: null }]);
            };
            reader.readAsDataURL(file);
        },

        gorselSil: function(idx) {
            this.gorseller = this.gorseller.filter(function(_, i) { return i !== idx; });
        },

        gorselSecCoklu: function(event) {
            var self = this;
            Array.from(event.target.files).forEach(function(f) { self.gorselIsle(f); });
            event.target.value = '';
        },

        gorselBirak: function(event) {
            this.dragUzerinde = false;
            var self = this;
            Array.from(event.dataTransfer.files).forEach(function(f) { self.gorselIsle(f); });
        },

        gorselYapistir: function(event) {
            var items = event.clipboardData && event.clipboardData.items;
            if (!items) return;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var file = items[i].getAsFile();
                    if (file) { this.gorselIsle(file); break; }
                }
            }
        },

        kaydet: function() {
            var self = this;
            self.hatalar = {};

            if (!self.form.ad.trim()) {
                self.hatalar.ad = 'Ürün adı zorunludur.';
                return;
            }

            self.kaydediliyor = true;
            var aciklama = self.quill ? self.quill.root.innerHTML : (self.form.aciklama || '');

            var fd = new FormData();
            fd.append('ad', self.form.ad.trim());
            fd.append('aciklama', aciklama);
            var korunan = self.gorseller.filter(function(g) { return g.yol !== null; }).map(function(g) { return g.yol; });
            fd.append('gorseller_koru', JSON.stringify(korunan));
            self.gorseller.filter(function(g) { return g.file !== null; }).forEach(function(g) {
                fd.append('gorseller_yeni[]', g.file);
            });
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            var url = self.duzenlenenId
                ? '{{ route("katalog.urunlerim.update", ":id") }}'.replace(':id', self.duzenlenenId)
                : '{{ route("katalog.urunlerim.store") }}';

            fetch(url, { method: 'POST', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        if (self.duzenlenenId) {
                            var idx = self.urunler.findIndex(function(u) { return u.id === self.duzenlenenId; });
                            if (idx >= 0) { var arr = self.urunler.slice(); arr[idx] = data.urun; self.urunler = arr; }
                        } else {
                            self.urunler = [data.urun].concat(self.urunler);
                        }
                        self.modalKapat();
                    }
                })
                .catch(function() { alert('Kaydetme sırasında bir hata oluştu.'); })
                .finally(function() { self.kaydediliyor = false; });
        },

        sil: function(id) {
            if (!confirm('Bu ürünü silmek istediğinizden emin misiniz?')) return;
            var self = this;
            fetch('{{ url("katalog/urunlerim") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            }).then(function(r) {
                if (r.ok) self.urunler = self.urunler.filter(function(u) { return u.id !== id; });
            });
        },
    };
}
</script>
@endpush
