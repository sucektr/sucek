<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function girisForm()
    {
        return view('auth.giris');
    }

    public function giris(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'E-posta alanı zorunludur.',
            'email.email'       => 'Geçerli bir e-posta girin.',
            'password.required' => 'Şifre alanı zorunludur.',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('hatirla'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'E-posta veya şifre hatalı.'])->onlyInput('email');
    }

    public function cikis(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
