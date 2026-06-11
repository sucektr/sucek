@extends('mail.layout')

@section('konu', 'Siparişiniz Alındı')
@section('header_alt', 'Sipariş Onayı')

@section('content')
<p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#0F172A;">Siparişiniz Alındı!</p>
<p style="margin:0 0 24px;font-size:14px;color:#64748B;">Merhaba {{ $siparis->ad_soyad }}, siparişinizi aldık.</p>

{{-- Sipariş no --}}
<table width="100%" cellpadding="0" cellspacing="0" style="background:#FEF2F2;border-radius:8px;margin-bottom:24px;">
  <tr>
    <td style="padding:16px 20px;">
      <p style="margin:0;font-size:12px;color:#94A3B8;text-transform:uppercase;letter-spacing:1px;">Sipariş No</p>
      <p style="margin:4px 0 0;font-size:20px;font-weight:700;color:#CC2200;letter-spacing:2px;">#{{ $siparis->referans }}</p>
    </td>
    <td style="padding:16px 20px;text-align:right;">
      <p style="margin:0;font-size:12px;color:#94A3B8;text-transform:uppercase;letter-spacing:1px;">Ödeme Yöntemi</p>
      <p style="margin:4px 0 0;font-size:14px;font-weight:600;color:#0F172A;">
        {{ $siparis->odeme_yontemi === 'havale' ? 'Havale / EFT' : 'Kredi Kartı' }}
      </p>
    </td>
  </tr>
</table>

{{-- Ürün listesi --}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
  <tr>
    <td colspan="3" style="padding:0 0 8px;font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:1px;border-bottom:2px solid #E2E8F0;">Ürünler</td>
  </tr>
  @foreach($siparis->kalemler as $kalem)
  <tr>
    <td style="padding:12px 0;font-size:14px;color:#0F172A;border-bottom:1px solid #F1F5F9;">
      {{ $kalem->urun_adi }}
      @if($kalem->varyant_bilgisi)
        <br><span style="font-size:12px;color:#94A3B8;">{{ $kalem->varyant_bilgisi }}</span>
      @endif
    </td>
    <td style="padding:12px 8px;font-size:13px;color:#64748B;text-align:center;border-bottom:1px solid #F1F5F9;">x{{ $kalem->adet }}</td>
    <td style="padding:12px 0;font-size:14px;font-weight:600;color:#0F172A;text-align:right;border-bottom:1px solid #F1F5F9;">
      {{ number_format($kalem->toplam, 2, ',', '.') }} ₺
    </td>
  </tr>
  @endforeach

  @if($siparis->kargo_ucreti > 0)
  <tr>
    <td colspan="2" style="padding:10px 0;font-size:13px;color:#64748B;">Kargo</td>
    <td style="padding:10px 0;font-size:13px;color:#64748B;text-align:right;">{{ number_format($siparis->kargo_ucreti, 2, ',', '.') }} ₺</td>
  </tr>
  @endif

  <tr>
    <td colspan="2" style="padding:12px 0 0;font-size:15px;font-weight:700;color:#0F172A;border-top:2px solid #0F172A;">Toplam</td>
    <td style="padding:12px 0 0;font-size:17px;font-weight:700;color:#CC2200;text-align:right;border-top:2px solid #0F172A;">
      {{ number_format($siparis->toplam, 2, ',', '.') }} ₺
    </td>
  </tr>
</table>

@if($siparis->odeme_yontemi === 'havale')
{{-- Havale bilgisi --}}
@php $bankalar = json_decode(icerik('odeme', 'bankalar', '[]'), true) ?? []; @endphp
@if(!empty($bankalar))
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFC;border-radius:8px;margin-bottom:24px;border:1px solid #E2E8F0;">
  <tr><td style="padding:16px 20px;">
    <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#0F172A;">Havale / EFT Bilgileri</p>
    @foreach($bankalar as $banka)
    <p style="margin:0 0 4px;font-size:13px;color:#374151;"><strong>{{ $banka['banka_adi'] ?? $banka['banka'] }}</strong></p>
    <p style="margin:0 0 4px;font-size:13px;color:#374151;">{{ $banka['alici_adi'] ?? $banka['alici'] }}</p>
    <p style="margin:0 0 12px;font-size:13px;color:#0F172A;font-family:monospace;">{{ $banka['iban'] }}</p>
    @endforeach
    <p style="margin:0;font-size:12px;color:#94A3B8;">Açıklama kısmına sipariş numaranızı yazmayı unutmayın: <strong>#{{ $siparis->referans }}</strong></p>
  </td></tr>
</table>
@endif
@endif

<p style="margin:0;font-size:14px;color:#64748B;">Siparişinizle ilgili güncellemeleri bu e-posta adresine ileteceğiz. Sorularınız için <a href="mailto:info@sucek.com.tr" style="color:#CC2200;">info@sucek.com.tr</a> adresine yazabilirsiniz.</p>
@endsection
