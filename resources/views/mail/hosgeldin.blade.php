@extends('mail.layout')

@section('konu', "SUÇEK'e Hoş Geldiniz!")
@section('header_alt', 'Üyelik')

@section('content')
<p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#0F172A;">Hoş Geldiniz!</p>
<p style="margin:0 0 24px;font-size:14px;color:#64748B;">Merhaba {{ $user->name }}, SUÇEK ailesine katıldığınız için teşekkür ederiz.</p>

<table width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFC;border-radius:8px;margin-bottom:24px;border:1px solid #E2E8F0;">
  <tr><td style="padding:24px;">
    <p style="margin:0 0 12px;font-size:14px;color:#374151;">Üyeliğinizle birlikte şunlara erişebilirsiniz:</p>
    <table cellpadding="0" cellspacing="0">
      <tr><td style="padding:4px 0;font-size:14px;color:#374151;">✓ &nbsp;Mağazadan alışveriş yapabilirsiniz</td></tr>
      <tr><td style="padding:4px 0;font-size:14px;color:#374151;">✓ &nbsp;Sipariş geçmişinizi takip edebilirsiniz</td></tr>
      <tr><td style="padding:4px 0;font-size:14px;color:#374151;">✓ &nbsp;Teklif gönderebilirsiniz</td></tr>
    </table>
  </td></tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
  <tr><td align="center">
    <a href="{{ url('/magaza') }}" style="display:inline-block;background:#CC2200;color:#FFFFFF;font-size:14px;font-weight:700;padding:14px 32px;border-radius:8px;text-decoration:none;letter-spacing:0.5px;">Mağazayı Keşfet</a>
  </td></tr>
</table>

<p style="margin:0;font-size:13px;color:#94A3B8;text-align:center;">Hesap e-postanız: <strong>{{ $user->email }}</strong></p>
@endsection
