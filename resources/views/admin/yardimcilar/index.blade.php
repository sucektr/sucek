@extends('admin.layouts.app')
@section('title', 'Yardımcılar')
@section('page-title', 'Yardımcılar')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- Yeni Yardımcı --}}
  <div class="lg:col-span-1">
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] p-5">
      <h2 class="text-[13px] font-semibold text-[#0F172A] mb-4">Yeni Yardımcı Ekle</h2>
      <form action="{{ route('admin.yardimcilar.store') }}" method="POST" class="space-y-3">
        @csrf
        <div>
          <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Ad Soyad</label>
          <input type="text" name="name" required value="{{ old('name') }}"
                 class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
          @error('name')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">E-posta</label>
          <input type="email" name="email" required value="{{ old('email') }}"
                 class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
          @error('email')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="block text-[11px] font-medium text-[#64748B] uppercase tracking-[.06em] mb-1.5">Şifre</label>
          <input type="text" name="password" required
                 class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] focus:ring-2 focus:ring-[rgba(204,34,0,0.08)] transition-colors">
          @error('password')<p class="text-[11px] text-[#CC2200] mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit"
                class="w-full flex items-center justify-center gap-1.5 bg-[#CC2200] text-white text-[11px] font-semibold tracking-[1px] uppercase px-4 py-2.5 rounded-[8px] hover:bg-[#a31b00] transition-colors">
          <i class="ti ti-plus text-sm"></i> Yardımcı Oluştur
        </button>
      </form>
      <p class="text-[11px] text-[#94A3B8] mt-3">
        Yardımcı <a href="{{ route('yardimci.giris') }}" target="_blank" class="text-[#CC2200] underline">{{ url('/urun-ekle') }}</a> adresinden bu bilgilerle giriş yapıp telefonundan ürün ekleyebilir.
      </p>
    </div>
  </div>

  {{-- Liste --}}
  <div class="lg:col-span-2">
    <div class="bg-white rounded-[12px] border border-[#E2E8F0] overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[#E2E8F0] bg-[#F8FAFC]">
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">Ad</th>
              <th class="px-5 py-3.5 text-left text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">E-posta</th>
              <th class="px-5 py-3.5 text-right text-[10px] font-semibold uppercase tracking-[.08em] text-[#94A3B8]">İşlem</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[rgba(0,0,0,0.04)]">
            @forelse($yardimcilar as $y)
            <tr class="hover:bg-[#F8FAFC] transition-colors">
              <td class="px-5 py-3 font-medium text-[#0F172A] text-[13px]">{{ $y->name }}</td>
              <td class="px-5 py-3 text-[13px] text-[#64748B]">{{ $y->email }}</td>
              <td class="px-5 py-3 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <button type="button" onclick="document.getElementById('sifre-modal-{{ $y->id }}').classList.remove('hidden')"
                          class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#64748B] transition-colors" title="Şifre Değiştir">
                    <i class="ti ti-key text-sm"></i>
                  </button>
                  <form action="{{ route('admin.yardimcilar.destroy', $y) }}" method="POST" onsubmit="return confirm('{{ $y->name }} silinsin mi?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-[6px] border border-transparent hover:bg-[#FEE2E2] hover:text-[#DC2626] text-[#C0C0C0] transition-colors">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                </div>

                {{-- Şifre modalı --}}
                <div id="sifre-modal-{{ $y->id }}" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
                  <div class="bg-white rounded-[12px] p-5 w-full max-w-[320px]">
                    <h3 class="text-[13px] font-semibold text-[#0F172A] mb-3">{{ $y->name }} — Yeni Şifre</h3>
                    <form action="{{ route('admin.yardimcilar.sifre', $y) }}" method="POST">
                      @csrf
                      <input type="text" name="password" required placeholder="Yeni şifre"
                             class="w-full px-4 py-2.5 border border-[#E2E8F0] rounded-[8px] text-[14px] focus:outline-none focus:border-[#CC2200] mb-3">
                      <div class="flex gap-2">
                        <button type="button" onclick="document.getElementById('sifre-modal-{{ $y->id }}').classList.add('hidden')"
                                class="flex-1 px-4 py-2.5 rounded-[8px] border border-[#E2E8F0] text-[12px] font-medium text-[#64748B]">Vazgeç</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 rounded-[8px] bg-[#141414] text-white text-[12px] font-semibold">Kaydet</button>
                      </div>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-5 py-10 text-center text-[13px] text-[#94A3B8]">Henüz yardımcı eklenmedi.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection
