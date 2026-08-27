<?php

namespace App\Http\Controllers\Pengurus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pengurus\PinjamanSetting;

class PinjamanSettingController extends Controller
{
    // Tampilkan daftar paket pinjaman
    public function index()
    {
        $pakets = PinjamanSetting::all();
        return view('pengurus.pinjaman.pengaturan.index', compact('pakets'));
    }

    public function create()
    {
        return view('pengurus.pinjaman.pengaturan.create');
    }

    // Simpan paket pinjaman baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:50',
            'nominal' => 'required|integer|min:100000',
            'tenor' => 'required|integer|min:1|max:36',
            'bunga' => 'required|numeric|min:0|max:100',
        ]);

        // Tambahkan default status
        $validated['status'] = true;

        // Simpan data paket pinjaman
        PinjamanSetting::create($validated);

        // Redirect ke halaman tabel paket pinjaman
        return redirect()
            ->route('pengurus.pinjaman.settings.index')
            ->with('success', 'Paket pinjaman berhasil ditambahkan!');
    }


    // Hidupkan / Matikan paket pinjaman
    public function toggleStatus($id)
    {
        $paket = PinjamanSetting::findOrFail($id);

        $paket->status = !$paket->status; // balik statusnya
        $paket->save();

        $pesan = $paket->status
            ? 'Paket berhasil diaktifkan.'
            : 'Paket berhasil dinonaktifkan.';

        return back()->with('success', $pesan);
    }

    public function destroy($id)
    {
        $paket = PinjamanSetting::findOrFail($id);

        $paket->delete();

        return redirect()
            ->route('pengurus.pinjaman.settings.index')
            ->with('success', 'Paket pinjaman berhasil dihapus.');
    }

}
