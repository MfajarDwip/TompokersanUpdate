<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        session()->flush();

        return view('login');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        // Login untuk admin
       
        if ($request->username === 'admin' && $request->password === '!Ab13579') {
            session()->put([
                'nama' => 'Tompokersan',
                'hak_akses' => 'admin',
            ]);

            // Notifikasi sukses
            return redirect('dashboard')->with('success', 'Login berhasil sebagai admin!');
        }

        // Jika login gagal, tetap di halaman login dengan pesan error
        return back()->withErrors([
            'login' => 'Username atau password salah',
        ])->withInput($request->only('username'));
    }
}
