@extends('admin.layouts.cari')

@section('title', 'Cari Hesap')
@section('page-title', 'Cari Hesap')

@section('content')

  @include('admin.cari._sekme', ['aktif' => 'genel'])

  {{-- Özet kartları --}}
  <div class="grid grid-cols-2 gap-3 mb-4">
    <div class="bg-[#141414] rounded-[14px] px-4 py-4 text-white">
      <p class="text-[9.5px] uppercase tracking-wider text-[rgba(255,255,255,0.4)] mb-1">Alacağınız</p>
      <p class="text-[19px] font-bold {{ $toplamBakiye > 0 ? 'text-[#CC2200]' : ($toplamBakiye < 0 ? 'text-[#4ADE80]' : 'text-white') }}">
        {{ number_format($toplamBakiye, 2, ',', '.') }} ₺
      </p>
      <p class="text-[10px] text-[rgba(255,255,255,0.5)] mt-1">Müşterilerden</p>
    </div>
    <div class="bg-[#141414] rounded-[14px] px-4 py-4 text-white">
      <p class="text-[9.5px] uppercase tracking-wider text-[rgba(255,255,255,0.4)] mb-1">Borcunuz</p>
      <p class="text-[19px] font-bold {{ $toplamBorc > 0 ? 'text-[#CC2200]' : ($toplamBorc < 0 ? 'text-[#4ADE80]' : 'text-white') }}">
        {{ number_format($toplamBorc, 2, ',', '.') }} ₺
      </p>
      <p class="text-[10px] text-[rgba(255,255,255,0.5)] mt-1">Tedarikçilere</p>
    </div>
  </div>

  {{-- Hızlı ekle --}}
  <div class="grid grid-cols-2 gap-3 mb-5">
    <button type="button" onclick="document.getElementById('yeni-musteri-modal').classList.remove('hidden')"
            class="flex items-center justify-center gap-1.5 bg-white border border-[#E2E8F0] rounded-[10px] py-3 text-[#0F172A] text-[13px] font-semibold">
      <i class="ti ti-user-plus text-[16px]"></i> Yeni Müşteri
    </button>
    <button type="button" onclick="document.getElementById('yeni-tedarikci-modal').classList.remove('hidden')"
            class="flex items-center justify-center gap-1.5 bg-white border border-[#E2E8F0] rounded-[10px] py-3 text-[#0F172A] text-[13px] font-semibold">
      <i class="ti ti-truck-delivery text-[16px]"></i> Yeni Tedarikçi
    </button>
  </div>

  {{-- Vadesi yaklaşan/geçen uyarı (müşteri + tedarikçi birlikte) --}}
  @if($vadesiYaklasanlar->isNotEmpty())
  <div class="bg-[#FFF7ED] border border-[#FED7AA] rounded-[12px] px-4 py-3 mb-5">
    <p class="text-[12px] font-semibold text-[#9A3412] flex items-center gap-1.5 mb-2">
      <i class="ti ti-alert-triangle"></i> Vadesi Yaklaşan/Geçen Ödemeler
    </p>
    <div class="space-y-1.5">
      @foreach($vadesiYaklasanlar as $h)
        <a href="{{ route('admin.cari.show', $h->musteri) }}" class="flex items-center justify-between text-[12px] text-[#78350F] gap-2">
          <span class="truncate flex items-center gap-1.5">
            <span class="shrink-0 text-[8.5px] font-bold uppercase px-1.5 py-0.5 rounded-full {{ $h->musteri->tip === 'tedarikci' ? 'bg-[#FEF3C7] text-[#92720F]' : 'bg-[#FEE2E2] text-[#9A3412]' }}">
              {{ $h->musteri->tip === 'tedarikci' ? 'Tedarikçi' : 'Müşteri' }}
            </span>
            {{ $h->musteri->ad_soyad }} — {{ $h->aciklama }}
          </span>
          <span class="shrink-0 font-semibold {{ $h->vade_tarihi->isPast() ? 'text-[#CC2200]' : '' }}">{{ $h->vade_tarihi->format('d.m.Y') }}</span>
        </a>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Genel cari durum grafikleri --}}
  <div class="mb-5 space-y-3">
    <p class="text-[12px] font-semibold text-[#64748B] uppercase tracking-wide">Grafikler · Müşteriler</p>

    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-4">
      <p class="text-[11px] font-medium text-[#64748B] mb-3">Toplam Bakiye Trendi · Son 6 Ay</p>
      <div style="height:190px;"><canvas id="grafikTrend"></canvas></div>
    </div>

    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-4">
      <p class="text-[11px] font-medium text-[#64748B] mb-3">En Yüksek Bakiyeli Müşteriler</p>
      <div style="height:190px;">
        @if($enYuksekBorclular->isEmpty())
          <p class="text-[12px] text-[#94A3B8] text-center pt-16">Borçlu müşteri yok.</p>
        @else
          <canvas id="grafikBorclular"></canvas>
        @endif
      </div>
    </div>

    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-4">
      <p class="text-[11px] font-medium text-[#64748B] mb-3">Aylık Borç / Tahsilat</p>
      <div style="height:190px;"><canvas id="grafikAylik"></canvas></div>
    </div>
  </div>

  {{-- Hatırlatma ayarları --}}
  <div class="mb-6 bg-white rounded-[12px] border border-[#E2E8F0] px-4 py-4">
    <p class="text-[12px] font-semibold text-[#0F172A] mb-3 flex items-center gap-1.5">
      <i class="ti ti-bell"></i> Günlük Hatırlatma Ayarları
    </p>
    <form action="{{ route('admin.cari.ayarlar') }}" method="POST" class="space-y-3">
      @csrf
      <div>
        <label class="text-[11px] text-[#64748B] mb-1 block">Hatırlatma E-postası</label>
        <input type="email" name="cari_hatirlatma_email" value="{{ $hatirlatmaAyarlari['email'] }}"
               class="w-full px-3 py-2 rounded-[8px] border border-[#E2E8F0] text-[13px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
      </div>
      <div>
        <label class="text-[11px] text-[#64748B] mb-1 block">Hatırlatma Telefonu (SMS)</label>
        <input type="text" name="cari_hatirlatma_telefon" value="{{ $hatirlatmaAyarlari['telefon'] }}" placeholder="05XX XXX XX XX"
               class="w-full px-3 py-2 rounded-[8px] border border-[#E2E8F0] text-[13px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
      </div>
      <button type="submit" class="w-full bg-[#141414] text-white text-[13px] font-medium py-2.5 rounded-[8px]">Kaydet</button>
    </form>
  </div>

  {{-- Yeni müşteri modalı --}}
  <div id="yeni-musteri-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Yeni Müşteri</p>
        <button type="button" onclick="document.getElementById('yeni-musteri-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.store') }}" method="POST" class="space-y-3">
        @csrf
        <input type="hidden" name="tip" value="musteri">
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Ad Soyad *</label>
          <input type="text" name="ad_soyad" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Telefon</label>
            <input type="text" name="telefon" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">E-posta</label>
            <input type="email" name="email" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Adres</label>
          <textarea name="adres" rows="2" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi Dairesi</label>
            <input type="text" name="vergi_dairesi" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi No</label>
            <input type="text" name="vergi_no" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Müşteri Ekle</button>
      </form>
    </div>
  </div>

  {{-- Yeni tedarikçi modalı --}}
  <div id="yeni-tedarikci-modal" class="hidden fixed inset-0 bg-black/50 z-40 flex items-end justify-center">
    <div class="bg-white w-full max-w-2xl rounded-t-[16px] p-5 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <p class="text-[15px] font-semibold text-[#0F172A]">Yeni Tedarikçi</p>
        <button type="button" onclick="document.getElementById('yeni-tedarikci-modal').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-[#F1F5F9]">
          <i class="ti ti-x text-[14px]"></i>
        </button>
      </div>
      <form action="{{ route('admin.cari.store') }}" method="POST" class="space-y-3">
        @csrf
        <input type="hidden" name="tip" value="tedarikci">
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Firma / Unvan *</label>
          <input type="text" name="ad_soyad" required class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Yetkili Kişi</label>
          <input type="text" name="yetkili_kisi" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Telefon</label>
            <input type="text" name="telefon" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">E-posta</label>
            <input type="email" name="email" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <div>
          <label class="text-[11px] text-[#64748B] mb-1 block">Adres</label>
          <textarea name="adres" rows="2" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi Dairesi</label>
            <input type="text" name="vergi_dairesi" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
          <div>
            <label class="text-[11px] text-[#64748B] mb-1 block">Vergi No</label>
            <input type="text" name="vergi_no" class="w-full px-3 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[14px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20">
          </div>
        </div>
        <button type="submit" class="w-full bg-[#CC2200] text-white text-[14px] font-semibold py-3 rounded-[10px] mt-2">Tedarikçi Ekle</button>
      </form>
    </div>
  </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Chart === 'undefined') return;

  Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
  Chart.defaults.font.size = 11;
  Chart.defaults.color = '#64748B';

  const etiketler = @json($grafik['etiketler']);

  new Chart(document.getElementById('grafikTrend'), {
    type: 'line',
    data: {
      labels: etiketler,
      datasets: [{
        data: @json($grafik['bakiyeTrend']),
        borderColor: '#CC2200',
        backgroundColor: 'rgba(204,34,0,0.08)',
        borderWidth: 2,
        pointRadius: 3,
        pointBackgroundColor: '#CC2200',
        tension: 0.3,
        fill: true,
      }]
    },
    options: {
      maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => c.parsed.y.toLocaleString('tr-TR', {minimumFractionDigits:2}) + ' ₺' } } },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { color: '#F1F5F9' }, ticks: { callback: (v) => v.toLocaleString('tr-TR') } },
      },
    },
  });

  @if($enYuksekBorclular->isNotEmpty())
  new Chart(document.getElementById('grafikBorclular'), {
    type: 'bar',
    data: {
      labels: @json($enYuksekBorclular->pluck('ad_soyad')),
      datasets: [{
        data: @json($enYuksekBorclular->pluck('hesaplanan_bakiye')),
        backgroundColor: '#CC2200',
        borderRadius: 4,
        maxBarThickness: 22,
      }]
    },
    options: {
      indexAxis: 'y',
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: (c) => c.parsed.x.toLocaleString('tr-TR', {minimumFractionDigits:2}) + ' ₺' } },
      },
      scales: {
        x: { grid: { color: '#F1F5F9' }, ticks: { callback: (v) => v.toLocaleString('tr-TR') } },
        y: { grid: { display: false } },
      },
    },
  });
  @endif

  new Chart(document.getElementById('grafikAylik'), {
    type: 'bar',
    data: {
      labels: etiketler,
      datasets: [
        { label: 'Borç', data: @json($grafik['aylikBorc']), backgroundColor: '#CC2200', borderRadius: 3, maxBarThickness: 16 },
        { label: 'Tahsilat', data: @json($grafik['aylikTahsilat']), backgroundColor: '#1D7A4C', borderRadius: 3, maxBarThickness: 16 },
      ]
    },
    options: {
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true, position: 'top', labels: { boxWidth: 10, boxHeight: 10, padding: 12 } },
        tooltip: { callbacks: { label: (c) => c.dataset.label + ': ' + c.parsed.y.toLocaleString('tr-TR', {minimumFractionDigits:2}) + ' ₺' } },
      },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { color: '#F1F5F9' }, ticks: { callback: (v) => v.toLocaleString('tr-TR') } },
      },
    },
  });
});
</script>
@endpush
