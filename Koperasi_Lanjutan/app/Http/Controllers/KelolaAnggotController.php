<?php

// app/Http/Controllers/AnggotaController.php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaAnggotController extends Controller
{
    // Tampilkan semua anggota
    public function index()
    {
        $anggota = User::all();
        return view('admin.anggota.index', compact('anggota'));
    }

    // Tampilkan form tambah anggota
    public function create()
    {
        return view('admin.anggota.create');
    }

    // Simpan anggota baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'no_telepon'    => 'required|string|max:20',
            'password'      => 'nullable',
            'nip'           => 'nullable|string|max:20',
            'tempat_lahir'  => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_rumah'  => 'nullable|string|max:255',
        ]);

        User::create($validated);

        return redirect()->route('admin.anggota.index')
                         ->with('success', 'Anggota berhasil ditambahkan');
    }

    // Tampilkan form edit anggota
    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('admin.anggota.edit', compact('anggota'));
    }

    // Update data anggota
    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'no_telepon'    => 'required|string|max:20',
            'nip'           => 'nullable|string|max:20',
            'tempat_lahir'  => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat_rumah'  => 'nullable|string|max:255',
        ]);

        $anggota->update($validated);

        return redirect()->route('admin.anggota.index')
                         ->with('success', 'Data anggota berhasil diperbarui');
    }

    // Hapus anggota
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')
                         ->with('success', 'Anggota berhasil dihapus');
    }
}