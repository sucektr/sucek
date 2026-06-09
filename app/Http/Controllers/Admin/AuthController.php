<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function girisForm()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.giris');
    }

    public function giris(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($request->only('email', 'password'), $request->boolean('beni_hatirla'))) {
            if (!auth()->user()->is_admin) {
                auth()->logout();
                return back()->withErrors(['email' => 'Bu hesabın yönetici yetkisi yok.']);
            }
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'E-posta veya şifre hatalı.'])->withInput($request->only('email'));
    }

    public function cikis(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.giris');
    }
}
