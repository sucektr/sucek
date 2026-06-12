<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $konu }}</title>
</head>
<body style="margin:0;padding:0;background:#F1F5F9;font-family:Arial,Helvetica,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;padding:32px 0;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

        {{-- ── Üst Bar ── --}}
        <tr>
          <td style="background:#1A0A0A;padding:24px 40px;">
            <p style="margin:0;font-size:24px;font-weight:700;color:#FFFFFF;letter-spacing:4px;">SUÇEK MİMARLIK</p>
            <p style="margin:5px 0 0;font-size:9px;color:rgba(255,255,255,0.55);letter-spacing:1.5px;text-transform:uppercase;">İÇ MİMARLIK &nbsp;·&nbsp; PROJE DANIŞMANLIK &nbsp;·&nbsp; İNŞAAT &nbsp;·&nbsp; EMLAK &nbsp;·&nbsp; SPOR MALZEMELERİ</p>
          </td>
        </tr>

        {{-- ── Mesaj İçeriği ── --}}
        <tr>
          <td style="padding:40px 40px 32px;">
            <div style="font-size:15px;color:#1E293B;line-height:1.9;white-space:pre-line;">{{ $icerik }}</div>
          </td>
        </tr>

        {{-- ── Kurumsal İmza ── --}}
        <tr>
          <td style="padding:0 40px 36px;">

            {{-- Ayırıcı --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
              <tr>
                <td style="height:2px;width:32px;background:#CC2200;"></td>
                <td style="height:1px;background:#E2E8F0;"></td>
              </tr>
            </table>

            {{-- İmza Kartı --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #E2E8F0;border-radius:8px;overflow:hidden;">
              <tr>
                {{-- Kırmızı sol çizgi --}}
                <td style="width:4px;background:#CC2200;">&nbsp;</td>

                {{-- İmza İçeriği --}}
                <td style="padding:20px 24px;background:#F8FAFC;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>

                      {{-- Sol: Kişi bilgileri --}}
                      <td style="vertical-align:top;padding-right:20px;">

                        <p style="margin:0 0 2px;font-size:16px;font-weight:700;color:#0F172A;">{{ $imzaAd }}</p>
                        <p style="margin:0 0 16px;font-size:11px;color:#CC2200;font-weight:700;text-transform:uppercase;letter-spacing:.07em;">{{ $imzaGorev }}</p>

                        <table cellpadding="0" cellspacing="4">
                          <tr>
                            <td style="padding-bottom:7px;">
                              <table cellpadding="0" cellspacing="0">
                                <tr>
                                  <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;width:52px;padding-top:1px;">TEL</td>
                                  <td style="font-size:13px;color:#374151;">{{ $imzaTel }}</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td style="padding-bottom:7px;">
                              <table cellpadding="0" cellspacing="0">
                                <tr>
                                  <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;width:52px;padding-top:1px;">E-POSTA</td>
                                  <td style="font-size:13px;color:#374151;">
                                    <a href="mailto:{{ $imzaEmail }}" style="color:#CC2200;text-decoration:none;">{{ $imzaEmail }}</a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <table cellpadding="0" cellspacing="0">
                                <tr>
                                  <td style="font-size:10px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;width:52px;padding-top:1px;">WEB</td>
                                  <td style="font-size:13px;">
                                    <a href="https://sucek.com.tr" style="color:#CC2200;text-decoration:none;">sucek.com.tr</a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>

                      {{-- Sağ: Şirket --}}
                      <td style="vertical-align:top;border-left:1px solid #E2E8F0;padding-left:20px;min-width:150px;">
                        <p style="margin:0 0 3px;font-size:20px;font-weight:800;color:#1A0A0A;letter-spacing:4px;">SUÇEK</p>
                        <p style="margin:0 0 12px;font-size:9px;color:#CC2200;letter-spacing:.14em;text-transform:uppercase;font-weight:700;">Mimarlık &amp; İnşaat</p>
                        @if($imzaAdres)
                        <p style="margin:0 0 8px;font-size:11px;color:#64748B;line-height:1.65;">{{ $imzaAdres }}</p>
                        @endif
                        <p style="margin:0;font-size:10px;color:#94A3B8;">T&uuml;rkiye</p>
                      </td>

                    </tr>
                  </table>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- ── Alt Bar ── --}}
        <tr>
          <td style="background:#1A0A0A;padding:16px 40px;text-align:center;">
            <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.35);">
              Bu e-posta <strong style="color:rgba(255,255,255,0.55);">SUÇEK</strong> taraf&#305;ndan g&ouml;nderilmi&#351;tir.
              &nbsp;&middot;&nbsp;
              <a href="{{ url('/') }}" style="color:#CC2200;text-decoration:none;">sucek.com.tr</a>
            </p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>

</body>
</html>
