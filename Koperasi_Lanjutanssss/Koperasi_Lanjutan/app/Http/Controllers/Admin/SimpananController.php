<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simpanan;
use App\Models\User;
use App\Models\SimpananRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function store(Request $request)
    {
        // Ambil aturan wajib terbaru
        $rule = SimpananRules::where('type', 'wajib')
            ->where('start_date', '<=', now())
            ->orderByDesc('start_date')
            ->first();

        if (!$rule) {
            return back()->with('error', 'Aturan simpanan wajib belum ditentukan');
        }

        // Simpan transaksi dengan nominal aturan saat ini
        Simpanan::create([
            'member_id' => $request->member_id ?? Auth::id(), // untuk admin bisa set member_id manual
            'type'      => 'wajib',
            'amount'    => $rule->amount, // ← nominal tersimpan tetap (riwayat aman)
            'status'    => 'success',
            'month'     => now()->startOfMonth(),
        ]);

        return back()->with('success', 'Simpanan wajib berhasil disetor.');
    }

    public function update(Request $request, Simpanan $transaction)
    {
        $request->validate([
            'status'    => 'required|array',
            'status.*'  => 'in:success,failed,pending',
            'note'      => 'nullable|array',
            'note.*'    => 'nullable|string'
        ]);

        $simpanans = Simpanan::where('member_id', $transaction->member_id)
            ->where('month', $transaction->month)
            ->get();

        foreach ($simpanans as $simpanan) {
            $simpanan->update([
                'status' => $request->status[$simpanan->id] ?? $simpanan->status,
                'note'   => $request->note[$simpanan->id] ?? $simpanan->note,
            ]);
        }

        // Ambil ulang untuk pesan WA
        $simpanans->fresh();

        $pesan = "Halo {$transaction->member->nama}, berikut status simpanan bulan " . 
                 Carbon::parse($transaction->month)->translatedFormat('F Y') . ":\n";

        foreach ($simpanans as $simpanan) {
            $pesan .= "- {$simpanan->type}: *{$simpanan->status}*";
            if ($simpanan->note) {
                $pesan .= " (Catatan: {$simpanan->note})";
            }
            $pesan .= "\n";
        }

        // Format nomor WA
        $phone = preg_replace('/^0/', '62', $transaction->member->no_telepon);
        $url = "https://wa.me/{$phone}?text=" . urlencode($pesan);

        return redirect()->away($url);
    }

    public function generate()
    {
        $bulanSekarang = now()->format('Y-m-01');
        $bulanLalu     = now()->subMonth()->format('Y-m-01');

        $anggota = User::where('role', 'anggota')->get();

        foreach ($anggota as $a) {
            // Cari aturan wajib yang berlaku di bulan ini
            $rule = SimpananRules::where('type', 'wajib')
                ->where('start_date', '<=', $bulanSekarang)
                ->where(function ($q) use ($bulanSekarang) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', $bulanSekarang);
                })
                ->orderBy('start_date', 'desc')
                ->first();

            $amountWajib = $rule ? $rule->amount : 40000;

            // Cek simpanan wajib bulan lalu
            $wajibLalu = Simpanan::where('member_id', $a->id)
                ->where('month', $bulanLalu)
                ->where('type', 'wajib')
                ->first();

            // Jika bulan lalu failed, dobel
            if ($wajibLalu && $wajibLalu->status === 'failed') {
                $amountWajib = $amountWajib * 2;
            }

            // Generate wajib bulan ini
            Simpanan::firstOrCreate(
                [
                    'member_id' => $a->id,
                    'month'     => $bulanSekarang,
                    'type'      => 'wajib',
                ],
                [
                    'amount' => $amountWajib,
                    'status' => 'pending',
                ]
            );

            // Sukarela default
            Simpanan::firstOrCreate(
                [
                    'member_id' => $a->id,
                    'month'     => $bulanSekarang,
                    'type'      => 'sukarela',
                ],
                [
                    'amount' => 0,
                    'status' => 'pending',
                ]
            );
        }

        return back()->with('success', 'Simpanan bulan ini berhasil dibuat!');
    }

    public function history()
    {
        $riwayat = Simpanan::where('member_id', Auth::id())
            ->orderBy('month', 'desc')
            ->get();

        return view('simpanan.history', compact('riwayat'));
    }
}
