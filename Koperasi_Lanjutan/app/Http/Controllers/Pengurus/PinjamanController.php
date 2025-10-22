<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    /**
     * ✅ Tampilkan semua pinjaman (semua status)
     */
    public function index()
    {
        $pinjaman = Pinjaman::with('user')
            ->where('status','disetujui')
            ->latest()
            ->get();

        return view('pengurus.pinjaman.index', compact('pinjaman'));
    }

    /**
     * ✅ Daftar pengajuan pinjaman dengan status pending
     */
    public function pengajuan()
    {
        $pinjaman = Pinjaman::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pengurus.pinjaman.pengajuan', compact('pinjaman'));
    }

    /**
     * ✅ Detail pengajuan pinjaman
     */
    public function show($id)
    {
        $pinjaman = Pinjaman::with('user')->findOrFail($id);
        return view('pengurus.pinjaman.show', compact('pinjaman'));
    }

    /**
     * ✅ Persetujuan pinjaman (hitung bunga, tenor, dan buat angsuran otomatis)
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'bunga' => 'required|numeric|min:0',
            'tenor' => 'required|integer|min:1',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);

        DB::beginTransaction();

        try {
            // Hitung bunga dan total pembayaran
            $bunga_per_bulan = $pinjaman->nominal * ($request->bunga / 100);
            $total_bunga = $bunga_per_bulan * $request->tenor;
            $total_pembayaran = $pinjaman->nominal + $total_bunga;
            $angsuran_bulanan = round($total_pembayaran / $request->tenor, 2);

            // Update status pinjaman & data perhitungan
            $pinjaman->update([
                'bunga' => $request->bunga,
                'tenor' => $request->tenor,
                'angsuran' => $angsuran_bulanan,
                'status' => 'disetujui',
            ]);

            // Generate data angsuran otomatis
            for ($i = 1; $i <= $request->tenor; $i++) {
                Angsuran::create([
                    'pinjaman_id' => $pinjaman->id,
                    'bulan_ke' => $i,
                    'jumlah_bayar' => $angsuran_bulanan,
                    'tanggal_bayar' => null,
                    'status' => 'belum_lunas',
                    'jenis_pembayaran' => 'angsuran',
                ]);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', '✅ Pinjaman berhasil disetujui dan angsuran otomatis dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
