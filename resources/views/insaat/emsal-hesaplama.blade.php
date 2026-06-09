@extends('layouts.app')

@section('title', 'Emsal Hesaplama — SUÇEK İnşaat')
@section('meta-description', 'Parsel alanı, çekme mesafeleri ve TAKS/KAKS değerlerine göre emsal ve yaklaşık inşaat maliyeti hesaplayın.')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
.et{--ink:#0F0F0F;--ink-2:#3A3A3A;--ink-3:#6B7490;--surface:#F4F3F0;--surface-2:#EAEAE6;--surface-3:#fff;--accent:#1a4a6b;--accent-light:#e8f1f7;--accent-mid:#4a85ad;--success:#1a5c3a;--success-light:#e6f4ec;--warn:#7a4800;--warn-light:#fdf3e3;--purple:#3d2d7a;--purple-light:#eeecfa;--border:rgba(0,0,0,0.10);--border-strong:rgba(0,0,0,0.20);--radius:8px;--radius-lg:12px;--mono:'DM Mono','Courier New',monospace;--sans:'DM Sans',system-ui,sans-serif;font-family:var(--sans);color:var(--ink);}
.et .et-steps{display:flex;flex-direction:column;gap:1.25rem;}
.et .et-card{background:var(--surface-3);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;}
.et .et-card-header{display:flex;align-items:center;gap:12px;padding:1rem 1.25rem;border-bottom:1px solid var(--border);}
.et .et-step-num{width:28px;height:28px;border-radius:50%;background:#141414;color:#fff;font-family:var(--mono);font-size:12px;font-weight:500;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.et .et-step-done{background:var(--success);}
.et .et-card-header h2{font-size:14px;font-weight:600;letter-spacing:.04em;text-transform:uppercase;color:var(--ink-2);}
.et .et-card-body{padding:1.25rem;}
.et .tabs{display:flex;gap:4px;background:var(--surface-2);padding:4px;border-radius:var(--radius);margin-bottom:1.25rem;}
.et .tab{flex:1;padding:8px 12px;font-family:var(--sans);font-size:13px;font-weight:500;border:none;border-radius:4px;cursor:pointer;background:transparent;color:var(--ink-3);transition:all .15s;}
.et .tab.active{background:var(--surface-3);color:#141414;box-shadow:0 1px 3px rgba(0,0,0,.08);}
.et .panel{display:none;}
.et .panel.active{display:block;}
.et .form-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;}
.et .et-field{display:flex;flex-direction:column;gap:5px;}
.et .et-label{font-size:12px;font-weight:500;color:var(--ink-3);text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:2px;}
.et .et-input{font-family:var(--mono);font-size:15px;padding:9px 12px;border:1px solid var(--border);border-radius:var(--radius);background:var(--surface);color:var(--ink);transition:border-color .15s;width:100%;}
.et .et-input:focus{outline:none;border-color:var(--accent-mid);background:#fff;}
.et .et-input[readonly]{background:var(--surface-2);color:var(--accent);font-weight:500;}
.et .et-select{font-family:var(--mono);font-size:14px;padding:10px 12px;border:1px solid var(--border);border-radius:var(--radius);background:var(--surface);color:var(--ink);width:100%;cursor:pointer;}
.et .et-select:focus{outline:none;border-color:var(--accent-mid);background:#fff;}
.et .input-suffix{position:relative;}
.et .input-suffix .et-input{padding-right:36px;}
.et .suffix{position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;color:var(--ink-3);font-family:var(--mono);pointer-events:none;}
.et .upload-zone{border:1.5px dashed var(--border-strong);border-radius:var(--radius-lg);padding:2rem;text-align:center;cursor:pointer;transition:all .15s;background:var(--surface);}
.et .upload-zone:hover,.et .upload-zone.drag-over{border-color:var(--accent-mid);background:var(--accent-light);}
.et .upload-zone input[type="file"]{display:none;}
.et .upload-icon{font-size:2rem;margin-bottom:8px;opacity:.45;}
.et .upload-zone p{font-size:13px;color:var(--ink-3);margin-top:4px;}
.et .upload-zone .formats{font-family:var(--mono);font-size:11px;color:var(--accent-mid);margin-top:8px;letter-spacing:.05em;}
.et .upload-btn{display:inline-block;margin-top:12px;padding:7px 16px;background:#141414;color:#fff;border-radius:var(--radius);font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:var(--sans);transition:opacity .15s;}
.et .upload-btn:hover{opacity:.8;}
.et .file-result{display:none;margin-top:1rem;padding:12px 14px;background:var(--success-light);border:1px solid #9dd4b5;border-radius:var(--radius);font-size:13px;color:var(--success);}
.et .file-result.error{background:#fdeaea;border-color:#f5a5a5;color:#7a1a1a;}
.et .nizam-row{display:flex;gap:8px;margin-bottom:1rem;}
.et .nizam-btn{flex:1;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius);background:var(--surface);font-family:var(--sans);font-size:13px;font-weight:500;color:var(--ink-3);cursor:pointer;transition:all .15s;text-align:center;}
.et .nizam-btn.active{border-color:#141414;background:#141414;color:#fff;}
.et .bitisik-row{display:none;gap:8px;margin-bottom:1rem;}
.et .bitisik-row.visible{display:flex;}
.et .bitisik-btn{flex:1;padding:8px;border:1.5px solid var(--border);border-radius:var(--radius);background:var(--surface);font-family:var(--sans);font-size:13px;color:var(--ink-3);cursor:pointer;transition:all .15s;text-align:center;}
.et .bitisik-btn.active{border-color:var(--accent-mid);background:var(--accent-light);color:var(--accent);}
.et .cekme-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;}
.et .parsel-visual{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.25rem;display:flex;align-items:center;justify-content:center;min-height:200px;}
.et .parsel-visual svg{max-width:100%;height:auto;}
.et .taks-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.et .taks-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;}
.et .taks-card h3{font-family:var(--mono);font-size:13px;font-weight:500;color:var(--accent);margin-bottom:4px;}
.et .taks-card p{font-size:11px;color:var(--ink-3);margin-bottom:10px;}
.et .toggle-list{display:flex;flex-direction:column;gap:10px;}
.et .toggle-item{display:flex;align-items:center;gap:12px;padding:10px 14px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);transition:border-color .15s;}
.et .toggle-item.enabled{border-color:var(--accent-mid);background:var(--accent-light);}
.et .toggle-item input[type="checkbox"]{width:16px;height:16px;flex-shrink:0;accent-color:#141414;cursor:pointer;}
.et .toggle-label{flex:1;}
.et .toggle-label strong{font-size:13px;font-weight:500;color:var(--ink-2);display:block;}
.et .toggle-label span{font-size:11px;color:var(--ink-3);}
.et .toggle-input{display:flex;align-items:center;gap:6px;}
.et .toggle-input .et-input{width:90px;font-size:13px;padding:6px 10px;}
.et .toggle-input .unit{font-size:11px;color:var(--ink-3);font-family:var(--mono);white-space:nowrap;}
.et .toggle-input.hidden{display:none;}
.et .mevzuat-note{margin-top:1rem;padding:10px 14px;background:#f5f0ff;border:1px solid #c8b8f5;border-radius:var(--radius);font-size:11px;color:#3d2d7a;line-height:1.6;}
.et .mevzuat-note strong{font-weight:600;}
.et .results-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;}
.et .result-card{background:var(--surface-3);border:1px solid var(--border);border-radius:var(--radius);padding:14px 16px;}
.et .result-card.accent{background:var(--accent-light);border-color:var(--accent-mid);}
.et .result-card.success{background:var(--success-light);border-color:#9dd4b5;}
.et .result-card.warn{background:var(--warn-light);border-color:#f5c87a;}
.et .result-card.purple{background:var(--purple-light);border-color:#b8aae8;}
.et .result-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--ink-3);margin-bottom:6px;}
.et .result-value{font-family:var(--mono);font-size:22px;font-weight:500;color:var(--ink);line-height:1.1;}
.et .result-card.accent .result-value{color:var(--accent);}
.et .result-card.success .result-value{color:var(--success);}
.et .result-card.warn .result-value{color:var(--warn);}
.et .result-card.purple .result-value{color:var(--purple);}
.et .result-unit{font-size:12px;color:var(--ink-3);margin-top:2px;}
.et .et-divider{height:1px;background:var(--border);margin:1.25rem 0;}
.et .et-section-title{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--ink-3);margin-bottom:10px;}
.et .kat-info{margin-top:10px;padding:10px 12px;background:var(--surface-2);border-radius:var(--radius);font-size:12px;color:var(--ink-2);font-family:var(--mono);}
.et .kat-info span{color:var(--accent);font-weight:500;}
.et .breakdown-table{width:100%;border-collapse:collapse;margin-top:10px;font-size:12px;}
.et .breakdown-table td{padding:5px 8px;border-bottom:1px solid var(--border);}
.et .breakdown-table td:last-child{text-align:right;font-family:var(--mono);}
.et .breakdown-table tr.total td{font-weight:600;border-bottom:none;padding-top:8px;color:var(--purple);}
.et .breakdown-table tr.total td:last-child{font-size:14px;}
.et .empty-state{text-align:center;padding:2rem;color:var(--ink-3);font-size:13px;}
.et .empty-state .big{font-size:2rem;margin-bottom:8px;opacity:.35;}
.et .et-helper{font-size:12px;color:var(--ink-3);margin-top:6px;}
.et .birim-display{display:flex;align-items:center;gap:16px;padding:12px 16px;background:var(--accent-light);border:1px solid var(--accent-mid);border-radius:var(--radius);margin-bottom:16px;}
.et .birim-val{font-family:var(--mono);font-size:20px;font-weight:500;color:var(--accent);}
.et .birim-meta{font-size:11px;color:var(--ink-3);}
.et .alan-secim{display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;}
.et .alan-btn{padding:8px 14px;border:1.5px solid var(--border);border-radius:var(--radius);background:var(--surface);font-family:var(--sans);font-size:13px;color:var(--ink-3);cursor:pointer;transition:all .15s;}
.et .alan-btn.active{border-color:#141414;background:#141414;color:#fff;font-weight:500;}
.et .alan-display{font-family:var(--mono);font-size:13px;color:var(--ink-2);padding:8px 12px;background:var(--surface-2);border-radius:var(--radius);margin-bottom:14px;}
.et .maliyet-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin-top:14px;}
.et .maliyet-card{border-radius:var(--radius);padding:14px 16px;border:1px solid var(--border);}
.et .maliyet-card.base{background:#f0f8ff;border-color:#a8d0ee;}
.et .maliyet-card.kdv{background:#f5f5f5;border-color:var(--border);}
.et .maliyet-card.total{background:#141414;border-color:#141414;}
.et .maliyet-card .ml{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--ink-3);margin-bottom:6px;}
.et .maliyet-card.total .ml{color:rgba(255,255,255,.7);}
.et .maliyet-card .mv{font-family:var(--mono);font-size:18px;font-weight:500;color:var(--ink);line-height:1.2;}
.et .maliyet-card.total .mv{color:#fff;font-size:20px;}
.et .maliyet-note{margin-top:12px;padding:10px 14px;background:#fff8e6;border:1px solid #f0d080;border-radius:var(--radius);font-size:11px;color:#6b4800;line-height:1.7;}
.et .source-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:var(--surface-2);border:1px solid var(--border);border-radius:3px;font-family:var(--mono);font-size:10px;color:var(--ink-3);margin-top:10px;}
@media(max-width:600px){.et .taks-grid{grid-template-columns:1fr;}.et .results-grid{grid-template-columns:1fr 1fr;}}
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden min-h-[220px] flex items-end" aria-label="Emsal hesaplama hero">
  <div class="absolute inset-0 bg-[#141414]"></div>
  <div class="absolute inset-0 opacity-10"
       style="background-image:url('https://images.unsplash.com/photo-1486325212027-8081e485255e?w=1280&q=80'); background-size:cover; background-position:center;"></div>
  <div class="relative z-10 px-9 lg:px-14 py-12 w-full">
    <div class="flex items-center gap-3 mb-3">
      <a href="{{ route('insaat.index') }}" class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.40)] hover:text-white transition-colors">İNŞAAT</a>
      <span class="text-[rgba(255,255,255,0.20)] text-xs">·</span>
      <span class="text-[9px] font-medium tracking-[2px] uppercase text-[rgba(255,255,255,0.40)]">ARAÇLAR</span>
    </div>
    <h1 class="font-display text-[36px] lg:text-[48px] font-semibold text-white leading-[1.1]">Emsal Hesaplama</h1>
    <p class="text-[13px] text-[rgba(255,255,255,0.45)] mt-2 max-w-lg">Parsel alanı, çekme mesafeleri ve TAKS/KAKS değerlerine göre yapılaşma ve tahmini inşaat maliyeti hesabı.</p>
  </div>
</section>

{{-- Tool --}}
<section class="section" aria-label="Emsal hesaplama aracı">
<div class="et">
<div class="et-steps">

  {{-- ADIM 1: Parsel Alanı --}}
  <div class="et-card">
    <div class="et-card-header">
      <div class="et-step-num">1</div>
      <h2>Parsel Alanı Girişi</h2>
    </div>
    <div class="et-card-body">
      <div class="tabs">
        <button class="tab active" onclick="etSwitchTab('manuel')">Manuel Giriş</button>
        <button class="tab" onclick="etSwitchTab('dosya')">Dosya Yükle (GeoJSON / KML / KMZ)</button>
      </div>
      <div id="et-panel-manuel" class="panel active">
        <div class="form-row">
          <div class="et-field">
            <label class="et-label">Parsel Eni</label>
            <div class="input-suffix">
              <input type="number" id="parsel-en" class="et-input" placeholder="0.00" min="0" step="0.01" oninput="etOnManuelChange()">
              <span class="suffix">m</span>
            </div>
          </div>
          <div class="et-field">
            <label class="et-label">Parsel Boyu</label>
            <div class="input-suffix">
              <input type="number" id="parsel-boy" class="et-input" placeholder="0.00" min="0" step="0.01" oninput="etOnManuelChange()">
              <span class="suffix">m</span>
            </div>
          </div>
          <div class="et-field">
            <label class="et-label">Hesaplanan Alan</label>
            <div class="input-suffix">
              <input type="text" id="parsel-alan-manuel" class="et-input" placeholder="—" readonly>
              <span class="suffix">m²</span>
            </div>
          </div>
        </div>
        <p class="et-helper">Dikdörtgen olmayan parseller için dosya yükle sekmesini kullanın.</p>
      </div>
      <div id="et-panel-dosya" class="panel">
        <div class="upload-zone" id="et-drop-zone"
             ondragover="event.preventDefault();this.classList.add('drag-over')"
             ondragleave="this.classList.remove('drag-over')"
             ondrop="etHandleDrop(event)">
          <input type="file" id="et-file-input" accept=".geojson,.json,.kml,.kmz" onchange="etHandleFile(this.files[0])">
          <div class="upload-icon">📂</div>
          <p><strong>Dosya sürükleyip bırakın</strong> veya</p>
          <button class="upload-btn" onclick="document.getElementById('et-file-input').click()">Dosya Seç</button>
          <p class="formats">GeoJSON &nbsp;·&nbsp; KML &nbsp;·&nbsp; KMZ</p>
        </div>
        <div class="file-result" id="et-file-result"></div>
      </div>
    </div>
  </div>

  {{-- ADIM 2: Çekme Mesafeleri --}}
  <div class="et-card">
    <div class="et-card-header">
      <div class="et-step-num">2</div>
      <h2>Çekme Mesafeleri &amp; Yapı Nizamı</h2>
    </div>
    <div class="et-card-body">
      <label class="et-label" style="display:block;margin-bottom:8px;">Yapı Nizamı</label>
      <div class="nizam-row">
        <button class="nizam-btn active" onclick="etSetNizam('ayrik')">Ayrık Nizam</button>
        <button class="nizam-btn" onclick="etSetNizam('bitisik')">Bitişik Nizam</button>
      </div>
      <div class="bitisik-row" id="et-bitisik-row">
        <label class="et-label" style="align-self:center;white-space:nowrap;min-width:80px;">Bitişik Yön:</label>
        <button class="bitisik-btn active" id="et-btn-saga" onclick="etSetBitisikYon('saga')">Sağa Bitişik</button>
        <button class="bitisik-btn" id="et-btn-sola" onclick="etSetBitisikYon('sola')">Sola Bitişik</button>
      </div>
      <div class="cekme-grid">
        <div class="et-field">
          <label class="et-label">Ön Cephe Çekmesi</label>
          <div class="input-suffix">
            <input type="number" id="sb-on" class="et-input" placeholder="0" min="0" step="0.5" oninput="etUpdateCalc()">
            <span class="suffix">m</span>
          </div>
        </div>
        <div class="et-field">
          <label class="et-label">Arka Cephe Çekmesi</label>
          <div class="input-suffix">
            <input type="number" id="sb-arka" class="et-input" placeholder="0" min="0" step="0.5" oninput="etUpdateCalc()">
            <span class="suffix">m</span>
          </div>
        </div>
        <div class="et-field">
          <label class="et-label" id="et-yan-label">Yan Çekme Mesafesi</label>
          <div class="input-suffix">
            <input type="number" id="sb-yan" class="et-input" placeholder="0" min="0" step="0.5" oninput="etUpdateCalc()">
            <span class="suffix">m</span>
          </div>
          <span class="et-helper" id="et-yan-helper">Her iki yan için uygulanır</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ADIM 3: Görselleştirme --}}
  <div class="et-card">
    <div class="et-card-header">
      <div class="et-step-num">3</div>
      <h2>Parsel Görselleştirme</h2>
    </div>
    <div class="et-card-body">
      <div class="parsel-visual" id="et-parsel-visual">
        <div class="empty-state" style="padding:1.5rem">
          <div class="big">🗺</div>
          Parsel boyutları girildiğinde görsel burada oluşacak.
        </div>
      </div>
    </div>
  </div>

  {{-- ADIM 4: TAKS & KAKS --}}
  <div class="et-card">
    <div class="et-card-header">
      <div class="et-step-num">4</div>
      <h2>TAKS &amp; KAKS Değerleri</h2>
    </div>
    <div class="et-card-body">
      <div class="taks-grid">
        <div class="taks-card">
          <h3>TAKS</h3>
          <p>Taban Alanı Katsayısı — zemin kat kapanma oranı (0.00 – 1.00)</p>
          <div class="input-suffix">
            <input type="number" id="taks" class="et-input" placeholder="0.40" min="0" max="1" step="0.01" oninput="etUpdateCalc()">
          </div>
        </div>
        <div class="taks-card">
          <h3>KAKS / Emsal</h3>
          <p>Kat Alanı Katsayısı — toplam inşaat alanı oranı (örn: 1.50)</p>
          <div class="input-suffix">
            <input type="number" id="kaks" class="et-input" placeholder="1.50" min="0" step="0.01" oninput="etUpdateCalc()">
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ADIM 5: Emsal Harici --}}
  <div class="et-card">
    <div class="et-card-header">
      <div class="et-step-num">5</div>
      <h2>Tahmini Yaklaşık İnşaat Alanı</h2>
    </div>
    <div class="et-card-body">
      <p style="font-size:13px;color:var(--ink-2);margin-bottom:1rem;">Planlı Alanlar İmar Yönetmeliği (PAİY) kapsamında emsale dahil edilmeyen alanları seçin.</p>
      <div class="toggle-list">

        <div class="toggle-item" id="ti-yuzde30">
          <input type="checkbox" id="cb-yuzde30" onchange="etToggleItem('yuzde30');etUpdateCalc()">
          <div class="toggle-label">
            <strong>Genel Emsal Harici Alanlar (%30 Ek)</strong>
            <span>Sığınak, kapıcı dairesi, ortak sosyal mekan, kömürlük/depo — emsale esas alanın en fazla %30'u</span>
          </div>
          <div class="toggle-input hidden" id="ti-yuzde30-inp"><span class="unit">Otomatik hesaplanır</span></div>
        </div>

        <div class="toggle-item" id="ti-yangin-merdiven">
          <input type="checkbox" id="cb-yangin-merdiven" onchange="etToggleItem('yangin-merdiven');etUpdateCalc()">
          <div class="toggle-label">
            <strong>Yangın Merdiveni &amp; Korunumlu Koridor</strong>
            <span>PAİY Md.5/8 — %30 sınırı dışında, sınırsız emsal harici. Kat başı alan girin.</span>
          </div>
          <div class="toggle-input hidden" id="ti-yangin-merdiven-inp">
            <input type="number" id="inp-yangin-merdiven" class="et-input" placeholder="0" min="0" step="0.5" oninput="etUpdateCalc()">
            <span class="unit">m²/kat</span>
          </div>
        </div>

        <div class="toggle-item" id="ti-yangin-hol">
          <input type="checkbox" id="cb-yangin-hol" onchange="etToggleItem('yangin-hol');etUpdateCalc()">
          <div class="toggle-label">
            <strong>Yangın Güvenlik Holü</strong>
            <span>PAİY Md.5/8 — Her katta 6 m² emsal harici, %30 sınırı dışında.</span>
          </div>
          <div class="toggle-input hidden" id="ti-yangin-hol-inp">
            <span class="unit" id="et-yangin-hol-auto">6 m² × kat sayısı</span>
          </div>
        </div>

        <div class="toggle-item" id="ti-otopark">
          <input type="checkbox" id="cb-otopark" onchange="etToggleItem('otopark');etUpdateCalc()">
          <div class="toggle-label">
            <strong>Bodrum Kapalı Otopark</strong>
            <span>PAİY Md.5/8(a) — Zorunlu otopark alanının 2 katına kadar, %30 dışında sınırsız emsal harici.</span>
          </div>
          <div class="toggle-input hidden" id="ti-otopark-inp">
            <input type="number" id="inp-otopark" class="et-input" placeholder="0" min="0" step="1" oninput="etUpdateCalc()">
            <span class="unit">m²</span>
          </div>
        </div>

        <div class="toggle-item" id="ti-tesisat">
          <input type="checkbox" id="cb-tesisat" onchange="etToggleItem('tesisat');etUpdateCalc()">
          <div class="toggle-label">
            <strong>Teknik Hacimler (Kazan Dairesi, Trafo, Jeneratör vb.)</strong>
            <span>PAİY Md.22 — %30 limiti içinde emsal harici.</span>
          </div>
          <div class="toggle-input hidden" id="ti-tesisat-inp">
            <input type="number" id="inp-tesisat" class="et-input" placeholder="0" min="0" step="1" oninput="etUpdateCalc()">
            <span class="unit">m²</span>
          </div>
        </div>

        <div class="toggle-item" id="ti-deprem">
          <input type="checkbox" id="cb-deprem" onchange="etToggleItem('deprem');etUpdateCalc()">
          <div class="toggle-label">
            <strong>Deprem Yalıtım Katı</strong>
            <span>PAİY Md.5/8 — İç yüksekliği 1.40 m'yi aşmayan yalıtım katı, %30 dışında sınırsız emsal harici.</span>
          </div>
          <div class="toggle-input hidden" id="ti-deprem-inp">
            <input type="number" id="inp-deprem" class="et-input" placeholder="0" min="0" step="1" oninput="etUpdateCalc()">
            <span class="unit">m²</span>
          </div>
        </div>

      </div>
      <div class="mevzuat-note">
        <strong>📋 PAİY Madde 5/8 özeti:</strong> Yangın merdiveni/holü, teras çatı, açık otopark, deprem yalıtım katı
        ve bodrum kapalı otopark <strong>%30 sınırı dışında</strong> sınırsız emsal haricidir. Diğer Madde 22 alanları ise
        toplam <strong>emsale esas alanın %30'unu geçemez</strong>.
      </div>
    </div>
  </div>

  {{-- ADIM 6: Maliyet --}}
  <div class="et-card">
    <div class="et-card-header">
      <div class="et-step-num">6</div>
      <h2>Yaklaşık Maliyet Hesabı</h2>
    </div>
    <div class="et-card-body">
      @php
        $fp = fn(string $k) => number_format($emsalFiyatlar[$k], 0, '.', '.');
      @endphp
      <p style="font-size:13px;color:var(--ink-2);margin-bottom:1rem;">
        Çevre, Şehircilik ve İklim Değişikliği Bakanlığı <strong>Yapı Yaklaşık Birim Maliyetleri Tebliği</strong>
        esas alınmaktadır. KDV hariç, %15 genel gider ve %10 yüklenici kârı dahildir.
        <span style="color:var(--ink-3);font-size:11px">({{ $tebligNot }})</span>
      </p>

      <label class="et-label" style="display:block;margin-bottom:6px;">Yapı Sınıfı &amp; Grubu</label>
      <select class="et-select" id="et-yapi-sinif" onchange="etOnSinifChange()">
        <option value="">— Yapı türünü seçin —</option>
        <optgroup label="── III. SINIF – KONUT (en yaygın) ──">
          <option value="{{ $emsalFiyatlar['sinif_IIIA'] }}|III-A|Konut – Apartman, 3 kata kadar (≤3 kat)">III-A: Konut – Apartman, ≤3 kat — {{ $fp('sinif_IIIA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIB'] }}|III-B|Konut – Yapı yüksekliği ≤21.50 m (3 kat üzeri)">III-B: Konut – h ≤ 21.50 m (3 kat üzeri) — {{ $fp('sinif_IIIB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIC'] }}|III-C|Konut – Yapı yüksekliği 21.50–30.50 m">III-C: Konut – h 21.50–30.50 m — {{ $fp('sinif_IIIC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIB'] }}|III-B|Müstakil/İkiz konut – bağımsız bölüm <200 m²">III-B: Müstakil/İkiz konut, brüt &lt;200 m² — {{ $fp('sinif_IIIB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIC'] }}|III-C|Müstakil/İkiz konut – bağımsız bölüm 200–500 m²">III-C: Müstakil/İkiz konut, brüt 200–500 m² — {{ $fp('sinif_IIIC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVB'] }}|IV-B|Müstakil/İkiz konut – bağımsız bölüm ≥500 m²">IV-B: Müstakil/İkiz konut, brüt ≥500 m² — {{ $fp('sinif_IVB') }} TL/m²</option>
        </optgroup>
        <optgroup label="── IV. SINIF – YÜKSEK KATLI KONUT ──">
          <option value="{{ $emsalFiyatlar['sinif_IVA'] }}|IV-A|Konut – Yapı yüksekliği 30.50–51.50 m">IV-A: Konut – h 30.50–51.50 m — {{ $fp('sinif_IVA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVB'] }}|IV-B|Konut – Yapı yüksekliği >51.50 m">IV-B: Konut – h >51.50 m — {{ $fp('sinif_IVB') }} TL/m²</option>
        </optgroup>
        <optgroup label="── TİCARİ / İŞ MERKEZİ ──">
          <option value="{{ $emsalFiyatlar['sinif_IIIB'] }}|III-B|İş merkezi, ≤3 kat">III-B: İş merkezi / Ticari, ≤3 kat — {{ $fp('sinif_IIIB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIC'] }}|III-C|İş merkezi, h ≤21.50 m (3 kat üzeri)">III-C: İş merkezi / Ticari, h ≤21.50 m — {{ $fp('sinif_IIIC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVA'] }}|IV-A|İş merkezi, h 21.50–30.50 m">IV-A: İş merkezi / Ticari, h 21.50–30.50 m — {{ $fp('sinif_IVA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVB'] }}|IV-B|İş merkezi, h 30.50–51.50 m">IV-B: İş merkezi / Ticari, h 30.50–51.50 m — {{ $fp('sinif_IVB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_VA'] }}|V-A|İş merkezi, h >51.50 m">V-A: İş merkezi / Ticari, h >51.50 m — {{ $fp('sinif_VA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVA'] }}|IV-A|Alışveriş merkezi (AVM) <25.000 m²">IV-A: AVM, brüt &lt;25.000 m² — {{ $fp('sinif_IVA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVC'] }}|IV-C|Alışveriş merkezi (AVM) ≥25.000 m²">IV-C: AVM, brüt ≥25.000 m² — {{ $fp('sinif_IVC') }} TL/m²</option>
        </optgroup>
        <optgroup label="── EĞİTİM / SAĞLIK ──">
          <option value="{{ $emsalFiyatlar['sinif_IIIA'] }}|III-A|Kreş / Okul öncesi eğitim merkezi">III-A: Kreş / Okul öncesi — {{ $fp('sinif_IIIA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIB'] }}|III-B|İlkokul ve ortaokul yapıları">III-B: İlkokul ve ortaokul — {{ $fp('sinif_IIIB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIC'] }}|III-C|Lise ve dengi okul yapıları">III-C: Lise ve dengi okul — {{ $fp('sinif_IIIC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVA'] }}|IV-A|Enstitü, fakülte, yüksekokul">IV-A: Enstitü / Fakülte — {{ $fp('sinif_IVA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIB'] }}|III-B|Aile sağlığı merkezi, klinik">III-B: Aile sağlığı merkezi — {{ $fp('sinif_IIIB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVA'] }}|IV-A|Tıp merkezi">IV-A: Tıp merkezi — {{ $fp('sinif_IVA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVC'] }}|IV-C|Hastane (yatak sayısı <200)">IV-C: Hastane, yatak &lt;200 — {{ $fp('sinif_IVC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIC'] }}|III-C|Öğrenci yurdu">III-C: Öğrenci yurdu — {{ $fp('sinif_IIIC') }} TL/m²</option>
        </optgroup>
        <optgroup label="── SANAYİ / DEPO ──">
          <option value="{{ $emsalFiyatlar['sinif_IIA'] }}|II-A|Genel amaçlı depo (betonarme/çelik)">II-A: Genel amaçlı depo — {{ $fp('sinif_IIA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIC'] }}|II-C|Sanayi – döşeme yükü 0–500 kg/m²">II-C: Sanayi, yük 0–500 kg/m² — {{ $fp('sinif_IIC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIIB'] }}|III-B|Sanayi – döşeme yükü 501–3.000 kg/m²">III-B: Sanayi, yük 501–3.000 kg/m² — {{ $fp('sinif_IIIB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVA'] }}|IV-A|Sanayi – döşeme yükü ≥3.001 kg/m²">IV-A: Sanayi, yük ≥3.001 kg/m² — {{ $fp('sinif_IVA') }} TL/m²</option>
        </optgroup>
        <optgroup label="── TARIM / BASİT YAPI ──">
          <option value="{{ $emsalFiyatlar['sinif_IA'] }}|I-A|Basit tarım ve hayvancılık yapıları">I-A: Basit tarım / hayvancılık — {{ $fp('sinif_IA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IB'] }}|I-B|Cam/plastik örtülü sera, yardımcı yapı">I-B: Sera / Yardımcı yapı — {{ $fp('sinif_IB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IC'] }}|I-C|Su deposu, ahır, şarj istasyonu">I-C: Su deposu / Ahır — {{ $fp('sinif_IC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_ID'] }}|I-D|Güneş enerji santrali (GES)">I-D: GES — {{ $fp('sinif_ID') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIB'] }}|II-B|Hangar, halı saha, kapalı pazar">II-B: Hangar / Halı saha — {{ $fp('sinif_IIB') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IIC'] }}|II-C|Bağ/dağ/yayla evi (kırsal <200 m²)">II-C: Bağ/dağ/yayla evi — {{ $fp('sinif_IIC') }} TL/m²</option>
        </optgroup>
        <optgroup label="── ÖZELLİKLİ YAPILAR ──">
          <option value="{{ $emsalFiyatlar['sinif_IIIA'] }}|III-A|Garaj / Katlı-kapalı otopark">III-A: Garaj / Katlı otopark — {{ $fp('sinif_IIIA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVA'] }}|IV-A|Otel (1–2 yıldız)">IV-A: Otel 1–2 yıldız — {{ $fp('sinif_IVA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_IVC'] }}|IV-C|Otel (3 yıldız) / Tatil köyü">IV-C: Otel 3 yıldız / Tatil köyü — {{ $fp('sinif_IVC') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_VB'] }}|V-B|Otel (4 yıldız)">V-B: Otel 4 yıldız — {{ $fp('sinif_VB') }} TL/m²*</option>
          <option value="{{ $emsalFiyatlar['sinif_VD'] }}|V-D|Otel 5 yıldız / Havalimanı / Şehir hastanesi">V-D: Otel 5 yıldız / Havalimanı — {{ $fp('sinif_VD') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_VA'] }}|V-A|Karma kullanımlı (AVM+ofis+konut)">V-A: Karma kullanımlı — {{ $fp('sinif_VA') }} TL/m²</option>
          <option value="{{ $emsalFiyatlar['sinif_VC'] }}|V-C|Müze, opera/tiyatro, kongre merkezi">V-C: Müze / Opera / Kongre — {{ $fp('sinif_VC') }} TL/m²*</option>
          <option value="{{ $emsalFiyatlar['sinif_VE'] }}|V-E|Rüzgar enerji santrali (RES)">V-E: RES — {{ $fp('sinif_VE') }} TL/m²</option>
        </optgroup>
      </select>

      <div id="et-birim-display" class="birim-display" style="display:none">
        <div>
          <div class="birim-val" id="et-birim-val">—</div>
          <div class="birim-meta" id="et-birim-sinif">—</div>
        </div>
        <div style="flex:1;font-size:12px;color:var(--ink-3)" id="et-birim-aciklama"></div>
      </div>

      <label class="et-label" style="display:block;margin-bottom:8px;">Hesaplamada Kullanılacak Alan</label>
      <div class="alan-secim">
        <button class="alan-btn active" id="et-alan-btn-emsal" onclick="etSetAlanTipi('emsal')">Emsale Esas İnşaat Alanı</button>
        <button class="alan-btn" id="et-alan-btn-tahmini" onclick="etSetAlanTipi('tahmini')">Tahmini Yaklaşık İnşaat Alanı</button>
        <button class="alan-btn" id="et-alan-btn-manuel" onclick="etSetAlanTipi('manuel')">Manuel Alan Girişi</button>
      </div>
      <div class="alan-display" id="et-alan-display">Alan hesaplanmadı — önce Adım 4'ü doldurun.</div>
      <div style="display:none" id="et-alan-manuel-wrap">
        <div class="et-field" style="max-width:220px">
          <label class="et-label">Hesaplanacak Alan</label>
          <div class="input-suffix">
            <input type="number" id="inp-alan-manuel" class="et-input" placeholder="0" min="0" step="1" oninput="etOnMaliyetChange()">
            <span class="suffix">m²</span>
          </div>
        </div>
      </div>

      <div id="et-maliyet-result" style="display:none">
        <div class="maliyet-grid">
          <div class="maliyet-card base"><div class="ml">KDV Hariç Yapım Maliyeti</div><div class="mv" id="et-mv-kdvsiz">—</div></div>
          <div class="maliyet-card kdv"><div class="ml">KDV (%20)</div><div class="mv" id="et-mv-kdv">—</div></div>
          <div class="maliyet-card total"><div class="ml">KDV Dahil Toplam Maliyet</div><div class="mv" id="et-mv-kdvli">—</div></div>
        </div>
        <div class="maliyet-note">
          ⚠ <strong>Bu rakamlara dahil olmayan giderler:</strong> Arsa bedeli · Çevre düzenlemesi (peyzaj, aydınlatma, ihata duvarı) ·
          Altyapı bağlantıları (elektrik, su, doğalgaz, kanalizasyon) · Zemin iyileştirme · Belediye harçları ve ruhsat bedelleri.<br>
          <span style="color:var(--ink-3)">* V-B ve V-C değerleri oransal yaklaşımla hesaplanmıştır.</span>
        </div>
        <div class="source-badge">📋 Kaynak: {{ $tebligNot }} &nbsp;|&nbsp; KDV Hariç, Genel Gider %15 + Yüklenici Kârı %10 Dahil</div>
      </div>
    </div>
  </div>

  {{-- Sonuçlar --}}
  <div class="et-card">
    <div class="et-card-header">
      <div class="et-step-num" style="background:var(--success)">✓</div>
      <h2>Hesaplama Sonuçları</h2>
    </div>
    <div class="et-card-body" id="et-results-body">
      <div class="empty-state">
        <div class="big">📐</div>
        Parsel alanı ve çekme mesafelerini girdikten sonra sonuçlar burada görünecek.
      </div>
    </div>
  </div>

</div>{{-- /et-steps --}}

{{-- CTA --}}
<div class="mt-8 bg-[#141414] rounded-[16px] p-8 flex flex-col sm:flex-row sm:items-center gap-6">
  <div class="flex-1">
    <p class="text-[9px] font-medium tracking-[3px] uppercase text-[rgba(255,255,255,0.40)] mb-2">PROFESYONEL DESTEK</p>
    <h3 class="font-display text-[22px] font-semibold text-white mb-1.5">Projenizi Birlikte Değerlendirelim</h3>
    <p class="text-[13px] text-[rgba(255,255,255,0.50)]">Hesaplama sonuçlarınızı uzmanlarımızla paylaşın, detaylı fizibilite ve proje teklifi alın.</p>
  </div>
  <div class="shrink-0">
    <a href="{{ route('iletisim.index') }}"
       class="inline-flex items-center gap-2 border border-[rgba(255,255,255,0.25)] text-white text-[11px] font-semibold tracking-[1.5px] uppercase px-7 py-4 rounded-[10px] hover:border-white hover:bg-[rgba(255,255,255,0.06)] transition-all duration-200 min-h-[48px]">
      <i class="ti ti-mail text-sm" aria-hidden="true"></i> Teklif Al
    </a>
  </div>
</div>

</div>{{-- /et --}}
</section>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
let etGeoArea=null, etActiveTab='manuel', etNizam='ayrik', etBitisikYon='saga';
let etAlanTipi='emsal', etSecilenBirim=0;

function etSetNizam(v){
  etNizam=v;
  document.querySelectorAll('.nizam-btn').forEach((b,i)=>b.classList.toggle('active',(i===0&&v==='ayrik')||(i===1&&v==='bitisik')));
  document.getElementById('et-bitisik-row').classList.toggle('visible',v==='bitisik');
  etUpdateYanLabel();etUpdateCalc();
}
function etSetBitisikYon(v){
  etBitisikYon=v;
  document.getElementById('et-btn-saga').classList.toggle('active',v==='saga');
  document.getElementById('et-btn-sola').classList.toggle('active',v==='sola');
  etUpdateCalc();
}
function etUpdateYanLabel(){
  const l=document.getElementById('et-yan-label'),h=document.getElementById('et-yan-helper');
  if(etNizam==='ayrik'){l.textContent='Yan Çekme Mesafesi';h.textContent='Her iki yan için uygulanır';}
  else{l.textContent='Serbest Yan Çekmesi';h.textContent=etBitisikYon==='saga'?'Sol yan serbest, sağ yan komşuya bitişik (0m)':'Sağ yan serbest, sol yan komşuya bitişik (0m)';}
}
function etSwitchTab(t){
  etActiveTab=t;
  document.querySelectorAll('.tabs .tab').forEach((b,i)=>b.classList.toggle('active',(i===0&&t==='manuel')||(i===1&&t==='dosya')));
  document.getElementById('et-panel-manuel').classList.toggle('active',t==='manuel');
  document.getElementById('et-panel-dosya').classList.toggle('active',t==='dosya');
  etUpdateCalc();
}
function etOnManuelChange(){
  const e=parseFloat(document.getElementById('parsel-en').value)||0,b=parseFloat(document.getElementById('parsel-boy').value)||0;
  document.getElementById('parsel-alan-manuel').value=e*b>0?etFmt(e*b):'';
  etUpdateCalc();
}
function etGetParselArea(){
  if(etActiveTab==='manuel'){return(parseFloat(document.getElementById('parsel-en').value)||0)*(parseFloat(document.getElementById('parsel-boy').value)||0);}
  return etGeoArea||0;
}
function etFmt(n,d=2){return n.toLocaleString('tr-TR',{minimumFractionDigits:d,maximumFractionDigits:d});}
function etGetSolSag(y){
  if(etNizam==='ayrik')return{sol:y,sag:y};
  if(etBitisikYon==='saga')return{sol:y,sag:0};
  return{sol:0,sag:y};
}
function etToggleItem(id){
  const cb=document.getElementById('cb-'+id),item=document.getElementById('ti-'+id),inp=document.getElementById('ti-'+id+'-inp');
  item.classList.toggle('enabled',cb.checked);
  if(inp)inp.classList.toggle('hidden',!cb.checked);
}

function etUpdateCalc(){
  etUpdateYanLabel();
  const pa=etGetParselArea(),on=parseFloat(document.getElementById('sb-on').value)||0,arka=parseFloat(document.getElementById('sb-arka').value)||0,yan=parseFloat(document.getElementById('sb-yan').value)||0,{sol,sag}=etGetSolSag(yan),taks=parseFloat(document.getElementById('taks').value),kaks=parseFloat(document.getElementById('kaks').value);
  etDrawParsel(pa,on,arka,sol,sag);
  if(pa<=0){document.getElementById('et-results-body').innerHTML='<div class="empty-state"><div class="big">📐</div>Parsel alanı ve çekme mesafelerini girdikten sonra sonuçlar burada görünecek.</div>';etRefreshAlanDisplay();return;}
  let netAlan,netEn,netBoy,usedRatio=false;
  if(etActiveTab==='manuel'){
    const e=parseFloat(document.getElementById('parsel-en').value)||0,b=parseFloat(document.getElementById('parsel-boy').value)||0;
    netEn=Math.max(0,e-sol-sag);netBoy=Math.max(0,b-on-arka);netAlan=netEn*netBoy;
  } else {
    const k=Math.sqrt(pa);netAlan=Math.max(0,(k-(sol+sag)/2)*(k-(on+arka)/2));usedRatio=true;
  }
  const indirim=pa-netAlan,indPct=pa>0?(indirim/pa*100):0;
  const hasTaks=!isNaN(taks)&&taks>0,hasKaks=!isNaN(kaks)&&kaks>0;
  const tabinAlan=hasTaks?pa*taks:null,emsaleEsas=hasKaks?pa*kaks:null,maxKat=(hasTaks&&hasKaks&&tabinAlan>0)?emsaleEsas/tabinAlan:null,tamKat=maxKat!==null?Math.floor(maxKat):null;
  if(tamKat!==null)document.getElementById('et-yangin-hol-auto').textContent='6 m² × '+tamKat+' kat = '+(6*tamKat)+' m²';
  let emHarici=0,breakdown=[];
  if(emsaleEsas!==null){
    const lim=emsaleEsas*0.30,cb30=document.getElementById('cb-yuzde30').checked,cbT=document.getElementById('cb-tesisat').checked;
    let otuz=cb30?lim:0;
    const tes=cbT?(parseFloat(document.getElementById('inp-tesisat').value)||0):0;
    const ek30=Math.min(otuz+tes,lim);emHarici+=ek30;
    if(cb30&&ek30>0)breakdown.push({label:'Genel emsal harici alanlar (%30)',value:ek30,note:'PAİY Md.22 kapsamı'});
    else if(cbT&&tes>0)breakdown.push({label:'Teknik hacimler',value:Math.min(tes,lim-otuz),note:'PAİY Md.22, %30 limit içinde'});
  }
  const cbYM=document.getElementById('cb-yangin-merdiven').checked;
  if(cbYM){const kb=parseFloat(document.getElementById('inp-yangin-merdiven').value)||0,k=tamKat||0,ym=kb*k;if(ym>0){emHarici+=ym;breakdown.push({label:'Yangın merdiveni & korunumlu koridor',value:ym,note:etFmt(kb,1)+' m²/kat × '+k+' kat'});}}
  const cbH=document.getElementById('cb-yangin-hol').checked;
  if(cbH&&tamKat!==null){const h=6*tamKat;emHarici+=h;breakdown.push({label:'Yangın güvenlik holü',value:h,note:'6 m² × '+tamKat+' kat'});}
  const cbO=document.getElementById('cb-otopark').checked;
  if(cbO){const o=parseFloat(document.getElementById('inp-otopark').value)||0;if(o>0){emHarici+=o;breakdown.push({label:'Bodrum kapalı otopark',value:o,note:'Cepheleri tamamen toprağın altında'});}}
  const cbD=document.getElementById('cb-deprem').checked;
  if(cbD){const d=parseFloat(document.getElementById('inp-deprem').value)||0;if(d>0){emHarici+=d;breakdown.push({label:'Deprem yalıtım katı',value:d,note:'Max. 1.40 m iç yükseklik'});}}
  const tahminTotal=emsaleEsas!==null?emsaleEsas+emHarici:null;
  let html='';
  html+='<div class="et-section-title">Parsel Alanları</div><div class="results-grid">';
  html+='<div class="result-card accent"><div class="result-label">Brüt Parsel Alanı</div><div class="result-value">'+etFmt(pa)+'</div><div class="result-unit">m²</div></div>';
  html+='<div class="result-card"><div class="result-label">Toplam Çekme Etkisi</div><div class="result-value">'+etFmt(indirim)+'</div><div class="result-unit">m² (−'+etFmt(indPct,1)+'%)</div></div>';
  html+='<div class="result-card success"><div class="result-label">Net Kullanılabilir Alan</div><div class="result-value">'+etFmt(netAlan)+'</div><div class="result-unit">m²'+(usedRatio?' ¹':'')+'</div></div>';
  if(etActiveTab==='manuel')html+='<div class="result-card"><div class="result-label">Net Boyutlar</div><div class="result-value" style="font-size:16px">'+etFmt(netEn,1)+' × '+etFmt(netBoy,1)+'</div><div class="result-unit">m × m</div></div>';
  html+='</div>';
  if(hasTaks||hasKaks){
    html+='<div class="et-divider"></div><div class="et-section-title">Yapılaşma Değerleri</div><div class="results-grid">';
    if(hasTaks)html+='<div class="result-card warn"><div class="result-label">Maks. Taban Alanı (TAKS)</div><div class="result-value">'+etFmt(tabinAlan)+'</div><div class="result-unit">m² (TAKS: '+taks+')</div></div>';
    if(hasKaks)html+='<div class="result-card warn"><div class="result-label">Emsale Esas İnşaat Alanı</div><div class="result-value">'+etFmt(emsaleEsas)+'</div><div class="result-unit">m² (KAKS: '+kaks+')</div></div>';
    if(maxKat!==null)html+='<div class="result-card"><div class="result-label">Teorik Maks. Kat Sayısı</div><div class="result-value">'+maxKat.toFixed(2)+'</div><div class="result-unit">kat</div></div>';
    html+='</div>';
    if(hasTaks&&hasKaks&&maxKat!==null)html+='<div class="kat-info">'+tamKat+' tam kat yapılabilir — kullanılan: <span>'+etFmt(tamKat*tabinAlan)+' m²</span> / müsaade: <span>'+etFmt(emsaleEsas)+' m²</span></div>';
  }
  if(tahminTotal!==null&&breakdown.length>0){
    html+='<div class="et-divider"></div><div class="et-section-title">Tahmini Yaklaşık İnşaat Alanı</div><div class="results-grid">';
    html+='<div class="result-card warn"><div class="result-label">Emsale Esas İnşaat Alanı</div><div class="result-value">'+etFmt(emsaleEsas)+'</div><div class="result-unit">m²</div></div>';
    html+='<div class="result-card"><div class="result-label">Emsal Harici Eklemeler</div><div class="result-value">+ '+etFmt(emHarici)+'</div><div class="result-unit">m²</div></div>';
    html+='<div class="result-card purple"><div class="result-label">Tahmini Yaklaşık İnşaat Alanı</div><div class="result-value">'+etFmt(tahminTotal)+'</div><div class="result-unit">m² toplam</div></div>';
    html+='</div><table class="breakdown-table"><thead><tr><td style="color:var(--ink-3);font-size:11px;text-transform:uppercase;letter-spacing:.06em;">Emsal Harici Kalem</td><td style="color:var(--ink-3);font-size:11px;text-transform:uppercase;letter-spacing:.06em;text-align:right;">Alan</td></tr></thead><tbody>';
    breakdown.forEach(r=>{html+='<tr><td><span style="font-size:13px;color:var(--ink-2)">'+r.label+'</span><br><span style="font-size:11px;color:var(--ink-3)">'+r.note+'</span></td><td>'+etFmt(r.value)+' m²</td></tr>';});
    html+='<tr class="total"><td>Toplam emsal harici</td><td>'+etFmt(emHarici)+' m²</td></tr></tbody></table>';
    html+='<p class="et-helper" style="margin-top:10px">⚠ Yaklaşık hesaptır; kesin proje sürecinde uzman tarafından doğrulanmalıdır.</p>';
  }
  if(usedRatio)html+='<p class="et-helper" style="margin-top:8px">¹ Dosya yüklemede çekme mesafeleri eşdeğer kare parsel yaklaşımıyla hesaplanmıştır.</p>';
  document.getElementById('et-results-body').innerHTML=html;
  etRefreshAlanDisplay();
  etOnMaliyetChange();
}

function etDrawParsel(pa,on,arka,sol,sag){
  const el=document.getElementById('et-parsel-visual');
  let W,H;
  if(etActiveTab==='manuel'){W=parseFloat(document.getElementById('parsel-en').value)||0;H=parseFloat(document.getElementById('parsel-boy').value)||0;}
  else if(pa>0){W=Math.sqrt(pa);H=W;}
  else{el.innerHTML='<div class="empty-state" style="padding:1.5rem"><div class="big">🗺</div>Parsel boyutları girildiğinde görsel burada oluşacak.</div>';return;}
  if(W<=0||H<=0){el.innerHTML='<div class="empty-state" style="padding:1.5rem"><div class="big">🗺</div>Parsel boyutları girildiğinde görsel burada oluşacak.</div>';return;}
  const SW=520,SH=380,PAD=60,scale=Math.min((SW-PAD*2)/W,(SH-PAD*2)/H),pw=W*scale,ph=H*scale,ox=(SW-pw)/2,oy=(SH-ph)/2;
  const cs=sol*scale,csa=sag*scale,con=on*scale,carka=arka*scale,nW=Math.max(0,pw-cs-csa),nH=Math.max(0,ph-con-carka),nx=ox+cs,ny=oy+con;
  const hasBit=etNizam==='bitisik',bitSag=hasBit&&etBitisikYon==='saga',komsuX=bitSag?ox+pw:(hasBit?ox:-1);
  const ac='#1a4a6b',dim='#4a85ad',tg='#6b7490';
  let svg='<svg viewBox="0 0 '+SW+' '+SH+'" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-height:360px"><defs><pattern id="hatch" patternUnits="userSpaceOnUse" width="6" height="6" patternTransform="rotate(45)"><line x1="0" y1="0" x2="0" y2="6" stroke="#b8b5ad" stroke-width="1"/></pattern></defs>';
  svg+='<rect x="'+ox+'" y="'+oy+'" width="'+pw+'" height="'+ph+'" fill="#e8f1f7" stroke="'+ac+'" stroke-width="1.5"/>';
  if(con>0)svg+='<rect x="'+ox+'" y="'+oy+'" width="'+pw+'" height="'+con+'" fill="url(#hatch)" opacity=".5"/>';
  if(carka>0)svg+='<rect x="'+ox+'" y="'+(oy+ph-carka)+'" width="'+pw+'" height="'+carka+'" fill="url(#hatch)" opacity=".5"/>';
  if(cs>0)svg+='<rect x="'+ox+'" y="'+oy+'" width="'+cs+'" height="'+ph+'" fill="url(#hatch)" opacity=".5"/>';
  if(csa>0)svg+='<rect x="'+(ox+pw-csa)+'" y="'+oy+'" width="'+csa+'" height="'+ph+'" fill="url(#hatch)" opacity=".5"/>';
  if(nW>0&&nH>0){svg+='<rect x="'+nx+'" y="'+ny+'" width="'+nW+'" height="'+nH+'" fill="#e6f4ec" stroke="#1a5c3a" stroke-width="1.5" stroke-dasharray="5,3"/>';svg+='<text x="'+(nx+nW/2)+'" y="'+(ny+nH/2+5)+'" text-anchor="middle" font-family="\'DM Mono\',monospace" font-size="11" fill="#1a5c3a">NET ALAN</text>';}
  if(hasBit&&komsuX>=0){svg+='<line x1="'+komsuX+'" y1="'+(oy-8)+'" x2="'+komsuX+'" y2="'+(oy+ph+8)+'" stroke="#c0392b" stroke-width="2.5" stroke-dasharray="6,3"/>';const lx=bitSag?komsuX+6:komsuX-6,an=bitSag?'start':'end';svg+='<text x="'+lx+'" y="'+(oy+ph/2)+'" text-anchor="'+an+'" font-family="\'DM Sans\',sans-serif" font-size="10" fill="#c0392b" transform="rotate(-90,'+lx+','+(oy+ph/2)+')">KOMŞU PARSEL</text>';}
  svg+='<line x1="'+ox+'" y1="'+(oy-18)+'" x2="'+(ox+pw)+'" y2="'+(oy-18)+'" stroke="'+dim+'" stroke-width="1"/><line x1="'+ox+'" y1="'+(oy-22)+'" x2="'+ox+'" y2="'+(oy-14)+'" stroke="'+dim+'" stroke-width="1"/><line x1="'+(ox+pw)+'" y1="'+(oy-22)+'" x2="'+(ox+pw)+'" y2="'+(oy-14)+'" stroke="'+dim+'" stroke-width="1"/>';
  svg+='<text x="'+(ox+pw/2)+'" y="'+(oy-22)+'" text-anchor="middle" font-family="\'DM Mono\',monospace" font-size="11" fill="'+dim+'">'+etFmt(W,1)+' m</text>';
  svg+='<line x1="'+(ox+pw+18)+'" y1="'+oy+'" x2="'+(ox+pw+18)+'" y2="'+(oy+ph)+'" stroke="'+dim+'" stroke-width="1"/><line x1="'+(ox+pw+14)+'" y1="'+oy+'" x2="'+(ox+pw+22)+'" y2="'+oy+'" stroke="'+dim+'" stroke-width="1"/><line x1="'+(ox+pw+14)+'" y1="'+(oy+ph)+'" x2="'+(ox+pw+22)+'" y2="'+(oy+ph)+'" stroke="'+dim+'" stroke-width="1"/>';
  svg+='<text x="'+(ox+pw+32)+'" y="'+(oy+ph/2+4)+'" text-anchor="start" font-family="\'DM Mono\',monospace" font-size="11" fill="'+dim+'">'+etFmt(H,1)+' m</text>';
  function cL(x,y,t){svg+='<text x="'+x+'" y="'+y+'" text-anchor="middle" font-family="\'DM Mono\',monospace" font-size="10" fill="'+tg+'">'+t+'</text>';}
  if(con>0)cL(ox+pw/2,oy+con/2+4,'Ön: '+etFmt(on,1)+'m');
  if(carka>0)cL(ox+pw/2,oy+ph-carka/2+4,'Arka: '+etFmt(arka,1)+'m');
  if(cs>0)cL(ox+cs/2,oy+ph/2,(etNizam==='bitisik'&&etBitisikYon==='sola')?'Bit.:'+etFmt(sol,1)+'m':'Sol: '+etFmt(sol,1)+'m');
  if(csa>0)cL(ox+pw-csa/2,oy+ph/2,(etNizam==='bitisik'&&etBitisikYon==='saga')?'Bit.:'+etFmt(sag,1)+'m':'Sağ: '+etFmt(sag,1)+'m');
  svg+='<text x="'+(ox+6)+'" y="'+(oy+ph-8)+'" font-family="\'DM Mono\',monospace" font-size="9" fill="'+tg+'" opacity=".7">N↑</text></svg>';
  el.innerHTML=svg;
}

function etHandleDrop(e){e.preventDefault();e.currentTarget.classList.remove('drag-over');if(e.dataTransfer.files[0])etHandleFile(e.dataTransfer.files[0]);}
function etShowFileResult(msg,err=false){const el=document.getElementById('et-file-result');el.style.display='block';el.className='file-result'+(err?' error':'');el.innerHTML=msg;}
async function etHandleFile(file){
  if(!file)return;
  const ext=file.name.split('.').pop().toLowerCase();
  try{
    if(ext==='geojson'||ext==='json'){etGeoArea=etCalcGeoJsonArea(JSON.parse(await file.text()));etShowFileResult('✓ <strong>'+file.name+'</strong> — Alan: <strong>'+etFmt(etGeoArea)+' m²</strong>');}
    else if(ext==='kml'){etGeoArea=etCalcKmlArea(await file.text());etShowFileResult('✓ <strong>'+file.name+'</strong> — Alan: <strong>'+etFmt(etGeoArea)+' m²</strong>');}
    else if(ext==='kmz'){
      const zip=await JSZip.loadAsync(await file.arrayBuffer());
      const kf=Object.values(zip.files).find(f=>f.name.endsWith('.kml'));
      if(!kf)throw new Error('KMZ içinde KML bulunamadı.');
      etGeoArea=etCalcKmlArea(await kf.async('text'));
      etShowFileResult('✓ <strong>'+file.name+'</strong> — Alan: <strong>'+etFmt(etGeoArea)+' m²</strong>');
    } else {etShowFileResult('Desteklenmeyen format.',true);return;}
    etUpdateCalc();
  } catch(err){etShowFileResult('Hata: '+err.message,true);}
}
function etCalcGeoJsonArea(geo){
  if(geo.type==='FeatureCollection'){const f=geo.features.find(f=>f.geometry&&(f.geometry.type==='Polygon'||f.geometry.type==='MultiPolygon'));if(f)geo=f;}
  if(geo.type==='Feature')geo=geo.geometry;
  const c=geo.type==='Polygon'?geo.coordinates[0]:geo.type==='MultiPolygon'?geo.coordinates[0][0]:null;
  if(!c)throw new Error('Polygon geometrisi bulunamadı.');
  return etPolygonArea(c);
}
function etCalcKmlArea(text){
  const doc=new DOMParser().parseFromString(text,'text/xml');
  const els=doc.getElementsByTagName('coordinates');
  if(!els.length)throw new Error('Koordinat bulunamadı.');
  let best=null,bestN=0;
  for(const el of els){
    const pts=el.textContent.trim().split(/\s+/).map(p=>{const[lng,lat]=p.split(',').map(Number);return[lng,lat];}).filter(p=>!isNaN(p[0])&&!isNaN(p[1]));
    if(pts.length>bestN){bestN=pts.length;best=pts;}
  }
  if(!best)throw new Error('Geçerli koordinat bulunamadı.');
  return etPolygonArea(best);
}
function etPolygonArea(c){
  const n=c.length;if(n<3)return 0;
  const la=c.reduce((s,p)=>s+p[1],0)/n*Math.PI/180,mL=111319.9,mG=mL*Math.cos(la);
  let a=0;
  for(let i=0;i<n;i++){const j=(i+1)%n;a+=c[i][0]*mG*c[j][1]*mL-c[j][0]*mG*c[i][1]*mL;}
  return Math.abs(a)/2;
}

function etSetAlanTipi(t){
  etAlanTipi=t;
  ['emsal','tahmini','manuel'].forEach(x=>document.getElementById('et-alan-btn-'+x).classList.toggle('active',x===t));
  document.getElementById('et-alan-manuel-wrap').style.display=t==='manuel'?'block':'none';
  etRefreshAlanDisplay();etOnMaliyetChange();
}
function etGetEmsalAlani(){
  const pa=etGetParselArea(),k=parseFloat(document.getElementById('kaks').value);
  return(pa>0&&!isNaN(k)&&k>0)?pa*k:0;
}
function etGetTahminiAlani(){
  const base=etGetEmsalAlani();if(base<=0)return 0;
  let ex=0;
  const pa=etGetParselArea(),taks=parseFloat(document.getElementById('taks').value)||0;
  const tabinAlan=pa*taks,tamKat=tabinAlan>0?Math.floor(base/tabinAlan):0;
  if(document.getElementById('cb-yuzde30').checked)ex+=base*0.30;
  if(document.getElementById('cb-yangin-merdiven').checked){const kb=parseFloat(document.getElementById('inp-yangin-merdiven').value)||0;ex+=kb*tamKat;}
  if(document.getElementById('cb-yangin-hol').checked)ex+=6*tamKat;
  if(document.getElementById('cb-otopark').checked)ex+=parseFloat(document.getElementById('inp-otopark').value)||0;
  if(document.getElementById('cb-deprem').checked)ex+=parseFloat(document.getElementById('inp-deprem').value)||0;
  if(document.getElementById('cb-tesisat').checked){
    const lim=base*0.30,used=document.getElementById('cb-yuzde30').checked?lim:0;
    ex+=Math.min(parseFloat(document.getElementById('inp-tesisat').value)||0,lim-used);
  }
  return base+ex;
}
function etRefreshAlanDisplay(){
  const el=document.getElementById('et-alan-display');
  if(etAlanTipi==='emsal'){const a=etGetEmsalAlani();el.textContent=a>0?'Emsale Esas İnşaat Alanı: '+etFmt(a)+' m²':'Henüz hesaplanmadı — Adım 4\'ü doldurun.';}
  else if(etAlanTipi==='tahmini'){const a=etGetTahminiAlani();el.textContent=a>0?'Tahmini Yaklaşık İnşaat Alanı: '+etFmt(a)+' m²':'Henüz hesaplanmadı — Adım 4 ve 5\'i doldurun.';}
  else{el.textContent='Manuel alan giriniz:';}
}
function etOnSinifChange(){
  const sel=document.getElementById('et-yapi-sinif').value,disp=document.getElementById('et-birim-display');
  if(!sel){disp.style.display='none';etSecilenBirim=0;etOnMaliyetChange();return;}
  const[fiyat,sinif,aciklama]=sel.split('|');
  etSecilenBirim=parseFloat(fiyat);
  disp.style.display='flex';
  document.getElementById('et-birim-val').textContent=parseInt(fiyat).toLocaleString('tr-TR')+' TL/m²';
  document.getElementById('et-birim-sinif').textContent=sinif+' Grubu';
  document.getElementById('et-birim-aciklama').textContent=aciklama;
  etOnMaliyetChange();
}
function etOnMaliyetChange(){
  etRefreshAlanDisplay();
  const res=document.getElementById('et-maliyet-result');
  if(etSecilenBirim<=0){res.style.display='none';return;}
  let alan=0;
  if(etAlanTipi==='emsal')alan=etGetEmsalAlani();
  else if(etAlanTipi==='tahmini')alan=etGetTahminiAlani();
  else alan=parseFloat(document.getElementById('inp-alan-manuel').value)||0;
  if(alan<=0){res.style.display='none';return;}
  const kdvsiz=alan*etSecilenBirim,kdv=kdvsiz*0.20,kdvli=kdvsiz+kdv;
  document.getElementById('et-mv-kdvsiz').textContent=kdvsiz.toLocaleString('tr-TR',{maximumFractionDigits:0})+' TL';
  document.getElementById('et-mv-kdv').textContent=kdv.toLocaleString('tr-TR',{maximumFractionDigits:0})+' TL';
  document.getElementById('et-mv-kdvli').textContent=kdvli.toLocaleString('tr-TR',{maximumFractionDigits:0})+' TL';
  res.style.display='block';
}
</script>
@endpush
