<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SimpananSukarelaController extends Controller
{
    /**
     * Anggota melihat daftar simpanan sukarela
     */
    public function index()
    {
        $userId = Auth::id();
        $sukarela = Simpanan::where('member_id', $userId)
            ->where('type', 'sukarela')
            ->orderBy('month', 'desc')
            ->get();

        return view('user.simpanan.sukarela.index', compact('sukarela'));
    }

    /**
     * Anggota mengajukan perubahan nominal simpanan sukarela
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'note'   => 'nullable|string',
        ]);

        $sukarela = Simpanan::where('id', $id)
            ->where('member_id', Auth::id())
            ->where('type', 'sukarela')
            ->firstOrFail();

        $sukarela->update([
            'amount' => $request->amount,
            'note'   => $request->note,
            'status' => 'pending', // reset status setelah ada perubahan
        ]);

        return redirect()->route('user.simpanan.sukarela.index')
            ->with('success', 'Perubahan simpanan sukarela berhasil diajukan dan menunggu persetujuan pengurus.');
    }

    /**
     * Pengurus melihat semua pengajuan perubahan yang pending
     */
    public function indexPending()
    {
        $pending = Simpanan::where('type', 'sukarela')
            ->where('status', 'pending')
            ->with('member')
            ->get();

        return view('admin.simpanan.kelola.pending', compact('pending'));
    }

    /**
     * Pengurus memproses pengajuan perubahan
     */
    public function process(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
            'note'   => 'nullable|string',
        ]);

        $simpanan->update([
            'status' => $request->status,
            'note'   => $request->note ?? $simpanan->note,
        ]);

        return redirect()->route('admin.simpanan.kelola.pending')
            ->with('success', 'Perubahan simpanan sukarela telah diproses.');
    }
}
