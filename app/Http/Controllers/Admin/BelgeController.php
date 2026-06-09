<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BelgeController extends Controller
{
    public function index()
    {
        $belgeler = \App\Models\Belge::orderBy('sira')->latest()->paginate(20);
        return view('admin.belgeler.index', compact('belgeler'));
    }

    public function create()
    {
        return view('admin.belgeler.form', ['belge' => new \App\Models\Belge]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'baslik'      => 'required|string|max:200',
            'slug'        => 'required|string|max:200|unique:belgeler,slug',
            'kategori'    => 'required|string|max:100',
            'dosya'       => 'required|file|max:20480',
            'herkese_acik'=> 'boolean',
            'aktif'       => 'boolean',
            'sira'        => 'nullable|integer|min:0',
        ]);

        $dosya = $request->file('dosya');
        $data['dosya_yolu']   = $dosya->store('belgeler', 'public');
        $data['dosya_turu']   = $dosya->getClientOriginalExtension();
        $data['dosya_boyutu'] = $dosya->getSize();
        $data['herkese_acik'] = $request->boolean('herkese_acik');
        $data['aktif']        = $request->boolean('aktif');
        unset($data['dosya']);

        \App\Models\Belge::create($data);
        return redirect()->route('admin.belgeler.index')->with('basari', 'Belge eklendi.');
    }

    public function show(string $id) { abort(404); }

    public function edit(\App\Models\Belge $belge)
    {
        return view('admin.belgeler.form', compact('belge'));
    }

    public function update(Request $request, \App\Models\Belge $belge)
    {
        $data = $request->validate([
            'baslik'      => 'required|string|max:200',
            'slug'        => 'required|string|max:200|unique:belgeler,slug,'.$belge->id,
            'kategori'    => 'required|string|max:100',
            'dosya'       => 'nullable|file|max:20480',
            'herkese_acik'=> 'boolean',
            'aktif'       => 'boolean',
            'sira'        => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('dosya')) {
            if ($belge->dosya_yolu) \Illuminate\Support\Facades\Storage::disk('public')->delete($belge->dosya_yolu);
            $dosya = $request->file('dosya');
            $data['dosya_yolu']   = $dosya->store('belgeler', 'public');
            $data['dosya_turu']   = $dosya->getClientOriginalExtension();
            $data['dosya_boyutu'] = $dosya->getSize();
        }

        $data['herkese_acik'] = $request->boolean('herkese_acik');
        $data['aktif']        = $request->boolean('aktif');
        unset($data['dosya']);

        $belge->update($data);
        return redirect()->route('admin.belgeler.index')->with('basari', 'Belge güncellendi.');
    }

    public function destroy(\App\Models\Belge $belge)
    {
        if ($belge->dosya_yolu) \Illuminate\Support\Facades\Storage::disk('public')->delete($belge->dosya_yolu);
        $belge->delete();
        return back()->with('basari', 'Belge silindi.');
    }
}
