<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class YardimciController extends Controller
{
    public function index()
    {
        $yardimcilar = User::where('is_yardimci', true)->latest()->get();
        return view('admin.yardimcilar.index', compact('yardimcilar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:200',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'        => $request->input('name'),
            'email'       => $request->input('email'),
            'password'    => Hash::make($request->input('password')),
            'is_admin'    => false,
            'is_yardimci' => true,
        ]);

        return back()->with('basari', 'Yardımcı hesabı oluşturuldu.');
    }

    public function sifreSifirla(Request $request, User $yardimci)
    {
        abort_unless($yardimci->is_yardimci, 404);

        $request->validate(['password' => 'required|string|min:6']);
        $yardimci->update(['password' => Hash::make($request->input('password'))]);

        return back()->with('basari', 'Şifre güncellendi.');
    }

    public function destroy(User $yardimci)
    {
        abort_unless($yardimci->is_yardimci, 404);

        $yardimci->delete();
        return back()->with('basari', 'Yardımcı hesabı silindi.');
    }
}
