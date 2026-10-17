<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $belgeler->count() === 1 ? $belgeler->first()->dosya_adi : $belgeler->count() . ' Belge' }}</title>
</head>
<body style="margin:0;padding:0;background:#F1F5F9;font-family:Arial,Helvetica,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;padding:32px 0;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

        <tr>
          <td style="background:#141414;padding:24px 40px;">
            <p style="margin:0;font-size:24px;font-weight:700;color:#FFFFFF;letter-spacing:4px;">SUÇEK</p>
            <p style="margin:5px 0 0;font-size:9px;color:rgba(255,255,255,0.55);letter-spacing:1.5px;text-transform:uppercase;">Belge Gönderimi</p>
          </td>
        </tr>

        <tr>
          <td style="padding:40px 40px 32px;">
            <p style="margin:0 0 16px;font-size:15px;color:#1E293B;line-height:1.8;">
              Sayın {{ $musteri->ad_soyad }},
            </p>
            @if($belgeler->count() === 1)
            <p style="margin:0;font-size:15px;color:#1E293B;line-height:1.8;">
              "<strong>{{ $belgeler->first()->dosya_adi }}</strong>" adlı belge bu e-postaya ek olarak iliştirilmiştir. Herhangi bir sorunuz olursa bizimle iletişime geçebilirsiniz.
            </p>
            @else
            <p style="margin:0 0 14px;font-size:15px;color:#1E293B;line-height:1.8;">
              Aşağıdaki <strong>{{ $belgeler->count() }}</strong> belge bu e-postaya ek olarak iliştirilmiştir. Herhangi bir sorunuz olursa bizimle iletişime geçebilirsiniz.
            </p>
            <ul style="margin:0;padding:0 0 0 18px;font-size:13px;color:#374151;line-height:1.9;">
              @foreach($belgeler as $belge)
                <li>{{ $belge->dosya_adi }}</li>
              @endforeach
            </ul>
            @endif
          </td>
        </tr>

        <tr>
          <td style="background:#141414;padding:16px 40px;text-align:center;">
            <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.35);">
              Bu e-posta <strong style="color:rgba(255,255,255,0.55);">SUÇEK</strong> taraf&#305;ndan g&ouml;nderilmi&#351;tir.
              &nbsp;&middot;&nbsp;
              <a href="mailto:info@sucek.com.tr" style="color:#CC2200;text-decoration:none;">info@sucek.com.tr</a>
            </p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>

</body>
</html>
