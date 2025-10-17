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

    public function toggle(Request $request)
    {
        $user = Auth::user();

        // Tidak ada logika, hanya untuk frontend
        // Data status simpanan bisa diambil dari $user dan dikirim ke frontend

        return back()->with('success', 'Status simpanan berhasil diperbarui.');
    }


}