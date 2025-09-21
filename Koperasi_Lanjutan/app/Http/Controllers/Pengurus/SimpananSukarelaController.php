<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\User;

class SimpananSukarelaController extends Controller
{
    public function index()
    {
        $simpanan = SimpananSukarela::with('user')->latest()->paginate(10);
        return view('pengurus.simpanan.sukarela.index', compact('simpanan'));
    }

    public function create()
    {
        $users = User::all();
        return view('pengurus.simpanan.sukarela.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nilai' => 'required|numeric',
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'status' => 'required|in:Diajukan,Dibayar,Ditarik,Libur',
        ]);

        SimpananSukarela::create($request->all());

        return redirect()->route('pengurus.simpanan.sukarela.index')
            ->with('success', 'Simpanan sukarela berhasil ditambahkan.');
    }

    public function ajukanLibur(Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        SimpananSukarela::create([
            'user_id' => auth()->id(),
            'nilai' => 0,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Diajukan',
            'alasan' => $request->alasan,
        ]);

        return redirect()->back()->with('success', 'Pengajuan libur berhasil, menunggu persetujuan.');
    }

    public function setujuiLibur($id)
    {
        $simpanan = SimpananSukarela::findOrFail($id);
        $simpanan->update(['status' => 'Libur']);

        return redirect()->back()->with('success', 'Pengajuan libur telah disetujui.');
    }

     public function pengajuanIndex()
    {
        $pengajuan = SimpananSukarela::with('user')
            ->where('status', 'Diajukan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengurus.simpanan.sukarela.pengajuan', compact('pengajuan'));
    }

    // ACC atau Tolak pengajuan
    public function accPerubahan($id, $status)
    {
        $simpanan = SimpananSukarela::findOrFail($id);

        if (!in_array($status, ['Dibayar', 'Ditolak'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $simpanan->update([
            'status' => $status,
        ]);

        return redirect()->back()->with('success', "Pengajuan simpanan berhasil diperbarui menjadi $status.");
    }
}
