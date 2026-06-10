@extends('layouts.tree')
@section('title', 'Soy Ağacı — SUÇEK')

@section('content')
<div id="soy-agaci-wrap" style="height:100%;display:flex;flex-direction:column;overflow:hidden;background:#f7f3ed;">

  {{-- ─── Araç Çubuğu ────────────────────────────────────────────────────── --}}
  <div id="toolbar" style="display:flex;align-items:center;gap:6px;padding:7px 14px;background:#fff;border-bottom:1px solid rgba(0,0,0,0.08);flex-shrink:0;flex-wrap:wrap;">
    <span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:#7a4f2e;letter-spacing:0.5px;margin-right:4px;">Soy Ağacı</span>
    <div style="width:1px;height:22px;background:rgba(0,0,0,0.10);margin:0 2px;"></div>

    <button class="tb-btn tb-btn-primary" onclick="openAdd()">
      <i class="ti ti-user-plus"></i> Kişi Ekle
    </button>

    <div style="width:1px;height:22px;background:rgba(0,0,0,0.10);margin:0 2px;"></div>

    <button class="tb-btn" id="bday-btn" onclick="toggleBdayPanel()">
      <i class="ti ti-cake"></i> <span id="bday-count"></span>
    </button>

    <div style="display:flex;align-items:center;border:1px solid rgba(0,0,0,0.12);border-radius:6px;overflow:hidden;background:#fff;">
      <button class="zoom-btn" onclick="zoom(-0.12)">−</button>
      <span id="zoom-lbl" style="font-size:11px;color:#A0A0A0;min-width:38px;text-align:center;font-weight:700;">100%</span>
      <button class="zoom-btn" onclick="zoom(0.12)">+</button>
    </div>

    <button class="tb-btn" onclick="fitView()"><i class="ti ti-arrows-maximize"></i> Sığdır</button>
    <button class="tb-btn" onclick="printTree()"><i class="ti ti-printer"></i> Yazdır</button>

    <div style="flex:1;"></div>

    {{-- Bekleyen değişiklikler --}}
    <div id="bekleyen-wrap" style="display:none;">
      <span id="bekleyen-badge" style="display:inline-flex;align-items:center;gap:4px;background:#FEF3C7;border:1px solid #F59E0B;color:#92400E;font-size:10px;font-weight:700;padding:3px 9px;border-radius:6px;">
        <i class="ti ti-clock"></i> <span id="bekleyen-sayi">0</span> bekleyen değişiklik
      </span>
    </div>

    <a href="{{ route('hesabim.index') }}" class="tb-btn" style="text-decoration:none;">
      <i class="ti ti-arrow-left"></i> Geri
    </a>
  </div>

  {{-- ─── Hizalama Çubuğu ────────────────────────────────────────────────── --}}
  <div id="align-bar">
    <span id="sel-count" style="color:#6B6B6B;font-size:11px;font-weight:600;">0 seçili</span>
    <div style="width:1px;height:20px;background:rgba(0,0,0,0.10);"></div>
    <button class="a-btn" title="Sola Hizala"    onclick="align('left')">⬤⬤⬤</button>
    <button class="a-btn" title="Yatay Ortala"   onclick="align('centerH')">◉</button>
    <button class="a-btn" title="Sağa Hizala"    onclick="align('right')">⬤⬤⬤</button>
    <div style="width:1px;height:20px;background:rgba(0,0,0,0.10);"></div>
    <button class="a-btn" title="Üste Hizala"    onclick="align('top')">⬆</button>
    <button class="a-btn" title="Dikey Ortala"   onclick="align('centerV')">↕</button>
    <button class="a-btn" title="Alta Hizala"    onclick="align('bottom')">⬇</button>
    <div style="width:1px;height:20px;background:rgba(0,0,0,0.10);"></div>
    <button id="btn-group" class="a-btn" style="padding:0 10px;font-size:11px;background:#141414;color:#fff;border-color:#141414;" onclick="groupSelected()">
      <i class="ti ti-layout-grid"></i> Grupla
    </button>
    <button class="a-btn" onclick="deselectAll()" title="Seçimi Temizle" style="font-size:11px;">✕</button>
  </div>

  {{-- ─── Canvas ─────────────────────────────────────────────────────────── --}}
  <div id="canvas-wrap" style="flex:1;position:relative;overflow:hidden;cursor:grab;
       background-image:radial-gradient(rgba(0,0,0,0.06) 1px,transparent 1px);
       background-size:24px 24px;">
    <svg id="tree-svg" style="position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;"></svg>
    <div id="tree-container" style="position:absolute;transform-origin:0 0;">
      <div id="frames-layer"></div>
      <div id="nodes-layer"></div>
    </div>
    <div id="sel-box" style="position:absolute;border:1.5px dashed #b8783a;background:rgba(184,120,58,0.06);border-radius:4px;pointer-events:none;display:none;z-index:50;"></div>

    {{-- Boş durum --}}
    <div id="empty-state" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
      <i class="ti ti-topology-star-3" style="font-size:64px;color:rgba(0,0,0,0.12);display:block;margin-bottom:12px;"></i>
      <p style="font-family:'Cormorant Garamond',serif;font-size:22px;color:rgba(0,0,0,0.25);margin-bottom:4px;">Soy ağacı henüz boş</p>
      <p style="font-size:12px;color:rgba(0,0,0,0.20);">"Kişi Ekle" ile değişiklik önerebilirsiniz</p>
    </div>
  </div>

  {{-- ─── Doğum Günleri Paneli ───────────────────────────────────────────── --}}
  <div id="bday-panel" style="position:fixed;top:110px;right:14px;width:270px;z-index:300;display:none;">
    <div style="background:#fffde7;border:1.5px solid #f9c74f;border-radius:10px;padding:13px 15px;box-shadow:0 6px 24px rgba(0,0,0,0.12);">
      <button onclick="toggleBdayPanel()" style="float:right;background:none;border:none;cursor:pointer;color:#a08800;font-size:16px;line-height:1;">✕</button>
      <h4 style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:700;color:#7a5c00;margin-bottom:9px;">🎂 Yaklaşan Doğum Günleri</h4>
      <div id="bday-list"></div>
    </div>
  </div>

  {{-- ─── Detay Paneli ───────────────────────────────────────────────────── --}}
  <div id="detail-panel" style="position:fixed;left:14px;top:110px;width:220px;z-index:150;transform:translateX(-250px);transition:transform .3s;">
    <div style="background:#fff;border:1.5px solid rgba(0,0,0,0.10);border-radius:12px;padding:14px;box-shadow:0 6px 24px rgba(0,0,0,0.12);">
      <h3 id="dp-name" style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;color:#7a4f2e;margin-bottom:9px;padding-bottom:7px;border-bottom:1px solid rgba(0,0,0,0.08);">—</h3>
      <div id="dp-body"></div>
      <div style="display:flex;gap:5px;margin-top:10px;padding-top:9px;border-top:1px solid rgba(0,0,0,0.08);flex-wrap:wrap;">
        <button class="tb-btn" style="font-size:10px;padding:3px 8px;" onclick="editSelected()"><i class="ti ti-pencil"></i> Düzenle</button>
        <button class="tb-btn tb-btn-danger" style="font-size:10px;padding:3px 8px;" onclick="deleteSelected()"><i class="ti ti-trash"></i> Sil</button>
        <button class="tb-btn" style="font-size:10px;padding:3px 8px;" onclick="closeDetail()">✕</button>
      </div>
    </div>
  </div>

  {{-- ─── Lejant ─────────────────────────────────────────────────────────── --}}
  <div id="legend" style="position:fixed;bottom:14px;right:14px;background:#fff;border:1px solid rgba(0,0,0,0.10);border-radius:8px;padding:8px 12px;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    <div style="display:flex;align-items:center;gap:5px;font-size:10px;color:#5A5A5A;font-weight:600;margin-bottom:3px;"><span style="width:9px;height:9px;border-radius:50%;background:#2a5f8f;display:inline-block;"></span> Erkek</div>
    <div style="display:flex;align-items:center;gap:5px;font-size:10px;color:#5A5A5A;font-weight:600;margin-bottom:3px;"><span style="width:9px;height:9px;border-radius:50%;background:#8f2a5f;display:inline-block;"></span> Kadın</div>
    <div style="display:flex;align-items:center;gap:5px;font-size:10px;color:#5A5A5A;font-weight:600;margin-bottom:3px;"><span style="width:22px;height:2px;background:#7a4f2e;display:inline-block;border-radius:1px;"></span> Ebeveyn</div>
    <div style="display:flex;align-items:center;gap:5px;font-size:10px;color:#5A5A5A;font-weight:600;"><span style="width:22px;height:2px;background:#8f2a5f;opacity:.7;display:inline-block;border-radius:1px;border-top:2px dashed #8f2a5f;"></span> Eş</div>
  </div>

</div>

{{-- ─── Kişi Ekle/Düzenle Modal ────────────────────────────────────────── --}}
<div id="add-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);display:none;align-items:center;justify-content:center;z-index:500;backdrop-filter:blur(4px);">
<div class="sa-modal">
  <h2 id="modal-title">Kişi Ekle — Onay İsteği</h2>
  <p style="font-size:11px;color:#A0A0A0;margin:-12px 0 16px;"><i class="ti ti-info-circle"></i> Değişiklik admin onayından geçtikten sonra ağaçta görünür.</p>

  <div class="sa-section-title"><i class="ti ti-user"></i> Kişisel Bilgiler</div>

  {{-- Fotoğraf --}}
  <div class="sa-fg">
    <label>Profil Fotoğrafı</label>
    <div id="photo-zone" class="sa-photo-zone"
         ondragover="event.preventDefault();this.classList.add('drag-over')"
         ondragleave="this.classList.remove('drag-over')"
         ondrop="handlePhotoDrop(event)">
      <input type="file" accept="image/*" id="f-photo" onchange="handlePhotoChange(event)" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
      <div style="position:relative;flex-shrink:0;">
        <div id="photo-placeholder" style="width:56px;height:56px;border-radius:50%;background:#F5F5F5;border:2px dashed rgba(0,0,0,0.20);display:flex;align-items:center;justify-content:center;font-size:20px;color:#C0C0C0;">
          <i class="ti ti-user"></i>
        </div>
        <img id="photo-preview-img" style="display:none;width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid rgba(0,0,0,0.12);" src="" alt="">
        <button type="button" id="photo-remove-btn" onclick="removePhoto(event)" style="display:none;position:absolute;top:-3px;right:-3px;width:18px;height:18px;border-radius:50%;background:#CC2200;color:#fff;border:none;cursor:pointer;font-size:11px;display:none;align-items:center;justify-content:center;line-height:1;"><i class="ti ti-x"></i></button>
      </div>
      <div>
        <strong style="font-size:12px;color:#5A5A5A;display:block;margin-bottom:2px;">Fotoğraf yükle veya sürükle</strong>
        <span style="font-size:10.5px;color:#A0A0A0;">JPG, PNG · Maks. 5 MB</span>
      </div>
    </div>
  </div>

  <div class="sa-row2">
    <div class="sa-fg"><label>Ad <span style="color:#CC2200;">*</span></label><input id="f-ad" type="text" placeholder="Ad" autocomplete="off"></div>
    <div class="sa-fg"><label>Soyad</label><input id="f-soyad" type="text" placeholder="Soyad" autocomplete="off"></div>
  </div>
  <div class="sa-row2">
    <div class="sa-fg"><label>Cinsiyet</label>
      <select id="f-cinsiyet">
        <option value="male">Erkek</option>
        <option value="female">Kadın</option>
        <option value="unknown">Belirtilmemiş</option>
      </select>
    </div>
    <div class="sa-fg"><label>Meslek / Unvan</label><input id="f-meslek" type="text" placeholder="Öğretmen…" autocomplete="off"></div>
  </div>

  <div class="sa-section-title"><i class="ti ti-calendar"></i> Tarihler</div>
  <div class="sa-row3">
    <div class="sa-fg"><label>Doğum Günü</label><input id="f-bd-gun" type="number" placeholder="Gün" min="1" max="31"></div>
    <div class="sa-fg"><label>Doğum Ayı</label>
      <select id="f-bd-ay">
        <option value="">— Ay —</option>
        @foreach(['','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'] as $i => $ay)
          @if($i > 0)<option value="{{ $i }}">{{ $ay }}</option>@endif
        @endforeach
      </select>
    </div>
    <div class="sa-fg"><label>Doğum Yılı</label><input id="f-bd-yil" type="number" placeholder="1960" min="1800" max="2030"></div>
  </div>
  <div class="sa-row2" style="margin-top:8px;">
    <div class="sa-fg"><label>Ölüm Yılı</label><input id="f-olum" type="number" placeholder="(varsa)" min="1800" max="2030"></div>
    <div class="sa-fg"><label>Yer</label><input id="f-yer" type="text" placeholder="Ankara" autocomplete="off"></div>
  </div>
  <div class="sa-fg"><label>Notlar</label><textarea id="f-not" placeholder="Ek bilgiler…"></textarea></div>

  <div class="sa-section-title"><i class="ti ti-git-branch"></i> Aile Bağlantıları</div>
  <div id="rel-list" class="rel-list"></div>
  <div style="display:flex;gap:7px;align-items:center;margin-top:7px;">
    <select id="new-rel-type" class="sa-select-inline">
      <option value="parent">↑ Ebeveyni</option>
      <option value="child">↓ Çocuğu</option>
      <option value="couple">♥ Eş</option>
      <option value="sibling">↔ Kardeşi</option>
    </select>
    <select id="new-rel-person" class="sa-select-inline" style="flex:1;"></select>
    <button class="tb-btn tb-btn-primary" style="font-size:11px;padding:5px 10px;" onclick="addRelRow()">+ Ekle</button>
  </div>

  <div style="display:flex;gap:9px;justify-content:flex-end;margin-top:18px;padding-top:14px;border-top:1px solid rgba(0,0,0,0.08);">
    <button class="tb-btn" onclick="closeModal('add-modal')">İptal</button>
    <button id="btn-onayla-gonder" class="tb-btn tb-btn-primary" onclick="savePerson()">
      <i class="ti ti-send"></i> Onaya Gönder
    </button>
  </div>
</div>
</div>

{{-- ─── Grup / Frame Modal ──────────────────────────────────────────────── --}}
<div id="frame-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);align-items:center;justify-content:center;z-index:500;backdrop-filter:blur(4px);">
<div class="sa-modal" style="width:340px;">
  <h2 id="frame-modal-title">Grubu Adlandır</h2>
  <div class="sa-fg">
    <label>Grup Adı</label>
    <input id="fm-label" type="text" placeholder="Anne Tarafı, Baba Tarafı…" autocomplete="off">
  </div>
  <div class="sa-fg">
    <label>Renk</label>
    <div id="color-swatches" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:4px;"></div>
  </div>
  <div class="sa-fg">
    <label>Üyeler</label>
    <div id="member-chips" style="display:flex;flex-wrap:wrap;gap:5px;margin-top:4px;"></div>
  </div>
  <div style="display:flex;gap:9px;justify-content:flex-end;margin-top:18px;padding-top:14px;border-top:1px solid rgba(0,0,0,0.08);">
    <button class="tb-btn" onclick="closeModal('frame-modal')">İptal</button>
    <button class="tb-btn tb-btn-primary" onclick="saveFrame()"><i class="ti ti-check"></i> Grubu Oluştur</button>
  </div>
</div>
</div>
@endsection

@push('styles')
<style>
:root {
  --sa-brown:#7a4f2e; --sa-amber:#b8783a; --sa-amber-lt:#f0d9b5;
  --sa-male:#2a5f8f;  --sa-male-lt:#d8eaf8;
  --sa-female:#8f2a5f; --sa-female-lt:#f8d8ec;
  --sa-neut:#5a6e5a;  --sa-neut-lt:#ddeedd;
}

/* Toolbar butonları */
.tb-btn {
  display:inline-flex;align-items:center;gap:4px;
  background:#fff;border:1px solid rgba(0,0,0,0.12);color:#5A5A5A;
  padding:5px 11px;border-radius:6px;cursor:pointer;
  font-size:11px;font-weight:600;transition:all .18s;white-space:nowrap;
  font-family:'DM Sans',sans-serif;
}
.tb-btn:hover { background:#F5F5F5;border-color:rgba(0,0,0,0.20);color:#0F0F0F; }
.tb-btn-primary { background:#141414;color:#fff;border-color:#141414; }
.tb-btn-primary:hover { background:#2a2a2a;border-color:#2a2a2a;color:#fff; }
.tb-btn-danger:hover { background:#FEE2E2;border-color:#FCA5A5;color:#CC2200; }
.zoom-btn { background:none;border:none;width:26px;height:28px;cursor:pointer;font-size:15px;color:#5A5A5A; }
.zoom-btn:hover { background:#F5F5F5; }

/* Hizalama çubuğu */
#align-bar {
  position:fixed;bottom:18px;left:50%;transform:translateX(-50%);
  background:#fff;border:1.5px solid rgba(0,0,0,0.10);border-radius:10px;
  padding:7px 12px;display:none;align-items:center;gap:6px;
  box-shadow:0 4px 16px rgba(0,0,0,0.12);z-index:300;
}
#align-bar.visible { display:flex; }
.a-btn {
  background:#F5F5F5;border:1px solid rgba(0,0,0,0.10);border-radius:5px;
  width:28px;height:28px;cursor:pointer;display:flex;align-items:center;justify-content:center;
  font-size:12px;transition:all .15s;flex-shrink:0;
}
.a-btn:hover { background:#F0D9B5;border-color:#b8783a; }

/* Person node */
.person-node {
  position:absolute;width:164px;
  background:#fff;border:1.5px solid rgba(0,0,0,0.10);
  border-radius:10px;padding:13px 11px 9px;
  cursor:pointer;transition:border-color .2s,box-shadow .2s,transform .15s;
  user-select:none;pointer-events:all;
  box-shadow:0 2px 8px rgba(0,0,0,0.08);
}
.person-node:hover { border-color:var(--sa-amber);box-shadow:0 6px 24px rgba(0,0,0,0.12),0 0 0 3px var(--sa-amber-lt);transform:translateY(-2px);z-index:20; }
.person-node.selected { border-color:var(--sa-brown);box-shadow:0 8px 32px rgba(0,0,0,0.15),0 0 0 3px var(--sa-amber-lt);z-index:30; }
.person-node.multi-selected { border-color:var(--sa-amber);box-shadow:0 0 0 2.5px var(--sa-amber),0 4px 16px rgba(0,0,0,0.10);z-index:25; }
.person-node.male    { border-top:3px solid var(--sa-male); }
.person-node.female  { border-top:3px solid var(--sa-female); }
.person-node.unknown { border-top:3px solid var(--sa-neut); }
.node-avatar { width:48px;height:48px;border-radius:50%;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:700;overflow:hidden;border:2px solid rgba(0,0,0,0.08); }
.node-avatar.male   { background:var(--sa-male-lt);color:var(--sa-male); }
.node-avatar.female { background:var(--sa-female-lt);color:var(--sa-female); }
.node-avatar.unknown{ background:var(--sa-neut-lt);color:var(--sa-neut); }
.node-avatar img { width:100%;height:100%;object-fit:cover; }
.node-name  { font-family:'Cormorant Garamond',serif;font-size:13.5px;font-weight:700;text-align:center;color:#0F0F0F;line-height:1.3;margin-bottom:2px; }
.node-dates { font-size:10px;text-align:center;color:#A0A0A0;font-weight:600; }
.node-place { font-size:10px;text-align:center;color:#A0A0A0;font-style:italic; }
.node-actions { display:none;gap:4px;justify-content:center;flex-wrap:wrap;margin-top:8px;padding-top:7px;border-top:1px solid rgba(0,0,0,0.08); }
.person-node:hover .node-actions,.person-node.selected .node-actions { display:flex; }
.nd-btn { background:#F5F5F5;border:1px solid rgba(0,0,0,0.10);color:#5A5A5A;padding:3px 7px;border-radius:4px;font-size:10px;font-weight:600;cursor:pointer;transition:all .15s;font-family:'DM Sans',sans-serif; }
.nd-btn:hover { background:#b8783a;color:#fff;border-color:#b8783a; }
.nd-btn.del:hover { background:#CC2200;color:#fff;border-color:#CC2200; }

/* Frame */
.tree-frame { position:absolute;border-radius:12px;border:2.5px solid;pointer-events:none;transition:box-shadow .2s; }
.frame-header {
  position:absolute;top:-1px;left:14px;
  display:inline-flex;align-items:center;gap:7px;
  padding:2px 10px;border-radius:6px;border:inherit;border-width:1.5px;
  font-size:11px;font-weight:700;font-family:'Cormorant Garamond',serif;
  transform:translateY(-50%);white-space:nowrap;pointer-events:all;cursor:default;
}
.frame-edit-btn,.frame-del-btn { background:none;border:none;cursor:pointer;font-size:11px;padding:1px 2px;border-radius:3px;opacity:.6;transition:opacity .15s; }
.frame-edit-btn:hover,.frame-del-btn:hover { opacity:1; }
.frame-del-btn:hover { color:#CC2200; }

/* SVG edges */
.edge-parent  { stroke:var(--sa-brown);stroke-width:1.8;fill:none;opacity:.5; }
.edge-couple  { stroke:var(--sa-female);stroke-width:2;fill:none;stroke-dasharray:5 3;opacity:.55; }
.edge-sibling { stroke:var(--sa-neut);stroke-width:1.5;fill:none;stroke-dasharray:3 3;opacity:.5; }

/* Modal */
.sa-modal {
  background:#fff;border:1px solid rgba(0,0,0,0.10);border-radius:14px;
  padding:24px 26px 20px;width:480px;max-width:96vw;max-height:90vh;
  overflow-y:auto;box-shadow:0 12px 40px rgba(0,0,0,0.18);
  animation:saSlideUp .25s;
}
@keyframes saSlideUp { from{transform:translateY(16px);opacity:0}to{transform:translateY(0);opacity:1} }
.sa-modal h2 { font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:700;color:#7a4f2e;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid rgba(0,0,0,0.08); }
.sa-section-title { font-family:'Cormorant Garamond',serif;font-size:13px;font-weight:700;color:#7a4f2e;margin:14px 0 8px;padding-bottom:4px;border-bottom:1px solid rgba(0,0,0,0.08);display:flex;align-items:center;gap:5px; }
.sa-fg { margin-bottom:10px; }
.sa-fg label { display:block;font-size:10px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#A0A0A0;margin-bottom:3px; }
.sa-fg input,.sa-fg select,.sa-fg textarea {
  width:100%;background:#FAFAFA;border:1.5px solid rgba(0,0,0,0.12);border-radius:7px;
  color:#0F0F0F;padding:8px 11px;font-family:'DM Sans',sans-serif;font-size:13px;
  outline:none;transition:border-color .2s;
}
.sa-fg input:focus,.sa-fg select:focus,.sa-fg textarea:focus { border-color:#7a4f2e; }
.sa-fg textarea { resize:vertical;min-height:52px; }
.sa-row2 { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
.sa-row3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px; }
.sa-select-inline { background:#FAFAFA;border:1.5px solid rgba(0,0,0,0.12);border-radius:7px;padding:6px 9px;font-size:12px;color:#0F0F0F;outline:none;cursor:pointer; }
.sa-select-inline:focus { border-color:#7a4f2e; }
.sa-photo-zone {
  display:flex;align-items:center;gap:14px;background:#FAFAFA;
  border:2px dashed rgba(0,0,0,0.15);border-radius:10px;padding:12px 14px;
  cursor:pointer;transition:border-color .2s;position:relative;
}
.sa-photo-zone:hover,.sa-photo-zone.drag-over { border-color:#7a4f2e; }
.rel-list { display:flex;flex-direction:column;gap:6px; }
.rel-row { display:flex;align-items:center;gap:8px;background:#FAFAFA;border:1px solid rgba(0,0,0,0.08);border-radius:8px;padding:6px 9px; }
.rel-row select { background:#fff;border:1px solid rgba(0,0,0,0.10);border-radius:5px;color:#0F0F0F;padding:4px 7px;font-size:11px;outline:none;cursor:pointer; }
.rel-row .person-name { flex:1;font-size:12px;font-weight:600;color:#5A5A5A; }
.rel-row .rm-rel { background:none;border:none;cursor:pointer;color:#C0C0C0;font-size:14px;border-radius:4px;transition:color .15s;padding:0 3px; }
.rel-row .rm-rel:hover { color:#CC2200; }
.color-swatch { width:24px;height:24px;border-radius:50%;cursor:pointer;border:2.5px solid transparent;transition:transform .15s,border-color .15s; }
.color-swatch:hover { transform:scale(1.15); }
.color-swatch.picked { border-color:#0F0F0F; }

/* Detail panel rows */
.dp-row { display:flex;gap:7px;margin-bottom:5px;font-size:11px;align-items:flex-start; }
.dp-label { color:#A0A0A0;min-width:54px;font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding-top:1px; }
.dp-val { color:#5A5A5A;font-weight:600;line-height:1.4; }

/* Toast */
.sa-toast { position:fixed;bottom:16px;left:50%;transform:translateX(-50%);background:#141414;color:#fff;padding:8px 18px;border-radius:18px;font-size:12px;font-weight:600;z-index:9999;pointer-events:none;white-space:nowrap;animation:saToastIn .25s,saToastOut .4s 2.4s forwards; }
@keyframes saToastIn  { from{transform:translateX(-50%) translateY(20px);opacity:0}to{transform:translateX(-50%) translateY(0);opacity:1} }
@keyframes saToastOut { to{opacity:0} }

#canvas-wrap { cursor:grab; }
#canvas-wrap.grabbing { cursor:grabbing; }
</style>
@endpush

@push('scripts')
<script>
// ── VERİ ─────────────────────────────────────────────────────────────────────
let people = [], relations = [], frames = [], nid = 1, fnid = 1;
let selectedIds = new Set(), singleSelected = null;
let editingId = null, pendingRels = [], currentPhotoData = null;
let editingFrameId = null, pendingFrameMembers = [];
let scale = 1, panX = 80, panY = 80;
let panning = false, lmx = 0, lmy = 0;
let selBoxing = false, sbStart = {x:0, y:0};
let _posTimer = null;

const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const VERI_URL  = '{{ route("soy-agaci.veri") }}';
const DEG_URL   = '{{ route("soy-agaci.degisiklik") }}';
const KONUM_URL = '{{ route("soy-agaci.konum") }}';
const GRUP_URL  = '{{ route("soy-agaci.grup.kaydet") }}';
const GRUP_DEL  = '{{ url("soy-agaci/grup") }}';

const MONTHS = ['','Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
const FRAME_COLORS = [
  {hex:'#b8783a',bg:'rgba(184,120,58,.07)'}, {hex:'#2a5f8f',bg:'rgba(42,95,143,.07)'},
  {hex:'#5a6e5a',bg:'rgba(90,110,90,.07)'},  {hex:'#8f2a5f',bg:'rgba(143,42,95,.07)'},
  {hex:'#6a4c9c',bg:'rgba(106,76,156,.07)'}, {hex:'#c0392b',bg:'rgba(192,57,43,.07)'},
];
const FRAME_PAD = 28;
let pickedColor = FRAME_COLORS[0];

const wrap        = document.getElementById('canvas-wrap');
const svgEl       = document.getElementById('tree-svg');
const container   = document.getElementById('tree-container');
const nodesLayer  = document.getElementById('nodes-layer');
const framesLayer = document.getElementById('frames-layer');
const selBoxEl    = document.getElementById('sel-box');

// ── VERİ YÜKLE ───────────────────────────────────────────────────────────────
async function veriYukle() {
  try {
    const r = await fetch(VERI_URL, {headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}});
    const d = await r.json();
    people    = d.kisiler.map(k => ({
      id: k.id, ad: k.ad, soyad: k.soyad, gender: k.cinsiyet,
      meslek: k.meslek, bdGun: k.bd_gun, bdAy: k.bd_ay, bdYil: k.bd_yil,
      olum: k.olum_yil, yer: k.yer, not: k.notlar, photo: k.foto,
      x: k.konum_x, y: k.konum_y, locked: false,
    }));
    relations = d.iliskiler.map(i => ({id: i.id, from: i.from, to: i.to, type: i.tip}));
    frames    = d.gruplar.map(g => ({
      id: g.id, memberIds: g.uye_idler,
      label: g.label, color: {hex: g.renk_hex, bg: g.renk_bg},
    }));
    nid  = people.length  ? Math.max(...people.map(p=>p.id))  + 1 : 1;
    fnid = frames.length  ? Math.max(...frames.map(f=>f.id))  + 1 : 1;

    // Bekleyen değişiklikler
    const beklSayi = d.bekleyenler.length;
    const beklWrap = document.getElementById('bekleyen-wrap');
    document.getElementById('bekleyen-sayi').textContent = beklSayi;
    beklWrap.style.display = beklSayi > 0 ? 'inline-flex' : 'none';

    renderAll();
    setTimeout(fitView, 100);
  } catch(e) {
    console.error('Veri yüklenemedi:', e);
  }
}

// ── DEĞİŞİKLİK GÖNDER ────────────────────────────────────────────────────────
async function degisiklikGonder(tip, veri, fotoFile) {
  const btnOnayla = document.getElementById('btn-onayla-gonder');
  if (btnOnayla) { btnOnayla.disabled = true; btnOnayla.innerHTML = '<i class="ti ti-loader ti-spin"></i> Gönderiliyor…'; }

  try {
    let body, headers = {'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json'};
    if (fotoFile) {
      const fd = new FormData();
      fd.append('tip', tip);
      fd.append('veri', JSON.stringify(veri));
      fd.append('foto', fotoFile);
      body = fd;
    } else {
      headers['Content-Type'] = 'application/json';
      headers['Accept'] = 'application/json';
      body = JSON.stringify({tip, veri});
    }
    const r = await fetch(DEG_URL, {method:'POST', headers, body});
    const d = await r.json();
    if (d.basarili) {
      toast('✓ Değişiklik onaya gönderildi');
      await veriYukle();
    } else {
      toast('Hata oluştu, tekrar deneyin');
    }
  } catch(e) {
    toast('Bağlantı hatası');
  } finally {
    if (btnOnayla) { btnOnayla.disabled = false; btnOnayla.innerHTML = '<i class="ti ti-send"></i> Onaya Gönder'; }
  }
}

// ── PAN / ZOOM ────────────────────────────────────────────────────────────────
function applyXform() {
  container.style.transform = `translate(${panX}px,${panY}px) scale(${scale})`;
  document.getElementById('zoom-lbl').textContent = Math.round(scale * 100) + '%';
  redrawEdges();
}
function zoom(d) {
  const ns = Math.max(.2, Math.min(3, scale + d));
  const cx = wrap.clientWidth / 2, cy = wrap.clientHeight / 2;
  panX = cx - (cx - panX) * (ns / scale);
  panY = cy - (cy - panY) * (ns / scale);
  scale = ns; applyXform();
}
function fitView() {
  if (!people.length) return;
  const xs = people.map(p => p.x), ys = people.map(p => p.y);
  const minX = Math.min(...xs) - 20, maxX = Math.max(...xs) + 184;
  const minY = Math.min(...ys) - 20, maxY = Math.max(...ys) + 140;
  const w = wrap.clientWidth - 80, h = wrap.clientHeight - 80;
  scale = Math.min(w / (maxX - minX || 1), h / (maxY - minY || 1), 1.4);
  panX = (wrap.clientWidth - (maxX - minX) * scale) / 2 - minX * scale;
  panY = (wrap.clientHeight - (maxY - minY) * scale) / 2 - minY * scale;
  applyXform();
}
wrap.addEventListener('wheel', e => {
  e.preventDefault();
  const d = e.deltaY > 0 ? -0.08 : 0.08;
  const rect = wrap.getBoundingClientRect();
  const mx = e.clientX - rect.left, my = e.clientY - rect.top;
  const ns = Math.max(.2, Math.min(3, scale + d));
  panX = mx - (mx - panX) * (ns / scale);
  panY = my - (my - panY) * (ns / scale);
  scale = ns; applyXform();
}, {passive: false});

wrap.addEventListener('mousedown', e => {
  const isCanvas = e.target === wrap || e.target === svgEl || e.target === framesLayer || e.target === nodesLayer;
  if (!isCanvas) return;
  if (e.shiftKey) {
    selBoxing = true;
    const r = wrap.getBoundingClientRect();
    sbStart = {x: e.clientX - r.left, y: e.clientY - r.top};
    selBoxEl.style.cssText = `left:${sbStart.x}px;top:${sbStart.y}px;width:0;height:0;display:block`;
  } else {
    panning = true; lmx = e.clientX; lmy = e.clientY;
    wrap.classList.add('grabbing'); deselectAll();
  }
});
window.addEventListener('mousemove', e => {
  if (panning) { panX += e.clientX - lmx; panY += e.clientY - lmy; lmx = e.clientX; lmy = e.clientY; applyXform(); return; }
  if (selBoxing) {
    const r = wrap.getBoundingClientRect();
    const cx = e.clientX - r.left, cy = e.clientY - r.top;
    const x = Math.min(sbStart.x, cx), y = Math.min(sbStart.y, cy);
    Object.assign(selBoxEl.style, {left:x+'px', top:y+'px', width:Math.abs(cx-sbStart.x)+'px', height:Math.abs(cy-sbStart.y)+'px'});
  }
});
window.addEventListener('mouseup', e => {
  if (panning) { panning = false; wrap.classList.remove('grabbing'); }
  if (selBoxing) {
    selBoxing = false; selBoxEl.style.display = 'none';
    const bx = parseFloat(selBoxEl.style.left), by = parseFloat(selBoxEl.style.top);
    const bw = parseFloat(selBoxEl.style.width), bh = parseFloat(selBoxEl.style.height);
    const tx = (bx - panX) / scale, ty = (by - panY) / scale;
    const tw = bw / scale, th = bh / scale;
    people.forEach(p => {
      if (p.x + 82 > tx && p.x < tx + tw && p.y + 65 > ty && p.y < ty + th) {
        selectedIds.add(p.id);
        document.getElementById('node-' + p.id)?.classList.add('multi-selected');
      }
    });
    updateAlignBar();
  }
});

// ── DRAG NODES ────────────────────────────────────────────────────────────────
function makeDraggable(el, id) {
  let drag = false, ox = 0, oy = 0, startX = 0, startY = 0, axis = null, moved = false;
  el.addEventListener('mousedown', e => {
    if (e.target.tagName === 'BUTTON') return;
    e.stopPropagation();
    drag = true; moved = false; axis = null;
    const p = getPerson(id); startX = p.x; startY = p.y;
    ox = (e.clientX - panX) / scale - p.x; oy = (e.clientY - panY) / scale - p.y;
  });
  window.addEventListener('mousemove', e => {
    if (!drag) return;
    const p = getPerson(id);
    const nx = (e.clientX - panX) / scale - ox, ny = (e.clientY - panY) / scale - oy;
    let fx = nx, fy = ny;
    if (e.shiftKey) {
      if (!axis) { const dx = Math.abs(nx - startX), dy = Math.abs(ny - startY); if (dx > 4 || dy > 4) axis = dx >= dy ? 'x' : 'y'; }
      if (axis === 'x') fy = startY;
      if (axis === 'y') fx = startX;
    } else { axis = null; }
    const dx = fx - p.x, dy = fy - p.y;
    if (Math.abs(dx) > 1 || Math.abs(dy) > 1) moved = true;
    if (selectedIds.has(id) && selectedIds.size > 1) {
      selectedIds.forEach(sid => {
        const sp = getPerson(sid); if (!sp) return;
        sp.x += dx; sp.y += dy;
        const sel = document.getElementById('node-' + sid);
        if (sel) { sel.style.left = sp.x + 'px'; sel.style.top = sp.y + 'px'; }
      });
    } else {
      p.x = fx; p.y = fy; el.style.left = p.x + 'px'; el.style.top = p.y + 'px';
    }
    redrawEdges(); renderFrames();
  });
  window.addEventListener('mouseup', () => {
    if (drag && moved) scheduleKonumKaydet();
    drag = false; axis = null;
  });
}

function scheduleKonumKaydet() {
  clearTimeout(_posTimer);
  _posTimer = setTimeout(async () => {
    const konumlar = people.map(p => ({id: p.id, x: p.x, y: p.y}));
    await fetch(KONUM_URL, {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
      body: JSON.stringify({konumlar}),
    });
  }, 800);
}

// ── FRAME BBOX ────────────────────────────────────────────────────────────────
function getFrameBBox(frame) {
  const NODE_W = 164, NODE_H = 130;
  const members = frame.memberIds.map(id => getPerson(id)).filter(Boolean);
  if (!members.length) return null;
  const xs = members.map(p => p.x), ys = members.map(p => p.y);
  return {
    x: Math.min(...xs) - FRAME_PAD,
    y: Math.min(...ys) - FRAME_PAD - 14,
    w: Math.max(...xs) - Math.min(...xs) + NODE_W + FRAME_PAD * 2,
    h: Math.max(...ys) - Math.min(...ys) + NODE_H + FRAME_PAD * 2 + 14,
  };
}

// ── RENDER ────────────────────────────────────────────────────────────────────
function getPerson(id) { return people.find(p => p.id === id); }
function getFrame(id)  { return frames.find(f => f.id === id); }

function daysUntilBirthday(day, month) {
  if (!day || !month) return null;
  const today = new Date(), ty = today.getFullYear();
  let bd = new Date(ty, month - 1, day);
  if (bd < today) bd = new Date(ty + 1, month - 1, day);
  return Math.ceil((bd - today) / (1000 * 60 * 60 * 24));
}

function renderAll() {
  nodesLayer.innerHTML = '';
  renderFrames();
  document.getElementById('empty-state').style.display = people.length ? 'none' : '';
  people.forEach(p => {
    const el = document.createElement('div');
    el.className = `person-node ${p.gender}`;
    el.id = `node-${p.id}`;
    el.style.left = p.x + 'px'; el.style.top = p.y + 'px';
    const initials = ((p.ad || '')[0] + (p.soyad || '')[0]).toUpperCase() || '?';
    const avatarInner = p.photo ? `<img src="${p.photo}" alt="${p.ad}">` : initials;
    const yilStr = p.bdYil ? `${p.bdYil}${p.olum ? ' † ' + p.olum : ''}` : p.olum ? `† ${p.olum}` : '';
    const days = daysUntilBirthday(p.bdGun, p.bdAy);
    if (selectedIds.has(p.id)) el.classList.add('multi-selected');
    if (singleSelected === p.id) el.classList.add('selected');
    el.innerHTML = `
      <div class="node-avatar ${p.gender}">${avatarInner}</div>
      <div class="node-name">${p.ad} ${p.soyad || ''}</div>
      ${yilStr ? `<div class="node-dates">${yilStr}${days !== null && days <= 30 ? ' 🎂' : ''}</div>` : ''}
      ${p.yer ? `<div class="node-place">${p.yer}</div>` : ''}
      <div class="node-actions">
        <button class="nd-btn" onclick="editNode(${p.id})">Düzenle</button>
        <button class="nd-btn del" onclick="deleteNode(${p.id})">Sil</button>
      </div>`;
    el.addEventListener('click', e => {
      if (e.target.tagName === 'BUTTON') return;
      if (e.shiftKey || e.ctrlKey || e.metaKey) toggleMultiSelect(p.id);
      else selectSingle(p.id);
    });
    nodesLayer.appendChild(el);
    makeDraggable(el, p.id);
  });
  redrawEdges();
  updateBdayPanel();
  updateAlignBar();
}

function renderFrames() {
  framesLayer.innerHTML = '';
  frames = frames.filter(f => { f.memberIds = f.memberIds.filter(id => getPerson(id)); return f.memberIds.length > 0; });
  frames.forEach(f => {
    const bbox = getFrameBBox(f); if (!bbox) return;
    const el = document.createElement('div');
    el.className = 'tree-frame'; el.id = 'frame-' + f.id;
    el.style.cssText = `left:${bbox.x}px;top:${bbox.y}px;width:${bbox.w}px;height:${bbox.h}px;border-color:${f.color.hex};background:${f.color.bg}`;
    el.innerHTML = `<div class="frame-header" style="border-color:${f.color.hex};background:${f.color.bg};color:${f.color.hex}">
      ${f.label}
      <button class="frame-edit-btn" onclick="openEditFrame(${f.id})" title="Düzenle">✏</button>
      <button class="frame-del-btn" onclick="deleteFrame(${f.id})" title="Sil">🗑</button>
    </div>`;
    framesLayer.appendChild(el);
  });
}

function redrawEdges() {
  const r = wrap.getBoundingClientRect();
  svgEl.setAttribute('width', r.width); svgEl.setAttribute('height', r.height);
  svgEl.innerHTML = '';
  relations.forEach(rel => {
    const p1 = getPerson(rel.from), p2 = getPerson(rel.to); if (!p1 || !p2) return;
    const x1 = p1.x * scale + panX + 82 * scale, y1 = p1.y * scale + panY + 62 * scale;
    const x2 = p2.x * scale + panX + 82 * scale, y2 = p2.y * scale + panY + 62 * scale;
    const mid = (x1 + x2) / 2;
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', `M${x1},${y1} C${mid},${y1} ${mid},${y2} ${x2},${y2}`);
    path.setAttribute('class', rel.type === 'couple' ? 'edge-couple' : rel.type === 'sibling' ? 'edge-sibling' : 'edge-parent');
    svgEl.appendChild(path);
  });
}

// ── SEÇİM ──────────────────────────────────────────────────────────────────
function selectSingle(id) {
  selectedIds.clear();
  document.querySelectorAll('.multi-selected').forEach(e => e.classList.remove('multi-selected'));
  singleSelected = id;
  document.querySelectorAll('.person-node.selected').forEach(e => e.classList.remove('selected'));
  document.getElementById('node-' + id)?.classList.add('selected');
  updateAlignBar(); openDetail(id);
}
function toggleMultiSelect(id) {
  singleSelected = null;
  document.querySelectorAll('.person-node.selected').forEach(e => e.classList.remove('selected'));
  closeDetail();
  if (selectedIds.has(id)) { selectedIds.delete(id); document.getElementById('node-' + id)?.classList.remove('multi-selected'); }
  else { selectedIds.add(id); document.getElementById('node-' + id)?.classList.add('multi-selected'); }
  updateAlignBar();
}
function deselectAll() {
  selectedIds.clear(); singleSelected = null;
  document.querySelectorAll('.person-node.selected,.person-node.multi-selected').forEach(e => e.classList.remove('selected', 'multi-selected'));
  updateAlignBar(); closeDetail();
}
function updateAlignBar() {
  const bar = document.getElementById('align-bar');
  const n = selectedIds.size;
  if (n >= 2) { bar.classList.add('visible'); document.getElementById('sel-count').textContent = n + ' seçili'; }
  else { bar.classList.remove('visible'); }
}

// ── HİZALAMA ──────────────────────────────────────────────────────────────
function align(type) {
  const ids = [...selectedIds]; if (ids.length < 2) return;
  const ps = ids.map(id => getPerson(id)).filter(Boolean);
  const xs = ps.map(p => p.x), ys = ps.map(p => p.y);
  switch (type) {
    case 'left':    ps.forEach(p => p.x = Math.min(...xs)); break;
    case 'right':   ps.forEach(p => p.x = Math.max(...xs)); break;
    case 'centerH': { const cx = (Math.min(...xs)+Math.max(...xs))/2; ps.forEach(p => p.x = cx); break; }
    case 'top':     ps.forEach(p => p.y = Math.min(...ys)); break;
    case 'bottom':  ps.forEach(p => p.y = Math.max(...ys)); break;
    case 'centerV': { const cy = (Math.min(...ys)+Math.max(...ys))/2; ps.forEach(p => p.y = cy); break; }
    case 'distH': { const s=ps.slice().sort((a,b)=>a.x-b.x),t=s[s.length-1].x-s[0].x,g=t/(s.length-1); s.forEach((p,i)=>p.x=s[0].x+i*g); break; }
    case 'distV': { const s=ps.slice().sort((a,b)=>a.y-b.y),t=s[s.length-1].y-s[0].y,g=t/(s.length-1); s.forEach((p,i)=>p.y=s[0].y+i*g); break; }
  }
  renderAll(); scheduleKonumKaydet();
}

// ── GRUP / FRAME ───────────────────────────────────────────────────────────
function groupSelected() {
  if (selectedIds.size < 2) { toast('Grup için en az 2 kişi seçin.'); return; }
  pendingFrameMembers = [...selectedIds];
  editingFrameId = null;
  openFrameModal();
}
function openEditFrame(id) {
  const f = getFrame(id); if (!f) return;
  editingFrameId = id; pendingFrameMembers = [...f.memberIds]; pickedColor = f.color;
  buildColorSwatches();
  document.getElementById('fm-label').value = f.label || '';
  document.getElementById('frame-modal-title').textContent = 'Grubu Düzenle';
  buildMemberChips();
  document.getElementById('frame-modal').style.display = 'flex';
  setTimeout(() => document.getElementById('fm-label').focus(), 80);
}
function openFrameModal() {
  pickedColor = FRAME_COLORS[0]; buildColorSwatches();
  document.getElementById('fm-label').value = '';
  document.getElementById('frame-modal-title').textContent = 'Grubu Adlandır';
  buildMemberChips();
  document.getElementById('frame-modal').style.display = 'flex';
  setTimeout(() => document.getElementById('fm-label').focus(), 80);
}
function buildColorSwatches() {
  document.getElementById('color-swatches').innerHTML = FRAME_COLORS.map(c =>
    `<div class="color-swatch${c.hex === pickedColor.hex ? ' picked' : ''}" style="background:${c.hex}" onclick="pickFrameColor('${c.hex}',this)"></div>`
  ).join('');
}
function buildMemberChips() {
  document.getElementById('member-chips').innerHTML = pendingFrameMembers.map(id => {
    const p = getPerson(id); if (!p) return '';
    const c = p.gender === 'male' ? '#2a5f8f' : p.gender === 'female' ? '#8f2a5f' : '#5a6e5a';
    return `<span style="display:inline-flex;align-items:center;gap:4px;background:#F5F5F5;border:1px solid rgba(0,0,0,0.10);border-radius:12px;padding:3px 8px;font-size:11px;font-weight:600;color:#5A5A5A;"><span style="width:7px;height:7px;border-radius:50%;background:${c};"></span>${p.ad} ${p.soyad || ''}</span>`;
  }).join('');
}
function pickFrameColor(hex, el) {
  pickedColor = FRAME_COLORS.find(c => c.hex === hex) || FRAME_COLORS[0];
  document.querySelectorAll('.color-swatch').forEach(e => e.classList.remove('picked'));
  el.classList.add('picked');
}
async function saveFrame() {
  const label = document.getElementById('fm-label').value.trim() || 'Grup';
  const data = {label, renk_hex: pickedColor.hex, renk_bg: pickedColor.bg, uye_idler: pendingFrameMembers};
  if (editingFrameId) data.id = editingFrameId;
  try {
    const r = await fetch(GRUP_URL, {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}, body:JSON.stringify(data)});
    await r.json();
    closeModal('frame-modal');
    await veriYukle();
    toast(editingFrameId ? 'Grup güncellendi' : 'Grup oluşturuldu');
    editingFrameId = null;
  } catch(e) { toast('Hata oluştu'); }
}
async function deleteFrame(id) {
  try {
    await fetch(`${GRUP_DEL}/${id}`, {method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF}});
    await veriYukle(); toast('Grup silindi');
  } catch(e) { toast('Hata oluştu'); }
}

// ── DETAY PANELİ ──────────────────────────────────────────────────────────
function openDetail(id) {
  const p = getPerson(id); if (!p) return;
  document.getElementById('dp-name').textContent = `${p.ad} ${p.soyad || ''}`;
  const rels = relations.filter(r => r.from === id || r.to === id);
  const relHtml = rels.map(r => {
    const oid = r.from === id ? r.to : r.from; const o = getPerson(oid); if (!o) return '';
    const lbl = r.type === 'couple' ? 'Eş' : r.type === 'sibling' ? 'Kardeş' : r.from === id ? 'Çocuğu' : 'Ebeveyni';
    return `<div class="dp-row"><span class="dp-label">${lbl}</span><span class="dp-val">${o.ad} ${o.soyad || ''}</span></div>`;
  }).join('');
  const bdStr = p.bdGun && p.bdAy ? `${p.bdGun} ${MONTHS[p.bdAy]}${p.bdYil ? ' ' + p.bdYil : ''}` : p.bdYil || '';
  const photoHtml = p.photo ? `<img src="${p.photo}" style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid rgba(0,0,0,0.10);margin:0 auto 10px;display:block;" alt="${p.ad}">` : '';
  document.getElementById('dp-body').innerHTML = `${photoHtml}
    ${bdStr ? `<div class="dp-row"><span class="dp-label">Doğum</span><span class="dp-val">${bdStr}</span></div>` : ''}
    ${p.olum ? `<div class="dp-row"><span class="dp-label">Vefat</span><span class="dp-val">${p.olum}</span></div>` : ''}
    ${p.yer ? `<div class="dp-row"><span class="dp-label">Yer</span><span class="dp-val">${p.yer}</span></div>` : ''}
    ${p.meslek ? `<div class="dp-row"><span class="dp-label">Meslek</span><span class="dp-val">${p.meslek}</span></div>` : ''}
    ${p.not ? `<div class="dp-row"><span class="dp-label">Not</span><span class="dp-val">${p.not}</span></div>` : ''}
    ${relHtml}`;
  document.getElementById('detail-panel').style.transform = 'translateX(0)';
}
function closeDetail() { singleSelected = null; document.getElementById('detail-panel').style.transform = 'translateX(-250px)'; }
function editSelected()   { if (singleSelected) editNode(singleSelected); }
function deleteSelected() { if (singleSelected) deleteNode(singleSelected); }

// ── DOĞUM GÜNÜ PANELİ ─────────────────────────────────────────────────────
function updateBdayPanel() {
  const upcoming = people.map(p => ({p, days: daysUntilBirthday(p.bdGun, p.bdAy)}))
    .filter(x => x.days !== null && x.days <= 30).sort((a, b) => a.days - b.days);
  const countEl = document.getElementById('bday-count');
  countEl.textContent = upcoming.length ? upcoming.length : '';
  const list = document.getElementById('bday-list');
  if (!upcoming.length) { list.innerHTML = '<div style="font-size:12px;color:#A0A0A0;padding:4px 0">Yakın 30 gün içinde doğum günü yok.</div>'; return; }
  list.innerHTML = upcoming.map(({p, days}) => {
    const initials = ((p.ad || '')[0] + (p.soyad || '')[0]).toUpperCase() || '?';
    const bg = p.gender === 'male' ? '#d8eaf8' : p.gender === 'female' ? '#f8d8ec' : '#ddeedd';
    const c  = p.gender === 'male' ? '#2a5f8f' : p.gender === 'female' ? '#8f2a5f' : '#5a6e5a';
    const when = days === 0 ? '🎂 Bugün!' : days === 1 ? 'Yarın!' : `${days} gün sonra`;
    const ava = p.photo ? `<img src="${p.photo}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1.5px solid rgba(0,0,0,0.12);">`
      : `<div style="width:28px;height:28px;border-radius:50%;background:${bg};color:${c};display:flex;align-items:center;justify-content:center;font-family:'Cormorant Garamond',serif;font-size:11px;font-weight:700;">${initials}</div>`;
    return `<div style="display:flex;align-items:center;gap:7px;padding:5px 0;border-bottom:1px solid #f0e080;font-size:12px;">${ava}<div><div style="font-weight:700;color:#0F0F0F;">${p.ad} ${p.soyad || ''}</div><div style="font-size:10.5px;color:#9a7800;font-weight:600;">${when} · ${p.bdGun} ${MONTHS[p.bdAy]}</div></div></div>`;
  }).join('');
}
function toggleBdayPanel() {
  const p = document.getElementById('bday-panel');
  p.style.display = p.style.display === 'none' ? 'block' : 'none';
}

// ── KİŞİ EKLE / DÜZENLE ───────────────────────────────────────────────────
function openAdd() {
  editingId = null; pendingRels = []; currentPhotoData = null;
  document.getElementById('modal-title').textContent = 'Kişi Ekle — Onay İsteği';
  ['f-ad','f-soyad','f-meslek','f-bd-gun','f-bd-yil','f-olum','f-yer','f-not'].forEach(id => { const el = document.getElementById(id); if(el) el.value=''; });
  document.getElementById('f-bd-ay').value = ''; document.getElementById('f-cinsiyet').value = 'male';
  document.getElementById('f-photo').value = ''; showPhotoPreview(null);
  refreshRelList(); refreshRelPersonSelect();
  document.getElementById('add-modal').style.display = 'flex';
  setTimeout(() => document.getElementById('f-ad').focus(), 100);
}
function editNode(id) {
  const p = getPerson(id); if (!p) return;
  editingId = id;
  pendingRels = relations.filter(r => r.from === id || r.to === id).map(r => ({
    type: r.from === id ? r.type : (r.type === 'parent' ? 'child' : r.type),
    otherId: r.from === id ? r.to : r.from
  }));
  document.getElementById('modal-title').textContent = 'Kişi Düzenle — Onay İsteği';
  document.getElementById('f-ad').value = p.ad || '';
  document.getElementById('f-soyad').value = p.soyad || '';
  document.getElementById('f-cinsiyet').value = p.gender;
  document.getElementById('f-meslek').value = p.meslek || '';
  document.getElementById('f-bd-gun').value = p.bdGun || '';
  document.getElementById('f-bd-ay').value = p.bdAy || '';
  document.getElementById('f-bd-yil').value = p.bdYil || '';
  document.getElementById('f-olum').value = p.olum || '';
  document.getElementById('f-yer').value = p.yer || '';
  document.getElementById('f-not').value = p.not || '';
  currentPhotoData = null; document.getElementById('f-photo').value = ''; showPhotoPreview(p.photo || null);
  refreshRelList(); refreshRelPersonSelect();
  document.getElementById('add-modal').style.display = 'flex';
}
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function handlePhotoChange(e) { const f = e.target.files[0]; if (f) loadPhotoFile(f); }
function handlePhotoDrop(e) {
  e.preventDefault(); document.getElementById('photo-zone').classList.remove('drag-over');
  const f = e.dataTransfer.files[0]; if (f && f.type.startsWith('image/')) loadPhotoFile(f);
}
function loadPhotoFile(file) {
  if (file.size > 5 * 1024 * 1024) { toast('Fotoğraf 5 MB\'dan büyük olamaz.'); return; }
  const reader = new FileReader();
  reader.onload = ev => { currentPhotoData = ev.target.result; showPhotoPreview(currentPhotoData); };
  reader.readAsDataURL(file);
}
function showPhotoPreview(src) {
  const img = document.getElementById('photo-preview-img');
  const ph  = document.getElementById('photo-placeholder');
  const rb  = document.getElementById('photo-remove-btn');
  if (src) { img.src = src; img.style.display = 'block'; ph.style.display = 'none'; rb.style.display = 'flex'; }
  else { img.src = ''; img.style.display = 'none'; ph.style.display = 'flex'; rb.style.display = 'none'; }
}
function removePhoto(e) { e.stopPropagation(); e.preventDefault(); currentPhotoData = null; document.getElementById('f-photo').value = ''; showPhotoPreview(null); }

const TYPE_LABELS = {parent:'↑ Ebeveyni', child:'↓ Çocuğu', couple:'♥ Eş', sibling:'↔ Kardeş'};
function refreshRelList() {
  const list = document.getElementById('rel-list');
  if (!pendingRels.length) { list.innerHTML = '<div style="font-size:11.5px;color:#A0A0A0;padding:3px 1px">Henüz bağlantı eklenmedi.</div>'; return; }
  list.innerHTML = pendingRels.map((r, i) => {
    const o = getPerson(r.otherId), name = o ? `${o.ad} ${o.soyad || ''}` : '?';
    return `<div class="rel-row">
      <select onchange="pendingRels[${i}].type=this.value">
        ${Object.entries(TYPE_LABELS).map(([v,l]) => `<option value="${v}"${r.type===v?' selected':''}>${l}</option>`).join('')}
      </select>
      <span class="person-name">${name}</span>
      <button class="rm-rel" onclick="removeRel(${i})">✕</button>
    </div>`;
  }).join('');
}
function refreshRelPersonSelect() {
  const sel = document.getElementById('new-rel-person');
  sel.innerHTML = '<option value="">— Kişi seç —</option>' + people.filter(p => p.id !== editingId).map(p => `<option value="${p.id}">${p.ad} ${p.soyad || ''}</option>`).join('');
}
function addRelRow() {
  const otherId = parseInt(document.getElementById('new-rel-person').value);
  const type = document.getElementById('new-rel-type').value;
  if (!otherId) { toast('Lütfen bir kişi seçin.'); return; }
  if (pendingRels.find(r => r.otherId === otherId && r.type === type)) { toast('Bu bağlantı zaten ekli.'); return; }
  pendingRels.push({type, otherId}); refreshRelList();
}
function removeRel(i) { pendingRels.splice(i, 1); refreshRelList(); }

async function savePerson() {
  const ad = document.getElementById('f-ad').value.trim();
  if (!ad) { toast('Ad alanı zorunludur.'); document.getElementById('f-ad').focus(); return; }

  const veri = {
    ad, soyad: document.getElementById('f-soyad').value.trim(),
    cinsiyet: document.getElementById('f-cinsiyet').value,
    meslek: document.getElementById('f-meslek').value.trim(),
    bd_gun: parseInt(document.getElementById('f-bd-gun').value) || null,
    bd_ay: parseInt(document.getElementById('f-bd-ay').value) || null,
    bd_yil: document.getElementById('f-bd-yil').value.trim(),
    olum_yil: document.getElementById('f-olum').value.trim(),
    yer: document.getElementById('f-yer').value.trim(),
    notlar: document.getElementById('f-not').value.trim(),
    konum_x: editingId ? undefined : freePos().x,
    konum_y: editingId ? undefined : freePos().y,
  };

  const tip = editingId ? 'kisi_duzenle' : 'kisi_ekle';
  if (editingId) veri.id = editingId;

  // İlişkiler ayrı degisiklik olarak gönderilecek
  const yeniIliskiler = pendingRels.filter(r => {
    if (!editingId) return true;
    const var_ = relations.find(rel => {
      const oid = rel.from === editingId ? rel.to : rel.from;
      const rt = rel.from === editingId ? rel.type : (rel.type === 'parent' ? 'child' : rel.type);
      return oid === r.otherId && rt === r.type;
    });
    return !var_;
  });

  // Fotoğraf dosyası
  const fotoInput = document.getElementById('f-photo');
  const fotoFile = fotoInput.files[0] || null;

  closeModal('add-modal');
  await degisiklikGonder(tip, veri, fotoFile);

  // Yeni ilişkileri ayrı ayrı gönder
  for (const r of yeniIliskiler) {
    let from, to, edgeType;
    if (r.type === 'parent') { from = r.otherId; to = editingId || '__yeni'; edgeType = 'parent'; }
    else if (r.type === 'child') { from = editingId || '__yeni'; to = r.otherId; edgeType = 'parent'; }
    else { from = editingId || '__yeni'; to = r.otherId; edgeType = r.type; }
    if (typeof from === 'number' && typeof to === 'number') {
      await degisiklikGonder('iliski_ekle', {from, to, tip: edgeType});
    }
  }
}

function freePos() {
  if (!people.length) return {x: 200, y: 150};
  const last = people[people.length - 1];
  return {x: last.x + 190, y: last.y};
}

async function deleteNode(id) {
  const p = getPerson(id); if (!p) return;
  if (!confirm(`${p.ad} ${p.soyad || ''} için silme isteği gönderilecek. Onaylıyor musunuz?`)) return;
  closeDetail();
  await degisiklikGonder('kisi_sil', {id});
}

// ── YAZDIRMA ──────────────────────────────────────────────────────────────
function printTree() {
  if (!people.length) { toast('Yazdırılacak kişi yok.'); return; }
  window.print();
}

// ── UTILS ──────────────────────────────────────────────────────────────────
function toast(msg) {
  const el = document.createElement('div'); el.className = 'sa-toast'; el.textContent = msg;
  document.body.appendChild(el); setTimeout(() => el.remove(), 3200);
}

// Modal kapat (overlay tıklama)
document.getElementById('add-modal').addEventListener('click', e => { if (e.target === document.getElementById('add-modal')) closeModal('add-modal'); });
document.getElementById('frame-modal').addEventListener('click', e => { if (e.target === document.getElementById('frame-modal')) closeModal('frame-modal'); });

// Klavye kısayolları
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { closeModal('add-modal'); closeModal('frame-modal'); closeDetail(); document.getElementById('bday-panel').style.display = 'none'; deselectAll(); }
  if (e.key === 'Delete' && singleSelected && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') deleteSelected();
  if ((e.key === 'a' || e.key === 'A') && (e.ctrlKey || e.metaKey)) { e.preventDefault(); people.forEach(p => { selectedIds.add(p.id); document.getElementById('node-' + p.id)?.classList.add('multi-selected'); }); updateAlignBar(); }
});

// Sayfa yüklendiğinde veriyi çek
document.addEventListener('DOMContentLoaded', veriYukle);
</script>
@endpush
