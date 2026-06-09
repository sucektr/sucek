<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Haber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HaberController extends Controller
{
    public function index()
    {
        $haberler = Haber::latest()->paginate(20);
        $yayindaSayisi = Haber::where('yayinda', true)->count();
        $taslakSayisi  = Haber::where('yayinda', false)->count();

        return view('admin.haberler.index', compact('haberler', 'yayindaSayisi', 'taslakSayisi'));
    }

    public function create()
    {
        return view('admin.haberler.form', ['haber' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'baslik'    => 'required|string|max:255',
            'ozet'      => 'nullable|string|max:500',
            'icerik'    => 'required|string',
            'kategori'  => 'nullable|string|max:80',
            'kapak'     => 'nullable|image|max:4096',
            'yayinda'   => 'nullable|boolean',
        ]);

        $validated['slug']    = Haber::slugOlustur($validated['baslik']);
        $validated['yayinda'] = $request->boolean('yayinda');

        if ($request->hasFile('kapak')) {
            $validated['kapak'] = $request->file('kapak')->store('haberler', 'public');
        }

        Haber::create($validated);

        return redirect()->route('admin.haberler.index')->with('basari', 'Haber oluşturuldu.');
    }

    public function edit(Haber $haber)
    {
        return view('admin.haberler.form', compact('haber'));
    }

    public function update(Request $request, Haber $haber)
    {
        $validated = $request->validate([
            'baslik'    => 'required|string|max:255',
            'ozet'      => 'nullable|string|max:500',
            'icerik'    => 'required|string',
            'kategori'  => 'nullable|string|max:80',
            'kapak'     => 'nullable|image|max:4096',
            'yayinda'   => 'nullable|boolean',
            'kapak_sil' => 'nullable|boolean',
        ]);

        $validated['yayinda'] = $request->boolean('yayinda');

        if ($request->boolean('kapak_sil') && $haber->kapak) {
            Storage::disk('public')->delete($haber->kapak);
            $validated['kapak'] = null;
        }

        if ($request->hasFile('kapak')) {
            if ($haber->kapak) {
                Storage::disk('public')->delete($haber->kapak);
            }
            $validated['kapak'] = $request->file('kapak')->store('haberler', 'public');
        }

        unset($validated['kapak_sil']);
        $haber->update($validated);

        return redirect()->route('admin.haberler.index')->with('basari', 'Haber güncellendi.');
    }

    public function destroy(Haber $haber)
    {
        if ($haber->kapak) {
            Storage::disk('public')->delete($haber->kapak);
        }
        $haber->delete();

        return back()->with('basari', 'Haber silindi.');
    }

    public function yayinToggle(Haber $haber)
    {
        $haber->update(['yayinda' => !$haber->yayinda]);

        $ad = $haber->baslik;
        $mesaj = $haber->yayinda ? ($ad . ' yayina alindi.') : ($ad . ' yayindan kaldirildi.');

        return back()->with('basari', $mesaj);
    }
}
