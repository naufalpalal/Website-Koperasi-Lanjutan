<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class SimpananSukarelaController extends Controller
{

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
     * Anggota mengajukan Simpanan Sukarela
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'note' => 'nullable|string',
            'month' => 'required|date_format:Y-m', // contoh: 2025-09
        ]);

        $month = $request->month . '-01'; // simpan dalam format DATE

        $sukarela = Simpanan::firstOrCreate(
            [
                'member_id' => Auth::id(),
                'month' => $month,
                'type' => 'sukarela',
            ],
            [
                'amount' => 0,
                'status' => 'pending',
            ]
        );

        // update data yang diisi anggota
        $sukarela->update([
            'amount' => $request->amount,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->route('user.simpanan.sukarela.index')
            ->with('success', 'Pengajuan Simpanan Sukarela berhasil diajukan dan menunggu persetujuan.');

        // kirim notifikasi ke pengurus via WA
        // $message = "📢 Pengajuan Simpanan Sukarela Baru\n\n"
        //     . "Anggota: {$sukarela->member->nama}\n"
        //     . "Bulan: {$request->month}\n"
        //     . "Jumlah: Rp " . number_format($request->amount, 0, ',', '.') . "\n"
        //     . "Catatan: {$request->note}\n\n"
        //     . "Status: Pending (menunggu persetujuan Anda)";

        // // ganti nomor pengurus (contoh: 6281234567890)
        // $phone = "6281234567890";
        // $waUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        // return redirect()->away($waUrl);
    }

    /**
     * Pengurus melihat semua pengajuan Sukarela yang pending
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
     * Pengurus memproses pengajuan (approve / reject)
     */
    public function process(Request $request, Simpanan $simpanan)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
            'note' => 'nullable|string',
        ]);

        // update status sesuai keputusan pengurus
        $simpanan->update([
            'status' => $request->status,
            'note' => $request->note ?? $simpanan->note,
        ]);

        return redirect()->route('admin.simpanan.kelola.pending')
            ->with('success', 'Pengajuan Simpanan Sukarela telah diproses.');

        // notifikasi ke anggota
        // $message = "Halo {$simpanan->member->nama},\n\n"
        //     . "Pengajuan Simpanan Sukarela bulan "
        //     . Carbon::parse($simpanan->month)->format('F Y')
        //     . " telah diproses.\n"
        //     . "Status: *{$simpanan->status}*\n";

        // if ($simpanan->note) {
        //     $message .= "Catatan: {$simpanan->note}";
        // }

        // $phone = preg_replace('/^0/', '62', $simpanan->member->no_telepon);
        // $waUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        // return redirect()->away($waUrl);
    }
}
