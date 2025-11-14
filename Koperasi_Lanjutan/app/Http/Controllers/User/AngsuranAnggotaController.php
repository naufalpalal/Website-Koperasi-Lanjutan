<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
use App\Models\PengajuanAngsuran;
use Illuminate\Support\Facades\Storage;

class AngsuranAnggotaController extends Controller
{
    /**
     * ===========================================================
     * STEP 1 — Pilih Bulan Angsuran
     * ===========================================================
     */
    public function pilihBulan(Request $request, $pinjamanId)
    {
        $request->validate([
            'angsuran_ids' => 'required|array|min:1',
            'angsuran_ids.*' => 'exists:angsuran_pinjaman,id'
        ]);


        // Pastikan pinjaman ada
        $pinjaman = Pinjaman::findOrFail($pinjamanId);

        // Ambil angsuran yang dipilih
        $angsuran = Angsuran::whereIn('id', $request->angsuran_ids)
            ->where('pinjaman_id', $pinjaman->id) // keamanan: tidak boleh bayar angsuran pinjaman orang lain
            ->get();

        if ($angsuran->isEmpty()) {
            return back()->withErrors('Angsuran tidak valid atau tidak terkait dengan pinjaman ini.');
        }

        // Total pembayaran
        $total = $angsuran->sum('jumlah_bayar');

        return view('user.pinjaman.transfer', [
            'pinjaman' => $pinjaman,
            'angsuran' => $angsuran,
            'total' => $total
        ]);
    }

    /**
     * ===========================================================
     * STEP 2 — Upload Bukti Transfer
     * ===========================================================
     */
    public function bayar(Request $request, $pinjamanId)
    {
        // Validasi input
        $validated = $request->validate([
            'angsuran_ids' => 'required|array|min:1',
            'angsuran_ids.*' => 'exists:angsuran_pinjaman,id',
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        try {
            $pinjaman = Pinjaman::findOrFail($pinjamanId);

            // Upload file bukti transfer
            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

            // Simpan ke database
            PengajuanAngsuran::create([
                'user_id' => auth()->id(),
                'pinjaman_id' => $pinjaman->id,
                'angsuran_ids' => json_encode($validated['angsuran_ids']),
                'bukti_transfer' => $path,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Bukti pembayaran berhasil dikirim.');

        } catch (\Exception $e) {

            return back()->withErrors([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->withInput();
        }
    }

}