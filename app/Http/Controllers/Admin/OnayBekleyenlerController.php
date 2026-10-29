<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Koleksiyon;
use App\Models\Urun;

class OnayBekleyenlerController extends Controller
{
    public function index()
    {
        $urunler = Urun::with('ekleyen')->where('onay_durumu', 'bekliyor')->latest()->get();
        $koleksiyonlar = Koleksiyon::with('ekleyen')->where('onay_durumu', 'bekliyor')->latest()->get();

        return view('admin.onay-bekleyenler.index', compact('urunler', 'koleksiyonlar'));
    }

    public function urunOnayla(Urun $urun)
    {
        $urun->update(['aktif' => true, 'onay_durumu' => 'onaylandi']);
        return back()->with('basari', $urun->ad . ' onaylandı ve yayına alındı.');
    }

    public function urunReddet(Urun $urun)
    {
        $urun->update(['onay_durumu' => 'reddedildi']);
        return back()->with('basari', $urun->ad . ' reddedildi.');
    }

    public function koleksiyonOnayla(Koleksiyon $koleksiyon)
    {
        $koleksiyon->update(['aktif' => true, 'onay_durumu' => 'onaylandi']);
        return back()->with('basari', $koleksiyon->ad . ' onaylandı ve yayına alındı.');
    }

    public function koleksiyonReddet(Koleksiyon $koleksiyon)
    {
        $koleksiyon->update(['onay_durumu' => 'reddedildi']);
        return back()->with('basari', $koleksiyon->ad . ' reddedildi.');
    }
}
