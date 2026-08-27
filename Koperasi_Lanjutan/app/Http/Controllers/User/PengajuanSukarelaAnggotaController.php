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

    public function libur(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'alasan' => 'nullable|string',
        ]);

        LiburSimpananSukarela::create([
            'users_id' => auth()->id(),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'alasan' => $request->alasan,
            'status' => 'diajukan',
        ]);

        return back()->with('success', 'Pengajuan libur berhasil dikirim');
    }
}