@extends('admin.layouts.app')
@section('title', 'İletişim Mesajları')
@section('page-title', 'İletişim Mesajları')

@section('content')

<div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
  <div class="px-5 py-4 border-b border-[#E2E8F0] flex items-center gap-3">
    @php $okunmamis = $mesajlar->where('okundu', false)->count(); @endphp
    <span class="text-[13px] text-[#64748B]">Toplam {{ $mesajlar->total() }} mesaj</span>
    @if($okunmamis > 0)
      <span class="text-[11px] font-semibold text-[#CC2200] bg-[#fdeaea] px-2.5 py-0.5 rounded-full">{{ $okunmamis }} okunmamış</span>
    @endif
  </div>

  <div class="divide-y divide-[rgba(0,0,0,0.04)]" x-data="{ acik: null }">
    @forelse($mesajlar as $m)
    <div class="{{ !$m->okundu ? 'bg-[#FFFCF5]' : 'bg-white' }}">
      {{-- Satır --}}
      <div class="flex items-start gap-4 px-5 py-4 cursor-pointer hover:bg-[#F8FAFC] transition-colors"
           @click="acik = acik === {{ $m->id }} ? null : {{ $m->id }}">
        <div class="w-9 h-9 rounded-full {{ !$m->okundu ? 'bg-[#FEF3C7]' : 'bg-[#F1F5F9]' }} flex items-center justify-center shrink-0 mt-0.5">
          <i class="ti {{ !$m->okundu ? 'ti-mail text-[#D97706]' : 'ti-mail-opened text-[#94A3B8]' }} text-base"></i>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[14px] font-{{ !$m->okundu ? 'semibold' : 'medium' }} text-[#0F172A]">{{ $m->ad }}</span>
            @if(!$m->okundu)<span class="w-1.5 h-1.5 rounded-full bg-[#CC2200]"></span>@endif
            @if($m->konu)<span class="text-[12px] text-[#64748B]">— {{ $m->konu }}</span>@endif
            <span class="text-[11px] text-[#94A3B8] ml-auto">{{ $m->created_at->format('d.m.Y H:i') }}</span>
          </div>
          <p class="text-[12px] text-[#94A3B8] mt-0.5">
            <a href="mailto:{{ $m->email }}" class="hover:underline" onclick="event.stopPropagation()">{{ $m->email }}</a>
            @if($m->telefon) · <a href="tel:{{ $m->telefon }}" onclick="event.stopPropagation()">{{ $m->telefon }}</a> @endif
          </p>
          <p class="text-[13px] text-[#64748B] mt-1 truncate">{{ Str::limit($m->mesaj, 100) }}</p>
        </div>
        <i class="ti ti-chevron-down text-[#C0C0C0] shrink-0 mt-1 transition-transform duration-200"
           :class="acik === {{ $m->id }} ? 'rotate-180' : ''"></i>
      </div>

      {{-- Genişletilmiş --}}
      <div x-show="acik === {{ $m->id }}"
           x-transition:enter="transition ease-out duration-150"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           class="px-5 pb-4 pl-[72px]"
           style="display:none;">
        <div class="bg-[#F9F9F9] rounded-[8px] p-4 text-[13px] text-[#334155] leading-relaxed mb-3 whitespace-pre-wrap">{{ $m->mesaj }}</div>
        <div class="flex items-center gap-2">
          <form action="{{ route('admin.iletisim.oku', $m) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit"
                    class="flex items-center gap-1.5 text-[11px] font-medium px-3 py-2 rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] transition-colors text-[#64748B]">
              <i class="ti {{ $m->okundu ? 'ti-mail' : 'ti-mail-opened' }} text-sm"></i>
              {{ $m->okundu ? 'Okunmadı İşaretle' : 'Okundu İşaretle' }}
            </button>
          </form>
          <a href="mailto:{{ $m->email }}"
             class="flex items-center gap-1.5 text-[11px] font-medium px-3 py-2 rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] transition-colors text-[#64748B]">
            <i class="ti ti-send text-sm"></i> Yanıtla
          </a>
          <form action="{{ route('admin.iletisim.destroy', $m) }}" method="POST"
                onsubmit="return confirm('Bu mesajı silmek istediğinize emin misiniz?')" class="ml-auto">
            @csrf @method('DELETE')
            <button type="submit"
                    class="flex items-center gap-1.5 text-[11px] font-medium px-3 py-2 rounded-[6px] border border-transparent hover:bg-[#FEE2E2] hover:text-[#991B1B] transition-colors text-[#94A3B8]">
              <i class="ti ti-trash text-sm"></i> Sil
            </button>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="px-5 py-16 text-center">
      <i class="ti ti-inbox text-4xl text-[#D0D0D0] block mb-3"></i>
      <p class="text-[14px] font-medium text-[#64748B]">Henüz mesaj yok</p>
    </div>
    @endforelse
  </div>

  @if($mesajlar->hasPages())
  <div class="px-5 py-4 border-t border-[#E2E8F0]">
    {{ $mesajlar->links() }}
  </div>
  @endif
</div>
@endsection


