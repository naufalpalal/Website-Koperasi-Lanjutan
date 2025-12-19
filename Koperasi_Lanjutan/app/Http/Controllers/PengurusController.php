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

        $totalSimpananWajib = SimpananWajib::sum('nilai');
        $totalSimpananSukarela = SimpananSukarela::sum('nilai');
        $totalTabungan = Tabungan::sum('nilai');

        $totalSimpanan = $totalSimpananWajib + $totalSimpananSukarela + $totalTabungan;

        $totalPinjaman = Pinjaman::sum('nominal');
        $totalPinjamanDibayar = Pinjaman::where('status', 'dibayar')->sum('nominal');
        $totalSimpananWajibDibayar = SimpananWajib::where('status', 'dibayar')->sum('nilai');
        $totalSimpananSukarelaDibayar = SimpananSukarela::where('status', 'dibayar')->sum('nilai');
        $totalTabunganDibayar = Tabungan::where('status', 'dibayar')->sum('nilai');
        return view('pengurus.dashboard.index', compact(
            'totalAnggota',
            'totalSimpanan',
            'totalSimpananWajib',
            'totalSimpananSukarela',
            'totalTabungan',
            'totalPinjaman',
            'totalPinjamanDibayar',
            'totalSimpananWajibDibayar',
            'totalSimpananSukarelaDibayar',
            'totalTabunganDibayar'
        ));
    }

    // ================= DOWNLOAD CSV BULANAN =================
    public function downloadCsvBulanan(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $users = User::where('status', 'aktif')->get();

        $filename = "laporan_keuangan_bulanan_$tahun.csv";

        return response()->streamDownload(function () use ($users, $tahun) {

            $handle = fopen('php://output', 'w');

            // ================= HEADER CSV =================
            fputcsv($handle, [
                'Nama Anggota',
                'Tahun',
                'Bulan',
                'Simpanan Wajib',
                'Simpanan Sukarela',
                'Tabungan',
                'Pinjaman',
                'TOTAL'
            ]);

            // ================= ISI CSV =================
            foreach ($users as $user) {

                foreach (range(1, 12) as $bulan) {

                    // Simpanan Wajib
                    $wajib = SimpananWajib::where('users_id', $user->id)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->sum('nilai');

                    // Simpanan Sukarela
                    $sukarela = SimpananSukarela::where('users_id', $user->id)
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $bulan)
                        ->sum('nilai');

                    // Tabungan
                    $tabungan = Tabungan::where('users_id', $user->id)
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $bulan)
                        ->sum('nilai');

                    // Pinjaman
                    $pinjaman = Pinjaman::where('user_id', $user->id)
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $bulan)
                        ->sum('nominal');

                    // TOTAL (tanpa pinjaman)
                    $total = $wajib + $sukarela + $tabungan;

                    // Skip baris kosong (biar CSV bersih)
                    if ($total == 0 && $pinjaman == 0) {
                        continue;
                    }

                    fputcsv($handle, [
                        $user->nama,
                        $tahun,
                        \Carbon\Carbon::create()->month($bulan)->translatedFormat('F'),
                        $wajib,
                        $sukarela,
                        $tabungan,
                        $pinjaman,
                        $total
                    ]);
                }
            }

            fclose($handle);

        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

}
