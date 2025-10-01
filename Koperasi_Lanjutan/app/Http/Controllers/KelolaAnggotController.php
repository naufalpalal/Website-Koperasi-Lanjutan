<?php

// app/Http/Controllers/AnggotaController.php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Simpanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaAnggotController extends Controller
{
    // Tampilkan semua anggota
    public function index()
    {
        $anggota = User::where('role', 'anggota')->get();
        return view('pengurus.KelolaAnggota.index', compact('anggota'));
    }

    // Tampilkan form tambah anggota
    public function create()
    {
        return view('Pengurus.KelolaAnggota.create');
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

            // simpanan
            'simpanan_pokok'        => 'required|numeric|min:10000',
            'simpanan_wajib'        => 'required|numeric|min:10000',
            'simpanan_sukarela_awal' => 'required|numeric|min:10000',
        ]);

        // buat user baru
        $user = User::create([
            'nama'          => $validated['nama'],
            'no_telepon'    => $validated['no_telepon'],
            'password'      => isset($validated['password']) ? bcrypt($validated['password']) : bcrypt('default123'),
            'nip'           => $validated['nip'] ?? null,
            'tempat_lahir'  => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'alamat_rumah'  => $validated['alamat_rumah'] ?? null,
            'role'          => 'anggota',
            'status'        => 'aktif',
        ]);

        // Simpanan Pokok
        Simpanan::create([
            'member_id' => $user->id,
            'type'      => 'pokok',
            'amount'    => $validated['simpanan_pokok'],
            'note'      => 'Simpanan pokok awal saat registrasi',
            'month'     => now()->format('Y-m-01'),
            'status'    => 'success',
        ]);

        // Simpanan Wajib
        Simpanan::create([
            'member_id' => $user->id,
            'type'      => 'wajib',
            'amount'    => $validated['simpanan_wajib'],
            'note'      => 'Simpanan wajib awal saat registrasi',
            'month'     => now()->format('Y-m-01'),
            'status'    => 'success',
        ]);

        // Simpanan Sukarela
        Simpanan::create([
            'member_id' => $user->id,
            'type'      => 'sukarela',
            'amount'    => $validated['simpanan_sukarela_awal'],
            'note'      => 'Simpanan sukarela awal saat registrasi',
            'month'     => now()->format('Y-m-01'),
            'status'    => 'success',
        ]);

        return redirect()->route('pengurus.KelolaAnggota.index')
            ->with('success', 'Anggota berhasil ditambahkan beserta simpanannya');
    }

    // Tampilkan form edit anggota
    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('pengurus.KelolaAnggota.edit', compact('anggota'));
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

            'simpanan_pokok'    => 'nullable|numeric|min:0',
            'simpanan_wajib'    => 'nullable|numeric|min:0',
            'simpanan_sukarela' => 'nullable|numeric|min:0',
        ]);

        $anggota->update($validated);

        // Update atau buat simpanan Pokok
        // if ($request->filled('simpanan_pokok')) {
        //     $simpanan = $anggota->simpananPokok()->latest('id')->first();
        //     if ($simpanan) {
        //         $simpanan->update([
        //             'amount' => $request->simpanan_pokok,
        //             'status' => 'Dibayar',
        //             'month'  => now()->format('Y-m-01'),
        //         ]);
        //     } else {
        //         $anggota->simpananPokok()->create([
        //             'amount' => $request->simpanan_pokok,
        //             'status' => 'Dibayar',
        //             'month'  => now()->format('Y-m-01'),
        //         ]);
        //     }
        // }

        // Update atau buat simpanan Wajib
        if ($request->filled('simpanan_wajib')) {
            $simpanan = $anggota->simpananWajib()->latest('id')->first();
            if ($simpanan) {
                $simpanan->update([
                    'nilai'  => $request->simpanan_wajib,
                    'status' => 'Dibayar',
                    'tahun'  => now()->year,
                    'bulan'  => now()->month,
                ]);
            } else {
                $anggota->simpananWajib()->create([
                    'nilai' => $request->simpanan_wajib,
                    'status' => 'Dibayar',
                    'tahun'  => now()->year,
                    'bulan'  => now()->month,
                ]);
            }
        }

        // Update atau buat simpanan Sukarela
        if ($request->filled('simpanan_sukarela')) {
            $simpanan = $anggota->simpananSukarela()->latest('id')->first();
            if ($simpanan) {
                $simpanan->update([
                    'nilai' => $request->simpanan_sukarela,
                    'status' => 'Dibayar',
                    'tahun'  => now()->year,
                    'bulan'  => now()->month,
                ]);
            } else {
                $anggota->simpananSukarela()->create([
                    'nilai' => $request->simpanan_sukarela,
                    'status' => 'Dibayar',
                    'tahun'  => now()->year,
                    'bulan'  => now()->month,
                ]);
            }
        }

        return redirect()->route('pengurus.KelolaAnggota.index')
            ->with('success', 'Data anggota beserta simpanan berhasil diperbarui');
    }


    // Hapus anggota
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('pengurus.KelolaAnggota.index')
            ->with('success', 'Anggota berhasil dihapus');
    }
}
