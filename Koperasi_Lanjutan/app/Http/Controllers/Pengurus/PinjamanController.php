<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use App\Models\Pengurus\Angsuran;
use Illuminate\Support\Facades\DB;


class PinjamanController extends Controller
{


    // ✅ Daftar pengajuan pinjaman yang statusnya pending
    public function index()
    {

        // Ambil semua data pinjaman dengan relasi user (anggota)
        $pinjaman = Pinjaman::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengurus.pinjaman.index', compact('pinjaman'));
    }


    public function pengajuan()
    {
        $pinjaman = Pinjaman::with(['user', 'dokumen'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pengurus.pinjaman.pengajuan', compact('pinjaman'));
    }

    // ✅ Form detail & persetujuan
    public function show($id)
    {
        $pinjaman = Pinjaman::with(['user', 'dokumen'])->findOrFail($id);
        return view('pengurus.pinjaman.show', compact('pinjaman'));
    }

    // ✅ Setujui pinjaman (dengan bunga & tenor)

    public function approve(Request $request, $id)
    {
        $request->validate([
            'bunga' => 'required|numeric|min:0',
            'tenor' => 'required|integer|min:1',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);

        DB::beginTransaction();

        try {
            // Hitung bunga total dan cicilan
            $bunga_per_bulan = $pinjaman->nominal * ($request->bunga / 100);
            $total_bunga = $bunga_per_bulan * $request->tenor;
            $total_pembayaran = $pinjaman->nominal + $total_bunga;
            $angsuran_bulanan = round($total_pembayaran / $request->tenor,);

            // Update status pinjaman
            $pinjaman->update([
                'bunga' => $request->bunga,
                'tenor' => $request->tenor,
                'total_pembayaran' => $total_pembayaran,
                'status' => 'disetujui',
            ]);

            // Generate angsuran otomatis
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
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}