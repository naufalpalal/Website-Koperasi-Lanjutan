<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // 🔹 Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 🔹 Proses login
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nip', $request->nip)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // 🔹 Cek status akun
            if ($user->status !== 'aktif') {
                // Tetap login agar bisa upload dokumen
                return view('guest.dashboard')
                       ->with('warning', 'Akun Anda belum aktif. Silakan lengkapi verifikasi.');
            }

            // 🔹 Redirect sesuai role
            switch ($user->role) {
                case 'pengurus':
                    return redirect()->route('pengurus.dashboard.index');
                case 'anggota':
                    return redirect()->route('user.dashboard.index');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'role' => 'Role tidak dikenali. Hubungi pengurus.',
                    ]);
            }
        }

        // 🔹 Jika gagal login
        return back()->withErrors([
            'nip' => 'NIP atau Password salah.',
        ])->withInput();
    }

    // 🔹 Dashboard pengurus
    public function dashboard()
    {
        return view('pengurus.index');
    }

    public function dashboardView()
    {
        // Hitung total anggota
        $totalAnggota = User::where('role', 'anggota')->count();
        return view('pengurus.dashboard.index', compact('totalAnggota'));
    }

    // 🔹 Dashboard user (anggota)
    public function dashboardUserView()
    {
        return view('user.dashboard.index');
    }
    public function dashboardnotverifikasi()
    {
        return view('guest.dashboard');
    }
    
}
