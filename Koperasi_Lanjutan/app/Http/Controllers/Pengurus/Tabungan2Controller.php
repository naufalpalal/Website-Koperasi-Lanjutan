<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use Illuminate\Http\Request;

class Tabungan2Controller extends Controller
{
    /**
     * Menampilkan semua pengajuan tabungan
     */
    public function index()
    {
        // Ambil semua tabungan dengan relasi user
        $tabungans = Tabungan::with(['user' => function($q) {$q->where('role', 'anggota');}])->latest()->get();

        // Sesuaikan dengan path view kamu
        return view('pengurus.simpanan.tabungan.index', compact('tabungans'));
    }

    /**
     * Setujui tabungan
     */
    public function approve($id)
    {
        $tabungan = Tabungan::findOrFail($id);
        $tabungan->status = 'disetujui';
        $tabungan->save();

        // pake nama route yg sudah dibuat: pengurus.tabungan.index
        return redirect()->route('pengurus.tabungan.index')
                         ->with('success', 'Tabungan berhasil disetujui.');
    }

    /**
     * Tolak tabungan
     */
    public function reject($id)
    {
        $tabungan = Tabungan::findOrFail($id);
        $tabungan->status = 'ditolak';
        $tabungan->save();

        return redirect()->route('pengurus.tabungan.index')
                         ->with('error', 'Tabungan ditolak.');
    }
}