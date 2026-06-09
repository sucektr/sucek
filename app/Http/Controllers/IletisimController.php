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

        $token = $request->input('recaptcha_token', '');
        if ($recaptcha->siteKey() && ! $recaptcha->dogrula($token, $request->ip())) {
            return back()
                ->withInput()
                ->withErrors(['recaptcha_token' => 'Güvenlik doğrulaması başarısız. Lütfen tekrar deneyin.']);
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
