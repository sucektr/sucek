<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<style>
  @page { margin: 0; }
  body { font-family: 'DejaVu Sans', sans-serif; color: #1E293B; font-size: 11px; margin: 0; padding: 0; }
  .sayfa { padding: 32px 40px; }

  .ust-bar { background: #141414; padding: 22px 40px; margin: -32px -40px 28px -40px; }
  .firma-ad { color: #FFFFFF; font-size: 22px; font-weight: bold; letter-spacing: 3px; margin: 0; }
  .firma-alt { color: #B8962E; font-size: 9px; letter-spacing: 1.5px; text-transform: uppercase; margin: 4px 0 0; }

  .baslik { font-size: 16px; font-weight: bold; color: #141414; margin: 0 0 16px; border-bottom: 2px solid #CC2200; padding-bottom: 8px; }

  .bilgi-kutu { width: 100%; margin-bottom: 20px; }
  .bilgi-kutu td { vertical-align: top; font-size: 11px; padding: 0; }
  .etiket { color: #94A3B8; font-size: 9px; text-transform: uppercase; letter-spacing: .06em; font-weight: bold; }
  .deger { color: #1E293B; font-size: 12px; font-weight: bold; }

  table.hareketler { width: 100%; border-collapse: collapse; margin-top: 8px; }
  table.hareketler th { background: #141414; color: #fff; font-size: 9px; text-transform: uppercase; letter-spacing: .05em; padding: 8px 10px; text-align: left; }
  table.hareketler td { padding: 7px 10px; font-size: 10.5px; border-bottom: 1px solid #E2E8F0; }
  table.hareketler tr:nth-child(even) td { background: #F8FAFC; }
  .sag { text-align: right; }
  .borc-metin { color: #CC2200; }
  .tahsilat-metin { color: #1D7A4C; }

  .bakiye-ozet { width: 100%; margin-top: 18px; border-top: 2px solid #141414; padding-top: 12px; }
  .bakiye-ozet td { font-size: 13px; font-weight: bold; padding: 4px 0; }

  .imza-alan { margin-top: 70px; width: 100%; }
  .imza-cizgi { border-top: 1px solid #94A3B8; width: 200px; padding-top: 6px; font-size: 9px; color: #94A3B8; text-transform: uppercase; letter-spacing: .06em; }

  .alt-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #141414; padding: 10px 40px; font-size: 8px; color: rgba(255,255,255,0.45); text-align: center; }
</style>
</head>
<body>
  <div class="sayfa">
    <div class="ust-bar">
      <p class="firma-ad">SUÇEK</p>
      <p class="firma-alt">Cari Hesap Ekstresi</p>
    </div>

    <p class="baslik">Hesap Özeti</p>

    <table class="bilgi-kutu">
      <tr>
        <td style="width:50%;">
          <div class="etiket">Müşteri</div>
          <div class="deger">{{ $musteri->ad_soyad }}</div>
          @if($musteri->telefon)<div>{{ $musteri->telefon }}</div>@endif
          @if($musteri->email)<div>{{ $musteri->email }}</div>@endif
          @if($musteri->vergi_no)<div>VD/VKN: {{ $musteri->vergi_dairesi }} {{ $musteri->vergi_no }}</div>@endif
        </td>
        <td style="width:50%;">
          <div class="etiket">Belge Tarihi</div>
          <div class="deger">{{ now()->format('d.m.Y') }}</div>
          <div class="etiket" style="margin-top:8px;">Firma</div>
          <div>info@sucek.com.tr</div>
          <div>{{ icerik('site', 'telefon', '') }}</div>
        </td>
      </tr>
    </table>

    <table class="hareketler">
      <thead>
        <tr>
          <th>Tarih</th>
          <th>Açıklama</th>
          <th class="sag">Borç</th>
          <th class="sag">Tahsilat</th>
          <th class="sag">Bakiye</th>
        </tr>
      </thead>
      <tbody>
        @php $cari = 0; @endphp
        @forelse($hareketler->sortBy('tarih') as $h)
          @php $cari += $h->tur === 'borc' ? $h->tutar : -$h->tutar; @endphp
          <tr>
            <td>{{ $h->tarih->format('d.m.Y') }}</td>
            <td>{{ $h->aciklama }}</td>
            <td class="sag borc-metin">{{ $h->tur === 'borc' ? number_format($h->tutar, 2, ',', '.') . ' ₺' : '' }}</td>
            <td class="sag tahsilat-metin">{{ $h->tur === 'tahsilat' ? number_format($h->tutar, 2, ',', '.') . ' ₺' : '' }}</td>
            <td class="sag">{{ number_format($cari, 2, ',', '.') }} ₺</td>
          </tr>
        @empty
          <tr><td colspan="5" style="text-align:center;color:#94A3B8;">Hareket bulunmuyor.</td></tr>
        @endforelse
      </tbody>
    </table>

    <table class="bakiye-ozet">
      <tr>
        <td>Güncel Bakiye</td>
        <td class="sag" style="color: {{ $bakiye > 0 ? '#CC2200' : '#1D7A4C' }};">
          {{ number_format($bakiye, 2, ',', '.') }} ₺
          {{ $bakiye > 0 ? '(Müşteri Borçlu)' : ($bakiye < 0 ? '(Müşteri Alacaklı)' : '') }}
        </td>
      </tr>
    </table>

    <div class="imza-alan">
      <div class="imza-cizgi">Yetkili İmza</div>
    </div>
  </div>

  <div class="alt-bar">SUÇEK · info@sucek.com.tr · Bu belge bilgilendirme amaçlıdır.</div>
</body>
</html>
