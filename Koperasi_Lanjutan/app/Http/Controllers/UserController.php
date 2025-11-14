<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\user\SimpananWajib;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
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
            ->orWhere('username', $request->nip)
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
        $simpananWajib = SimpananWajib::where('users_id', $user->id)->get();
        $simpananSukarela = SimpananSukarela::where('users_id', $user->id)->get();
        $pinjaman = Pinjaman::where('user_id', $user->id)->get();

        // =============================
        // 1. Total Simpanan Wajib User
        // =============================
        $totalSimpananWajib = SimpananWajib::where('users_id', $user->id)
            ->where('status', 'Dibayar')
            ->sum('nilai');

        // =============================
        // 2. Total Simpanan Sukarela User
        // =============================
        $totalSimpananSukarela = SimpananSukarela::where('users_id', $user->id)
            ->where('status', 'Dibayar')
            ->sum('nilai');

        // =============================
        // 3. Hitung Total Tabungan User
        // =============================
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

        // =============================
        // 4. Ambil Pinjaman Aktif User
        // =============================
        $pinjamanAktif = Pinjaman::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->orderBy('created_at', 'desc')
            ->first();

        // Jika tidak ada pinjaman aktif
        $totalPinjaman = $pinjamanAktif ? $pinjamanAktif->nominal : 0;

        // =============================
        // 5. Hitung total angsuran yang belum dibayar
        // =============================
        $totalBayar = 0;

        if ($pinjamanAktif) {
            $totalBayar = Angsuran::where('pinjaman_id', $pinjamanAktif->id)
                ->where('status', 'belum_lunas')
                ->sum('jumlah_bayar');
        }

        // =============================
        // 6. Status angsuran bulan ini
        // =============================
        $statusAngsuranBulanIni = "-";

        if ($pinjamanAktif) {
            $angsuranBulanIni = Angsuran::where('pinjaman_id', $pinjamanAktif->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->first();

            if ($angsuranBulanIni) {
                $statusAngsuranBulanIni = $angsuranBulanIni->status;
            } else {
                $statusAngsuranBulanIni = "Belum Ada Tagihan";
            }
        }


        // =============================
        // 7. Total Keseluruhan Dana
        // =============================
        $totalKeseluruhan =
            $totalSimpananWajib +
            $totalSimpananSukarela +
            $totalTabungan;

        return view('user.dashboard.index', compact(
            'user',
            'totalSimpananWajib',
            'totalSimpananSukarela',
            'totalTabungan',
            'totalKeseluruhan',
            'totalPinjaman',
            'totalBayar',
            'statusAngsuranBulanIni'
        ));
    }

    public function dashboardnotverifikasi()
    {
        return view('guest.dashboard');
    }
}
