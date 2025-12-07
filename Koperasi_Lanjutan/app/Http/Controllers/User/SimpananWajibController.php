<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\user\SimpananWajib;
// use App\Models\MasterSimpananWajib;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pengurus;
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
    public function potongSimpanan($gaji, $tahun, $bulan, $waktuTransfer = null)
    {
        $userId = Auth::id();

        // Ambil kewajiban simpanan bulan/tahun ini
        $master = SimpananWajib::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('users_id', $userId)
            ->first();

        if (!$master) {
            return back()->with('error', 'Data kewajiban simpanan bulan ini belum ditentukan.');
        }

        if ($gaji >= $master->nilai || $waktuTransfer) {
            // Berhasil dipotong (otomatis atau manual)
            $waktuTransfer = $request->waktu_transfer ?? now(); // bisa dari input user
            SimpananWajib::create([
                'nilai' => $master->nilai,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => 'Dibayar',
                'users_id' => $userId,
                'created_at' => $waktuTransfer ? $waktuTransfer : now(),
                'updated_at' => $waktuTransfer ? $waktuTransfer : now(),
            ]);
        } else {
            // Gagal dipotong → tetap dicatat status Diajukan
            SimpananWajib::create([
                'nilai' => $master->nilai,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => 'Diajukan',
                'users_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kirim notifikasi (opsional)
            // Notification::send(Auth::user(), new SimpananNotification(
            //     "Pemotongan simpanan wajib bulan $bulan/$tahun gagal karena gaji tidak mencukupi."
            // ));
        }
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'simpanan_id' => 'required|exists:simpanan_wajib,id',
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $simpanan = SimpananWajib::find($request->simpanan_id);

        $file = $request->file('bukti_transfer');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan dengan disk public
        $file->storeAs('bukti_transfer', $filename, 'public');

        // Update database
        $simpanan->bukti_transfer = $filename;
        $simpanan->status = 'Diajukan';
        $simpanan->save();

        return back()->with('success', 'Bukti transfer berhasil diunggah.');
    }

    public function hubungiBendahara()
    {
        // Ambil pengurus dengan jabatan bendahara
        $bendahara = Pengurus::where('role', 'bendahara')->first();

        if (!$bendahara || !$bendahara->phone) {
            return back()->with('error', 'Nomor bendahara belum tersedia.');
        }

        $bendaharaPhone = $bendahara->phone;

        // Redirect ke WhatsApp
        return redirect('https://wa.me/' . $bendaharaPhone . '?text=Halo Bendahara, saya ingin konfirmasi pembayaran simpanan wajib.');
    }

}
