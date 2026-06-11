@extends('mail.layout')

@section('konu', 'Şifre Sıfırlama — SUÇEK')
@section('header_alt', 'Şifre Sıfırlama')

@section('content')
<p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#0F172A;">Şifrenizi Sıfırlayın</p>
<p style="margin:0 0 24px;font-size:14px;color:#64748B;">Merhaba {{ $user->name }}, şifre sıfırlama talebinizi aldık.</p>

<table width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFC;border-radius:8px;margin-bottom:24px;border:1px solid #E2E8F0;">
  <tr><td style="padding:24px;">
    <p style="margin:0 0 16px;font-size:14px;color:#374151;">Aşağıdaki butona tıklayarak yeni şifrenizi belirleyebilirsiniz.</p>
    <p style="margin:0;font-size:13px;color:#94A3B8;">Bu bağlantı <strong>60 dakika</strong> geçerlidir. Eğer şifre sıfırlama talebinde bulunmadıysanız bu e-postayı yoksayabilirsiniz.</p>
  </td></tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
  <tr><td align="center">
    <a href="{{ url('/sifre-sifirla/' . $token . '?email=' . urlencode($user->email)) }}"
       style="display:inline-block;background:#0F0F0F;color:#FFFFFF;font-size:14px;font-weight:700;padding:14px 32px;border-radius:8px;text-decoration:none;letter-spacing:0.5px;">
      Şifremi Sıfırla
    </a>
  </td></tr>
</table>

<p style="margin:0 0 8px;font-size:12px;color:#94A3B8;text-align:center;">Buton çalışmıyorsa aşağıdaki bağlantıyı tarayıcınıza yapıştırın:</p>
<p style="margin:0;font-size:11px;color:#94A3B8;text-align:center;word-break:break-all;">
  {{ url('/sifre-sifirla/' . $token . '?email=' . urlencode($user->email)) }}
</p>
@endsection
