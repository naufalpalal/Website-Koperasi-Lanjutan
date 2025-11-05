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

        $user = User::where('nip', $request->nip)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if ($user->status !== 'aktif') {
                return redirect()->route('guest.dashboard')->with('warning', 'Akun Anda belum aktif.');
            }

            return redirect()->route('user.dashboard.index');
        }

        return back()->withErrors([
            'nip' => 'NIP atau Password salah, atau Anda bukan anggota.',
        ])->withInput();
    }

    // 🔹 Dashboard pengurus
  

    // 🔹 Dashboard user (anggota)
    public function dashboardUserView()
    {
        $user = Auth::user();

        // Hitung total simpanan wajib
        $totalSimpananWajib = $user->simpananWajib()->sum('jumlah');

        // Hitung total simpanan sukarela
        $totalSimpananSukarela = $user->simpananSukarela()->sum('jumlah');

        // Hitung total simpanan pokok (kalau tabelnya ada)
        if (method_exists($user, 'simpanan')) {
            $totalSimpananPokok = $user->simpanan()->sum('jumlah');
        } else {
            $totalSimpananPokok = 0;
        }

        // Hitung total tabungan
        if (method_exists($user, 'tabungans')) {
            $totalTabungan = $user->tabungans()->sum('jumlah');
        } else {
            $totalTabungan = 0;
        }

        // Total keseluruhan
        $totalKeseluruhan = $totalSimpananWajib + $totalSimpananSukarela + $totalSimpananPokok + $totalTabungan;

        return view('user.dashboard.index', compact(
            'user',
            'totalSimpananWajib',
            'totalSimpananSukarela',
            'totalSimpananPokok',
            'totalTabungan',
            'totalKeseluruhan'
        ));
    }

    public function dashboardnotverifikasi()
    {
        return view('guest.dashboard');
    }
}
