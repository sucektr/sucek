@extends('mail.layout')

@section('konu', $konu)
@section('header_alt', 'Bülten')

@section('content')
<p style="margin:0 0 24px;font-size:22px;font-weight:700;color:#0F172A;">{{ $konu }}</p>

<div style="font-size:15px;color:#374151;line-height:1.8;">
  {!! nl2br(e($icerik)) !!}
</div>

<hr style="border:none;border-top:1px solid #E2E8F0;margin:32px 0;">
<p style="margin:0;font-size:13px;color:#94A3B8;">SUÇEK bülteni. <a href="{{ url('/') }}" style="color:#CC2200;text-decoration:none;">sucek.com.tr</a></p>
@endsection
