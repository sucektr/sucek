@extends('admin.layouts.app')
@section('title', 'SMS Yönetimi')
@section('page-title', 'SMS Yönetimi')

@section('breadcrumb')
<span class="text-[12px] text-[#64748B]">İletimerkezi</span>
@endsection

@section('content')

{{-- Bildirimler --}}
@if(session('basari'))
<div class="mb-4 px-4 py-3 bg-[#E6F4EC] border border-[#9DD4B5] rounded-[10px] text-[13px] text-[#1A5C3A] flex items-center gap-2">
  <i class="ti ti-circle-check text-base"></i> {{ session('basari') }}
</div>
@endif
@if(session('hata'))
<div class="mb-4 px-4 py-3 bg-[#FEE2E2] border border-red-300 rounded-[10px] text-[13px] text-[#DC2626] flex items-center gap-2">
  <i class="ti ti-alert-circle text-base"></i> {{ session('hata') }}
</div>
@endif

{{-- İstatistikler --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#0F172A]">{{ $istatistik['bugun'] }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Bugün</p>
  </div>
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#7C3AED]">{{ $istatistik['bu_ay'] }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Bu Ay</p>
  </div>
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#065F46]">{{ $istatistik['basarili'] }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Başarılı</p>
  </div>
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 text-center">
    <p class="text-[28px] font-bold text-[#DC2626]">{{ $istatistik['hatali'] }}</p>
    <p class="text-[11px] text-[#94A3B8] mt-1">Hatalı</p>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  {{-- Sol kolon --}}
  <div class="space-y-6">

    {{-- Toplu SMS Gönder --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
        <i class="ti ti-send text-[#CC2200] text-base"></i>
        <h2 class="text-[13px] font-semibold text-[#0F172A]">Toplu SMS Gönder</h2>
      </div>
      <div class="p-6">
        <form action="{{ route('admin.sms.toplu') }}" method="POST" x-data="{mesaj:'', max:500}"
              onsubmit="return confirm('SMS gönderilecek. Emin misiniz?')">
          @csrf
          <div class="mb-4">
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Alıcı Grubu</label>
            <select name="grup" required
                    class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]">
              <option value="sucek">Suçek Aile Üyeleri</option>
              <option value="tum_premium">Tüm Premium Üyeler</option>
              <option value="sms_izinli">SMS İzni Olan Herkes</option>
              <option value="tum_uyeler">Tüm Üyeler (SMS izni olanlar)</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Mesaj</label>
            <textarea name="mesaj" required maxlength="500" rows="4" x-model="mesaj"
                      class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] resize-none focus:outline-none focus:border-[#CC2200]"
                      placeholder="SMS metnini yazın..."></textarea>
            <p class="text-[10px] text-[#94A3B8] mt-1 text-right" x-text="mesaj.length + '/500 karakter'"></p>
          </div>
          <button type="submit"
                  class="w-full py-2.5 text-white text-[12px] font-semibold rounded-[8px] transition-colors"
                  style="background:#CC2200;" onmouseover="this.style.background='#a31b00'" onmouseout="this.style.background='#CC2200'">
            <i class="ti ti-send text-sm mr-1"></i> SMS Gönder
          </button>
        </form>
      </div>
    </div>

    {{-- Tek SMS Gönder --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
        <i class="ti ti-device-mobile text-[#CC2200] text-base"></i>
        <h2 class="text-[13px] font-semibold text-[#0F172A]">Tek Numara SMS</h2>
      </div>
      <div class="p-6">
        <form action="{{ route('admin.sms.tek') }}" method="POST" x-data="{mesaj:''}">
          @csrf
          <div class="mb-4">
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Telefon Numarası</label>
            <input type="text" name="telefon" required placeholder="05XX XXX XX XX"
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]">
          </div>
          <div class="mb-4">
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Mesaj</label>
            <textarea name="mesaj" required maxlength="500" rows="3" x-model="mesaj"
                      class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] resize-none focus:outline-none focus:border-[#CC2200]"
                      placeholder="SMS metnini yazın..."></textarea>
            <p class="text-[10px] text-[#94A3B8] mt-1 text-right" x-text="mesaj.length + '/500 karakter'"></p>
          </div>
          <button type="submit"
                  class="w-full py-2.5 bg-[#1E293B] text-white text-[12px] font-semibold rounded-[8px] transition-colors hover:bg-[#0F172A]">
            <i class="ti ti-send text-sm mr-1"></i> Gönder
          </button>
        </form>
      </div>
    </div>

  </div>

  {{-- Sağ kolon --}}
  <div class="space-y-6">

    {{-- SMS Şablonları --}}
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
        <i class="ti ti-template text-[#CC2200] text-base"></i>
        <h2 class="text-[13px] font-semibold text-[#0F172A]">SMS Şablonları</h2>
      </div>
      <div class="divide-y divide-[rgba(0,0,0,0.05)]" x-data="{acik: null}">
        @foreach($sablonlar as $sablon)
        <div class="px-5 py-3">
          <div class="flex items-center justify-between cursor-pointer"
               @click="acik = acik === {{ $sablon->id }} ? null : {{ $sablon->id }}">
            <div class="flex items-center gap-2">
              <span class="text-[12px] font-semibold text-[#0F172A]">{{ $sablon->baslik }}</span>
              @if($sablon->aktif)
              <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-[#E6F4EC] text-[#065F46]">Aktif</span>
              @else
              <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-[#F3F4F6] text-[#6B7280]">Pasif</span>
              @endif
            </div>
            <i class="ti text-[#94A3B8] text-sm" :class="acik === {{ $sablon->id }} ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
          </div>
          <p class="text-[10px] text-[#64748B] mt-1 font-mono bg-[#F8FAFC] rounded px-2 py-1.5 cursor-pointer"
             @click="acik = acik === {{ $sablon->id }} ? null : {{ $sablon->id }}">
            {{ $sablon->sablon }}
          </p>

          <div x-show="acik === {{ $sablon->id }}" x-cloak class="mt-3">
            <form action="{{ route('admin.sms.sablon', $sablon) }}" method="POST">
              @csrf @method('PATCH')
              <textarea name="sablon" required rows="3" maxlength="500"
                        class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[12px] font-mono text-[#0F172A] resize-none focus:outline-none focus:border-[#CC2200]">{{ $sablon->sablon }}</textarea>
              <p class="text-[10px] text-[#94A3B8] mb-2">
                Değişkenler:
                @if(in_array($sablon->anahtar, ['siparis_olusturuldu','siparis_odeme_alindi','siparis_hazirlaniyor','siparis_kargolandi','siparis_teslim_edildi','siparis_iptal']))
                  <code class="bg-[#F1F5F9] px-1 rounded">{ad_soyad}</code>
                  <code class="bg-[#F1F5F9] px-1 rounded">{referans}</code>
                  <code class="bg-[#F1F5F9] px-1 rounded">{toplam}</code>
                @elseif($sablon->anahtar === 'dogum_gunu')
                  <code class="bg-[#F1F5F9] px-1 rounded">{ad_soyad}</code>
                @elseif($sablon->anahtar === 'yeni_haber')
                  <code class="bg-[#F1F5F9] px-1 rounded">{baslik}</code>
                  <code class="bg-[#F1F5F9] px-1 rounded">{url}</code>
                @endif
              </p>
              <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="hidden" name="aktif" value="0">
                  <input type="checkbox" name="aktif" value="1" {{ $sablon->aktif ? 'checked' : '' }}
                         class="w-4 h-4 rounded accent-[#CC2200]">
                  <span class="text-[11px] text-[#374151]">Aktif</span>
                </label>
                <button type="submit"
                        class="px-3 py-1.5 text-white text-[11px] font-semibold rounded-[6px] transition-colors"
                        style="background:#CC2200;" onmouseover="this.style.background='#a31b00'" onmouseout="this.style.background='#CC2200'">
                  Kaydet
                </button>
              </div>
            </form>
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </div>
</div>

{{-- Son Gönderimler --}}
<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden mt-6">
  <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
    <i class="ti ti-history text-[#CC2200] text-base"></i>
    <h2 class="text-[13px] font-semibold text-[#0F172A]">Son Gönderimler</h2>
  </div>

  @if($loglar->isEmpty())
  <div class="px-6 py-10 text-center">
    <p class="text-[13px] text-[#94A3B8]">Henüz SMS gönderilmedi.</p>
  </div>
  @else
  <div class="overflow-x-auto">
    <table class="w-full text-[12px]">
      <thead class="bg-[#F8FAFC]">
        <tr>
          <th class="px-4 py-3 text-left text-[10px] font-semibold text-[#94A3B8] uppercase tracking-wider">Tarih</th>
          <th class="px-4 py-3 text-left text-[10px] font-semibold text-[#94A3B8] uppercase tracking-wider">Alıcı</th>
          <th class="px-4 py-3 text-left text-[10px] font-semibold text-[#94A3B8] uppercase tracking-wider">Şablon</th>
          <th class="px-4 py-3 text-left text-[10px] font-semibold text-[#94A3B8] uppercase tracking-wider">Mesaj</th>
          <th class="px-4 py-3 text-left text-[10px] font-semibold text-[#94A3B8] uppercase tracking-wider">Durum</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
        @foreach($loglar as $log)
        <tr class="hover:bg-[#FAFAFA]">
          <td class="px-4 py-3 text-[#64748B] whitespace-nowrap">{{ $log->created_at->format('d.m.Y H:i') }}</td>
          <td class="px-4 py-3 text-[#0F172A] font-mono">{{ $log->alici }}</td>
          <td class="px-4 py-3 text-[#64748B]">{{ $log->sablon_anahtar ?? '—' }}</td>
          <td class="px-4 py-3 text-[#374151] max-w-xs truncate">{{ $log->mesaj }}</td>
          <td class="px-4 py-3">
            @if($log->basarili)
            <span class="inline-flex items-center gap-1 bg-[#E6F4EC] text-[#065F46] text-[10px] font-bold px-2 py-0.5 rounded-full">
              <i class="ti ti-check text-[9px]"></i> OK
            </span>
            @else
            <span class="inline-flex items-center gap-1 bg-[#FEE2E2] text-[#DC2626] text-[10px] font-bold px-2 py-0.5 rounded-full" title="{{ $log->hata }}">
              <i class="ti ti-x text-[9px]"></i> Hata
            </span>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>

@endsection
