<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        $admin = User::where('nip', $request->nip)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('web')->login($admin);
            if ($admin->role === 'pengurus') {
                return redirect()->route('admin.dashboard.index');
            } 
            // else {
            //     return redirect()->route('dashboard');
            // }
        }

        // 🔹 Coba login sebagai User
        $user = User::where('nip', $request->nip)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard('web')->login($user);
            return redirect()->route('user.dashboard.index');
        }

        // 🔹 Jika gagal semua
        return back()->withErrors([
            'nip' => 'NIP salah',
            'password' => 'Password salah',
        ])->withInput();
    }

    // Halaman dashboard
    public function dashboard()
    {
        return view('admin.index');
    }

    public function dashboardUser()
    {
        return view('dashboard');
    }

    public function dashboardView()
    {
        return view('admin.dashboard.index');
    }

    public function dashboardUserView()
    {
        return view('user.dashboard.index');
    }
}
