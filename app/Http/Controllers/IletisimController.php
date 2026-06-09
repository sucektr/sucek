<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IletisimController extends Controller
{
    public function index()
    {
        return view('iletisim.index');
    }

    public function gonder(Request $request)
    {
        $validated = $request->validate([
            'ad'      => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'telefon' => 'nullable|string|max:20',
            'konu'    => 'nullable|string|max:150',
            'mesaj'   => 'required|string|min:10|max:2000',
        ]);

        \App\Models\IletisimMesaji::create($validated + [
            'kaynak' => $request->input('kaynak', 'iletisim'),
        ]);

        return back()->with('basari', 'Mesajınız başarıyla iletildi. En kısa sürede dönüş yapacağız.');
    }
}
