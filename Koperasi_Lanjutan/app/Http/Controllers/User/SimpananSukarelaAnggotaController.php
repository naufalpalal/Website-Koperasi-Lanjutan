<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus\SimpananSukarela;
use Illuminate\Support\Facades\Auth;

class SimpananSukarelaAnggotaController extends Controller
{
    // Halaman utama: total saldo + status bulan ini
    public function index()
    {
        $user = Auth::user();

        // Total saldo hanya dari status "Dibayar"
        $totalSaldo = SimpananSukarela::where('users_id', $user->id)
            ->where('status', 'Dibayar')
            ->sum('nilai');

        // Status bulan ini (sudah setor / belum / diajukan / libur)
        $bulanIni = SimpananSukarela::where('users_id', $user->id)
            ->where('tahun', now()->year)
            ->where('bulan', now()->month)
            ->first();

        return view('user.simpanan.sukarela.index', compact('totalSaldo', 'bulanIni'));
    }

    // Riwayat simpanan sukarela user
    public function riwayat()
    {
        $user = Auth::user();

        $riwayat = SimpananSukarela::where('users_id', $user->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('user.simpanan.sukarela.riwayat', compact('riwayat'));
    }
<<<<<<< HEAD

    public function pengajuanUserIndex()
    {
    $user = Auth::user();

        // Ambil pengajuan yang pernah diajukan
        $pengajuan = SimpananSukarela::where('users_id', $user->id)
            ->where('status', 'Diajukan')
            ->orderByDesc('created_at')
            ->get();

        return view('user.simpanan.sukarela.pengajuan', compact('pengajuan'));
    }

    public function ajukanPerubahan(Request $request)
    {
        $request->validate([
            'nilai_baru' => 'required|integer|min:1000',
        ]);

    $user = Auth::user();

        SimpananSukarela::create([
            'user_id' => $user->id,
            'nilai' => $request->nilai_baru,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Diajukan',
        ]);

        return redirect()->back()->with('success', 'Pengajuan perubahan nominal berhasil dikirim.');
    }


=======
>>>>>>> f164040d339f55501385ca60f5a74b7f0ac83c7e
}
