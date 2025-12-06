<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanAngsuran;
use App\Models\Pengurus\Angsuran as AngsuranPinjaman;

class PengajuanAngsuranController extends Controller
{
    public function pengajuanList()
    {
        $pengajuan = PengajuanAngsuran::with(['user', 'pinjaman'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Pengurus.pinjaman.pengajuan_angsuran', compact('pengajuan'));
    }

    public function accPengajuan($id)
    {
        $pengajuan = PengajuanAngsuran::findOrFail($id);

        // Update status utama menjadi ACC
        $pengajuan->update(['status' => 'disetujui']);

        // Ubah status angsuran yang dipilih menjadi lunas
        // Note: angsuran_ids sudah di-cast ke array di Model
        $angsuranIds = $pengajuan->angsuran_ids;

        AngsuranPinjaman::whereIn('id', $angsuranIds)
            ->update(['status' => 'lunas', 'tanggal_bayar' => now()]);

        return back()->with('success', 'Pengajuan angsuran berhasil disetujui.');
    }

    public function tolakPengajuan($id)
    {
        $pengajuan = PengajuanAngsuran::findOrFail($id);

        $pengajuan->update([
            'status' => 'ditolak'
        ]);

        return back()->with('success', 'Pengajuan angsuran ditolak.');
    }



}
