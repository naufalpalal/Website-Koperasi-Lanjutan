<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\User;
use Illuminate\Http\Request;

class Tabungan2Controller extends Controller
{
    /**
     * Menampilkan semua pengajuan tabungan
     */
    public function index()
    {
        // Ambil semua tabungan dengan relasi user
            $tabungans = Tabungan::with('user')->whereHas('user', function ($query) {
            $query->where('role', 'anggota'); // hanya user yang role anggota
        })
        ->latest()
        ->get();

        // Ambil semua anggota untuk ditampilkan di form
        $users = User::where('role', 'anggota')->get();
        // Sesuaikan dengan path view kamu
        return view('pengurus.simpanan.tabungan.index', compact('tabungans', 'users'));
    }

    /**
     * Setujui tabungan
     */
    public function approve($id)
{
    $tabungans = Tabungan::findOrFail($id);
    $tabungans->status = 'diterima'; // harus sesuai enum
    $tabungans->save();

    return redirect()->route('pengurus.tabungan.index')
                     ->with('success', 'Tabungan berhasil disetujui.');
}

public function reject($id)
{
    $tabungans = Tabungan::findOrFail($id);
    $tabungans->status = 'ditolak';
    $tabungans->save();

    return redirect()->route('pengurus.tabungan.index')
                     ->with('error', 'Tabungan ditolak.');
}
public function store(Request $request)
{
    $request->validate([
        'users_id' => 'required|exists:users,id',
        'tanggal' => 'required|date',
        'nilai' => 'required|numeric|min:1000',
    ]);

    Tabungan::create([
        'users_id' => $request->users_id,
        'tanggal' => $request->tanggal,
        'nilai' => $request->nilai,
        'status' => 'diterima',// langsung diterima
        'bukti_transfer' => null, // karena pengurus yang menambah
    ]);

    return redirect()->route('pengurus.tabungan.index')
                     ->with('success', 'Tabungan berhasil ditambahkan.');
}
}