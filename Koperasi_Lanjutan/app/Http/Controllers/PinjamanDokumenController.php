<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pinjaman;
use App\Models\IdentitasKoperasi;

class PinjamanDokumenController extends Controller
{
    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        if ($angka < 12)
            return $baca[$angka];
        elseif ($angka < 20)
            return $baca[$angka - 10] . " Belas";
        elseif ($angka < 100)
            return $this->terbilang($angka / 10) . " Puluh " . $this->terbilang($angka % 10);
        elseif ($angka < 200)
            return "Seratus " . $this->terbilang($angka - 100);
        elseif ($angka < 1000)
            return $this->terbilang($angka / 100) . " Ratus " . $this->terbilang($angka % 100);
        elseif ($angka < 2000)
            return "Seribu " . $this->terbilang($angka - 1000);
        elseif ($angka < 1000000)
            return $this->terbilang($angka / 1000) . " Ribu " . $this->terbilang($angka % 1000);
        elseif ($angka < 1000000000)
            return $this->terbilang($angka / 1000000) . " Juta " . $this->terbilang($angka % 1000000);
        else
            return $this->terbilang($angka / 1000000000) . " Milyar " . $this->terbilang($angka % 1000000000);
    }

    public function generate($tipe)
    {
        $user = auth()->user();
        $pinjaman = Pinjaman::where('user_id', $user->id)->first();
        $identitas = IdentitasKoperasi::first();

        if (!$pinjaman) {
            return back()->with('error', 'Data pinjaman tidak ditemukan.');
        }

        // ============================
        // DATA PEMOHON
        // ============================
        $pemohon = $user;

        // Jumlah pinjaman
        $jumlah = $pinjaman->nominal ?? 0;

        // Terbilang jumlah
        $jumlah_terbilang = $this->terbilang($jumlah);

        // Lama angsuran
        $lama_angsuran = $pinjaman->lama_bulan ?? 10;

        // Hitung angsuran (misal bunga 1% per bulan)
        $total = $jumlah + ($jumlah * 0.01 * $lama_angsuran);
        $angsuran = ceil($total / $lama_angsuran);

        // Terbilang angsuran
        $angsuran_terbilang = $this->terbilang($angsuran);

        // Tanggal
        $tanggal = now();
        $tanggal_permohonan = now();

        // Tentukan view berdasarkan tipe
        if ($tipe == 1) {
            $view = 'dokumen.pinjaman_1';
            $filename = 'Surat_Pernyataan_Kredit.pdf';
        } else {
            $view = 'dokumen.pinjaman_2';
            $filename = 'Surat_Permohonan_Pinjaman.pdf';
        }

        return Pdf::loadView($view, compact(
            'user',
            'pinjaman',
            'identitas',
            'tanggal',
            'tanggal_permohonan',

            // khusus dokumen 2
            'pemohon',
            'jumlah',
            'jumlah_terbilang',
            'lama_angsuran',
            'angsuran',
            'angsuran_terbilang'
        ))->download($filename);
    }
}