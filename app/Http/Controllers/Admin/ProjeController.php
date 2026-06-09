<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjeController extends Controller
{
    public function index()
    {
        $projeler = \App\Models\Proje::latest()->paginate(20);
        return view('admin.projeler.index', compact('projeler'));
    }

    public function create()
    {
        return view('admin.projeler.form', ['proje' => new \App\Models\Proje]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'baslik'       => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:projeler,slug',
            'kategori'     => 'required|string|max:100',
            'alt_kategori' => 'nullable|string|max:100',
            'konum'        => 'nullable|string|max:150',
            'yil'          => 'nullable|integer|min:1900|max:2100',
            'kapak_gorsel' => 'nullable|image|max:4096',
            'detaylar'     => 'nullable|string',
            'sira'         => 'nullable|integer|min:0',
            'aktif'        => 'boolean',
            'one_cikan'    => 'boolean',
        ]);

        if ($request->hasFile('kapak_gorsel')) {
            $data['kapak_gorsel'] = $request->file('kapak_gorsel')->store('projeler', 'public');
        }

        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        \App\Models\Proje::create($data);
        return redirect()->route('admin.projeler.index')->with('basari', 'Proje eklendi.');
    }

    public function show(string $id) { abort(404); }

    public function edit(\App\Models\Proje $proje)
    {
        return view('admin.projeler.form', compact('proje'));
    }

    public function update(Request $request, \App\Models\Proje $proje)
    {
        $request->validate([
            'baslik'       => 'required|string|max:200',
            'slug'         => 'required|string|max:200|unique:projeler,slug,'.$proje->id,
            'kategori'     => 'required|string|max:100',
            'alt_kategori' => 'nullable|string|max:100',
            'konum'        => 'nullable|string|max:150',
            'yil'          => 'nullable|integer|min:1900|max:2100',
            'kapak_gorsel' => 'nullable|image|max:4096',
            'detaylar'     => 'nullable|string',
            'sira'         => 'nullable|integer|min:0',
            'aktif'        => 'boolean',
            'one_cikan'    => 'boolean',
        ]);

        $data = $request->only(['baslik', 'slug', 'kategori', 'alt_kategori', 'konum', 'yil', 'detaylar', 'sira']);

        if ($request->hasFile('kapak_gorsel')) {
            if ($proje->kapak_gorsel) \Illuminate\Support\Facades\Storage::disk('public')->delete($proje->kapak_gorsel);
            $data['kapak_gorsel'] = $request->file('kapak_gorsel')->store('projeler', 'public');
        }

        $data['aktif']     = $request->boolean('aktif');
        $data['one_cikan'] = $request->boolean('one_cikan');

        $proje->update($data);
        return redirect()->route('admin.projeler.index')->with('basari', 'Proje güncellendi.');
    }

    public function destroy(\App\Models\Proje $proje)
    {
        if ($proje->kapak_gorsel) \Illuminate\Support\Facades\Storage::disk('public')->delete($proje->kapak_gorsel);
        $proje->delete();
        return back()->with('basari', 'Proje silindi.');
    }
}
