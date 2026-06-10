<?php

namespace App\Http\Controllers;

use App\Models\Adres;
use App\Models\Siparis;
use App\Models\UserUrun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HesabimController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $siparisler   = Siparis::where('user_id', $user->id)->with('kalemler')->latest()->paginate(10, ['*'], 'sp');
        $teklifler    = $user->teklifler()->with('koleksiyon')->latest()->paginate(10, ['*'], 'tp');
        $kargoAdresi  = $user->kargoAdresi;
        $faturaAdresi = $user->faturaAdresi;

        $aktifSekme = session()->pull('aktif_sekme', 'siparisler');

        $urunler = $user->isTeknik()
            ? UserUrun::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($u) {
                    $yollar = !empty($u->gorseller) ? $u->gorseller : ($u->gorsel ? [$u->gorsel] : []);
                    return [
                        'id'               => $u->id,
                        'ad'               => $u->ad,
                        'gorsel'           => !empty($yollar) ? asset('storage/' . $yollar[0]) : null,
                        'gorseller'        => array_map(fn($p) => asset('storage/' . $p), $yollar),
                        'gorseller_yollar' => $yollar,
                        'aciklama'         => $u->aciklama ?? '',
                    ];
                })
                ->values()
            : collect();

        return view('hesabim.index', compact('siparisler', 'teklifler', 'kargoAdresi', 'faturaAdresi', 'aktifSekme', 'urunler'));
    }

    public function profilGuncelle(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . auth()->id(),
            'telefon'    => 'nullable|string|max:20',
            'dogum_gun'  => 'nullable|integer|min:1|max:31',
            'dogum_ay'   => 'nullable|integer|min:1|max:12',
            'dogum_yil'  => 'nullable|integer|min:1900|max:' . date('Y'),
        ], [
            'name.required'  => 'Ad Soyad zorunludur.',
            'email.required' => 'E-posta zorunludur.',
            'email.unique'   => 'Bu e-posta başka bir hesapta kayıtlı.',
        ]);

        auth()->user()->update([
            ...$request->only('name', 'email', 'telefon', 'dogum_gun', 'dogum_ay', 'dogum_yil'),
            'sms_izni' => $request->boolean('sms_izni'),
        ]);

        return redirect()->route('hesabim.index')
            ->with('aktif_sekme', 'profil')
            ->with('profil_basari', 'Profil bilgileriniz güncellendi.');
    }

    public function sifreDegistir(Request $request)
    {
        $request->validate([
            'mevcut_sifre' => 'required',
            'yeni_sifre'   => 'required|min:8|confirmed',
        ], [
            'mevcut_sifre.required' => 'Mevcut şifrenizi girin.',
            'yeni_sifre.min'        => 'Yeni şifre en az 8 karakter olmalı.',
            'yeni_sifre.confirmed'  => 'Şifreler eşleşmiyor.',
        ]);

        if (!Hash::check($request->mevcut_sifre, auth()->user()->password)) {
            return redirect()->route('hesabim.index')
                ->with('aktif_sekme', 'profil')
                ->withErrors(['mevcut_sifre' => 'Mevcut şifreniz hatalı.']);
        }

        auth()->user()->update(['password' => Hash::make($request->yeni_sifre)]);

        return redirect()->route('hesabim.index')
            ->with('aktif_sekme', 'profil')
            ->with('sifre_basari', 'Şifreniz başarıyla değiştirildi.');
    }

    public function kargoAdresKaydet(Request $request)
    {
        $request->validate([
            'ad_soyad'    => 'required|string|max:150',
            'telefon'     => 'nullable|string|max:20',
            'adres_satiri'=> 'required|string|max:500',
            'sehir'       => 'required|string|max:100',
            'ilce'        => 'nullable|string|max:100',
            'posta_kodu'  => 'nullable|string|max:10',
        ], [
            'ad_soyad.required'    => 'Ad Soyad zorunludur.',
            'adres_satiri.required'=> 'Adres satırı zorunludur.',
            'sehir.required'       => 'Şehir zorunludur.',
        ]);

        Adres::updateOrCreate(
            ['user_id' => auth()->id(), 'tip' => 'kargo'],
            array_merge($request->only(['ad_soyad','telefon','adres_satiri','sehir','ilce','posta_kodu']), [
                'ulke' => $request->input('ulke', 'Türkiye'),
            ])
        );

        return redirect()->route('hesabim.index')
            ->with('aktif_sekme', 'adresler')
            ->with('kargo_basari', 'Kargo adresiniz kaydedildi.');
    }

    public function faturaAdresKaydet(Request $request)
    {
        $request->validate([
            'ad_soyad'    => 'required|string|max:150',
            'adres_satiri'=> 'required|string|max:500',
            'sehir'       => 'required|string|max:100',
            'ilce'        => 'nullable|string|max:100',
            'posta_kodu'  => 'nullable|string|max:10',
            'sirket_adi'  => 'nullable|string|max:200',
            'vergi_dairesi'=> 'nullable|string|max:100',
            'vergi_no'    => 'nullable|string|max:20',
        ], [
            'ad_soyad.required'    => 'Ad Soyad zorunludur.',
            'adres_satiri.required'=> 'Adres satırı zorunludur.',
            'sehir.required'       => 'Şehir zorunludur.',
        ]);

        Adres::updateOrCreate(
            ['user_id' => auth()->id(), 'tip' => 'fatura'],
            array_merge($request->only([
                'ad_soyad','adres_satiri','sehir','ilce','posta_kodu',
                'sirket_adi','vergi_dairesi','vergi_no',
            ]), ['ulke' => $request->input('ulke', 'Türkiye')])
        );

        return redirect()->route('hesabim.index')
            ->with('aktif_sekme', 'adresler')
            ->with('fatura_basari', 'Fatura bilgileriniz kaydedildi.');
    }
}
