<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\User;

class PinjamanController extends Controller
{
    /**
     * Menampilkan daftar pinjaman koperasi
     */
    public function index()
    {
        // Ambil semua pinjaman dengan relasi member
        $pinjamans = Pinjaman::with('member')->whereHas('member', function ($query) {$query->where('role', 'anggota');})->latest()->get();

        // Kirim data ke view
        return view('pengurus.pinjaman.index', compact('pinjamans'));
    }

    /**
     * Update status pinjaman (diterima/ditolak)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);
        $pinjaman->status = $request->status;
        $pinjaman->save();

        return redirect()->route('pengurus.pinjaman.index')
                         ->with('success', 'Status pinjaman berhasil diperbarui.');
    }
}