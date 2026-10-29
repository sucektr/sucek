@extends('admin.layouts.app')
@section('title', 'Onay Bekleyenler')
@section('page-title', 'Onay Bekleyenler')

@section('content')
<div class="space-y-6">

  {{-- Mağaza Ürünleri --}}
  <div>
    <h2 class="text-[12px] font-semibold tracking-[1px] uppercase text-[#64748B] mb-2">Mağaza Ürünleri ({{ $urunler->count() }})</h2>
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]"></th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ürün</th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Fiyat</th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ekleyen</th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Tarih</th>
              <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
            @forelse($urunler as $u)
            <tr class="hover:bg-[#F8FAFC] transition-colors">
              <td class="px-5 py-3">
                <div class="w-10 h-10 rounded-[6px] bg-[#F8FAFC] overflow-hidden">
                  @if($u->gorsel)<img src="{{ asset('storage/'.$u->gorsel) }}" class="w-full h-full object-cover">@endif
                </div>
              </td>
              <td class="px-5 py-3">
                <p class="font-medium text-[#0F172A] text-[13px]">{{ $u->ad }}</p>
                <p class="text-[11px] text-[#94A3B8]">{{ $u->kategori }}</p>
              </td>
              <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ number_format($u->fiyat, 2, ',', '.') }} ₺</td>
              <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $u->ekleyen->name ?? '—' }}</td>
              <td class="px-5 py-3 text-[12px] text-[#94A3B8]">{{ $u->created_at->diffForHumans() }}</td>
              <td class="px-5 py-3 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <a href="{{ route('admin.urunler.edit', $u) }}"
                     class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors" title="Düzenle">
                    <i class="ti ti-edit text-sm"></i>
                  </a>
                  <form action="{{ route('admin.urunler.destroy', $u) }}" method="POST" onsubmit="return confirm('Bu ürün kalıcı olarak silinsin mi?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#FEE2E2] hover:text-[#DC2626] text-[#C0C0C0] transition-colors" title="Sil">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                  <form action="{{ route('admin.onay-bekleyenler.urun.reddet', $u) }}" method="POST" onsubmit="return confirm('Bu ürün reddedilsin mi?')">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#FEF3C7] hover:text-[#B8962E] text-[#C0C0C0] transition-colors" title="Reddet">
                      <i class="ti ti-x text-sm"></i>
                    </button>
                  </form>
                  <form action="{{ route('admin.onay-bekleyenler.urun.onayla', $u) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-1 px-3 h-8 rounded-[6px] bg-[#1A5C3A] text-white text-[11px] font-semibold hover:bg-[#15492e] transition-colors">
                      <i class="ti ti-check text-sm"></i> Onayla
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-[13px] text-[#94A3B8]">Onay bekleyen mağaza ürünü yok.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Koleksiyon Ürünleri --}}
  <div>
    <h2 class="text-[12px] font-semibold tracking-[1px] uppercase text-[#64748B] mb-2">Koleksiyon Ürünleri ({{ $koleksiyonlar->count() }})</h2>
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]"></th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ürün</th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Fiyat</th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ekleyen</th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Tarih</th>
              <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
            @forelse($koleksiyonlar as $k)
            <tr class="hover:bg-[#F8FAFC] transition-colors">
              <td class="px-5 py-3">
                <div class="w-10 h-10 rounded-[6px] bg-[#F8FAFC] overflow-hidden">
                  @if($k->gorsel)<img src="{{ asset('storage/'.$k->gorsel) }}" class="w-full h-full object-cover">@endif
                </div>
              </td>
              <td class="px-5 py-3">
                <p class="font-medium text-[#0F172A] text-[13px]">{{ $k->ad }}</p>
                <p class="text-[11px] text-[#94A3B8]">{{ $k->kategori }}{{ $k->ulke ? ' · '.$k->ulke : '' }}</p>
              </td>
              <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $k->fiyat ? number_format($k->fiyat, 2, ',', '.').' ₺' : '—' }}</td>
              <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $k->ekleyen->name ?? '—' }}</td>
              <td class="px-5 py-3 text-[12px] text-[#94A3B8]">{{ $k->created_at->diffForHumans() }}</td>
              <td class="px-5 py-3 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <a href="{{ route('admin.koleksiyonlar.edit', $k) }}"
                     class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors" title="Düzenle">
                    <i class="ti ti-edit text-sm"></i>
                  </a>
                  <form action="{{ route('admin.koleksiyonlar.destroy', $k) }}" method="POST" onsubmit="return confirm('Bu ürün kalıcı olarak silinsin mi?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#FEE2E2] hover:text-[#DC2626] text-[#C0C0C0] transition-colors" title="Sil">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                  <form action="{{ route('admin.onay-bekleyenler.koleksiyon.reddet', $k) }}" method="POST" onsubmit="return confirm('Bu ürün reddedilsin mi?')">
                    @csrf
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#FEF3C7] hover:text-[#B8962E] text-[#C0C0C0] transition-colors" title="Reddet">
                      <i class="ti ti-x text-sm"></i>
                    </button>
                  </form>
                  <form action="{{ route('admin.onay-bekleyenler.koleksiyon.onayla', $k) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-1 px-3 h-8 rounded-[6px] bg-[#1A5C3A] text-white text-[11px] font-semibold hover:bg-[#15492e] transition-colors">
                      <i class="ti ti-check text-sm"></i> Onayla
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-[13px] text-[#94A3B8]">Onay bekleyen koleksiyon ürünü yok.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
