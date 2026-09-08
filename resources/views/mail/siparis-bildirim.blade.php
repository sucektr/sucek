@extends('mail.layout')

@section('konu', $baslik)
@section('header_alt', 'Sipariş Bildirimi')

@section('content')
<p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#0F172A;">{{ $baslik }}</p>
<p style="margin:0 0 24px;font-size:14px;color:#64748B;">{{ $mesaj }}</p>

<table width="100%" cellpadding="0" cellspacing="0" style="background:#FEF2F2;border-radius:8px;margin-bottom:24px;">
  <tr>
    <td style="padding:16px 20px;">
      <p style="margin:0;font-size:12px;color:#94A3B8;text-transform:uppercase;letter-spacing:1px;">Sipariş No</p>
      <p style="margin:4px 0 0;font-size:20px;font-weight:700;color:#CC2200;letter-spacing:2px;">#{{ $siparis->referans }}</p>
    </td>
    <td style="padding:16px 20px;text-align:right;">
      <p style="margin:0;font-size:12px;color:#94A3B8;text-transform:uppercase;letter-spacing:1px;">Tutar</p>
      <p style="margin:4px 0 0;font-size:17px;font-weight:700;color:#0F172A;">{{ number_format((float) $siparis->toplam, 2, ',', '.') }} ₺</p>
    </td>
  </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
  <tr>
    <td style="padding:6px 0;font-size:13px;color:#64748B;width:35%;">Müşteri</td>
    <td style="padding:6px 0;font-size:13px;color:#0F172A;font-weight:600;">{{ $siparis->ad_soyad }}</td>
  </tr>
  <tr>
    <td style="padding:6px 0;font-size:13px;color:#64748B;">E-posta</td>
    <td style="padding:6px 0;font-size:13px;color:#0F172A;">{{ $siparis->email }}</td>
  </tr>
  @if($siparis->telefon)
  <tr>
    <td style="padding:6px 0;font-size:13px;color:#64748B;">Telefon</td>
    <td style="padding:6px 0;font-size:13px;color:#0F172A;">{{ $siparis->telefon }}</td>
  </tr>
  @endif
  <tr>
    <td style="padding:6px 0;font-size:13px;color:#64748B;">Ödeme Yöntemi</td>
    <td style="padding:6px 0;font-size:13px;color:#0F172A;">{{ $siparis->odeme_yontemi === 'havale' ? 'Havale / EFT' : 'Kredi Kartı' }}</td>
  </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td style="text-align:center;padding:8px 0;">
      <a href="{{ route('admin.siparisler.show', $siparis) }}" style="display:inline-block;background:#0F172A;color:#fff;font-size:13px;font-weight:600;padding:12px 28px;border-radius:8px;text-decoration:none;">Siparişi Panelde Görüntüle</a>
    </td>
  </tr>
</table>
@endsection
