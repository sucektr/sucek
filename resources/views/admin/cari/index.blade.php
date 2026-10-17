@extends('admin.layouts.cari')

@section('title', 'Cari Hesap')
@section('page-title', 'Müşteriler')

@section('header-actions')
  <button type="button" onclick="document.getElementById('yeni-musteri-modal').classList.remove('hidden')"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-[rgba(255,255,255,0.08)] text-white">
    <i class="ti ti-plus text-[18px]"></i>
  </button>
@endsection

@section('content')

  {{-- Toplam bakiye kartı --}}
  <div class="bg-[#141414] rounded-[14px] px-5 py-5 mb-4 text-white">
    <p class="text-[10px] uppercase tracking-wider text-[rgba(255,255,255,0.4)] mb-1">Toplam Bakiye</p>
    <p class="text-[28px] font-bold {{ $toplamBakiye > 0 ? 'text-[#CC2200]' : ($toplamBakiye < 0 ? 'text-[#4ADE80]' : 'text-white') }}">
      {{ number_format($toplamBakiye, 2, ',', '.') }} ₺
    </p>
    <p class="text-[11px] text-[rgba(255,255,255,0.5)] mt-1">
      {{ $toplamBakiye > 0 ? 'Müşterilerden toplam alacağınız' : ($toplamBakiye < 0 ? 'Müşterilere toplam borcunuz' : 'Hesaplar dengede') }}
    </p>
  </div>

  {{-- Vadesi yaklaşan/geçen uyarı --}}
  @if($vadesiYaklasanlar->isNotEmpty())
  <div class="bg-[#FFF7ED] border border-[#FED7AA] rounded-[12px] px-4 py-3 mb-4">
    <p class="text-[12px] font-semibold text-[#9A3412] flex items-center gap-1.5 mb-2">
      <i class="ti ti-alert-triangle"></i> Vadesi Yaklaşan/Geçen Ödemeler
    </p>
    <div class="space-y-1.5">
      @foreach($vadesiYaklasanlar as $h)
        <a href="{{ route('admin.cari.show', $h->musteri) }}" class="flex items-center justify-between text-[12px] text-[#78350F]">
          <span class="truncate">{{ $h->musteri->ad_soyad }} — {{ $h->aciklama }}</span>
          <span class="shrink-0 font-semibold {{ $h->vade_tarihi->isPast() ? 'text-[#CC2200]' : '' }}">{{ $h->vade_tarihi->format('d.m.Y') }}</span>
        </a>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Genel cari durum grafikleri --}}
  <div class="mb-5">
    <p class="text-[12px] font-semibold text-[#64748B] uppercase tracking-wide mb-2">Genel Durum</p>
    <div class="flex gap-3 overflow-x-auto snap-x snap-mandatory -mx-4 px-4 pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
      <div class="snap-center shrink-0 w-[86%] bg-white rounded-[12px] border border-[#E2E8F0] p-4">
        <p class="text-[11px] font-medium text-[#64748B] mb-3">Toplam Bakiye Trendi · Son 6 Ay</p>
        <div style="height:170px;"><canvas id="grafikTrend"></canvas></div>
      </div>
      <div class="snap-center shrink-0 w-[86%] bg-white rounded-[12px] border border-[#E2E8F0] p-4">
        <p class="text-[11px] font-medium text-[#64748B] mb-3">En Yüksek Bakiyeli Müşteriler</p>
        <div style="height:170px;">
          @if($enYuksekBorclular->isEmpty())
            <p class="text-[12px] text-[#94A3B8] text-center pt-14">Borçlu müşteri yok.</p>
          @else
            <canvas id="grafikBorclular"></canvas>
          @endif
        </div>
      </div>
      <div class="snap-center shrink-0 w-[86%] bg-white rounded-[12px] border border-[#E2E8F0] p-4">
        <p class="text-[11px] font-medium text-[#64748B] mb-3">Aylık Borç / Tahsilat</p>
        <div style="height:170px;"><canvas id="grafikAylik"></canvas></div>
      </div>
    </div>
  </div>

  {{-- Arama --}}
  <form method="GET" class="mb-4">
    <div class="relative">
      <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#94A3B8] text-[16px]"></i>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Müşteri ara..."
             class="w-full pl-10 pr-4 py-2.5 rounded-[10px] border border-[#E2E8F0] bg-white text-[13px] focus:outline-none focus:ring-2 focus:ring-[#CC2200]/20 focus:border-[#CC2200]">
    </div>
  </form>

  {{-- Müşteri listesi --}}
  <div class="space-y-2">
    @forelse($musteriler as $musteri)
      <a href="{{ route('admin.cari.show', $musteri) }}" class="block bg-white rounded-[12px] border border-[#E2E8F0] px-4 py-3.5 flex items-center justify-between gap-3">
        <div class="min-w-0">
          <p class="text-[14px] font-semibold text-[#0F172A] truncate">{{ $musteri->ad_soyad }}</p>
          @if($musteri->telefon)<p class="text-[11px] text-[#94A3B8] mt-0.5">{{ $musteri->telefon }}</p>@endif
        </div>
        <div class="shrink-0 text-right">
          <p class="text-[14px] font-bold {{ $musteri->hesaplanan_bakiye > 0 ? 'text-[#CC2200]' : ($musteri->hesaplanan_bakiye < 0 ? 'text-[#1D7A4C]' : 'text-[#94A3B8]') }}">
            {{ number_format($musteri->hesaplanan_bakiye, 2, ',', '.') }} ₺
          </p>
          <i class="ti ti-chevron-right text-[#CBD5E1] text-[16px]"></i>
        </div>
      </a>
    @empty
      <div class="text-center py-12 text-[#94A3B8]">
        <i class="ti ti-users text-[32px]"></i>
        <p class="text-[13px] mt-2">Henüz müşteri eklenmedi.</p>
      </div>
    @endforelse
  </div>

  {{-- Hatırlatma ayarları --}}
  <div class="mt-6 bg-white rounded-[12px] border border-[#E2E8F0] px-4 py-4">
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

  {{-- Yeni müşteri modal --}}
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

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
<script>
(function () {
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
})();
</script>
@endpush
