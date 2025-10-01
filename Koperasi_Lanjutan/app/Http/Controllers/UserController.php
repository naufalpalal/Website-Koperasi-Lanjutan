<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
// use App\Models\Pinjaman;
use Illuminate\Support\Facades\Hash;
// use App\Http\Controllers\PinjamanController;

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

            // Redirect sesuai role
            switch ($user->role) {
                case 'pengurus':
                    return redirect()->route('pengurus.dashboard.index'); // kalau ada dashboard khusus pengurus bisa diarahkan ke sana
                   
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


    public function dashboard()
    {
        return view('pengurus.index');
    }
   public function dashboardView()
{
    // Hitung total anggota
    $totalAnggota = User::where('role', 'anggota')->count();

    // (opsional) kalau ada tabel Pinjaman / Simpanan bisa ditambah di sini:
    // $totalPinjaman =  Pinjaman::where('status', 'aktif')->count();
    // $totalSimpanan = Simpanan::sum('jumlah');

    return view('pengurus.dashboard.index', compact('totalAnggota'));
}

    public function dashboardUserView()
    {
        return view('user.dashboard.index');
    }
    
}
