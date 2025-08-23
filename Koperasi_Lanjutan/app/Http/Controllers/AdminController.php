<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            // Login sukses → redirect ke dashboard
            return redirect()->route('admin.dashboard');
        } else {
            // Login gagal → kembali ke login dengan error
            return redirect()->route('admin.login')->with('error', 'Invalid credentials');
        }
    }

    // Halaman dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
