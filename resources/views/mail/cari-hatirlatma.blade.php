<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cari Hesap Hatırlatma</title>
</head>
<body style="margin:0;padding:0;background:#F1F5F9;font-family:Arial,Helvetica,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;padding:32px 0;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

        <tr>
          <td style="background:#141414;padding:24px 40px;">
            <p style="margin:0;font-size:22px;font-weight:700;color:#FFFFFF;letter-spacing:3px;">SUÇEK</p>
            <p style="margin:5px 0 0;font-size:9px;color:rgba(255,255,255,0.55);letter-spacing:1.5px;text-transform:uppercase;">Cari Hesap · Günlük Hatırlatma</p>
          </td>
        </tr>

        @php $toplam = $musteriHareketleri->count() + $tedarikciHareketleri->count(); @endphp
        <tr>
          <td style="padding:32px 40px 16px;">
            <p style="margin:0;font-size:14px;color:#1E293B;line-height:1.7;">
              Vadesi yaklaşan veya geçmiş <strong>{{ $toplam }}</strong> ödeme bulunuyor:
            </p>
          </td>
        </tr>

        @if($musteriHareketleri->isNotEmpty())
        <tr>
          <td style="padding:0 40px 8px;">
            <p style="margin:0;font-size:11px;font-weight:700;color:#CC2200;text-transform:uppercase;letter-spacing:.05em;">Müşterilerden Alacaklarınız</p>
          </td>
        </tr>
        <tr>
          <td style="padding:0 40px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
              <tr>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;">Müşteri</td>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;">Açıklama</td>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;text-align:right;">Tutar</td>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;text-align:right;">Vade</td>
              </tr>
              @foreach($musteriHareketleri as $h)
              <tr>
                <td style="font-size:13px;color:#1E293B;padding:9px 0;border-bottom:1px solid #F1F5F9;">{{ $h->musteri->ad_soyad }}</td>
                <td style="font-size:13px;color:#1E293B;padding:9px 0;border-bottom:1px solid #F1F5F9;">{{ $h->aciklama }}</td>
                <td style="font-size:13px;color:#1E293B;padding:9px 0;border-bottom:1px solid #F1F5F9;text-align:right;">{{ number_format($h->tutar, 2, ',', '.') }} ₺</td>
                <td style="font-size:13px;padding:9px 0;border-bottom:1px solid #F1F5F9;text-align:right;color:{{ $h->vade_tarihi->isPast() ? '#CC2200' : '#1E293B' }};font-weight:{{ $h->vade_tarihi->isPast() ? '700' : '400' }};">
                  {{ $h->vade_tarihi->format('d.m.Y') }}
                </td>
              </tr>
              @endforeach
            </table>
          </td>
        </tr>
        @endif

        @if($tedarikciHareketleri->isNotEmpty())
        <tr>
          <td style="padding:0 40px 8px;">
            <p style="margin:0;font-size:11px;font-weight:700;color:#B8962E;text-transform:uppercase;letter-spacing:.05em;">Tedarikçilere Borçlarınız</p>
          </td>
        </tr>
        <tr>
          <td style="padding:0 40px 32px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
              <tr>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;">Tedarikçi</td>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;">Açıklama</td>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;text-align:right;">Tutar</td>
                <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;padding:8px 0;border-bottom:1px solid #E2E8F0;text-align:right;">Vade</td>
              </tr>
              @foreach($tedarikciHareketleri as $h)
              <tr>
                <td style="font-size:13px;color:#1E293B;padding:9px 0;border-bottom:1px solid #F1F5F9;">{{ $h->musteri->ad_soyad }}</td>
                <td style="font-size:13px;color:#1E293B;padding:9px 0;border-bottom:1px solid #F1F5F9;">{{ $h->aciklama }}</td>
                <td style="font-size:13px;color:#1E293B;padding:9px 0;border-bottom:1px solid #F1F5F9;text-align:right;">{{ number_format($h->tutar, 2, ',', '.') }} ₺</td>
                <td style="font-size:13px;padding:9px 0;border-bottom:1px solid #F1F5F9;text-align:right;color:{{ $h->vade_tarihi->isPast() ? '#CC2200' : '#1E293B' }};font-weight:{{ $h->vade_tarihi->isPast() ? '700' : '400' }};">
                  {{ $h->vade_tarihi->format('d.m.Y') }}
                </td>
              </tr>
              @endforeach
            </table>
          </td>
        </tr>
        @endif

        <tr>
          <td style="background:#141414;padding:16px 40px;text-align:center;">
            <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.35);">
              Bu e-posta <strong style="color:rgba(255,255,255,0.55);">SUÇEK Cari Hesap</strong> modülü tarafından otomatik gönderilmiştir.
            </p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>

</body>
</html>
