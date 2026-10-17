<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tedarikci;
use App\Models\TedarikciHareket;
use Illuminate\Http\Request;

class TedarikciController extends Controller
{
    public function index(Request $request)
    {
        $arama = $request->get('q');

        $tedarikciler = Tedarikci::query()
            ->when($arama, fn ($q) => $q->where('unvan', 'like', "%{$arama}%"))
            ->orderBy('unvan')
            ->get()
            ->map(function (Tedarikci $t) {
                $t->hesaplanan_bakiye = $t->bakiye();
                return $t;
            });

        $toplamBorc = $tedarikciler->sum('hesaplanan_bakiye');

        $vadesiYaklasanlar = TedarikciHareket::with('tedarikci')
            ->where('tur', 'borc')
            ->where('odendi_mi', false)
            ->whereNotNull('vade_tarihi')
            ->whereDate('vade_tarihi', '<=', now()->addDays(3))
            ->orderBy('vade_tarihi')
            ->get();

        return view('admin.cari.tedarikci.index', compact('tedarikciler', 'toplamBorc', 'vadesiYaklasanlar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unvan'         => 'required|string|max:150',
            'yetkili_kisi'  => 'nullable|string|max:150',
            'telefon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:150',
            'adres'         => 'nullable|string|max:500',
            'vergi_dairesi' => 'nullable|string|max:100',
            'vergi_no'      => 'nullable|string|max:50',
            'notlar'        => 'nullable|string|max:1000',
        ]);

        $tedarikci = Tedarikci::create($data);

        return redirect()->route('admin.cari.tedarikci.show', $tedarikci)->with('basari', '"' . $tedarikci->unvan . '" tedarikçi kaydı oluşturuldu.');
    }

    public function show(Tedarikci $tedarikci)
    {
        $hareketler = $tedarikci->hareketler()->get();
        $bakiye     = $tedarikci->bakiye();

        return view('admin.cari.tedarikci.show', compact('tedarikci', 'hareketler', 'bakiye'));
    }

    public function update(Request $request, Tedarikci $tedarikci)
    {
        $data = $request->validate([
            'unvan'         => 'required|string|max:150',
            'yetkili_kisi'  => 'nullable|string|max:150',
            'telefon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:150',
            'adres'         => 'nullable|string|max:500',
            'vergi_dairesi' => 'nullable|string|max:100',
            'vergi_no'      => 'nullable|string|max:50',
            'notlar'        => 'nullable|string|max:1000',
            'aktif'         => 'nullable|boolean',
        ]);
        $data['aktif'] = $request->boolean('aktif', true);

        $tedarikci->update($data);

        return back()->with('basari', 'Tedarikçi bilgileri güncellendi.');
    }

    public function destroy(Tedarikci $tedarikci)
    {
        $unvan = $tedarikci->unvan;
        $tedarikci->delete();

        return redirect()->route('admin.cari.tedarikci.index')->with('basari', '"' . $unvan . '" tedarikçi kaydı silindi.');
    }

    public function hareketEkle(Request $request, Tedarikci $tedarikci)
    {
        $data = $request->validate([
            'tur'         => 'required|in:borc,odeme',
            'tutar'       => 'required|numeric|min:0.01',
            'aciklama'    => 'required|string|max:200',
            'tarih'       => 'required|date',
            'vade_tarihi' => 'nullable|date',
        ]);

        $tedarikci->hareketler()->create($data);

        return back()->with('basari', 'Hareket kaydedildi.');
    }

    public function hareketGuncelle(Request $request, Tedarikci $tedarikci, TedarikciHareket $hareket)
    {
        abort_unless($hareket->tedarikci_id === $tedarikci->id, 404);

        if ($request->has('odendi_mi') && !$request->has('tutar')) {
            $hareket->update(['odendi_mi' => $request->boolean('odendi_mi')]);
            return back()->with('basari', 'Hareket durumu güncellendi.');
        }

        $data = $request->validate([
            'tur'         => 'required|in:borc,odeme',
            'tutar'       => 'required|numeric|min:0.01',
            'aciklama'    => 'required|string|max:200',
            'tarih'       => 'required|date',
            'vade_tarihi' => 'nullable|date',
        ]);

        $hareket->update($data);

        return back()->with('basari', 'Hareket güncellendi.');
    }

    public function hareketSil(Tedarikci $tedarikci, TedarikciHareket $hareket)
    {
        abort_unless($hareket->tedarikci_id === $tedarikci->id, 404);

        $hareket->delete();

        return back()->with('basari', 'Hareket silindi.');
    }
}
