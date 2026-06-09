@extends('admin.layouts.app')
@section('title', 'Sistem Güncelle')
@section('page-title', 'Sistem Güncelle')

@section('content')

<div class="max-w-2xl">

  {{-- Açıklama kutusu --}}
  <div class="bg-white border border-[#E2E8F0] rounded-xl p-6 mb-6">
    <div class="flex items-start gap-4">
      <div class="w-10 h-10 rounded-xl bg-[#0F172A] flex items-center justify-center shrink-0">
        <i class="ti ti-cloud-download text-white text-lg"></i>
      </div>
      <div>
        <h2 class="text-[15px] font-semibold text-[#0F172A] mb-1">Sunucu Güncellemesi</h2>
        <p class="text-[13px] text-[#64748B] leading-relaxed">
          GitHub'daki son değişiklikleri sunucuya çeker ve aşağıdaki işlemleri sırayla çalıştırır:
        </p>
        <ul class="mt-2 space-y-0.5">
          @foreach(['git pull origin main', 'php artisan migrate --force', 'config:cache', 'route:cache', 'view:cache', 'storage:link'] as $adim)
          <li class="flex items-center gap-2 text-[12px] text-[#475569]">
            <i class="ti ti-chevron-right text-[#CC2200] text-xs"></i>
            <code class="font-mono">{{ $adim }}</code>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  {{-- Güncelle butonu --}}
  @if(!isset($sonuclar))
  <form method="POST" action="{{ route('admin.deploy.guncelle') }}"
        x-data="{ yukleniyor: false }"
        @submit="yukleniyor = true">
    @csrf
    <button type="submit"
            :disabled="yukleniyor"
            class="w-full flex items-center justify-center gap-2 bg-[#0F172A] hover:bg-[#1E293B] text-white text-[14px] font-semibold px-6 py-3.5 rounded-xl transition-colors disabled:opacity-60">
      <i class="ti ti-refresh" :class="yukleniyor ? 'animate-spin' : ''"></i>
      <span x-text="yukleniyor ? 'Güncelleniyor, lütfen bekleyin...' : 'Güncellemeyi Başlat'"></span>
    </button>
    <p class="text-center text-[11px] text-[#94A3B8] mt-3">Güncelleme sırasında site kısa süre yavaşlayabilir.</p>
  </form>
  @endif

  {{-- Sonuçlar --}}
  @if(isset($sonuclar))
  <div class="space-y-3">
    @php $tamamenBasarili = collect($sonuclar)->every(fn($s) => $s['basarili']); @endphp

    {{-- Genel durum --}}
    <div class="flex items-center gap-3 p-4 rounded-xl border {{ $tamamenBasarili ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
      <i class="ti {{ $tamamenBasarili ? 'ti-circle-check text-green-500' : 'ti-circle-x text-red-500' }} text-xl"></i>
      <span class="text-[14px] font-semibold {{ $tamamenBasarili ? 'text-green-800' : 'text-red-800' }}">
        {{ $tamamenBasarili ? 'Güncelleme başarıyla tamamlandı.' : 'Güncelleme sırasında hata oluştu.' }}
      </span>
    </div>

    {{-- Adım detayları --}}
    @foreach($sonuclar as $sonuc)
    <div class="bg-white border border-[#E2E8F0] rounded-xl overflow-hidden">
      <div class="flex items-center gap-3 px-4 py-3 border-b border-[#E2E8F0]">
        <i class="ti {{ $sonuc['basarili'] ? 'ti-circle-check text-green-500' : 'ti-circle-x text-red-500' }} text-base"></i>
        <code class="text-[13px] font-mono font-semibold text-[#0F172A]">{{ $sonuc['ad'] }}</code>
        <span class="ml-auto text-[11px] font-medium px-2 py-0.5 rounded-full {{ $sonuc['basarili'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
          {{ $sonuc['basarili'] ? 'Başarılı' : 'Hata' }}
        </span>
      </div>
      @if($sonuc['cikti'])
      <pre class="text-[11px] font-mono text-[#475569] bg-[#F8FAFC] px-4 py-3 overflow-x-auto whitespace-pre-wrap leading-relaxed">{{ $sonuc['cikti'] }}</pre>
      @endif
    </div>
    @endforeach

    {{-- Tekrar güncelle --}}
    <form method="POST" action="{{ route('admin.deploy.guncelle') }}"
          x-data="{ yukleniyor: false }"
          @submit="yukleniyor = true">
      @csrf
      <button type="submit"
              :disabled="yukleniyor"
              class="w-full flex items-center justify-center gap-2 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] text-[13px] font-medium px-6 py-3 rounded-xl transition-colors mt-2 disabled:opacity-60">
        <i class="ti ti-refresh" :class="yukleniyor ? 'animate-spin' : ''"></i>
        <span x-text="yukleniyor ? 'Çalışıyor...' : 'Tekrar Güncelle'"></span>
      </button>
    </form>
  </div>
  @endif

</div>

@endsection
