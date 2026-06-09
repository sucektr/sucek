<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeployController extends Controller
{
    public function index()
    {
        return view('admin.deploy.index');
    }

    public function guncelle(Request $request)
    {
        $kok     = base_path();
        $php     = PHP_BINARY;
        $artisan = $kok . '/artisan';

        $adimlar = [
            'git pull'       => "cd \"{$kok}\" && git pull origin main 2>&1",
            'migrate'        => "\"{$php}\" \"{$artisan}\" migrate --force 2>&1",
            'config:cache'   => "\"{$php}\" \"{$artisan}\" config:cache 2>&1",
            'route:cache'    => "\"{$php}\" \"{$artisan}\" route:cache 2>&1",
            'view:cache'     => "\"{$php}\" \"{$artisan}\" view:cache 2>&1",
            'storage:link'   => "\"{$php}\" \"{$artisan}\" storage:link 2>&1",
        ];

        $sonuclar = [];
        foreach ($adimlar as $ad => $komut) {
            $cikti = [];
            $kod   = 0;
            exec($komut, $cikti, $kod);
            $sonuclar[] = [
                'ad'       => $ad,
                'cikti'    => implode("\n", $cikti),
                'basarili' => $kod === 0,
            ];
            // git pull başarısız olduysa sonraki adımları çalıştırma
            if ($ad === 'git pull' && $kod !== 0) {
                break;
            }
        }

        return view('admin.deploy.index', compact('sonuclar'));
    }
}
