<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pengurus\SimpananSukarela;
use Illuminate\Support\Facades\Auth;
use App\Models\User\MasterSimpananSukarela;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengajuanSukarelaAnggotaController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        $pengajuan = MasterSimpananSukarela::where('users_id', $user->id)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();
        return view('user.simpanan.sukarela.pengajuan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|integer|min:10000',
            'tahun' => 'required|integer|min:2000|max:2100',
            'bulan' => 'required|integer|min:1|max:12',
        ]);

        $user = auth()->user();

        $cekDuplikat = MasterSimpananSukarela::where('users_id', $user->id)
            ->where('tahun', $request->tahun)
            ->where('bulan', $request->bulan)
            ->exists();

        if ($cekDuplikat) {
            return redirect()->back()
                ->with('error', 'Anda sudah mengajukan perubahan untuk periode tersebut.');
        }

        // 🚨 Cek apakah bulan yang diajukan sudah lewat (tidak boleh ajukan mundur)
        $tanggalSekarang = now();
        $periodeDiajukan = Carbon::createFromDate($request->tahun, $request->bulan, 1);

        if ($periodeDiajukan->lt($tanggalSekarang->startOfMonth())) {
            return redirect()->back()
                ->with('error', 'Periode yang diajukan sudah lewat, silakan pilih bulan berikutnya.');
        }

        MasterSimpananSukarela::create([
            'users_id' => $user->id,
            'nilai' => $request->nilai,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan simpanan sukarela berhasil diajukan.');
    }

    public function formLibur()
    {
        return view('user.simpanan.sukarela.libur');
    }

    public function libur(Request $request)
    {
        $user = Auth::user();
        $bulan = now()->month;
        $tahun = now()->year;

        // Cek apakah user sudah punya simpanan di bulan ini
        $cek = SimpananSukarela::where('users_id', $user->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        if ($cek) {
            return back()->with('error', 'Kamu sudah punya data simpanan bulan ini 🥺');
        }

        // Simpan data kosong sebagai tanda libur tanpa ubah tabel
        SimpananSukarela::create([
            'users_id' => $user->id,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nilai' => 0, // Nilai 0 menandakan libur
            'tanggal' => now(),
        ]);

        return redirect()->route('user.simpanan.sukarela.index')
            ->with('success', 'Kamu berhasil mengajukan libur simpanan bulan ini 💕');
    }

}
