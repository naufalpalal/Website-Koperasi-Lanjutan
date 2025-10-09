<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tabungan;
use Carbon\Carbon;

class TabunganController extends Controller
{
    public function index()
    {
        $tabungan = Tabungan::where('users_id', auth()->id())
                    ->orderBy('tanggal', 'desc')
                    ->paginate(10);

        return view('user.simpanan.tabungan.index', compact('tabungan'));
    }

    public function create()
    {
        return view('user.simpanan.tabungan.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nilai' => 'required|numeric|min:1000',
        'tanggal' => 'required|date',
    ]);

    // Konversi tanggal ke integer YYYYMMDD
    $tanggalInt = (int) Carbon::parse($request->tanggal)->format('Ymd');

    Tabungan::create([
        'nilai' => $request->nilai,
        'tanggal' => $request->tanggal,
        'users_id' => auth()->id(),
    ]);

    return redirect()->route('user.simpanan.tabungan.index')
                     ->with('success', 'Tabungan berhasil ditambahkan.');
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $tabungan = Tabungan::findOrFail($id);
        $tabungan->status = strtolower($request->status);
        //$tabungan->status = $request->status;
        $tabungan->save();

        return redirect()->back()->with('success', 'Status pengajuan tabungan berhasil diperbarui.');
    }
    // public function destroy(string $id)
    // {
    //     $tabungan = Tabungan::where('users_id', auth()->id())->findOrFail($id);
    //     $tabungan->delete();

    //     return redirect()->route('user.simpanan.tabungan.index')
    //                      ->with('success', 'Tabungan berhasil dihapus.');
    // }
}
