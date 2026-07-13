<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class MaliyetTeklifController extends Controller
{
    public function index()
    {
        return view('admin.maliyet-teklif.index');
    }

    public function pozKutuphanesi()
    {
        return new Response(
            file_get_contents(public_path('assets/poz-kutuphanesi-2026-05.json')),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
