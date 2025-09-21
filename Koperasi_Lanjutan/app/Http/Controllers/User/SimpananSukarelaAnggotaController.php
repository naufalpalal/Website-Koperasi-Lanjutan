<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus\SimpananSukarela;
use Illuminate\Support\Facades\Auth;

class SimpananSukarelaAnggotaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalSaldo = SimpananSukarela::where('users_id', $user->id)
            ->where('status', 'Dibayar')
            ->sum('nilai');

        $bulanIni = SimpananSukarela::where('users_id', $user->id)
            ->where('tahun', now()->year)
            ->where('bulan', now()->month)
            ->first();


        return view('user.simpanan.sukarela.index', compact('totalSaldo', 'bulanIni'));
    }

    // Ajukan libur potongan
    public function ajukanLibur(Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        SimpananSukarela::create([
            'users_id' => $user->id,
            'nilai' => 0,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Diajukan',
        ]);

        return redirect()->route('user.simpanan.sukarela.index')
            ->with('success', 'Pengajuan libur potongan berhasil dikirim ke pengurus.');
    }

    // Riwayat setoran & penarikan
    public function riwayat()
    {
        $user = Auth::user();
        $riwayat = SimpananSukarela::where('users_id', $user->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('user.simpanan.sukarela.riwayat', compact('riwayat'));
    }

    public function pengajuanUserIndex()
    {
        $user = auth()->user();

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

        $user = auth()->user();

        SimpananSukarela::create([
            'user_id' => $user->id,
            'nilai' => $request->nilai_baru,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Diajukan',
        ]);

        return redirect()->back()->with('success', 'Pengajuan perubahan nominal berhasil dikirim.');
    }


}
