<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\user\SimpananWajib;
//use App\Models\MasterSimpananWajib;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class SimpananWajibController extends Controller
{
    // Menampilkan riwayat simpanan wajib anggota
    public function index()
    {
        $simpanan = SimpananWajib::where('users_id', Auth::id())
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->get();

        return view('user.simpanan.wajib.index', compact('simpanan'));
    }

    // Proses pemotongan simpanan wajib saat gaji masuk
    public function potongSimpanan($gaji, $tahun, $bulan)
    {
        $userId = Auth::id();

        // Ambil kewajiban simpanan bulan/tahun ini
        $master = MasterSimpananWajib::where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('users_id', $userId)
                    ->first();

        if(!$master){
            return back()->with('error', 'Data kewajiban simpanan bulan ini belum ditentukan.');
        }

        if($gaji >= $master->nilai){
            // Berhasil dipotong
            SimpananWajib::create([
                'nilai' => $master->nilai,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => 'Dibayar',
                'users_id' => $userId
            ]);
        } else {
            // Gagal dipotong → tetap dicatat status Diajukan
            SimpananWajib::create([
                'nilai' => $master->nilai,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => 'Diajukan', // bisa dimaknai gagal
                'users_id' => $userId
            ]);

            // Kirim notifikasi
            $user = Auth::user();
            Notification::send($user, new SimpananNotification(
                "Pemotongan simpanan wajib bulan $bulan/$tahun gagal karena gaji tidak mencukupi."
            ));
        }
    }
}
