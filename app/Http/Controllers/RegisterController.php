<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function uyeOlForm()
    {
        return view('auth.uye-ol');
    }

    public function uyeOl(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
        ], [
            'name.required'         => 'Ad Soyad zorunludur.',
            'email.required'        => 'E-posta zorunludur.',
            'email.unique'          => 'Bu e-posta zaten kayıtlı.',
            'password.required'     => 'Şifre zorunludur.',
            'password.min'          => 'Şifre en az 8 karakter olmalı.',
            'password.confirmed'    => 'Şifreler eşleşmiyor.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->intended(route('home'));
    }
}
