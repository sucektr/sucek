<?php

namespace App\Http\Controllers;

use App\Mail\HosgeldinMail;
use App\Models\User;
use App\Services\MailService;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function uyeOlForm()
    {
        return view('auth.uye-ol');
    }

    public function uyeOl(Request $request, RecaptchaService $recaptcha)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => 'Ad Soyad zorunludur.',
            'email.required'     => 'E-posta zorunludur.',
            'email.unique'       => 'Bu e-posta zaten kayıtlı.',
            'password.required'  => 'Şifre zorunludur.',
            'password.min'       => 'Şifre en az 8 karakter olmalı.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
        ]);

        if ($recaptcha->aktif()) {
            $token = $request->input('g-recaptcha-response', '');
            if (empty($token) || ! $recaptcha->dogrula($token, $request->ip())) {
                return back()
                    ->withInput()
                    ->withErrors(['recaptcha' => 'Lütfen "Ben robot değilim" kutucuğunu işaretleyin.']);
            }
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        app(MailService::class)->gonder($user->email, new HosgeldinMail($user), 'hosgeldin');

        Auth::login($user);
        return redirect()->intended(route('home'));
    }
}
