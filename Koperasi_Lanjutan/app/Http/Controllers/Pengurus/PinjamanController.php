<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PinjamanController extends Controller
{
    /**
     * ✅ Tampilkan semua pinjaman (semua status)
     */
    public function index()
    {
        $pinjaman = Pinjaman::with('user')
            ->where('status', 'disetujui')
            ->latest()
            ->get();

        return view('pengurus.pinjaman.index', compact('pinjaman'));
    }

    /**
     * ✅ Daftar pengajuan pinjaman dengan status pending
     */
    public function pengajuan()
    {
        $pinjaman = Pinjaman::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pengurus.pinjaman.pengajuan', compact('pinjaman'));
    }

    /**
     * ✅ Detail pengajuan pinjaman
     */
    public function show($id)
    {
        $pinjaman = Pinjaman::with('user')->findOrFail($id);
        return view('pengurus.pinjaman.show', compact('pinjaman'));
    }

    private function terbilang($angka)
    {
        $angka = abs($angka);
        $huruf = [
            "",
            "Satu",
            "Dua",
            "Tiga",
            "Empat",
            "Lima",
            "Enam",
            "Tujuh",
            "Delapan",
            "Sembilan",
            "Sepuluh",
            "Sebelas"
        ];
        $temp = "";

        if ($angka < 12) {
            $temp = " " . $huruf[$angka];
        } elseif ($angka < 20) {
            $temp = $this->terbilang($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            $temp = $this->terbilang(intval($angka / 10)) . " Puluh" . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = " Seratus" . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = $this->terbilang(intval($angka / 100)) . " Ratus" . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = " Seribu" . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = $this->terbilang(intval($angka / 1000)) . " Ribu" . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = $this->terbilang(intval($angka / 1000000)) . " Juta" . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = $this->terbilang(intval($angka / 1000000000)) . " Miliar" . $this->terbilang($angka % 1000000000);
        } elseif ($angka < 1000000000000000) {
            $temp = $this->terbilang(intval($angka / 1000000000000)) . " Triliun" . $this->terbilang($angka % 1000000000000);
        }

        return trim($temp) . " Rupiah";
    }

    /**
     * ✅ Persetujuan pinjaman (hitung bunga, tenor, dan buat angsuran otomatis)
     */
    public function approve(Request $request, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        // Ambil dari database pinjaman (anggota sudah memilih sebelumnya)
        $bunga = $pinjaman->bunga;
        $tenor = $pinjaman->tenor;

        if ($tenor == 0 || $tenor == null) {
            return back()->with('error', 'Tenor tidak boleh kosong atau 0.');
        }

        DB::beginTransaction();

        try {

            $bunga_per_bulan = $pinjaman->nominal * ($bunga / 100);
            $total_bunga = $bunga_per_bulan * $tenor;
            $total_pembayaran = $pinjaman->nominal + $total_bunga;
            $angsuran_bulanan = round($total_pembayaran / $tenor, 2);

            // Update status pinjaman
            $pinjaman->update([
                'status' => 'disetujui',
                'angsuran_bulanan' => $angsuran_bulanan,
                'approved_at' => now(),
            ]);

            // Generate angsuran
            $tanggalPersetujuan = now();
            for ($i = 1; $i <= $tenor; $i++) {
                $jatuhTempo = $tanggalPersetujuan->copy()->addMonths($i);


                Angsuran::create([
                    'pinjaman_id' => $pinjaman->id,
                    'bulan_ke' => $i,
                    'jumlah_bayar' => $angsuran_bulanan,
                    'tanggal_bayar' => $jatuhTempo,
                    'status' => 'belum_lunas',
                    'jenis_pembayaran' => 'angsuran',
                ]);
            }

            DB::commit();
            return back()->with('success', 'Pinjaman disetujui dan angsuran dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);

        // Validasi status
        if ($pinjaman->status !== 'pending') {
            return back()->with('error', 'Pinjaman tidak dapat ditolak karena status bukan pending.');
        }

        $pinjaman->update([
            'status' => 'ditolak',
            'keterangan' => $request->input('alasan', 'Ditolak oleh pengurus'),
        ]);

        return back()->with('success', 'Pinjaman berhasil ditolak.');
    }

    public function downloadExcel()
    {
        $pinjaman = Pinjaman::with('user')->get();

        $filename = 'laporan_pinjaman_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($pinjaman) {

            // Bersihkan output buffer agar tidak merusak CSV
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $handle = fopen('php://output', 'w');

            // Tambahkan UTF-8 BOM agar excel tidak berantakan
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'Nama Anggota',
                'Nominal Pinjaman',
                'Tenor (bulan)',
                'Bunga (%)',
                'Total Bunga',
                'Angsuran Bulanan',
                'Status',
                'Tanggal Pengajuan',
                'Tanggal Disetujui'
            ]);

            // Isi data CSV
            foreach ($pinjaman as $p) {

                $totalBunga = ($p->nominal * ($p->bunga / 100)) * ($p->tenor ?? 0);

                fputcsv($handle, [
                    $p->user->nama ?? '-',
                    $p->nominal,
                    $p->tenor,
                    $p->bunga,
                    $totalBunga,
                    $p->angsuran_bulanan,
                    ucfirst($p->status),
                    $p->created_at,
                    $p->created_at?->format('d-m-Y H:i'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

}