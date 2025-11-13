<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AngsuranController extends Controller
{
    // Tampilkan semua angsuran untuk pengurus
    public function index($pinjaman_id)
    {
        $pinjaman = Pinjaman::findOrFail($pinjaman_id);
        $angsuran = Angsuran::where('pinjaman_id', $pinjaman_id)->get();

        return view('pengurus.pinjaman.angsuran.index', compact('pinjaman', 'angsuran'));
    }

    public function updateStatus(Request $request, $pinjaman_id)
    {
       

        // Ambil semua ID angsuran yang dicentang
        $angsuranIds = $request->input('angsuran_ids', []);

        if (empty($angsuranIds)) {
            return redirect()
                ->back()
                ->with('warning', 'Tidak ada angsuran yang dipilih untuk diperbarui.');
        }

        // Update semua angsuran yang dipilih menjadi lunas
        Angsuran::whereIn('id', $angsuranIds)->update([
            'status' => 'lunas',
            'tanggal_bayar' => now(),
            'petugas_id' => auth()->id(),
        ]);

        // Opsi tambahan (jika semua sudah lunas, update status pinjaman)
        $pinjaman = Pinjaman::with('angsuran')->findOrFail($pinjaman_id);
        $sisa = $pinjaman->angsuran->where('status', 'belum_lunas')->count();

        if ($sisa === 0) {
            $pinjaman->update(['status' => 'lunas']);
        }

        return redirect()
            ->route('pengurus.angsuran.index', ['pinjaman_id' => $pinjaman_id])
            ->with('success', 'Status angsuran berhasil diperbarui.');
    }


    public function periodePotongan(Request $request)
    {
        $periode = $request->query('periode', now()->format('Y-m'));

        try {
            $selectedPeriod = Carbon::createFromFormat('Y-m', $periode);
        } catch (\Exception $e) {
            $selectedPeriod = now();
        }

        // Ambil semua angsuran jatuh tempo (tanggal_bayar) bulan ini & belum lunas
        $angsuran = Angsuran::with(['pinjaman.user'])
            ->whereYear('tanggal_bayar', $selectedPeriod->year)
            ->whereMonth('tanggal_bayar', $selectedPeriod->month)
            ->where('status', 'belum_lunas')
            ->orderBy('tanggal_bayar', 'asc')
            ->get();

        return view('pengurus.pinjaman.pemotongan', [
            'angsuran' => $angsuran,
            'selectedPeriod' => $selectedPeriod,
        ]);
    }

}

