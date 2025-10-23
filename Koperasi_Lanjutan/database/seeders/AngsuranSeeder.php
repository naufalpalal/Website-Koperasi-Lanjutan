<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
use Carbon\Carbon;

class AngsuranSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua pinjaman yang sudah disetujui
        $pinjamanList = Pinjaman::where('status', 'disetujui')->get();

        foreach ($pinjamanList as $pinjaman) {
            $jumlahAngsuran = 10; // 10 bulan
            // Pastikan nominal ada (pakai kolom yang benar: nominal)
            $totalPinjaman = $pinjaman->nominal ?? 0;

            // hindari pembagian 0
            $jumlahPerBulan = $jumlahAngsuran > 0 ? (int) round($totalPinjaman / $jumlahAngsuran) : 0;

            for ($i = 1; $i <= $jumlahAngsuran; $i++) {
                // <-- assign variabel tanggalBayar dulu, sebelum dipakai
                // gunakan tanggal persetujuan kalau ada, kalau tidak gunakan now()
                $baseDate = $pinjaman->tanggal_persetujuan ?? $pinjaman->created_at ?? now();

                // pastikan $baseDate adalah Carbon instance
                if (! $baseDate instanceof Carbon) {
                    $baseDate = Carbon::parse($baseDate);
                }

                // tanggal jatuh tempo / tanggal_bayar simulasi untuk bulan ke-$i
                $tanggalBayar = $baseDate->copy()->addMonths($i);

                Angsuran::create([
                    'pinjaman_id'   => $pinjaman->id,
                    'bulan_ke'      => $i,
                    'jumlah_bayar'  => $jumlahPerBulan,
                    'status'        => 'belum_lunas',
                    'tanggal_bayar' => $tanggalBayar->format('Y-m-d'), // simpan sebagai string 'YYYY-MM-DD'
                    'petugas_id'    => null,
                ]);
            }
        }

        $this->command->info('✅ Angsuran (10 bulan) untuk semua pinjaman disetujui berhasil dibuat.');
    }
}
