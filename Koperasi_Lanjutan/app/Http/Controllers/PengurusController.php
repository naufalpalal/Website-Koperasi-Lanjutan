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
            $header = ['Nama Anggota'];

            foreach (range(1, 12) as $b) {
                $header[] = "Wajib $b";
            }
            foreach (range(1, 12) as $b) {
                $header[] = "Sukarela $b";
            }
            foreach (range(1, 12) as $b) {
                $header[] = "Tabungan $b";
            }
            foreach (range(1, 12) as $b) {
                $header[] = "Pinjaman $b";
            }

            // TOTAL
            $header[] = "TOTAL SIMPANAN";
            $header[] = "TOTAL PINJAMAN";

            fputcsv($handle, $header);

            // ================= ISI CSV =================
            foreach ($users as $user) {

                $row = [$user->nama];

                $totalWajib = 0;
                $totalSukarela = 0;
                $totalTabungan = 0;
                $totalPinjaman = 0;

                // Simpanan Wajib
                foreach (range(1, 12) as $bulan) {
                    $nilai = SimpananWajib::where('users_id', $user->id)
                        ->where('tahun', $tahun)
                        ->where('bulan', $bulan)
                        ->sum('nilai');

                    $row[] = $nilai;
                    $totalWajib += $nilai;
                }

                // Simpanan Sukarela
                foreach (range(1, 12) as $bulan) {
                    $nilai = SimpananSukarela::where('users_id', $user->id)
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $bulan)
                        ->sum('nilai');

                    $row[] = $nilai;
                    $totalSukarela += $nilai;
                }

                // Tabungan
                foreach (range(1, 12) as $bulan) {
                    $nilai = Tabungan::where('users_id', $user->id)
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $bulan)
                        ->sum('nilai');

                    $row[] = $nilai;
                    $totalTabungan += $nilai;
                }

                // Pinjaman
                foreach (range(1, 12) as $bulan) {
                    $nilai = Pinjaman::where('user_id', $user->id)
                        ->whereYear('created_at', $tahun)
                        ->whereMonth('created_at', $bulan)
                        ->sum('nominal');

                    $row[] = $nilai;
                    $totalPinjaman += $nilai;
                }

                // TOTAL AKHIR
                $row[] = $totalWajib + $totalSukarela + $totalTabungan;
                $row[] = $totalPinjaman;

                fputcsv($handle, $row);
            }

            fclose($handle);

        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

}
