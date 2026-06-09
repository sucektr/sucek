<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UyeController extends Controller
{
    public function index(Request $request)
    {
        $ara = $request->get('ara');

        $uyeler = User::where('is_admin', false)
            ->when($ara, fn($q) => $q->where(function ($q) use ($ara) {
                $q->where('name', 'like', "%{$ara}%")
                  ->orWhere('email', 'like', "%{$ara}%");
            }))
            ->orderByRaw("FIELD(rol, 'sucek', 'teknik', 'standart')")
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        $sayilar = User::where('is_admin', false)
            ->selectRaw("rol, COUNT(*) as sayi")
            ->groupBy('rol')
            ->pluck('sayi', 'rol');

        return view('admin.uyeler.index', compact('uyeler', 'sayilar', 'ara'));
    }

    public function show(User $uye)
    {
        $kargoAdresi  = $uye->kargoAdresi;
        $faturaAdresi = $uye->faturaAdresi;
        $teklifler    = $uye->teklifler()->with('koleksiyon')->latest()->get();

        return view('admin.uyeler.show', compact('uye', 'kargoAdresi', 'faturaAdresi', 'teklifler'));
    }

    public function destroy(User $uye)
    {
        if ($uye->is_admin) {
            return back()->with('hata', 'Admin kullanıcısı silinemez.');
        }

        $uye->delete();

        return back()->with('basari', $uye->name . ' adlı üye silindi.');
    }

    public function rolGuncelle(Request $request, User $uye)
    {
        if ($uye->is_admin) {
            return back()->with('hata', 'Admin kullanıcısının rolü değiştirilemez.');
        }

        $request->validate(['rol' => 'required|in:standart,sucek,teknik']);

        $uye->update(['rol' => $request->rol]);

        $bilgi = User::rolBilgi($uye->rol);

        return back()->with('basari', "{$uye->name} → {$bilgi['isim']} üyeliğe güncellendi.");
    }
}
