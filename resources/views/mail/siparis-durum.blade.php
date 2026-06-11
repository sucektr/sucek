@extends('mail.layout')

@section('konu', $durumBaslik)
@section('header_alt', 'Sipariş Güncelleme')

@section('content')
<p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#0F172A;">{{ $durumBaslik }}</p>
<p style="margin:0 0 24px;font-size:14px;color:#64748B;">Merhaba {{ $siparis->ad_soyad }}, siparişinizde bir güncelleme var.</p>

<table width="100%" cellpadding="0" cellspacing="0" style="background:#FEF2F2;border-radius:8px;margin-bottom:24px;">
  <tr>
    <td style="padding:16px 20px;">
      <p style="margin:0;font-size:12px;color:#94A3B8;text-transform:uppercase;letter-spacing:1px;">Sipariş No</p>
      <p style="margin:4px 0 0;font-size:20px;font-weight:700;color:#CC2200;letter-spacing:2px;">#{{ $siparis->referans }}</p>
    </td>
  </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="background:#F0FDF4;border-left:4px solid #22C55E;border-radius:0 8px 8px 0;margin-bottom:24px;">
  <tr><td style="padding:16px 20px;">
    <p style="margin:0;font-size:14px;color:#166534;">{{ $durumMesaj }}</p>
    @if($siparis->admin_notu)
    <p style="margin:12px 0 0;font-size:13px;color:#374151;"><strong>Not:</strong> {{ $siparis->admin_notu }}</p>
    @endif
  </td></tr>
</table>

@if($siparis->durum === 'kargolandi')
<p style="margin:0 0 24px;font-size:14px;color:#64748B;">Kargo takip numaranızı kısa süre içinde ayrıca bildireceğiz.</p>
@endif

<p style="margin:0;font-size:14px;color:#64748B;">Sorularınız için <a href="mailto:info@sucek.com.tr" style="color:#CC2200;">info@sucek.com.tr</a> adresine yazabilirsiniz.</p>
@endsection
