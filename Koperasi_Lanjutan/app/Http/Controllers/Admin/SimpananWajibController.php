<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simpanan;
use App\Models\wajib;
use Illuminate\Http\Request;
// use App\Services\WhatsappService;
use App\Models\User;
use Carbon\Carbon;


class SimpananWajibController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m-01'));

        $transactions = Simpanan::with('member')
            ->where('month', $bulan)
            ->whereHas('member', function ($q) {
                $q->where('role', 'anggota');
            })
            ->get()
            ->groupBy('member_id');

        $bulanList = [];
        for ($i = 0; $i < 12; $i++) {
            $bulanList[] = now()->subMonths($i)->format('Y-m-01');
        }

        return view('admin.simpanan.wajib.wajib', compact('transactions', 'bulan', 'bulanList'));
    }

    // public function edit(Simpanan $transaction)
    // {
    //     // Ambil semua simpanan bulan yang sama (wajib & sukarela)
    //     $simpanans = Simpanan::where('member_id', $transaction->member_id)
    //         ->where('month', $transaction->month)
    //         ->get();

    //     return view('admin.simpanan.edit', compact('simpanans', 'transaction'));
    // }
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'start_date' => 'required|date',
        ]);

        wajib::create([
            'amount' => $request->amount,
            'start_date' => Carbon::parse($request->start_date)->startOfMonth()
        ]);

        return back()->with('success', 'Aturan simpanan wajib baru berhasil ditambahkan');
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
        if (auth()->user()->role !== 'pengurus') {
            abort(403, 'Anda tidak memiliki akses untuk generate simpanan.');
        }
        $bulanSekarang = now()->format('Y-m-01');
        $bulanLalu = now()->subMonth()->format('Y-m');
        $nominal = $this->getNominalWajib($bulanSekarang);

        $anggota = User::where('role', 'anggota')->get();

        foreach ($anggota as $a) {

            $wajibLalu = Simpanan::where('member_id', $a->id)
                ->where('month', $bulanLalu)
                ->where('type', 'wajib')
                ->first();

            $amountWajib = $nominal;


            if ($wajibLalu && $wajibLalu->status === 'failed') {
                $amountWajib = $amountWajib * 2;
            }

            // Generate simpanan wajib bulan ini
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
        }

        return back()->with('success', 'Simpanan wajib bulan ini berhasil dibuat!');
    }

    public function getNominalWajib($bulan)
    {
        return wajib::where('start_date', '<=', $bulan)
            ->orderBy('start_date', 'desc')
            ->value('amount');
    }


}
