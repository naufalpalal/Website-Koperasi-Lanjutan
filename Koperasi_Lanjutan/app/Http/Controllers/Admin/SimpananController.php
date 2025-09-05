<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simpanan;
use Illuminate\Http\Request;
// use App\Services\WhatsappService;
use App\Models\User;

class SimpananController extends Controller
{
    public function index()
    {
        $bulanSekarang = now()->format('Y-m-01');

        $anggota = User::where('role', 'anggota')->get();

        $transactions = Simpanan::with('member')
            ->where('month', $bulanSekarang)
            ->get()
            ->groupBy('member_id');

        return view('admin.simpanan.index', compact('transactions'));
    }

    public function edit(simpanan $transaction)
    {
        return view('admin.simpanan.edit', compact('transaction'));
    }

    public function update(Request $request, Simpanan $transaction)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
            'note' => 'nullable|string'
        ]);

        $transaction->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        $message = "Halo {$transaction->member->nama}, status simpanan bulan {$transaction->month} adalah *{$transaction->status}*.";
        if ($transaction->note) {
            $message .= "\nCatatan: {$transaction->note}";
        }

        $phone = preg_replace('/^0/', '62', $transaction->member->no_telepon);

        $url = "https://wa.me/{$phone}?text=" . urlencode($message);

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

            $amountWajib = 100000; // nominal default wajib

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
