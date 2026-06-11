<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('konu')</title>
</head>
<body style="margin:0;padding:0;background:#F1F5F9;font-family:Arial,Helvetica,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;padding:32px 0;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

        {{-- Header --}}
        <tr>
          <td style="background:#1A0A0A;padding:28px 40px;text-align:center;">
            <p style="margin:0;font-size:22px;font-weight:700;color:#FFFFFF;letter-spacing:4px;">SUÇEK</p>
            <p style="margin:6px 0 0;font-size:11px;color:#CC2200;letter-spacing:2px;text-transform:uppercase;">@yield('header_alt', 'sucek.com.tr')</p>
          </td>
        </tr>

        {{-- Body --}}
        <tr>
          <td style="padding:40px;">
            @yield('content')
          </td>
        </tr>

        {{-- Footer --}}
        <tr>
          <td style="background:#F8FAFC;border-top:1px solid #E2E8F0;padding:24px 40px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#94A3B8;">Bu e-posta otomatik olarak gönderilmiştir. Lütfen yanıtlamayınız.</p>
            <p style="margin:8px 0 0;font-size:12px;color:#94A3B8;">
              <a href="{{ url('/') }}" style="color:#CC2200;text-decoration:none;">sucek.com.tr</a>
            </p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
