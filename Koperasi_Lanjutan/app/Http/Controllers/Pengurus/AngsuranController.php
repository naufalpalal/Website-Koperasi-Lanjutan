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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:lunas,belum lunas',
            'diskon' => 'nullable|numeric|min:0',
        ]);

        $angsuran = Angsuran::findOrFail($id);
        $angsuran->status = $request->status;
        
        // Jika status lunas, simpan diskon (jika ada)
        if ($request->status === 'lunas') {
            $angsuran->diskon = $request->diskon ?? 0;
        } else {
            // Jika dikembalikan ke belum lunas, reset diskon
            $angsuran->diskon = 0;
        }

        $angsuran->save();

        return back()->with('success', 'Status angsuran diperbarui');
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

