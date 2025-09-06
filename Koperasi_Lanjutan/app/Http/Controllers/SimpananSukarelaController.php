<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananSukarelaController extends Controller
{
    /**
     * Anggota mengajukan Simpanan Sukarela
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'note' => 'nullable|string',
            'month' => 'required|date',
        ]);

        $month = $request->month . '-01';

        $simpanan = Simpanan::create([
            'member_id' => Auth::id(),
            'type' => 'sukarela',
            'amount' => $request->amount,
            'note' => $request->note,
            'month' => $month,
            'status' => 'pending',
        ]);

        // Jika ingin menambah notifikasi ke pengurus, bisa di sini

        // Ubah response menjadi redirect dengan flash message
        return redirect()->back()->with('success', 'Pengajuan Simpanan Sukarela berhasil dikirim');
    }

    /**
     * Pengurus melihat semua pengajuan Simpanan Sukarela yang pending
     */
    public function indexPending()
    {
        $pending = Simpanan::where('type', 'sukarela')
            ->where('status', 'pending')
            ->with('member')  // relasi ke user anggota
            ->get();

        return response()->json($pending);
    }

    public function index()
    {
        $riwayat = Simpanan::where('member_id', Auth::id())
                    ->where('type', 'sukarela')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('user.simpanan.sukarela.index', compact('riwayat'));
    }

    /**
     * Pengurus memproses pengajuan (approve / reject)
     */
    public function process(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
            'note' => 'nullable|string',
        ]);

        $simpanan->update([
            'status' => $request->status,
            'note' => $request->note ?? $simpanan->note,
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil diproses',
            'data' => $simpanan
        ]);
    }

    /**
     * Laporan Simpanan Sukarela (sudah disetujui)
     */
    // public function laporan()
    // {
    //     $laporan = Simpanan::where('type', 'sukarela')
    //         ->where('status', 'success')
    //         ->with('member')
    //         ->get();

    //     return response()->json($laporan);
    // }
}
