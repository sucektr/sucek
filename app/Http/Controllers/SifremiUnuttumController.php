<?php

namespace App\Http\Controllers;

use App\Mail\SifreSifirlamaMail;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class SifremiUnuttumController extends Controller
{
    public function form()
    {
        return view('auth.sifremi-unuttum');
    }

    public function gonder(Request $request)
    {
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email'    => 'Geçerli bir e-posta adresi girin.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Password::createToken($user);
            app(MailService::class)->gonder(
                $user->email,
                new SifreSifirlamaMail($user, $token),
                'sifre_sifirlama'
            );
        }

        return back()->with('basari', 'Eğer bu e-posta kayıtlıysa şifre sıfırlama bağlantısı gönderildi.');
    }

    public function sifirlaForm(Request $request, string $token)
    {
        return view('auth.sifre-sifirla', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function sifirla(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ], [
            'email.required'        => 'E-posta zorunludur.',
            'password.required'     => 'Şifre zorunludur.',
            'password.min'          => 'Şifre en az 8 karakter olmalı.',
            'password.confirmed'    => 'Şifreler eşleşmiyor.',
        ]);

        $sonuc = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($sonuc === Password::PASSWORD_RESET) {
            return redirect()->route('giris')
                ->with('basari', 'Şifreniz başarıyla sıfırlandı. Giriş yapabilirsiniz.');
        }

        return back()->withErrors(['email' => match ($sonuc) {
            Password::INVALID_TOKEN => 'Sıfırlama bağlantısı geçersiz veya süresi dolmuş. Lütfen tekrar isteyin.',
            Password::INVALID_USER  => 'Bu e-posta ile kayıtlı üye bulunamadı.',
            default                 => 'Bir hata oluştu. Lütfen tekrar deneyin.',
        }]);
    }
}
