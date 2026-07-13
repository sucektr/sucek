<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SUÇEK MİMARLIK — Yaklaşık Maliyet & Fiyat Teklifi</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<style>
:root{
  --bg:#F8FAFC; --panel:#FFFFFF; --panel2:#F1F5F9; --line:#E2E8F0;
  --ink:#0F172A; --muted:#64748B; --accent:#CC2200; --accent-ink:#FFFFFF;
  --ok:#1A5C3A; --danger:#991B1B;
}
*{box-sizing:border-box; margin:0; padding:0;}
body{background:var(--bg); color:var(--ink); font-family:'IBM Plex Sans',sans-serif; font-size:14px;}
input,select,textarea,button{font-family:inherit; font-size:inherit; color:inherit;}
input,select,textarea{background:var(--panel2); border:1px solid var(--line); border-radius:3px; padding:7px 9px; width:100%;}
input:focus,select:focus,textarea:focus{outline:2px solid var(--accent); outline-offset:-1px;}
input[type=number]{font-family:'IBM Plex Mono',monospace;}
button{cursor:pointer; border:1px solid var(--line); background:var(--panel2); border-radius:3px; padding:8px 14px;}
button:hover{border-color:var(--accent);}
button.primary{background:var(--accent); color:var(--accent-ink); border-color:var(--accent); font-weight:600;}
button.danger{color:var(--danger);}
button:focus-visible{outline:2px solid var(--accent); outline-offset:2px;}

/* ===== Toolbar ===== */
.topbar{position:sticky; top:0; z-index:50; background:var(--bg); border-bottom:2px solid var(--accent);
  display:flex; align-items:center; gap:14px; padding:12px 20px; flex-wrap:wrap;}
.brand{font-family:'Oswald',sans-serif; font-weight:600; letter-spacing:.14em; font-size:17px; text-transform:uppercase;}
.brand small{display:block; font-family:'IBM Plex Sans'; font-weight:400; letter-spacing:.02em;
  text-transform:none; color:var(--muted); font-size:11px;}
.brand-input{background:transparent; border:1px solid transparent; border-radius:3px; padding:2px 4px; margin:-2px -4px;
  width:auto; min-width:120px; max-width:280px; font:inherit; font-family:inherit; font-weight:inherit;
  letter-spacing:inherit; text-transform:inherit; color:inherit;}
.brand-input:hover{border-color:var(--line);}
.brand-input:focus{outline:none; border-color:var(--accent);}
.topbar .grow{flex:1;}
.topbar select{width:auto; min-width:200px;}

/* Mode toggle — stamped switch */
.mode{display:flex; border:1px solid var(--line); border-radius:3px; overflow:hidden;}
.mode button{border:0; border-radius:0; padding:8px 16px; font-family:'Oswald'; letter-spacing:.1em;
  text-transform:uppercase; font-size:12px; color:var(--muted); background:var(--panel);}
.mode button.active{background:var(--accent); color:var(--accent-ink); font-weight:600;}

.wrap{max-width:1180px; margin:0 auto; padding:20px;}

/* ===== Cards ===== */
.card{background:var(--panel); border:1px solid var(--line); border-radius:4px; margin-bottom:18px;}
.card-head{display:flex; align-items:center; gap:10px; padding:10px 14px; border-bottom:1px solid var(--line);}
.card-head h2{font-family:'Oswald'; font-size:13px; letter-spacing:.14em; text-transform:uppercase; font-weight:500;}
.card-head::before{content:""; width:8px; height:22px; background:var(--accent);}
.card-body{padding:14px;}

.grid{display:grid; gap:10px;}
.grid.c3{grid-template-columns:repeat(3,1fr);}
.grid.c2{grid-template-columns:repeat(2,1fr);}
label.f{display:block;}
label.f span{display:block; font-size:11px; color:var(--muted); margin-bottom:4px; letter-spacing:.04em; text-transform:uppercase;}

/* ===== Groups & rows ===== */
.grp{border:1px solid var(--line); border-radius:4px; margin-bottom:14px; background:var(--panel); overflow:hidden;}
.grp-head{display:flex; align-items:center; gap:10px; background:var(--panel2); padding:8px 10px; border-bottom:1px solid var(--line);}
.grp-head .tab{width:8px; height:26px; background:var(--accent); flex:none;}
.grp-head input.gname{background:transparent; border:0; font-family:'Oswald'; letter-spacing:.08em;
  text-transform:uppercase; font-size:14px; font-weight:500; padding:4px 6px;}
.grp-head .gtotal{font-family:'IBM Plex Mono'; font-weight:600; white-space:nowrap; color:var(--accent);}
.grp-head button{padding:4px 10px; font-size:12px;}
table.items{width:100%; border-collapse:collapse;}
table.items th{font-size:10px; letter-spacing:.08em; text-transform:uppercase; color:var(--muted);
  text-align:left; padding:8px 8px 6px; border-bottom:1px solid var(--line); font-weight:500;}
table.items td{padding:4px 6px; border-bottom:1px solid var(--line); vertical-align:middle;}
table.items tr:last-child td{border-bottom:0;}
table.items input,table.items select{padding:6px 7px;}
td.num input{text-align:right; font-family:'IBM Plex Mono';}
td.tot{font-family:'IBM Plex Mono'; text-align:right; white-space:nowrap; padding-right:10px;}
td.del{width:34px; text-align:center;}
td.del button{padding:3px 8px; border:0; background:transparent; color:var(--muted); font-size:15px; line-height:1;}
td.del button:hover{color:var(--danger);}
.addrow{margin:8px; padding:6px 12px; font-size:12px; color:var(--muted); border-style:dashed;}
.addrow:hover{color:var(--accent);}
th.w-poz{width:9%;} th.w-birim{width:9%;} th.w-mik{width:10%;} th.w-bf{width:13%;} th.w-tut{width:13%;}

/* ===== Summary ===== */
.summary{display:grid; grid-template-columns:1fr 380px; gap:18px;}
.sumtable{width:100%;}
.sumtable td{padding:8px 6px; border-bottom:1px solid var(--line);}
.sumtable td:last-child{text-align:right; font-family:'IBM Plex Mono'; white-space:nowrap;}
.sumtable tr.grand td{border-top:2px solid var(--accent); border-bottom:0; font-weight:600; font-size:16px; color:var(--accent); padding-top:12px;}
.sumtable input{width:70px; text-align:right; font-family:'IBM Plex Mono'; padding:4px 6px; display:inline-block;}
.m2info{font-size:12px; color:var(--muted); margin-top:10px;}

/* ===== Teklif ayarları ===== */
#teklifCard{display:none;}
body.mode-teklif #teklifCard{display:block;}
.chk{display:flex; align-items:center; gap:8px; font-size:13px; color:var(--muted); margin-top:10px;}
.chk input{width:auto;}

.toolrow{display:flex; gap:10px; flex-wrap:wrap; margin:6px 0 40px;}
.hint{font-size:12px; color:var(--muted); padding:8px 2px 30px;}

/* ===== Poz kütüphanesi ===== */
#suggest{position:fixed; z-index:200; background:var(--panel); border:1px solid var(--accent);
  border-radius:3px; max-height:300px; overflow:auto; display:none; box-shadow:0 8px 24px rgba(15,23,42,.15);}
#suggest .s-item{padding:7px 10px; cursor:pointer; border-bottom:1px solid var(--line); font-size:12px;}
#suggest .s-item:hover{background:var(--panel);}
#suggest .s-item b{font-family:'IBM Plex Mono'; color:var(--accent); font-weight:500;}
#suggest .s-item .s-meta{color:var(--muted); font-family:'IBM Plex Mono'; font-size:11px; margin-top:2px; display:flex; justify-content:space-between;}
.src-tag{display:inline-block; padding:1px 6px; border:1px solid var(--line); border-radius:2px; font-size:10px; letter-spacing:.05em;}

.modal-bg{position:fixed; inset:0; background:rgba(0,0,0,.6); z-index:150; display:none; align-items:flex-start; justify-content:center; padding:6vh 16px;}
.modal-bg.open{display:flex;}
.modal{background:var(--panel); border:1px solid var(--line); border-top:3px solid var(--accent);
  border-radius:4px; width:100%; max-width:860px; max-height:84vh; display:flex; flex-direction:column;}
.modal-head{display:flex; gap:10px; align-items:center; padding:12px 14px; border-bottom:1px solid var(--line);}
.modal-head h3{font-family:'Oswald'; font-size:14px; letter-spacing:.12em; text-transform:uppercase; font-weight:500; flex:1;}
.modal-body{padding:12px 14px; overflow:auto;}
.chips{display:flex; gap:8px; flex-wrap:wrap; margin:10px 0;}
.chips label{display:flex; gap:5px; align-items:center; font-size:12px; color:var(--muted);
  border:1px solid var(--line); border-radius:3px; padding:5px 9px; cursor:pointer;}
.chips input{width:auto;}
table.pozres{width:100%; border-collapse:collapse; font-size:12px;}
table.pozres td{padding:6px 8px; border-bottom:1px solid var(--line); vertical-align:top;}
table.pozres td.mono{font-family:'IBM Plex Mono'; white-space:nowrap;}
table.pozres tr:hover{background:var(--panel2);}
table.pozres button{padding:3px 10px; font-size:12px;}
.libinfo{font-size:11px; color:var(--muted); margin-left:auto;}

/* ===== PRINT ===== */
#printArea{display:none;}
@media print{
  body>*:not(#printArea){display:none !important;}
  body{background:#fff; color:#111; font-size:11px;}
  #printArea{display:block; font-family:'IBM Plex Sans',sans-serif;}
  @page{size:A4; margin:14mm 12mm;}

  .p-antet{display:grid; grid-template-columns:1fr auto; border:2px solid #111; margin-bottom:6mm;}
  .p-antet .p-firm{padding:4mm 5mm; border-right:2px solid #111;}
  .p-antet .p-firm h1{font-family:'Oswald'; font-size:18px; letter-spacing:.14em; text-transform:uppercase;}
  .p-antet .p-firm .sub{font-size:9px; color:#444; margin-top:1mm; letter-spacing:.03em;}
  .p-antet .p-doc{padding:4mm 5mm; text-align:right; background:#CC2200; min-width:52mm; color:#fff;}
  .p-antet .p-doc .dt{font-family:'Oswald'; font-size:13px; letter-spacing:.12em; text-transform:uppercase; font-weight:600;}
  .p-antet .p-doc .dd{font-size:9px; margin-top:1mm;}

  table.p-info{width:100%; border-collapse:collapse; margin-bottom:5mm; font-size:10px;}
  table.p-info td{border:1px solid #999; padding:1.6mm 2.5mm;}
  table.p-info td.k{background:#F2F2F0; font-weight:600; width:16%; text-transform:uppercase; font-size:8.5px; letter-spacing:.05em;}

  table.p-items{width:100%; border-collapse:collapse; font-size:10px;}
  table.p-items th{background:#111; color:#fff; padding:1.8mm 2mm; text-align:left; font-size:8.5px;
    letter-spacing:.08em; text-transform:uppercase; font-weight:600;}
  table.p-items td{border:1px solid #BBB; padding:1.5mm 2mm; vertical-align:top;}
  table.p-items tr.pg td{background:#CC2200; color:#fff; font-family:'Oswald'; font-weight:600; letter-spacing:.08em;
    text-transform:uppercase; font-size:10px; border-color:#111;}
  table.p-items td.r{text-align:right; font-family:'IBM Plex Mono'; white-space:nowrap;}
  table.p-items tr.psub td{background:#F2F2F0; font-weight:600;}
  table.p-items tr{page-break-inside:avoid;}

  table.p-sum{border-collapse:collapse; margin-left:auto; margin-top:5mm; font-size:10.5px; min-width:70mm;}
  table.p-sum td{padding:1.6mm 3mm; border-bottom:1px solid #CCC;}
  table.p-sum td:last-child{text-align:right; font-family:'IBM Plex Mono'; white-space:nowrap;}
  table.p-sum tr.pgrand td{border-top:2px solid #111; border-bottom:2px solid #111; font-weight:700; font-size:12px; background:#CC2200; color:#fff;}

  .p-notes{margin-top:6mm; font-size:9.5px; page-break-inside:avoid;}
  .p-notes h3{font-family:'Oswald'; font-size:10px; letter-spacing:.1em; text-transform:uppercase;
    border-bottom:1.5px solid #111; padding-bottom:1mm; margin-bottom:2mm;}
  .p-notes ul{margin-left:5mm;}
  .p-notes li{margin-bottom:1mm;}

  .p-sign{display:grid; grid-template-columns:1fr 1fr; gap:10mm; margin-top:10mm; page-break-inside:avoid;}
  .p-sign .box{border-top:1.5px solid #111; padding-top:2mm; font-size:9px; text-align:center; color:#444;}
  .p-sign .box b{display:block; color:#111; font-size:10px; margin-bottom:6mm;}
}

@media(max-width:820px){
  .grid.c3{grid-template-columns:1fr;}
  .summary{grid-template-columns:1fr;}
  table.items{font-size:12px;}
}
@media(prefers-reduced-motion:reduce){*{transition:none !important;}}
</style>
</head>
<body class="mode-maliyet">

<div class="topbar">
  <div class="brand">
    <input id="firmaAdi" class="brand-input" oninput="saveFirma()" aria-label="Firma Adı" spellcheck="false">
    <small>Yaklaşık Maliyet & Fiyat Teklifi</small>
  </div>
  <div class="grow"></div>
  <select id="projSelect" title="Kayıtlı projeler"></select>
  <button onclick="newProject()">+ Yeni</button>
  <button onclick="duplicateProject()" title="Seçili projeyi şablon olarak kopyala">Kopyala</button>
  <button class="danger" onclick="deleteProject()">Sil</button>
  <div class="mode" role="tablist" aria-label="Belge modu">
    <button id="mMaliyet" class="active" onclick="setMode('maliyet')">Yaklaşık Maliyet</button>
    <button id="mTeklif" onclick="setMode('teklif')">Fiyat Teklifi</button>
  </div>
  <a href="{{ route('admin.dashboard') }}" style="color:var(--muted); font-size:12px; text-decoration:none; border:1px solid var(--line); border-radius:3px; padding:8px 14px;">← Panele Dön</a>
</div>

<div class="wrap">

  <div class="card">
    <div class="card-head"><h2>Proje Bilgileri</h2></div>
    <div class="card-body grid c3">
      <label class="f"><span>Proje Adı</span><input id="pAd" data-k="ad" placeholder="Örn. Konut İç Mimari Uygulama"></label>
      <label class="f"><span>İşveren / Müşteri</span><input id="pIsveren" data-k="isveren" placeholder="Ad Soyad / Firma"></label>
      <label class="f"><span>Proje Yeri</span><input id="pYer" data-k="yer" placeholder="İlçe / İl"></label>
      <label class="f"><span>Tarih</span><input id="pTarih" data-k="tarih" type="date"></label>
      <label class="f"><span>Belge No</span><input id="pNo" data-k="no" placeholder="SM-2026-001"></label>
      <label class="f"><span>Uygulama Alanı (m²)</span><input id="pAlan" data-k="alan" type="number" min="0" step="0.01" placeholder="0"></label>
    </div>
  </div>

  <div id="groups"></div>
  <button class="addrow" style="width:100%; margin:0 0 18px;" onclick="addGroup()">+ İmalat Grubu Ekle</button>

  <div class="card">
    <div class="card-head"><h2>Maliyet Özeti</h2></div>
    <div class="card-body summary">
      <div class="hint" id="sumHint"></div>
      <div>
        <table class="sumtable">
          <tr><td>İmalat Toplamı</td><td id="sAra">0,00 ₺</td></tr>
          <tr><td>Genel Gider + Kâr <input id="sGGK" type="number" min="0" step="0.5" value="25"> %</td><td id="sGGKt">0,00 ₺</td></tr>
          <tr><td>İskonto <input id="sIsk" type="number" min="0" step="0.5" value="0"> %</td><td id="sIskT">0,00 ₺</td></tr>
          <tr><td>KDV <input id="sKdv" type="number" min="0" step="1" value="20"> %</td><td id="sKdvT">0,00 ₺</td></tr>
          <tr class="grand"><td>GENEL TOPLAM</td><td id="sGenel">0,00 ₺</td></tr>
        </table>
        <div class="m2info" id="sM2"></div>
      </div>
    </div>
  </div>

  <div class="card" id="teklifCard">
    <div class="card-head"><h2>Teklif Ayarları</h2></div>
    <div class="card-body">
      <div class="grid c3">
        <label class="f"><span>Teklif Geçerlilik Süresi</span><input id="tGecerlilik" data-k="gecerlilik" placeholder="15 gün"></label>
        <label class="f"><span>İş Süresi</span><input id="tSure" data-k="sure" placeholder="45 takvim günü"></label>
        <label class="f"><span>Ödeme Koşulları</span><input id="tOdeme" data-k="odeme" placeholder="%40 peşin, %40 imalat, %20 teslim"></label>
      </div>
      <label class="f" style="margin-top:10px;"><span>Teklif Notları / Kapsam Dışı İşler (her satır ayrı madde)</span>
        <textarea id="tNotlar" data-k="notlar" rows="5" placeholder="Fiyatlara nakliye dahildir.&#10;Elektrik altyapı işleri kapsam dışıdır.&#10;..."></textarea></label>
      <label class="chk"><input type="checkbox" id="tGizle" data-k="gizle"> Çıktıda birim fiyatları gizle (yalnızca grup toplamları görünsün)</label>
    </div>
  </div>

  <div class="toolrow">
    <button class="primary" onclick="printDoc()">🖨 Yazdır / PDF</button>
    <button onclick="exportExcel()">⬇ Excel'e Aktar</button>
    <button onclick="exportJSON()">⬇ Yedekle (JSON)</button>
    <button onclick="document.getElementById('impFile').click()">⬆ Yedekten Yükle</button>
    <input type="file" id="impFile" accept=".json" style="display:none" onchange="importJSON(this)">
    <button onclick="exportLib()" title="Gömülü + özel pozları JSON olarak indir">⬇ Poz Kütüphanesi</button>
    <button onclick="document.getElementById('libFile').click()" title="Yeni dönem poz listesi yükle (JSON)">⬆ Poz Listesi Güncelle</button>
    <input type="file" id="libFile" accept=".json" style="display:none" onchange="importLib(this)">
  </div>
  <div class="hint">Veriler tarayıcınızda otomatik kaydedilir (localStorage). Farklı bir bilgisayara taşımak için JSON yedeği kullanın.</div>
</div>

<div id="suggest"></div>

<div class="modal-bg" id="pozModalBg" onclick="if(event.target===this)closePozModal()">
  <div class="modal">
    <div class="modal-head">
      <h3>Poz Kütüphanesi</h3>
      <span class="libinfo" id="libInfo"></span>
      <button onclick="closePozModal()">✕ Kapat</button>
    </div>
    <div class="modal-body">
      <input id="pozQuery" placeholder="Poz no veya tanımda ara... (örn. saten alçı, 15.280, laminat)" oninput="pozModalSearch()">
      <div class="chips" id="srcChips"></div>
      <div style="max-height:52vh; overflow:auto;">
        <table class="pozres"><tbody id="pozResults"></tbody></table>
      </div>
      <div class="hint" style="padding-top:8px;">Fiyatlar 2026/05 dönemi, KDV hariçtir. ÇŞB ve MSB pozlarına %25 yüklenici kârı ve genel gider dahildir; PTT-Rayiç bedelleri çıplak rayiçtir.</div>
    </div>
  </div>
</div>

<div id="printArea"></div>

<script>
// Poz kütüphanesi ayrı bir JSON dosyası olarak sunuluyor (public/assets/poz-kutuphanesi-2026-05.json).
// Dosya büyük olduğundan (binlerce kalem) sayfaya gömülmek yerine tarayıcıda önbelleğe alınacak
// şekilde ayrı indiriliyor; "Poz Listesi Güncelle" ile yüklenen özel liste her zaman localStorage'da kalır.
let POZLIB = [];
fetch('{{ asset('assets/poz-kutuphanesi-2026-05.json') }}')
  .then(r => r.ok ? r.json() : [])
  .then(data => { POZLIB = Array.isArray(data) ? data : []; })
  .catch(() => { POZLIB = []; });
</script>
<script>
/* ================= STATE ================= */
const LS_KEY = 'sucek_maliyet_v1';
const FIRMA_KEY = 'sucek_maliyet_firma';
const getFirma = () => localStorage.getItem(FIRMA_KEY) || 'SUÇEK MİMARLIK';
function saveFirma(){
  const v = document.getElementById('firmaAdi').value.trim() || 'SUÇEK MİMARLIK';
  localStorage.setItem(FIRMA_KEY, v);
}
const BIRIMLER = ['m²','m³','mt','adet','kg','ton','takım','saat','gün','götürü'];
const DEFAULT_GROUPS = ['SÖKÜM VE HAZIRLIK','KABA YAPI / DUVAR-SIVA','ZEMİN KAPLAMALARI','DUVAR KAPLAMALARI','TAVAN İŞLERİ','DOĞRAMA VE KAPILAR','ÖZEL İMALAT / MOBİLYA','MEKANİK TESİSAT','ELEKTRİK TESİSAT','BOYA VE SON KAT İŞLERİ'];

let db = { projects:{}, currentId:null };
let mode = 'maliyet';
const uid = () => Date.now().toString(36) + Math.random().toString(36).slice(2,7);

function blankProject(name){
  return {
    ad:name||'', isveren:'', yer:'', tarih:new Date().toISOString().slice(0,10),
    no:'SM-'+new Date().getFullYear()+'-', alan:'',
    ggk:25, isk:0, kdv:20,
    gecerlilik:'15 gün', sure:'', odeme:'', notlar:'', gizle:false,
    groups: DEFAULT_GROUPS.slice(0,4).map(g=>({ id:uid(), name:g, items:[blankItem()] }))
  };
}
const blankItem = () => ({ id:uid(), poz:'', tanim:'', birim:'m²', miktar:'', bf:'' });

function load(){
  try{ const raw = localStorage.getItem(LS_KEY); if(raw) db = JSON.parse(raw); }catch(e){}
  if(!db.currentId || !db.projects[db.currentId]){
    const id = uid();
    db.projects[id] = blankProject('Yeni Proje');
    db.currentId = id;
  }
  save();
}
let saveT;
function save(){ clearTimeout(saveT); saveT = setTimeout(()=>localStorage.setItem(LS_KEY, JSON.stringify(db)), 250); }
const P = () => db.projects[db.currentId];

/* ================= FORMAT ================= */
const fmt = new Intl.NumberFormat('tr-TR',{minimumFractionDigits:2, maximumFractionDigits:2});
const money = v => fmt.format(v||0) + ' ₺';
const num = v => parseFloat(String(v).replace(',','.')) || 0;

/* ================= CALC ================= */
function calc(){
  const p = P();
  let ara = 0;
  const groupTotals = {};
  p.groups.forEach(g=>{
    let gt = 0;
    g.items.forEach(it=>{ gt += num(it.miktar)*num(it.bf); });
    groupTotals[g.id] = gt; ara += gt;
  });
  const ggkT  = ara * num(p.ggk)/100;
  const brut  = ara + ggkT;
  const iskT  = brut * num(p.isk)/100;
  const net   = brut - iskT;
  const kdvT  = net * num(p.kdv)/100;
  const genel = net + kdvT;
  return {ara, ggkT, iskT, net, kdvT, genel, groupTotals};
}

/* ================= RENDER ================= */
function renderProjectList(){
  const sel = document.getElementById('projSelect');
  sel.innerHTML = '';
  Object.entries(db.projects).forEach(([id,p])=>{
    const o = document.createElement('option');
    o.value = id; o.textContent = p.ad || '(isimsiz proje)';
    if(id===db.currentId) o.selected = true;
    sel.appendChild(o);
  });
}
document.getElementById('projSelect').addEventListener('change', e=>{ db.currentId = e.target.value; save(); renderAll(); });

function renderInfo(){
  const p = P();
  document.querySelectorAll('[data-k]').forEach(el=>{
    const k = el.dataset.k;
    if(el.type==='checkbox') el.checked = !!p[k]; else el.value = p[k] ?? '';
  });
  document.getElementById('sGGK').value = p.ggk;
  document.getElementById('sIsk').value = p.isk;
  document.getElementById('sKdv').value = p.kdv;
}
document.querySelectorAll('[data-k]').forEach(el=>{
  el.addEventListener('input', ()=>{
    const k = el.dataset.k;
    P()[k] = el.type==='checkbox' ? el.checked : el.value;
    if(k==='ad') renderProjectList();
    if(k==='alan') renderSummary();
    save();
  });
});
['sGGK','sIsk','sKdv'].forEach(id=>{
  document.getElementById(id).addEventListener('input', e=>{
    const map = {sGGK:'ggk', sIsk:'isk', sKdv:'kdv'};
    P()[map[id]] = e.target.value; save(); renderSummary();
  });
});

function renderGroups(){
  const p = P();
  const host = document.getElementById('groups');
  host.innerHTML = '';
  p.groups.forEach((g,gi)=>{
    const d = document.createElement('div');
    d.className = 'grp';
    d.innerHTML = `
      <div class="grp-head">
        <div class="tab"></div>
        <input class="gname" value="${esc(g.name)}" placeholder="GRUP ADI" data-g="${g.id}">
        <div style="flex:1"></div>
        <div class="gtotal" id="gt-${g.id}">0,00 ₺</div>
        <button title="Poz kütüphanesinde ara" onclick="openPozModal('${g.id}')">🔍 Poz</button>
        <button title="Yukarı taşı" onclick="moveGroup('${g.id}',-1)">↑</button>
        <button title="Aşağı taşı" onclick="moveGroup('${g.id}',1)">↓</button>
        <button class="danger" title="Grubu sil" onclick="delGroup('${g.id}')">✕</button>
      </div>
      <table class="items">
        <thead><tr>
          <th style="width:34px">No</th><th class="w-poz">Poz No</th><th>İmalatın Tanımı</th>
          <th class="w-birim">Birim</th><th class="w-mik">Miktar</th><th class="w-bf">Birim Fiyat</th>
          <th class="w-tut" style="text-align:right">Tutar</th><th style="width:34px"></th>
        </tr></thead>
        <tbody id="tb-${g.id}"></tbody>
      </table>
      <button class="addrow" onclick="addItem('${g.id}')">+ satır ekle</button>`;
    host.appendChild(d);
    const tb = d.querySelector('#tb-'+g.id);
    g.items.forEach((it,ii)=>{
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td style="color:var(--muted); font-family:'IBM Plex Mono'; font-size:12px; text-align:center">${gi+1}.${ii+1}</td>
        <td><input value="${esc(it.poz)}" data-it="${it.id}" data-f="poz" placeholder="—"></td>
        <td><input value="${esc(it.tanim)}" data-it="${it.id}" data-f="tanim" placeholder="İmalat açıklaması"></td>
        <td><select data-it="${it.id}" data-f="birim">${BIRIMLER.map(b=>`<option ${b===it.birim?'selected':''}>${b}</option>`).join('')}</select></td>
        <td class="num"><input type="number" min="0" step="0.01" value="${esc(it.miktar)}" data-it="${it.id}" data-f="miktar"></td>
        <td class="num"><input type="number" min="0" step="0.01" value="${esc(it.bf)}" data-it="${it.id}" data-f="bf"></td>
        <td class="tot" id="it-${it.id}">0,00 ₺</td>
        <td class="del"><button title="Satırı sil" onclick="delItem('${g.id}','${it.id}')">✕</button></td>`;
      tb.appendChild(tr);
    });
  });
  host.querySelectorAll('input.gname').forEach(el=>{
    el.addEventListener('input', ()=>{ findGroup(el.dataset.g).name = el.value; save(); });
  });
  host.querySelectorAll('[data-it]').forEach(el=>{
    el.addEventListener('input', ()=>{
      const it = findItem(el.dataset.it);
      it[el.dataset.f] = el.value; save(); renderSummary();
      if(el.dataset.f==='tanim' || el.dataset.f==='poz') showSuggest(el, el.dataset.it);
    });
    if(el.dataset.f==='poz'){
      el.addEventListener('change', ()=>{
        const hit = pozExact(el.value);
        const it = findItem(el.dataset.it);
        if(hit && it && !it.tanim) fillRow(el.dataset.it, hit);
      });
    }
  });
  renderSummary();
}

function renderSummary(){
  const p = P(), c = calc();
  p.groups.forEach(g=>{
    const el = document.getElementById('gt-'+g.id);
    if(el) el.textContent = money(c.groupTotals[g.id]);
    g.items.forEach(it=>{
      const t = document.getElementById('it-'+it.id);
      if(t) t.textContent = money(num(it.miktar)*num(it.bf));
    });
  });
  document.getElementById('sAra').textContent  = money(c.ara);
  document.getElementById('sGGKt').textContent = money(c.ggkT);
  document.getElementById('sIskT').textContent = c.iskT ? '− '+money(c.iskT) : money(0);
  document.getElementById('sKdvT').textContent = money(c.kdvT);
  document.getElementById('sGenel').textContent= money(c.genel);
  const alan = num(p.alan);
  document.getElementById('sM2').textContent = alan>0 ? `Birim maliyet: ${money(c.genel/alan)} / m²  (KDV dahil)` : '';
  document.getElementById('sumHint').textContent =
    mode==='maliyet'
      ? 'İç yaklaşık maliyet görünümü: Genel Gider + Kâr oranı iç hesap içindir; çıktıda ayrı satır olarak görünür.'
      : 'Teklif görünümü: GG+K tutarı birim fiyatlara dağıtılır, müşteri çıktısında ayrı satır olarak görünmez.';
}

const esc = s => String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/"/g,'&quot;');
const findGroup = id => P().groups.find(g=>g.id===id);
function findItem(id){ for(const g of P().groups){ const it=g.items.find(i=>i.id===id); if(it) return it; } }

/* ================= ACTIONS ================= */
function addGroup(){ P().groups.push({id:uid(), name:'YENİ GRUP', items:[blankItem()]}); save(); renderGroups(); }
function delGroup(id){
  const g = findGroup(id);
  if(g.items.some(i=>i.tanim||i.miktar||i.bf) && !confirm('"'+g.name+'" grubu ve satırları silinsin mi?')) return;
  P().groups = P().groups.filter(x=>x.id!==id); save(); renderGroups();
}
function moveGroup(id,dir){
  const arr = P().groups, i = arr.findIndex(g=>g.id===id), j = i+dir;
  if(j<0||j>=arr.length) return;
  [arr[i],arr[j]] = [arr[j],arr[i]]; save(); renderGroups();
}
function addItem(gid){ findGroup(gid).items.push(blankItem()); save(); renderGroups(); }
function delItem(gid,iid){
  const g = findGroup(gid);
  g.items = g.items.filter(i=>i.id!==iid);
  if(!g.items.length) g.items.push(blankItem());
  save(); renderGroups();
}
function newProject(){
  const id = uid(); db.projects[id] = blankProject('Yeni Proje'); db.currentId = id;
  save(); renderAll();
}
function duplicateProject(){
  const id = uid();
  const copy = JSON.parse(JSON.stringify(P()));
  copy.ad = (copy.ad||'Proje') + ' (kopya)';
  copy.groups.forEach(g=>{ g.id=uid(); g.items.forEach(i=>i.id=uid()); });
  db.projects[id] = copy; db.currentId = id; save(); renderAll();
}
function deleteProject(){
  if(!confirm('"'+(P().ad||'isimsiz')+'" projesi silinsin mi?')) return;
  delete db.projects[db.currentId];
  const ids = Object.keys(db.projects);
  db.currentId = ids[0] || null;
  load(); renderAll();
}
function setMode(m){
  mode = m;
  document.body.className = 'mode-'+m;
  document.getElementById('mMaliyet').classList.toggle('active', m==='maliyet');
  document.getElementById('mTeklif').classList.toggle('active', m==='teklif');
  renderSummary();
}

/* ================= PRINT ================= */
function printDoc(){
  const p = P(), c = calc();
  const isTeklif = mode==='teklif';
  const carpan = isTeklif ? (1 + num(p.ggk)/100) : 1;   // teklifte GG+K birim fiyata gömülür
  const title = isTeklif ? 'FİYAT TEKLİFİ' : 'YAKLAŞIK MALİYET';
  let no = 1, rows = '';

  p.groups.forEach((g,gi)=>{
    const items = g.items.filter(i=>i.tanim||num(i.miktar)||num(i.bf));
    if(!items.length) return;
    rows += `<tr class="pg"><td colspan="${p.gizle&&isTeklif?3:6}">${String.fromCharCode(64+gi+1)} — ${esc(g.name)}</td></tr>`;
    let gt = 0;
    items.forEach(it=>{
      const bf = num(it.bf)*carpan, tut = num(it.miktar)*bf; gt += tut;
      if(p.gizle && isTeklif){
        rows += `<tr><td class="r" style="width:8mm">${no++}</td><td>${esc(it.tanim)}</td>
                 <td class="r">${fmt.format(num(it.miktar))} ${esc(it.birim)}</td></tr>`;
      }else{
        rows += `<tr><td class="r" style="width:8mm">${no++}</td>
                 <td style="width:16mm">${esc(it.poz)||'—'}</td><td>${esc(it.tanim)}</td>
                 <td class="r" style="width:20mm">${fmt.format(num(it.miktar))} ${esc(it.birim)}</td>
                 <td class="r" style="width:24mm">${money(bf)}</td>
                 <td class="r" style="width:26mm">${money(tut)}</td></tr>`;
      }
    });
    rows += `<tr class="psub"><td colspan="${p.gizle&&isTeklif?2:5}" style="text-align:right">${esc(g.name)} TOPLAMI</td><td class="r">${money(gt*(p.gizle&&isTeklif?1:1))}</td></tr>`;
  });

  const araP = isTeklif ? c.ara + c.ggkT : c.ara;
  let sum = `<tr><td>İmalat Toplamı</td><td>${money(araP)}</td></tr>`;
  if(!isTeklif) sum = `<tr><td>İmalat Toplamı</td><td>${money(c.ara)}</td></tr>
    <tr><td>Genel Gider + Kâr (%${p.ggk})</td><td>${money(c.ggkT)}</td></tr>`;
  if(num(p.isk)>0) sum += `<tr><td>İskonto (%${p.isk})</td><td>− ${money(c.iskT)}</td></tr>`;
  sum += `<tr><td>KDV (%${p.kdv})</td><td>${money(c.kdvT)}</td></tr>
          <tr class="pgrand"><td>GENEL TOPLAM</td><td>${money(c.genel)}</td></tr>`;

  const notlar = (p.notlar||'').split('\n').map(s=>s.trim()).filter(Boolean);
  const koşullar = [];
  if(isTeklif){
    if(p.gecerlilik) koşullar.push('Teklif geçerlilik süresi: '+esc(p.gecerlilik));
    if(p.sure)       koşullar.push('Öngörülen iş süresi: '+esc(p.sure));
    if(p.odeme)      koşullar.push('Ödeme koşulları: '+esc(p.odeme));
  }
  const allNotes = [...koşullar, ...notlar.map(esc)];

  const alan = num(p.alan);
  document.getElementById('printArea').innerHTML = `
    <div class="p-antet">
      <div class="p-firm"><h1>${esc(getFirma())}</h1>
        <div class="sub">Mimarlık · İç Mimari · Uygulama — Etimesgut / Ankara · sucek.com.tr</div></div>
      <div class="p-doc"><div class="dt">${title}</div>
        <div class="dd">Belge No: ${esc(p.no)||'—'}<br>Tarih: ${p.tarih ? p.tarih.split('-').reverse().join('.') : '—'}</div></div>
    </div>
    <table class="p-info">
      <tr><td class="k">Proje</td><td>${esc(p.ad)||'—'}</td><td class="k">İşveren</td><td>${esc(p.isveren)||'—'}</td></tr>
      <tr><td class="k">Proje Yeri</td><td>${esc(p.yer)||'—'}</td><td class="k">Alan</td><td>${alan?fmt.format(alan)+' m²':'—'}</td></tr>
    </table>
    <table class="p-items">
      <thead><tr>
        ${p.gizle&&isTeklif
          ? '<th>No</th><th>İmalatın Tanımı</th><th style="text-align:right">Miktar</th>'
          : '<th>No</th><th>Poz No</th><th>İmalatın Tanımı</th><th style="text-align:right">Miktar</th><th style="text-align:right">Birim Fiyat</th><th style="text-align:right">Tutar</th>'}
      </tr></thead>
      <tbody>${rows}</tbody>
    </table>
    <table class="p-sum"><tbody>${sum}</tbody></table>
    ${alan>0?`<div style="text-align:right; font-size:9px; color:#555; margin-top:1mm">Birim maliyet: ${money(c.genel/alan)} / m² (KDV dahil)</div>`:''}
    ${allNotes.length?`<div class="p-notes"><h3>${isTeklif?'Teklif Koşulları ve Notlar':'Notlar'}</h3><ul>${allNotes.map(n=>'<li>'+n+'</li>').join('')}</ul></div>`:''}
    <div class="p-sign">
      <div class="box"><b>${isTeklif?'TEKLİFİ SUNAN':'HAZIRLAYAN'}</b>${esc(getFirma())} — Kaşe / İmza</div>
      <div class="box"><b>${isTeklif?'TEKLİFİ KABUL EDEN':'ONAYLAYAN'}</b>${esc(p.isveren)||'İşveren'} — Ad Soyad / İmza / Tarih</div>
    </div>`;
  window.print();
}

/* ================= EXPORT ================= */
function exportExcel(){
  const p = P(), c = calc();
  const isTeklif = mode==='teklif';
  const carpan = isTeklif ? (1 + num(p.ggk)/100) : 1;
  const aoa = [
    [getFirma() + ' — ' + (isTeklif?'FİYAT TEKLİFİ':'YAKLAŞIK MALİYET')],
    ['Proje', p.ad, '', 'İşveren', p.isveren],
    ['Yer', p.yer, '', 'Tarih', p.tarih],
    [],
    ['No','Poz No','İmalatın Tanımı','Birim','Miktar','Birim Fiyat','Tutar']
  ];
  let no=1;
  p.groups.forEach((g,gi)=>{
    const items = g.items.filter(i=>i.tanim||num(i.miktar)||num(i.bf));
    if(!items.length) return;
    aoa.push([String.fromCharCode(64+gi+1), '', g.name]);
    let gt=0;
    items.forEach(it=>{
      const bf=num(it.bf)*carpan, tut=num(it.miktar)*bf; gt+=tut;
      aoa.push([no++, it.poz, it.tanim, it.birim, num(it.miktar), +bf.toFixed(2), +tut.toFixed(2)]);
    });
    aoa.push(['','', g.name+' TOPLAMI','','','', +gt.toFixed(2)]);
  });
  aoa.push([]);
  if(isTeklif){ aoa.push(['','','','','','İmalat Toplamı', +(c.ara+c.ggkT).toFixed(2)]); }
  else{
    aoa.push(['','','','','','İmalat Toplamı', +c.ara.toFixed(2)]);
    aoa.push(['','','','','',`Genel Gider + Kâr (%${p.ggk})`, +c.ggkT.toFixed(2)]);
  }
  if(num(p.isk)>0) aoa.push(['','','','','',`İskonto (%${p.isk})`, -+c.iskT.toFixed(2)]);
  aoa.push(['','','','','',`KDV (%${p.kdv})`, +c.kdvT.toFixed(2)]);
  aoa.push(['','','','','','GENEL TOPLAM', +c.genel.toFixed(2)]);

  const ws = XLSX.utils.aoa_to_sheet(aoa);
  ws['!cols'] = [{wch:5},{wch:12},{wch:52},{wch:8},{wch:10},{wch:14},{wch:15}];
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, isTeklif?'Teklif':'Maliyet');
  XLSX.writeFile(wb, (p.ad||'proje').replace(/[^\wğüşöçıİĞÜŞÖÇ ]/g,'').trim()+' - '+(isTeklif?'teklif':'maliyet')+'.xlsx');
}

function exportJSON(){
  const blob = new Blob([JSON.stringify(db,null,2)],{type:'application/json'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'sucek-maliyet-yedek.json';
  a.click(); URL.revokeObjectURL(a.href);
}
function importJSON(inp){
  const f = inp.files[0]; if(!f) return;
  const r = new FileReader();
  r.onload = () => {
    try{
      const data = JSON.parse(r.result);
      if(!data.projects) throw 0;
      db = data; save(); load(); renderAll();
      alert('Yedek yüklendi.');
    }catch(e){ alert('Dosya okunamadı: geçerli bir yedek dosyası seçin.'); }
    inp.value='';
  };
  r.readAsText(f);
}

/* ================= POZ KÜTÜPHANESİ ================= */
const LIB_KEY = 'sucek_pozlib_custom';
let customLib = [];
try{ customLib = JSON.parse(localStorage.getItem(LIB_KEY)||'[]'); }catch(e){}
const LIB = () => POZLIB.concat(customLib);
const SRC_ORDER = ['ÇŞB','MSB','PTT','PTT-Rayiç'];
const trLower = s => String(s||'').toLocaleLowerCase('tr');
const fold = s => trLower(s).replace(/ı/g,'i').replace(/ş/g,'s').replace(/ğ/g,'g')
  .replace(/ü/g,'u').replace(/ö/g,'o').replace(/ç/g,'c').replace(/İ/g,'i');

function pozSearch(q, sources, limit){
  const terms = fold(q).split(/\s+/).filter(t=>t.length>=2);
  if(!terms.length) return [];
  const res = [];
  for(const it of LIB()){
    if(sources && !sources.has(it.k)) continue;
    const hay = fold(it.p+' '+it.t);
    if(terms.every(t=>hay.includes(t))){
      res.push(it);
      if(res.length >= (limit||50)*3) break;
    }
  }
  res.sort((a,b)=> (SRC_ORDER.indexOf(a.k)-SRC_ORDER.indexOf(b.k)) || a.t.length-b.t.length);
  return res.slice(0, limit||50);
}
function pozExact(no){
  const n = fold(no).replace(/\s/g,'');
  const hits = LIB().filter(it=>fold(it.p).replace(/\s/g,'')===n);
  hits.sort((a,b)=>SRC_ORDER.indexOf(a.k)-SRC_ORDER.indexOf(b.k));
  return hits[0]||null;
}
function fillRow(itemId, lib){
  const it = findItem(itemId); if(!it) return;
  it.poz = lib.p; it.tanim = lib.t; if(lib.b) it.birim = mapBirim(lib.b);
  it.bf = String(lib.f);
  save(); renderGroups();
}
function mapBirim(b){
  const m = {'mt':'mt','m²':'m²','m³':'m³','adet':'adet','kg':'kg','ton':'ton','takım':'takım','saat':'saat','lt':'adet','kutu':'adet','kwh':'adet'};
  return m[b] || (BIRIMLER.includes(b)?b:'adet');
}

/* --- satır içi öneriler --- */
const sug = document.getElementById('suggest');
let sugTarget = null;
function showSuggest(input, itemId){
  const q = input.value;
  const isPozField = input.dataset.f==='poz';
  const results = isPozField && /^\d/.test(q)
    ? LIB().filter(it=>it.p.startsWith(q)).slice(0,8)
    : pozSearch(q, null, 8);
  if(q.length<3 || !results.length){ sug.style.display='none'; return; }
  sugTarget = itemId;
  sug.innerHTML = results.map((r,i)=>`
    <div class="s-item" data-i="${i}">
      <b>${esc(r.p)}</b> ${esc(r.t.length>90?r.t.slice(0,90)+'…':r.t)}
      <div class="s-meta"><span class="src-tag">${esc(r.k)}</span><span>${r.b?esc(r.b)+' · ':''}${money(r.f)}</span></div>
    </div>`).join('');
  sug._results = results;
  const rc = input.getBoundingClientRect();
  sug.style.left = Math.min(rc.left, innerWidth-560)+'px';
  sug.style.top = (rc.bottom+2)+'px';
  sug.style.width = Math.max(rc.width, 540)+'px';
  sug.style.display = 'block';
}
sug.addEventListener('mousedown', e=>{
  const d = e.target.closest('.s-item'); if(!d) return;
  e.preventDefault();
  fillRow(sugTarget, sug._results[+d.dataset.i]);
  sug.style.display='none';
});
document.addEventListener('click', e=>{ if(!sug.contains(e.target)) sug.style.display='none'; });
window.addEventListener('scroll', ()=>sug.style.display='none', true);

/* --- modal --- */
let modalTargetGroup = null;
function openPozModal(gid){
  modalTargetGroup = gid;
  document.getElementById('pozModalBg').classList.add('open');
  document.getElementById('libInfo').textContent = LIB().length.toLocaleString('tr-TR')+' poz · 2026/05';
  const chips = document.getElementById('srcChips');
  if(!chips.children.length){
    const srcs = [...new Set(LIB().map(i=>i.k))];
    chips.innerHTML = srcs.map(s=>`<label><input type="checkbox" checked value="${esc(s)}" onchange="pozModalSearch()"> ${esc(s)}</label>`).join('');
  }
  document.getElementById('pozQuery').focus();
  pozModalSearch();
}
function closePozModal(){ document.getElementById('pozModalBg').classList.remove('open'); }
function pozModalSearch(){
  const q = document.getElementById('pozQuery').value;
  const sources = new Set([...document.querySelectorAll('#srcChips input:checked')].map(c=>c.value));
  const res = q.trim().length>=2 ? pozSearch(q, sources, 60) : [];
  const tb = document.getElementById('pozResults');
  tb.innerHTML = res.map((r,i)=>`
    <tr><td class="mono">${esc(r.p)}</td><td>${esc(r.t)}</td>
    <td class="mono">${esc(r.b)}</td><td class="mono" style="text-align:right">${money(r.f)}</td>
    <td><span class="src-tag">${esc(r.k)}</span></td>
    <td><button class="primary" onclick="addFromLib(${i})">Ekle</button></td></tr>`).join('')
    || `<tr><td style="color:var(--muted)">${q.trim().length>=2?'Sonuç bulunamadı.':'Aramak için en az 2 karakter yazın.'}</td></tr>`;
  tb._results = res;
}
function addFromLib(i){
  const lib = document.getElementById('pozResults')._results[i];
  const g = findGroup(modalTargetGroup); if(!g) return;
  const last = g.items[g.items.length-1];
  const empty = last && !last.tanim && !last.poz && !num(last.bf);
  const it = empty ? last : blankItem();
  it.poz=lib.p; it.tanim=lib.t; it.birim=mapBirim(lib.b||'adet'); it.bf=String(lib.f);
  if(!empty) g.items.push(it);
  save(); renderGroups();
}

/* --- kütüphane güncelleme --- */
function importLib(inp){
  const f = inp.files[0]; if(!f) return;
  const r = new FileReader();
  r.onload = () => {
    try{
      const data = JSON.parse(r.result);
      if(!Array.isArray(data) || !data[0].p) throw 0;
      customLib = data;
      localStorage.setItem(LIB_KEY, JSON.stringify(customLib));
      alert(data.length+' poz yüklendi. Aramalarda gömülü kütüphaneyle birlikte kullanılacak.');
    }catch(e){ alert('Geçersiz dosya: [{"p":"poz no","t":"tanım","b":"birim","f":fiyat,"k":"kaynak"}] biçiminde JSON bekleniyor.'); }
    inp.value='';
  };
  r.readAsText(f);
}
function exportLib(){
  const blob = new Blob([JSON.stringify(LIB())],{type:'application/json'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob); a.download = 'poz-kutuphanesi-2026-05.json';
  a.click(); URL.revokeObjectURL(a.href);
}

/* ================= INIT ================= */
function renderAll(){ renderProjectList(); renderInfo(); renderGroups(); }
document.getElementById('firmaAdi').value = getFirma();
load(); renderAll();
</script>

</body>
</html>
