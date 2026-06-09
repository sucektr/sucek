<?php

namespace App\Http\Controllers;

use App\Services\RecaptchaService;
use Illuminate\Http\Request;

class IletisimController extends Controller
{
    public function index()
    {
        return view('iletisim.index');
    }

    public function gonder(Request $request, RecaptchaService $recaptcha)
    {
        $validated = $request->validate([
            'ad'      => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'telefon' => 'nullable|string|max:20',
            'konu'    => 'nullable|string|max:150',
            'mesaj'   => 'required|string|min:10|max:2000',
        ]);

        if ($recaptcha->aktif()) {
            $token = $request->input('g-recaptcha-response', '');
            if (empty($token) || ! $recaptcha->dogrula($token, $request->ip())) {
                return back()
                    ->withInput()
                    ->withErrors(['recaptcha' => 'Lütfen "Ben robot değilim" kutucuğunu işaretleyin.']);
            }
        }

        \App\Models\IletisimMesaji::create([
            'ad'      => $validated['ad'],
            'email'   => $validated['email'],
            'telefon' => $validated['telefon'] ?? null,
            'konu'    => $validated['konu'] ?? null,
            'mesaj'   => $validated['mesaj'],
            'kaynak'  => $request->input('kaynak', 'iletisim'),
        ]);

        return back()->with('basari', 'Mesajınız başarıyla iletildi. En kısa sürede dönüş yapacağız.');
    }
}
