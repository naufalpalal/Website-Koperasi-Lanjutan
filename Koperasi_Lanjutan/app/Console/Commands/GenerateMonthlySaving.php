<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Simpanan;
use App\Models\SavingTransaction;
use Carbon\Carbon;


class GenerateMonthlySaving extends Command
{
    protected $signature = 'savings:generate';
    protected $description = 'Generate monthly saving transactions for all active members';

   public function handle()
{
    $month = Carbon::now()->startOfMonth()->toDateString();

    User::where('is_simpanan_aktif', false)
        ->whereNotNull('nonaktif_hingga')
        ->whereDate('nonaktif_hingga', '<', now())
        ->update([
            'is_simpanan_aktif' => true,
            'nonaktif_hingga' => null,
        ]);

    $members = User::where('status', 'aktif')
        ->where('is_simpanan_aktif', true)
        ->get();

    $this->info("Total anggota dengan simpanan aktif: " . $members->count());
    $this->info("Proses simpanan bulan: " . $month);

    foreach ($members as $member) {
        $this->info("Cek anggota: {$member->id} - {$member->nama}");

        $exists = Simpanan::where('member_id', $member->id)
            ->where('month', $month)
            ->exists();

        if ($exists) {
            $this->warn("⚠️ Sudah ada transaksi untuk {$member->nama} bulan {$month}");
            continue;
        }

        Simpanan::create([
            'member_id' => $member->id,
            'month'     => $month,
            'type'      => 'wajib',
            'amount'    => 100000,
            'status'    => 'pending',
        ]);

        $this->info("✅ Transaksi dibuat untuk {$member->nama}");
    }

    $this->info('Selesai membuat simpanan bulanan.');
}

}
