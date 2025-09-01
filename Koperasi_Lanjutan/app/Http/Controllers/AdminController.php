<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('nip', 'password');

        // 🔹 Coba login sebagai Admin
        $admin = Admin::where('nip', $request->nip)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            if ($admin->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }

        // 🔹 Coba login sebagai User
        // $user = Admin::where('nip', $request->nip)->first();
        // if ($user && Hash::check($request->password, $user->password)) {
        //     Auth::guard('Admin')->login($user);
        //     return redirect()->route('dashboard');
        // }

        // 🔹 Jika gagal semua
        return back()->withErrors([
            'nip' => 'NIP salah',
            'password' => 'Password salah',
        ])->withInput();
    }

    // Halaman dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function dashboardUser()
    {
        return view('dashboard');
    }
}
