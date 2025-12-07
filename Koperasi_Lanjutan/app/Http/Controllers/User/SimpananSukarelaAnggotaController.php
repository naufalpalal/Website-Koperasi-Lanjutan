<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus\SimpananSukarela;
use Illuminate\Support\Facades\Auth;

class SimpananSukarelaAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Total saldo
        $totalSaldo = SimpananSukarela::where('users_id', $userId)
            ->sum('nilai');

        // List tahun
        $tahunList = SimpananSukarela::where('users_id', $userId)
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data utama
        $data = SimpananSukarela::where('users_id', $userId)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return view('user.simpanan.sukarela.index', [
            'totalSaldo' => $totalSaldo,
            'tahunList'  => $tahunList,
            'data'       => $data
        ]);
    }

    // ==============================
    // RIWAYAT
    // ==============================
    public function riwayat(Request $request)
    {
        $userId = Auth::id();

        $query = SimpananSukarela::where('users_id', $userId);

        // Filter bulan & tahun
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Dropdown tahun
        $tahunList = SimpananSukarela::where('users_id', $userId)
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Data riwayat
        $riwayat = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10)
            ->withQueryString(); // supaya paginasi tetap membawa filter

        return view('user.simpanan.sukarela.riwayat', [
            'riwayat'    => $riwayat,
            'tahunList'  => $tahunList,
            'totalSaldo' => SimpananSukarela::where('users_id', $userId)->sum('nilai'),
            'totalSudah' => SimpananSukarela::where('users_id', $userId)->where('status', 'sudah')->sum('nilai'),
            'totalBelum' => SimpananSukarela::where('users_id', $userId)->where('status', 'belum')->sum('nilai'),
        ]);
    }

    public function toggle(Request $request)
    {
        // Kamu bisa tambahkan logika toggle di sini,
        // misalnya update status simpanan.

        return back()->with('success', 'Status simpanan berhasil diperbarui.');
    }
}
