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

    public function updateStatus(Request $request)
    {
        if (!$request->filled('angsuran_ids')) {
            return back()->with('error', 'Pilih minimal satu angsuran');
        }

        Angsuran::whereIn('id', $request->angsuran_ids)
            ->update([
                'status' => 'lunas',
            ]);

        return back()->with('success', 'Angsuran berhasil dilunasi');
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
            ->orderBy('tanggal_bayar',)
            ->get();

        return view('pengurus.pinjaman.pemotongan', [
            'angsuran' => $angsuran,
            'selectedPeriod' => $selectedPeriod,
        ]);
    }

}

