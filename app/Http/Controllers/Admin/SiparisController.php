<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siparis;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SiparisController extends Controller
{
    public function index(Request $request)
    {
        $durum = $request->input('durum');

        $siparisler = Siparis::with('kalemler')
            ->when($durum, fn($q) => $q->where('durum', $durum))
            ->latest()
            ->paginate(20);

        $sayilar = Siparis::selectRaw('durum, count(*) as adet')
            ->groupBy('durum')
            ->pluck('adet', 'durum');

        return view('admin.siparisler.index', compact('siparisler', 'sayilar', 'durum'));
    }

    public function show(Siparis $siparis)
    {
        $siparis->load('kalemler', 'user');
        return view('admin.siparisler.show', compact('siparis'));
    }

    public function durumGuncelle(Request $request, Siparis $siparis)
    {
        $request->validate([
            'durum'      => 'required|in:bekliyor,odeme_alindi,hazirlaniyor,kargolandi,teslim_edildi,iptal',
            'admin_notu' => 'nullable|string|max:500',
        ]);

        $siparis->update([
            'durum'      => $request->durum,
            'admin_notu' => $request->admin_notu ?? $siparis->admin_notu,
        ]);

        $smsSablonMap = [
            'odeme_alindi'  => 'siparis_odeme_alindi',
            'hazirlaniyor'  => 'siparis_hazirlaniyor',
            'kargolandi'    => 'siparis_kargolandi',
            'teslim_edildi' => 'siparis_teslim_edildi',
            'iptal'         => 'siparis_iptal',
        ];

        if ($siparis->telefon && isset($smsSablonMap[$request->durum])) {
            app(SmsService::class)->sablonGonder($smsSablonMap[$request->durum], $siparis->telefon, [
                'ad_soyad' => $siparis->ad_soyad,
                'referans' => $siparis->referans,
                'toplam'   => number_format((float) $siparis->toplam, 2, ',', '.') . ' TL',
            ]);
        }

        return back()->with('basari', 'Sipariş durumu güncellendi.');
    }

    public function topluIslem(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:siparisler,id',
            'islem' => 'required|in:iptal,sil',
        ]);

        $ids = $request->ids;

        if ($request->islem === 'iptal') {
            Siparis::whereIn('id', $ids)->update(['durum' => 'iptal']);
            $mesaj = count($ids) . ' sipariş iptal edildi.';
        } else {
            Siparis::whereIn('id', $ids)->delete();
            $mesaj = count($ids) . ' sipariş silindi.';
        }

        return redirect()->back()->with('basari', $mesaj);
    }
}
