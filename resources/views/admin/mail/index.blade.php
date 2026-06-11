@extends('admin.layouts.app')
@section('title', 'E-Posta Yönetimi')

@section('content')
<div class="max-w-[1100px] mx-auto px-4 py-8 space-y-6">

  {{-- Başlık --}}
  <div>
    <h1 class="text-[20px] font-semibold text-[#0F172A]">E-Posta Yönetimi</h1>
    <p class="text-[13px] text-[#64748B] mt-1">SMTP ayarları, toplu mail ve gönderim geçmişi</p>
  </div>

  {{-- Flash --}}
  @if(session('basari'))
    <div class="bg-[#F0FDF4] border border-[#86EFAC] rounded-[10px] px-4 py-3 text-[13px] text-[#166534]">{{ session('basari') }}</div>
  @endif
  @if(session('hata'))
    <div class="bg-[#FEF2F2] border border-[#FECACA] rounded-[10px] px-4 py-3 text-[13px] text-[#991B1B]">{{ session('hata') }}</div>
  @endif

  {{-- İstatistikler --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach([
      ['Bugün', $istatistik['bugun'], '#3B82F6'],
      ['Bu Ay', $istatistik['bu_ay'], '#8B5CF6'],
      ['Başarılı', $istatistik['basarili'], '#22C55E'],
      ['Hatalı', $istatistik['hatali'], '#EF4444'],
    ] as [$etiket, $sayi, $renk])
    <div class="bg-white border border-[#E2E8F0] rounded-[12px] p-4">
      <p class="text-[12px] text-[#94A3B8] mb-1">{{ $etiket }}</p>
      <p class="text-[26px] font-bold" style="color:{{ $renk }}">{{ $sayi }}</p>
    </div>
    @endforeach
  </div>

  {{-- SMTP Ayarları --}}
  <div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden">
    <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center justify-between">
      <div class="flex items-center gap-2">
        <i class="ti ti-settings text-[#CC2200]"></i>
        <span class="text-[14px] font-semibold text-[#0F172A]">SMTP Ayarları</span>
      </div>
      @if($mailAyarlar['host'])
        <span class="text-[11px] bg-[#F0FDF4] text-[#166534] border border-[#86EFAC] px-2 py-0.5 rounded-full">Yapılandırıldı</span>
      @else
        <span class="text-[11px] bg-[#FEF2F2] text-[#991B1B] border border-[#FECACA] px-2 py-0.5 rounded-full">Yapılandırılmadı</span>
      @endif
    </div>
    <div class="p-6" x-data="{ac: {{ $mailAyarlar['host'] ? 'false' : 'true' }}}">
      <button type="button" @click="ac=!ac"
              class="text-[12px] text-[#64748B] hover:text-[#0F172A] flex items-center gap-1 mb-4">
        <i class="ti text-xs" :class="ac ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
        <span x-text="ac ? 'Gizle' : 'Düzenle'">Düzenle</span>
      </button>
      <form x-show="ac" action="{{ route('admin.mail.ayarlar') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">SMTP Host</label>
            <input type="text" name="host" value="{{ $mailAyarlar['host'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] font-mono text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="mail.sucek.com.tr">
          </div>
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Port</label>
            <input type="number" name="port" value="{{ $mailAyarlar['port'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="587">
          </div>
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Şifreleme</label>
            <select name="encryption" class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]">
              <option value="tls" {{ $mailAyarlar['encryption'] === 'tls' ? 'selected' : '' }}>TLS (587)</option>
              <option value="ssl" {{ $mailAyarlar['encryption'] === 'ssl' ? 'selected' : '' }}>SSL (465)</option>
              <option value="none" {{ $mailAyarlar['encryption'] === 'none' ? 'selected' : '' }}>Yok</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Kullanıcı Adı (E-posta)</label>
            <input type="text" name="username" value="{{ $mailAyarlar['username'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="info@sucek.com.tr">
          </div>
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Şifre</label>
            <input type="password" name="password" value="{{ $mailAyarlar['password'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="••••••••">
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Gönderen Adres</label>
            <input type="email" name="from" value="{{ $mailAyarlar['from'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="info@sucek.com.tr">
          </div>
          <div>
            <label class="block text-[12px] font-medium text-[#374151] mb-1">Gönderen Adı</label>
            <input type="text" name="from_name" value="{{ $mailAyarlar['from_name'] }}" required
                   class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                   placeholder="SUÇEK">
          </div>
        </div>
        <button type="submit" class="bg-[#CC2200] text-white text-[13px] font-semibold px-5 py-2 rounded-[8px] hover:bg-[#A31B00] transition-colors">
          Ayarları Kaydet
        </button>
      </form>
    </div>
  </div>

  {{-- Şablon Önizlemeleri --}}
  <div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden">
    <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
      <i class="ti ti-eye text-[#CC2200]"></i>
      <span class="text-[14px] font-semibold text-[#0F172A]">E-Posta Şablonları</span>
      <span class="text-[11px] text-[#94A3B8] ml-1">— Örnek veriyle görünüm</span>
    </div>
    <div class="p-6">
      <div class="flex flex-wrap gap-2">
        @foreach([
          ['hosgeldin',             'Hoş Geldin',         'ti-user-check',  '#8B5CF6'],
          ['siparis-onay',          'Sipariş Onayı',      'ti-shopping-bag','#3B82F6'],
          ['siparis-durum-odeme',   'Ödeme Alındı',       'ti-circle-check','#22C55E'],
          ['siparis-durum-kargo',   'Kargoya Verildi',    'ti-truck',       '#F59E0B'],
          ['siparis-durum-iptal',   'İptal Edildi',       'ti-x',           '#EF4444'],
          ['haber',                 'Haber Duyurusu',     'ti-news',        '#0EA5E9'],
          ['bulten',                'Bülten',             'ti-mail',        '#CC2200'],
        ] as [$tip, $etiket, $ikon, $renk])
        <a href="{{ route('admin.mail.onizleme', $tip) }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-[#E2E8F0] rounded-[8px] text-[12px] font-medium text-[#374151] hover:border-current hover:text-current transition-colors cursor-pointer"
           style="--c:{{ $renk }};" onmouseover="this.style.color='{{ $renk }}';this.style.borderColor='{{ $renk }}'" onmouseout="this.style.color='';this.style.borderColor=''">
          <i class="ti {{ $ikon }} text-sm"></i>
          {{ $etiket }}
          <i class="ti ti-external-link text-[10px] opacity-50"></i>
        </a>
        @endforeach
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Toplu Mail --}}
    <div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
        <i class="ti ti-mail-forward text-[#CC2200]"></i>
        <span class="text-[14px] font-semibold text-[#0F172A]">Toplu E-Posta</span>
      </div>
      <form action="{{ route('admin.mail.toplu') }}" method="POST" class="p-6 space-y-4">
        @csrf
        <div>
          <label class="block text-[12px] font-medium text-[#374151] mb-1">Alıcı Grubu</label>
          <select name="grup" class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]">
            <option value="sucek">Suçek Ailesi</option>
            <option value="tum_premium">Tüm Premium Üyeler</option>
            <option value="tum_uyeler">Tüm Üyeler</option>
          </select>
        </div>
        <div>
          <label class="block text-[12px] font-medium text-[#374151] mb-1">Konu</label>
          <input type="text" name="konu" required maxlength="200"
                 class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                 placeholder="E-posta konusu">
        </div>
        <div>
          <label class="block text-[12px] font-medium text-[#374151] mb-1">
            İçerik <span class="text-[#94A3B8] font-normal">(max 5000 kr)</span>
          </label>
          <textarea name="mesaj" required maxlength="5000" rows="5"
                    class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200] resize-none"
                    placeholder="E-posta içeriği..."></textarea>
        </div>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[13px] font-semibold py-2.5 rounded-[8px] hover:bg-[#A31B00] transition-colors">
          Gönder
        </button>
      </form>
    </div>

    {{-- Tek Mail --}}
    <div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden">
      <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
        <i class="ti ti-send text-[#CC2200]"></i>
        <span class="text-[14px] font-semibold text-[#0F172A]">Tek Adrese Gönder</span>
      </div>
      <form action="{{ route('admin.mail.tek') }}" method="POST" class="p-6 space-y-4">
        @csrf
        <div>
          <label class="block text-[12px] font-medium text-[#374151] mb-1">E-posta Adresi</label>
          <input type="email" name="email" required
                 class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                 placeholder="ornek@domain.com">
        </div>
        <div>
          <label class="block text-[12px] font-medium text-[#374151] mb-1">Konu</label>
          <input type="text" name="konu" required maxlength="200"
                 class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200]"
                 placeholder="E-posta konusu">
        </div>
        <div>
          <label class="block text-[12px] font-medium text-[#374151] mb-1">İçerik</label>
          <textarea name="mesaj" required maxlength="5000" rows="5"
                    class="w-full border border-[#E2E8F0] rounded-[8px] px-3 py-2 text-[13px] text-[#0F172A] focus:outline-none focus:border-[#CC2200] resize-none"
                    placeholder="E-posta içeriği..."></textarea>
        </div>
        <button type="submit"
                style="background:#1E293B;"
                onmouseover="this.style.background='#0F172A'"
                onmouseout="this.style.background='#1E293B'"
                class="w-full text-white text-[13px] font-semibold py-2.5 rounded-[8px] transition-colors">
          Gönder
        </button>
      </form>
    </div>

  </div>

  {{-- Son Gönderimler --}}
  <div class="bg-white border border-[#E2E8F0] rounded-[12px] overflow-hidden">
    <div class="px-6 py-4 border-b border-[#E2E8F0] flex items-center gap-2">
      <i class="ti ti-history text-[#CC2200]"></i>
      <span class="text-[14px] font-semibold text-[#0F172A]">Son Gönderimler</span>
    </div>
    @if($loglar->isEmpty())
      <div class="px-6 py-10 text-center text-[13px] text-[#94A3B8]">Henüz gönderim yapılmadı.</div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
          <thead>
            <tr class="bg-[#F8FAFC] border-b border-[#E2E8F0]">
              <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wide">Alıcı</th>
              <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wide">Konu</th>
              <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wide">Şablon</th>
              <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wide">Durum</th>
              <th class="px-4 py-3 text-left text-[11px] font-semibold text-[#94A3B8] uppercase tracking-wide">Tarih</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#F1F5F9]">
            @foreach($loglar as $log)
            <tr class="hover:bg-[#F8FAFC] transition-colors">
              <td class="px-4 py-3 text-[#0F172A] font-mono text-[12px]">{{ $log->alici }}</td>
              <td class="px-4 py-3 text-[#374151] max-w-[200px] truncate">{{ $log->konu }}</td>
              <td class="px-4 py-3 text-[#64748B]">{{ $log->sablon ?? '—' }}</td>
              <td class="px-4 py-3">
                @if($log->basarili)
                  <span class="inline-flex items-center gap-1 text-[11px] font-medium text-[#166534] bg-[#F0FDF4] px-2 py-0.5 rounded-full">
                    <i class="ti ti-check text-xs"></i> Başarılı
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-[11px] font-medium text-[#991B1B] bg-[#FEF2F2] px-2 py-0.5 rounded-full" title="{{ $log->hata }}">
                    <i class="ti ti-x text-xs"></i> Hata
                  </span>
                  @if($log->hata)
                    <p class="text-[11px] text-[#EF4444] mt-1">{{ $log->hata }}</p>
                  @endif
                @endif
              </td>
              <td class="px-4 py-3 text-[#94A3B8] text-[12px] whitespace-nowrap">{{ $log->created_at->format('d.m.Y H:i') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</div>
@endsection
