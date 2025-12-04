<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengurus\SimpananWajib;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\Tabungan;
use App\Models\Pinjaman;


class PengurusController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-pengurus');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('pengurus')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/pengurus/dashboard');
        }

        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('pengurus')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/pengurus/login');
    }
    public function dashboard()
    {
        $totalAnggota = User::where('status', 'aktif')->count();

        // Total simpanan wajib dan sukarela
        $totalSimpananWajib = SimpananWajib::sum('nilai');
        $totalSimpananSukarela = SimpananSukarela::sum('nilai');
        $totalTabungan = Tabungan::sum('nilai');
        $totalSimpanan = $totalSimpananWajib + $totalSimpananSukarela + $totalTabungan;

        // Total simpanan yang sudah dibayar
        $totalSimpananWajibDibayar = SimpananWajib::where('status', 'Dibayar')->sum('nilai');
        $totalSimpananSukarelaDibayar = SimpananSukarela::where('status', 'Dibayar')->sum('nilai');
        $totalTabunganDibayar = Tabungan::where('status', 'Dibayar')->sum('nilai');
        $totalSimpananDibayar = $totalSimpananWajibDibayar + $totalSimpananSukarelaDibayar + $totalTabunganDibayar;

        // Total pinjaman (kalau modelnya ada)
        $totalPinjaman = Pinjaman::sum('nominal');
        $totalPinjamanDibayar = Pinjaman::where('status', 'Dibayar')->sum('nominal');

        // Kirim semua variabel ke Blade
        return view('pengurus.dashboard.index', [
            'totalAnggota' => $totalAnggota,

            // Simpanan total
            'totalSimpanan' => $totalSimpanan,
            'totalSimpananDibayar' => $totalSimpananDibayar,

            // Rincian simpanan wajib & sukarela
            'totalSimpananWajib' => $totalSimpananWajib,
            'totalSimpananWajibDibayar' => $totalSimpananWajibDibayar,
            'totalSimpananSukarela' => $totalSimpananSukarela,
            'totalSimpananSukarelaDibayar' => $totalSimpananSukarelaDibayar,
            'totalTabungan' => $totalTabungan,
            'totalTabunganDibayar' => $totalTabunganDibayar,

            // Pinjaman
            'totalPinjaman' => $totalPinjaman,
            'totalPinjamanDibayar' => $totalPinjamanDibayar,
        ]);
    }

}