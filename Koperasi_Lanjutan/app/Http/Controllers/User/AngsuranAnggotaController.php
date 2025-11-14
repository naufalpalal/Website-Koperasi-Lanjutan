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
     * STEP 1 — Halaman pilih bulan angsuran
     */
    public function pilihBulan(Request $request, $pinjamanId)
    {
        // Validasi minimal pilih 1 angsuran
        $request->validate([
            'angsuran_ids' => 'required|array|min:1'
        ]);

        $pinjaman = Pinjaman::findOrFail($pinjamanId);

        // Ambil angsuran yang dipilih
        $angsuran = Angsuran::whereIn('id', $request->angsuran_ids)->get();

        // Hitung total harus dibayar
        $total = $angsuran->sum('jumlah_bayar');

        // Tampilkan halaman transfer
        return view('user.pinjaman.transfer', [
            'pinjaman' => $pinjaman,
            'angsuran' => $angsuran,
            'total' => $total
        ]);
    }

    /**
     * STEP 2 — Upload bukti transfer
     */
    public function bayar(Request $request, $pinjamanId)
    {
        $request->validate([
            'angsuran_ids'   => 'required|array|min:1',
            'bukti_transfer' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $pinjaman = Pinjaman::findOrFail($pinjamanId);

        // Upload bukti transfer
        $file = $request->file('bukti_transfer');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('bukti_transfer', $fileName, 'public');

        // Simpan pengajuan ke tabel pengajuan_angsuran
        PengajuanAngsuran::create([
            'user_id'       => auth()->id(),
            'pinjaman_id'   => $pinjaman->id,
            'angsuran_ids'  => json_encode($request->angsuran_ids),
            'bukti_transfer' => $fileName,
            'status'        => 'pending'
        ]);

        return redirect()
            ->route('anggota.angsuran.index', $pinjaman->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu persetujuan pengurus.');
    }
}
