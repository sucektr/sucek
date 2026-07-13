<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MaliyetTeklifController extends Controller
{
    public function index()
    {
        return view('admin.maliyet-teklif.index');
    }
}
