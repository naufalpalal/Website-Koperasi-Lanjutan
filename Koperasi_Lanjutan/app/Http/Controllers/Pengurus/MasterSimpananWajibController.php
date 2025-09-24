<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pengurus\MasterSimpananWajib;
use App\Models\Pengurus\SimpananWajib;
use Illuminate\Http\Request;

class MasterSimpananWajibController extends Controller
{
    // Update nominal simpanan wajib (Master)
    public function updateNominal(Request $request)
{
    $request->validate([
        'nilai' => 'required|integer|min:0',
    ]);

    $nilaiBaru = $request->nilai;

    // Ambil record master terakhir (jika ada)
    $master = MasterSimpananWajib::latest()->first();

    if ($master) {
        // Update master
        $master->update([
            'nilai' => $nilaiBaru,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'users_id' => auth()->id(),
        ]);
    } else {
        // Buat baru jika belum ada
        MasterSimpananWajib::create([
            'nilai' => $nilaiBaru,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'users_id' => auth()->id(),
        ]);
    }

    // Update semua SimpananWajib yang sudah digenerate tapi belum dibayar
    $simpananBulanIni = SimpananWajib::where('tahun', now()->year)
        ->where('bulan', now()->month)
        ->whereNull('status') // hanya yang belum dibayar
        ->get();

    foreach ($simpananBulanIni as $simpanan) {
        $simpanan->nilai = $nilaiBaru;
        $simpanan->save();
    }

    return redirect()->route('pengurus.simpanan.wajib_2.index')
                     ->with('success', 'Nominal simpanan wajib berhasil diperbarui dan diperbarui pada data anggota yang belum dibayar.');
}

    public function editNominal()
    {
        $master = MasterSimpananWajib::first(); // ambil data nominal saat ini
        return view('pengurus.simpanan.wajib_2.edit', compact('master'));
    }
}
