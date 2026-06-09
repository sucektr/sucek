<?php

namespace App\Http\Controllers;

use App\Models\Urun;
use App\Services\InstagramService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(InstagramService $instagram)
    {
        $instagramPosts = $instagram->getPosts(5);

        $slider_urunler = Urun::where('aktif', true)
            ->where(function ($q) {
                $q->where('one_cikan', true)
                  ->orWhere(function ($q2) {
                      $q2->whereNotNull('eski_fiyat')
                         ->whereColumn('eski_fiyat', '>', 'fiyat');
                  });
            })
            ->orderByDesc('one_cikan')
            ->limit(12)
            ->get();

        return view('welcome', compact('instagramPosts', 'slider_urunler'));
    }
}
