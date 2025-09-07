<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simpanan;
use Illuminate\Http\Request;
// use App\Services\WhatsappService;
use App\Models\User;
use Carbon\Carbon;

class SimpananController extends Controller
{
    public function index(Request $request)
    {
        // default bulan = bulan sekarang
        $bulan = $request->input('bulan', now()->format('Y-m-01'));

        // Ambil data simpanan grup per anggota & bulan
        $transactions = Simpanan::with('member')
            ->where('month', $bulan)
            ->get()
            ->groupBy('member_id');

        // daftar bulan untuk dropdown (misalnya 12 bulan terakhir)
        $bulanList = [];
        for ($i = 0; $i < 12; $i++) {
            $bulanList[] = now()->subMonths($i)->format('Y-m-01');
        }

        return view('admin.simpanan.index', compact('transactions', 'bulan', 'bulanList'));
    }

    public function edit(Simpanan $transaction)
    {
        // Ambil semua simpanan bulan yang sama (wajib & sukarela)
        $simpanans = Simpanan::where('member_id', $transaction->member_id)
            ->where('month', $transaction->month)
            ->get();

        return view('admin.simpanan.edit', compact('simpanans', 'transaction'));
    }


    public function update(Request $request, Simpanan $transaction)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:success,failed,pending',
            'note' => 'nullable|array',
            'note.*' => 'nullable|string'
        ]);

        $simpanans = Simpanan::where('member_id', $transaction->member_id)
            ->where('month', $transaction->month)
            ->get();

        foreach ($simpanans as $simpanan) {
            $simpanan->update([
                'status' => $request->status[$simpanan->id] ?? $simpanan->status,
                'note' => $request->note[$simpanan->id] ?? $simpanan->note,
            ]);
        }

        // Ambil ulang untuk pesan WA
        $simpanans->fresh();

        $pesan = "Halo {$transaction->member->nama}, berikut status simpanan bulan {$transaction->month}:\n";
        foreach ($simpanans as $simpanan) {
            $pesan .= "- {$simpanan->type}: *{$simpanan->status}*";
            if ($simpanan->note) {
                $pesan .= " (Catatan: {$simpanan->note})";
            }
            $pesan .= "\n";
        }

        $phone = preg_replace('/^0/', '62', $transaction->member->no_telepon);
        $url = "https://wa.me/{$phone}?text=" . urlencode($pesan);

        return redirect()->away($url);
    }
    public function generate()
    {
        $bulanSekarang = now()->format('Y-m-01');
        $bulanLalu = now()->subMonth()->format('Y-m');

        $anggota = User::where('role', 'anggota')->get();

        foreach ($anggota as $a) {
            // Cek simpanan wajib bulan lalu
            $wajibLalu = Simpanan::where('member_id', $a->id)
                ->where('month', $bulanLalu)
                ->where('type', 'wajib')
                ->first();

            $amountWajib = 50000; // nominal default wajib

            // Jika bulan lalu failed, dobel
            if ($wajibLalu && $wajibLalu->status === 'failed') {
                $amountWajib = $amountWajib * 2;
            }

            // Generate wajib bulan ini
            Simpanan::firstOrCreate(
                [
                    'member_id' => $a->id,
                    'month' => $bulanSekarang,
                    'type' => 'wajib',
                ],
                [
                    'amount' => $amountWajib,
                    'status' => 'pending',
                ]
            );

            // Generate sukarela bulan ini (opsional, default 0 atau tidak ada?)
            $sukarela = Simpanan::firstOrCreate(
                [
                    'member_id' => $a->id,
                    'month' => $bulanSekarang,
                    'type' => 'sukarela',
                ],
                [
                    'amount' => 0, // default, anggota isi sendiri kalau mau
                    'status' => 'pending',
                ]
            );
        }

        return back()->with('success', 'Simpanan bulan ini berhasil dibuat!');
    }


}
