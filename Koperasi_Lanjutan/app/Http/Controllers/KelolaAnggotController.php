<?php

// app/Http/Controllers/AnggotaController.php
namespace App\Http\Controllers;

use App\Models\Anggot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaAnggotController extends Controller
{
    public function index()
    {
        $anggota = Anggot::all();
        return view('admin.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'tgl_lahir'     => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
            'tgl_masuk'     => 'required|date',
        ]);

        Anggot::create($request->all());

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $anggota = Anggot::findOrFail($id);
        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggot::findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:255',
            'tgl_lahir'     => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
            'tgl_masuk'     => 'required|date',
        ]);

        $anggota->update($request->all());

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil diperbarui');
    }

    public function destroy($id)
    {
        $anggota = Anggot::findOrFail($id);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus');
    }
}