<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    public function index()
    {
        return view('admin.deploy.index');
    }

    public function guncelle(Request $request)
    {
        $kok = base_path();
        $sonuclar = [];

        // ── Git adımları (exec gerekli) ───────────────────────────────────
        foreach ([
            'git remote' => "cd \"{$kok}\" && git remote set-url origin https://github.com/sucektr/sucek.git 2>&1",
            'git pull'   => "cd \"{$kok}\" && git pull origin main 2>&1",
        ] as $ad => $komut) {
            $cikti = [];
            $kod   = 0;
            exec($komut, $cikti, $kod);
            $sonuclar[] = [
                'ad'       => $ad,
                'cikti'    => implode("\n", $cikti),
                'basarili' => $kod === 0,
            ];
            if ($kod !== 0) {
                return view('admin.deploy.index', compact('sonuclar'));
            }
        }

        // ── Artisan adımları (PHP içinde) ────────────────────────────────
        $artisanAdimlar = [
            'migrate'           => fn() => Artisan::call('migrate', ['--force' => true]),
            'soy-agaci:seed'    => fn() => Artisan::call('db:seed', ['--class' => 'SoyAgaciSeeder', '--force' => true]),
            'config:clear'   => fn() => Artisan::call('config:clear'),
            'config:cache'   => fn() => Artisan::call('config:cache'),
            'route:clear'    => fn() => Artisan::call('route:clear'),
            'route:cache'    => fn() => Artisan::call('route:cache'),
            'view:clear'     => fn() => Artisan::call('view:clear'),
            'view:cache'     => fn() => Artisan::call('view:cache'),
            'storage:link'   => fn() => Artisan::call('storage:link', ['--force' => true]),
        ];

        foreach ($artisanAdimlar as $ad => $calistir) {
            try {
                $kod    = $calistir();
                $cikti  = Artisan::output();
                $basarili = $kod === 0;
            } catch (\Throwable $e) {
                $cikti    = $e->getMessage();
                $basarili = false;
            }

            $sonuclar[] = [
                'ad'       => $ad,
                'cikti'    => trim($cikti),
                'basarili' => $basarili,
            ];
        }

        return view('admin.deploy.index', compact('sonuclar'));
    }
}
