<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
use Illuminate\Http\Request;

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
        $angsuranIds = $request->input('angsuran_ids', []);

        if (count($angsuranIds) > 0) {
            foreach ($angsuranIds as $id) {
                $angsuran = Angsuran::find($id);
                if ($angsuran && $angsuran->status != 'lunas') {
                    $angsuran->update([
                        'status' => 'lunas',
                        'tanggal_bayar' => now(),
                        'petugas_id' => auth()->id(),
                    ]);
                }
            }

            return redirect()
                ->back()
                ->with('success', '✅ Status angsuran berhasil diperbarui.');
        }

        return redirect()
            ->back()
            ->with('warning', 'Tidak ada angsuran yang dicentang.');
    }


}

