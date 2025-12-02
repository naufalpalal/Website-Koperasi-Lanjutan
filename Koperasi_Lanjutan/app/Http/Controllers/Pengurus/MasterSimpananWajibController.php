<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pengurus\MasterSimpananWajib;
use App\Models\Pengurus\SimpananWajib;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MasterSimpananWajibController extends Controller
{
    // Update nominal simpanan wajib (Master)
   public function updateNominal(Request $request)
{
    // Validasi input
    $request->validate([
        'nilai' => 'required|integer|min:0',
        'periode_opsi' => 'required|in:hari_ini,custom',
        'periode_mulai' => 'nullable|date_format:Y-m', // hanya dipakai kalau custom
    ]);

    $nilaiBaru = $request->nilai;

    // Tentukan bulan target
    if ($request->periode_opsi === 'hari_ini') {
        $bulan = now(); // bulan berjalan
    } else {
        // Custom bulan
        if (!$request->periode_mulai) {
            return back()->withErrors(['periode_mulai' => 'Bulan harus dipilih jika opsi custom dipilih']);
        }
        $bulan = \Carbon\Carbon::createFromFormat('Y-m', $request->periode_mulai)->startOfMonth();
    }

    // Update atau buat MasterSimpananWajib untuk bulan itu
    $master = MasterSimpananWajib::where('tahun', $bulan->year)
        ->where('bulan', $bulan->month)
        ->latest()
        ->first();

    if ($master) {
        $master->update([
            'nilai' => $nilaiBaru,
            'tahun' => $bulan->year,
            'bulan' => $bulan->month,
            'pengurus_id' => Auth::guard('pengurus')->id(),
            'status'  => $master->status,
        ]);
    } else {
        MasterSimpananWajib::create([
            'nilai' => $nilaiBaru,
            'tahun' => $bulan->year,
            'bulan' => $bulan->month,
            'pengurus_id' => Auth::guard('pengurus')->id(),
            'status'  => 'Diajukan',
        ]);
    }

    // Update semua SimpananWajib yang sudah digenerate tapi belum dibayar
    $simpananBelumBayar = SimpananWajib::where('tahun', $bulan->year)
        ->where('bulan', $bulan->month)
        ->whereIn('status', ['Diajukan', 'Gagal'])
        ->get();

    foreach ($simpananBelumBayar as $simpanan) {
        $simpanan->nilai = $nilaiBaru;
        $simpanan->save();
    }

    return redirect()->route('pengurus.simpanan.wajib_2.index')
        ->with('success', 'Nominal simpanan wajib berhasil diperbarui untuk bulan ' . $bulan->translatedFormat('F Y') . '.');
}


    public function editNominal()
    {
        $master = MasterSimpananWajib::first(); // ambil data nominal saat ini
        return view('pengurus.simpanan.wajib_2.edit', compact('master'));
    }
}
