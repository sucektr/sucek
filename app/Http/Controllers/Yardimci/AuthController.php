<?php

namespace App\Http\Controllers\Yardimci;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function girisForm()
    {
        if (auth()->check() && (auth()->user()->is_yardimci || auth()->user()->is_admin)) {
            return redirect()->route('yardimci.index');
        }
        return view('yardimci.auth.giris');
    }

    public function giris(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            if (!auth()->user()->is_yardimci && !auth()->user()->is_admin) {
                auth()->logout();
                return back()->withErrors(['email' => 'Bu hesabın bu alana erişim yetkisi yok.']);
            }
            $request->session()->regenerate();
            return redirect()->route('yardimci.index');
        }

        return back()->withErrors(['email' => 'E-posta veya şifre hatalı.'])->withInput($request->only('email'));
    }

    public function cikis(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('yardimci.giris');
    }
}
