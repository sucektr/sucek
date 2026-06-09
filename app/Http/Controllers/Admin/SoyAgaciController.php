<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SoyAgaciDegisiklik;
use App\Models\SoyAgaciGrup;
use App\Models\SoyAgaciIliski;
use App\Models\SoyAgaciKisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SoyAgaciController extends Controller
{
    public function index()
    {
        $degisiklikler = SoyAgaciDegisiklik::with('user')
            ->latest()
            ->paginate(20);

        $bekleyenSayisi = SoyAgaciDegisiklik::where('durum', 'beklemede')->count();

        return view('admin.soy-agaci.index', compact('degisiklikler', 'bekleyenSayisi'));
    }

    public function karar(Request $request, SoyAgaciDegisiklik $degisiklik)
    {
        $request->validate([
            'karar'      => 'required|in:onaylandi,reddedildi',
            'admin_notu' => 'nullable|string|max:500',
        ]);

        if ($request->karar === 'onaylandi') {
            $this->uygula($degisiklik);
        } else {
            // Reddedilen kişi eklemede yüklenen foto varsa sil
            if ($degisiklik->tip === 'kisi_ekle' && !empty($degisiklik->veri['foto'])) {
                Storage::disk('public')->delete($degisiklik->veri['foto']);
            }
        }

        $degisiklik->update([
            'durum'          => $request->karar,
            'admin_notu'     => $request->admin_notu,
            'onaylayan_id'   => auth()->id(),
            'kararlandi_at'  => now(),
        ]);

        // TODO: kullanıcıya email bildirimi gönderilebilir

        return back()->with('success', $request->karar === 'onaylandi' ? 'Değişiklik onaylandı.' : 'Değişiklik reddedildi.');
    }

    private function uygula(SoyAgaciDegisiklik $degisiklik): void
    {
        $v = $degisiklik->veri;

        match($degisiklik->tip) {
            'kisi_ekle'    => SoyAgaciKisi::create(collect($v)->only([
                'ad', 'soyad', 'cinsiyet', 'meslek',
                'bd_gun', 'bd_ay', 'bd_yil', 'olum_yil',
                'yer', 'notlar', 'foto', 'konum_x', 'konum_y',
            ])->toArray()),

            'kisi_duzenle' => SoyAgaciKisi::find($v['id'])?->update(
                collect($v)->except('id')->only([
                    'ad', 'soyad', 'cinsiyet', 'meslek',
                    'bd_gun', 'bd_ay', 'bd_yil', 'olum_yil',
                    'yer', 'notlar', 'foto',
                ])->toArray()
            ),

            'kisi_sil'     => (function () use ($v) {
                $kisi = SoyAgaciKisi::find($v['id']);
                if ($kisi) {
                    if ($kisi->foto) Storage::disk('public')->delete($kisi->foto);
                    // Gruplardan çıkar
                    SoyAgaciGrup::all()->each(function ($g) use ($v) {
                        $yeni = array_values(array_filter($g->uye_idler, fn($id) => $id != $v['id']));
                        if (empty($yeni)) $g->delete();
                        else $g->update(['uye_idler' => $yeni]);
                    });
                    $kisi->delete();
                }
            })(),

            'iliski_ekle'  => SoyAgaciIliski::firstOrCreate([
                'kisi1_id' => $v['from'],
                'kisi2_id' => $v['to'],
                'tip'      => $v['tip'],
            ]),

            'iliski_sil'   => SoyAgaciIliski::where('kisi1_id', $v['from'])
                ->where('kisi2_id', $v['to'])
                ->where('tip', $v['tip'])
                ->delete(),

            default => null,
        };
    }
}
