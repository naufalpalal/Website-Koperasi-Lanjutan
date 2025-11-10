<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\user\SimpananWajib;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\Pinjaman;

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
        $simpananWajib = SimpananWajib::all();
        $simpananSukarela = SimpananSukarela::all();
        $pinjaman = Pinjaman::all();

        // Hitung total simpanan wajib (hanya yang berhasil dibayar)
        $totalSimpananWajib = $simpananWajib->where('status', 'Dibayar')->sum('nilai');

        // Hitung total simpanan sukarela (hanya yang berhasil dibayar)
        $totalSimpananSukarela = $simpananSukarela->where('status', 'Dibayar')->sum('nilai');

        $totalPinjaman = 0; // Default 0 jika tidak ada

        if (method_exists($user, 'pinjaman')) {
            $totalPinjaman = $pinjaman
                ->where('status', 'disetujui') // atau status yang sesuai
                ->sum('nominal'); // sesuaikan nama kolom
        }

        // // Hitung total simpanan pokok (kalau tabelnya ada)
        // if (method_exists($user, 'simpanan')) {
        //     $totalSimpananPokok = $user->simpanan()->sum('jumlah');
        // } else {
        //     $totalSimpananPokok = 0;
        // }

        // Hitung total tabungan
        if (method_exists($user, 'tabungans')) {
        $totalMasuk = $user->tabungans()
            ->where('status', 'diterima')
            ->sum('nilai');

        $totalKeluar = $user->tabungans()
            ->where('status', 'dipotong')
            ->sum('debit');

        $totalTabungan = $totalMasuk - $totalKeluar;
        } else {
            $totalTabungan = 0;
        }

        // Total keseluruhan
        $totalKeseluruhan = $totalSimpananWajib + $totalSimpananSukarela + $totalTabungan;

        return view('user.dashboard.index', compact(
            'user',
            'totalSimpananWajib',
            'totalSimpananSukarela',
            'totalTabungan',
            'totalKeseluruhan',
            'totalPinjaman'
        ));
    }

    public function dashboardnotverifikasi()
    {
        return view('guest.dashboard');
    }
}
