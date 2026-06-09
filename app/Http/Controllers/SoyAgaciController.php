<?php

namespace App\Http\Controllers;

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
        $bekleyenSayisi = SoyAgaciDegisiklik::where('user_id', auth()->id())
            ->where('durum', 'beklemede')
            ->count();

        return view('soy-agaci.index', compact('bekleyenSayisi'));
    }

    public function veri()
    {
        $kisiler   = SoyAgaciKisi::all()->map(fn($k) => [
            'id'       => $k->id,
            'ad'       => $k->ad,
            'soyad'    => $k->soyad,
            'cinsiyet' => $k->cinsiyet,
            'meslek'   => $k->meslek,
            'bd_gun'   => $k->bd_gun,
            'bd_ay'    => $k->bd_ay,
            'bd_yil'   => $k->bd_yil,
            'olum_yil' => $k->olum_yil,
            'yer'      => $k->yer,
            'notlar'   => $k->notlar,
            'foto'     => $k->foto ? Storage::url($k->foto) : null,
            'konum_x'  => $k->konum_x,
            'konum_y'  => $k->konum_y,
        ]);

        $iliskiler = SoyAgaciIliski::all()->map(fn($i) => [
            'id'   => $i->id,
            'from' => $i->kisi1_id,
            'to'   => $i->kisi2_id,
            'tip'  => $i->tip,
        ]);

        $gruplar = SoyAgaciGrup::all()->map(fn($g) => [
            'id'        => $g->id,
            'label'     => $g->label,
            'renk_hex'  => $g->renk_hex,
            'renk_bg'   => $g->renk_bg,
            'uye_idler' => $g->uye_idler,
        ]);

        $bekleyenler = SoyAgaciDegisiklik::where('user_id', auth()->id())
            ->where('durum', 'beklemede')
            ->latest()
            ->get()
            ->map(fn($d) => [
                'id'  => $d->id,
                'tip' => $d->tip,
                'tarih' => $d->created_at->diffForHumans(),
            ]);

        return response()->json(compact('kisiler', 'iliskiler', 'gruplar', 'bekleyenler'));
    }

    public function degisiklik(Request $request)
    {
        $validated = $request->validate([
            'tip'  => 'required|in:kisi_ekle,kisi_duzenle,kisi_sil,iliski_ekle,iliski_sil',
            'veri' => 'required|array',
        ]);

        $veri = $validated['veri'];

        // Fotoğraf yükleme
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('soy-agaci/kisiler', 'public');
            $veri['foto'] = $path;
        }

        SoyAgaciDegisiklik::create([
            'user_id' => auth()->id(),
            'tip'     => $validated['tip'],
            'veri'    => $veri,
            'durum'   => 'beklemede',
        ]);

        return response()->json(['basarili' => true]);
    }

    public function konumGuncelle(Request $request)
    {
        $request->validate([
            'konumlar' => 'required|array',
            'konumlar.*.id' => 'required|integer|exists:soy_agaci_kisiler,id',
            'konumlar.*.x'  => 'required|numeric',
            'konumlar.*.y'  => 'required|numeric',
        ]);

        foreach ($request->konumlar as $k) {
            SoyAgaciKisi::where('id', $k['id'])->update([
                'konum_x' => $k['x'],
                'konum_y' => $k['y'],
            ]);
        }

        return response()->json(['basarili' => true]);
    }

    public function grupKaydet(Request $request)
    {
        $request->validate([
            'id'        => 'nullable|integer',
            'label'     => 'required|string|max:100',
            'renk_hex'  => 'required|string',
            'renk_bg'   => 'required|string',
            'uye_idler' => 'required|array',
        ]);

        SoyAgaciGrup::updateOrCreate(
            ['id' => $request->id],
            $request->only(['label', 'renk_hex', 'renk_bg', 'uye_idler'])
        );

        return response()->json(['basarili' => true]);
    }

    public function grupSil(SoyAgaciGrup $grup)
    {
        $grup->delete();
        return response()->json(['basarili' => true]);
    }
}
