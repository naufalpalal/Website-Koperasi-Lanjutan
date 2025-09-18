<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;

class PinjamanController extends Controller
{
    /**
     * Menampilkan daftar pinjaman koperasi
     */
    public function index()
    {
        // Ambil semua pinjaman dengan relasi member
        $pinjamans = Pinjaman::with('member')->latest()->get();

        // Kirim data ke view
        return view('admin.pinjaman.index', compact('pinjamans'));
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

        return redirect()->route('admin.pinjaman.index')
                         ->with('success', 'Status pinjaman berhasil diperbarui.');
    }
}