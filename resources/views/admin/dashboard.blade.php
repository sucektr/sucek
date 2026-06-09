@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('header-actions')
<a href="{{ route('home') }}" target="_blank"
   class="flex items-center gap-1.5 text-[11px] font-medium text-[#64748B] hover:text-[#0F172A] transition-colors">
  <i class="ti ti-external-link text-sm"></i> Siteyi Görüntüle
</a>
@endsection

@section('content')

{{-- İstatistikler --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
  @php
  $statCards = [
    ['label' => 'Ürün', 'value' => $stats['urun'], 'icon' => 'ti-shopping-bag', 'color' => '#1a4a6b', 'bg' => '#e8f1f7', 'route' => 'admin.urunler.index'],
    ['label' => 'Koleksiyon', 'value' => $stats['koleksiyon'], 'icon' => 'ti-diamond', 'color' => '#3d2d7a', 'bg' => '#eeecfa', 'route' => 'admin.koleksiyonlar.index'],
    ['label' => 'Proje', 'value' => $stats['proje'], 'icon' => 'ti-building-arch', 'color' => '#7a4800', 'bg' => '#fdf3e3', 'route' => 'admin.projeler.index'],
    ['label' => 'Belge', 'value' => $stats['belge'], 'icon' => 'ti-files', 'color' => '#1a5c3a', 'bg' => '#e6f4ec', 'route' => 'admin.belgeler.index'],
    ['label' => 'Mesaj', 'value' => $stats['mesaj'], 'icon' => 'ti-mail', 'color' => '#5A5A5A', 'bg' => '#F5F5F5', 'route' => 'admin.iletisim.index'],
    ['label' => 'Okunmamış', 'value' => $stats['okunmamis'], 'icon' => 'ti-mail-opened', 'color' => '#CC2200', 'bg' => '#fdeaea', 'route' => 'admin.iletisim.index'],
  ];
  @endphp
  @foreach($statCards as $card)
  <a href="{{ route($card['route']) }}"
     class="bg-white rounded-[12px] border border-[#E2E8F0] p-5 hover:shadow-md transition-shadow">
    <div class="w-9 h-9 rounded-[8px] flex items-center justify-center mb-3"
         style="background:{{ $card['bg'] }}">
      <i class="ti {{ $card['icon'] }}" style="color:{{ $card['color'] }};font-size:18px;"></i>
    </div>
    <div class="text-[26px] font-semibold text-[#0F172A] leading-none mb-1">{{ $card['value'] }}</div>
    <div class="text-[11px] font-medium text-[#94A3B8] uppercase tracking-[.05em]">{{ $card['label'] }}</div>
  </a>
  @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">

  {{-- Son Mesajlar --}}
  <div class="lg:col-span-2 bg-white rounded-[12px] border border-[#E2E8F0]">
    <div class="flex items-center justify-between px-5 py-4 border-b border-[#E2E8F0]">
      <h2 class="text-[14px] font-semibold text-[#0F172A]">Son Mesajlar</h2>
      <a href="{{ route('admin.iletisim.index') }}" class="text-[11px] text-[#94A3B8] hover:text-[#0F172A] transition-colors">Tümünü Gör →</a>
    </div>
    @forelse($sonMesajlar as $m)
    <div class="flex items-start gap-3 px-5 py-3.5 border-b border-[#E2E8F0] last:border-0 {{ !$m->okundu ? 'bg-[#FFFBF0]' : '' }}">
      <div class="w-8 h-8 rounded-full bg-[#F1F5F9] flex items-center justify-center shrink-0 mt-0.5">
        <i class="ti ti-user text-sm text-[#94A3B8]"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-0.5">
          <span class="text-[13px] font-medium text-[#0F172A]">{{ $m->ad }}</span>
          @if(!$m->okundu)<span class="w-1.5 h-1.5 rounded-full bg-[#CC2200] shrink-0"></span>@endif
          <span class="text-[11px] text-[#94A3B8] ml-auto">{{ $m->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-[12px] text-[#64748B] truncate">{{ $m->konu ?: $m->email }}</p>
        <p class="text-[12px] text-[#94A3B8] truncate mt-0.5">{{ Str::limit($m->mesaj, 60) }}</p>
      </div>
    </div>
    @empty
    <div class="px-5 py-10 text-center text-[13px] text-[#94A3B8]">Henüz mesaj yok.</div>
    @endforelse
  </div>

  {{-- Hızlı Erişim --}}
  <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5">
    <h2 class="text-[14px] font-semibold text-[#0F172A] mb-4">Hızlı Ekle</h2>
    <div class="flex flex-col gap-2">
      @foreach([
        ['Yeni Ürün', 'admin.urunler.create', 'ti-shopping-bag'],
        ['Yeni Koleksiyon', 'admin.koleksiyonlar.create', 'ti-diamond'],
        ['Yeni Proje', 'admin.projeler.create', 'ti-building-arch'],
        ['Yeni Belge', 'admin.belgeler.create', 'ti-file-plus'],
      ] as [$label, $route, $icon])
      <a href="{{ route($route) }}"
         class="flex items-center gap-3 px-4 py-3 rounded-[8px] border border-[#E2E8F0] text-[13px] font-medium text-[#334155] hover:bg-[#F8FAFC] hover:border-[#E2E8F0] transition-all">
        <i class="ti {{ $icon }} text-base text-[#94A3B8]"></i>
        {{ $label }}
        <i class="ti ti-plus text-sm ml-auto text-[#C0C0C0]"></i>
      </a>
      @endforeach
    </div>

    <div class="mt-6 pt-5 border-t border-[#E2E8F0]">
      <h3 class="text-[11px] font-semibold uppercase tracking-[.08em] text-[#94A3B8] mb-3">Sistem Bilgisi</h3>
      <dl class="space-y-2">
        <div class="flex justify-between text-[12px]">
          <dt class="text-[#94A3B8]">PHP Versiyonu</dt>
          <dd class="font-medium text-[#334155]">{{ PHP_VERSION }}</dd>
        </div>
        <div class="flex justify-between text-[12px]">
          <dt class="text-[#94A3B8]">Laravel</dt>
          <dd class="font-medium text-[#334155]">{{ app()->version() }}</dd>
        </div>
        <div class="flex justify-between text-[12px]">
          <dt class="text-[#94A3B8]">Ortam</dt>
          <dd class="font-medium text-[#334155]">{{ app()->environment() }}</dd>
        </div>
      </dl>
    </div>
  </div>

</div>
@endsection


