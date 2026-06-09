<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ErisimController extends Controller
{
    public function index()
    {
        $kurallar = DB::table('erisim_kurallari')
            ->orderBy('bolum_adi')
            ->get()
            ->map(function ($k) {
                $k->izin_tipler = json_decode($k->izin_tipler, true);
                return $k;
            });

        return view('admin.erisim.index', compact('kurallar'));
    }

    public function guncelle(Request $request, int $id)
    {
        $request->validate([
            'izin_tipler'   => 'array',
            'izin_tipler.*' => 'in:standart,sucek,teknik',
        ]);

        $tipler = $request->input('izin_tipler', []);

        DB::table('erisim_kurallari')->where('id', $id)->update([
            'izin_tipler' => json_encode(array_values($tipler)),
            'updated_at'  => now(),
        ]);

        $kural = DB::table('erisim_kurallari')->find($id);
        Cache::forget("erisim_kural:{$kural->bolum_slug}");

        return back()->with('basari', "'{$kural->bolum_adi}' erişim izinleri güncellendi.");
    }

    public function ekle(Request $request)
    {
        $request->validate([
            'bolum_slug' => 'required|string|unique:erisim_kurallari,bolum_slug|regex:/^[a-z0-9\-]+$/',
            'bolum_adi'  => 'required|string|max:100',
            'aciklama'   => 'nullable|string|max:200',
        ], [
            'bolum_slug.unique' => 'Bu URL zaten kullanımda.',
            'bolum_slug.regex'  => 'URL sadece küçük harf, rakam ve tire içerebilir.',
        ]);

        DB::table('erisim_kurallari')->insert([
            'bolum_slug'  => $request->bolum_slug,
            'bolum_adi'   => $request->bolum_adi,
            'aciklama'    => $request->aciklama,
            'izin_tipler' => json_encode([]),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return back()->with('basari', "'{$request->bolum_adi}' bölümü eklendi.");
    }

    public function sil(int $id)
    {
        $kural = DB::table('erisim_kurallari')->find($id);
        if (!$kural) return back()->with('hata', 'Bölüm bulunamadı.');

        DB::table('erisim_kurallari')->where('id', $id)->delete();
        Cache::forget("erisim_kural:{$kural->bolum_slug}");

        return back()->with('basari', "'{$kural->bolum_adi}' bölümü silindi.");
    }
}
