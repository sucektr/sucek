@extends('mail.layout')

@section('konu', 'Yeni Haber: ' . $haber->baslik)
@section('header_alt', 'Haber Duyurusu')

@section('content')
<p style="margin:0 0 4px;font-size:12px;font-weight:700;color:#CC2200;text-transform:uppercase;letter-spacing:2px;">Yeni Haber</p>
<p style="margin:0 0 16px;font-size:22px;font-weight:700;color:#0F172A;line-height:1.3;">{{ $haber->baslik }}</p>

@if($haber->ozet)
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.7;">{{ $haber->ozet }}</p>
@elseif($haber->icerik)
<p style="margin:0 0 24px;font-size:15px;color:#374151;line-height:1.7;">{{ Str::limit(strip_tags($haber->icerik), 200) }}</p>
@endif

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
  <tr><td>
    <a href="{{ route('haberler.show', $haber->slug) }}" style="display:inline-block;background:#CC2200;color:#FFFFFF;font-size:14px;font-weight:700;padding:14px 28px;border-radius:8px;text-decoration:none;">Haberi Oku →</a>
  </td></tr>
</table>

<p style="margin:0;font-size:13px;color:#94A3B8;">Bu duyuruyu almak istemiyorsanız hesabınızdan SMS/e-posta tercihlerinizi güncelleyebilirsiniz.</p>
@endsection
