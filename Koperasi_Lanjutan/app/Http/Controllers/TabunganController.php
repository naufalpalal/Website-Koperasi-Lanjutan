<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tabungan;

class TabunganController extends Controller
{
    /**
     * Menampilkan semua data tabungan.
     */
    public function index()
    {
        $tabungan = Tabungan::all();
        return view('user.simpanan.tabungan.index', compact('tabungan'));
    }

    /**
     * Menampilkan form tambah tabungan.
     */
    public function create()
    {
        return view('user.simpanan.tabungan.create');
    }

    /**
     * Menyimpan data tabungan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
        ]);

        Tabungan::create([
            'nilai' => $request->nilai,
            'tanggal' => $request->tanggal,
            'users_id' => auth(),
        ]);

        return redirect()->route('user.simpanan.tabungan.index')
                         ->with('success', 'Tabungan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail tabungan tertentu.
     */
    public function show(string $id)
    {
        $tabungan = Tabungan::with('user')->findOrFail($id);
        return view('user.simpanan.tabungan.show', compact('tabungan'));
    }

    /**
     * Update data tabungan.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
        ]);

        $tabungan = Tabungan::findOrFail($id);
        $tabungan->update([
            'nilai' => $request->nilai,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('user.simpanan.tabungan.index')
                         ->with('success', 'Tabungan berhasil diperbarui.');
    }

    /**
     * Hapus tabungan.
     */
    public function destroy(string $id)
    {
        $tabungan = Tabungan::findOrFail($id);
        $tabungan->delete();

        return redirect()->route('user.simpanan.tabungan.index')
                         ->with('success', 'Tabungan berhasil dihapus.');
    }
}