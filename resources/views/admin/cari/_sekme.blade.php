{{-- $aktif: 'musteri' | 'tedarikci' --}}
<div class="flex gap-2 mb-4 bg-[#F1F5F9] p-1 rounded-[10px]">
  <a href="{{ route('admin.cari.index') }}"
     class="flex-1 text-center text-[12.5px] font-semibold py-2 rounded-[8px] {{ $aktif === 'musteri' ? 'bg-white text-[#0F172A] shadow-sm' : 'text-[#64748B]' }}">
    Müşteriler
  </a>
  <a href="{{ route('admin.cari.tedarikci.index') }}"
     class="flex-1 text-center text-[12.5px] font-semibold py-2 rounded-[8px] {{ $aktif === 'tedarikci' ? 'bg-white text-[#0F172A] shadow-sm' : 'text-[#64748B]' }}">
    Tedarikçiler
  </a>
</div>
