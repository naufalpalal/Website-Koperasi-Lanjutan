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
        $request->validate([
            'bunga' => 'required|numeric|min:0',
            'tenor' => 'required|integer|min:1',
        ]);

        // $pinjaman = Pinjaman::findOrFail($id);

        // DB::beginTransaction();

        // try {
        //     // Hitung bunga dan total pembayaran
        //     $bunga_per_bulan = $pinjaman->nominal * ($request->bunga / 100);
        //     $total_bunga = $bunga_per_bulan * $request->tenor;
        //     $total_pembayaran = $pinjaman->nominal + $total_bunga;
        //     $angsuran_bulanan = round($total_pembayaran / $request->tenor, 2);

        //     // Update status pinjaman & data perhitungan
 

        //     // Tentukan tanggal awal (tanggal persetujuan)
        //     $tanggalPersetujuan = now();

        //     // Generate data angsuran otomatis (dengan tanggal jatuh tempo)
        //     for ($i = 1; $i <= $request->tenor; $i++) {
        //         $jatuhTempo = $tanggalPersetujuan->copy()->addMonths($i - 1);

        //         Angsuran::create([
        //             'pinjaman_id' => $pinjaman->id,
        //             'bulan_ke' => $i,
        //             'jumlah_bayar' => $angsuran_bulanan,
        //             'tanggal_bayar' => $jatuhTempo, // agar bisa terbaca oleh periodePotongan()
        //             'status' => 'belum_lunas',
        //             'jenis_pembayaran' => 'angsuran',
        //         ]);
        //     }

        //     DB::commit();

        //     return redirect()
        //         ->back()
        //         ->with('success', '✅ Pinjaman berhasil disetujui dan angsuran otomatis dibuat.');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()
        //         ->back()
        //         ->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        // }
        // Ambil data pinjaman + relasi user (anggota)
        $pinjaman = Pinjaman::with('user')->findOrFail($id);

        $pinjaman->update([
            'bunga' => $request->bunga,
            'tenor' => $request->tenor,
            'status' => 'disetujui',
        ]);

        // Update status jadi disetujui
        $pinjaman->status = 'disetujui';
        $pinjaman->save();

        // Data tambahan untuk surat
        $data = [
            'pemohon' => $pinjaman->user,
            'jumlah' => $pinjaman->jumlah_pinjaman,
            'jumlah_terbilang' => $this->terbilang($pinjaman->jumlah_pinjaman),
            'lama_angsuran' => $pinjaman->lama_angsuran,
            'angsuran' => $pinjaman->angsuran_bulanan,
            'angsuran_terbilang' => $this->terbilang($pinjaman->angsuran_bulanan),
            'tanggal' => now(),
        ];

        // Buat PDF dari view SuratPinjaman2
        $pdf = Pdf::loadView('dokumen.SuratPinjaman2', $data);

        // Simpan ke storage (public)
        $fileName = 'verifikasi_pinjaman_' . $pinjaman->id . '.pdf';
        $filePath = 'public/dokumen_verifikasi/' . $fileName;
        Storage::put($filePath, $pdf->output());

        // Simpan path ke database
        $pinjaman->dokumen_verifikasi = 'dokumen_verifikasi/' . $fileName;
        $pinjaman->save();

        // Kembalikan response
        return redirect()->back()->with('success', 'Pinjaman disetujui dan dokumen verifikasi berhasil dibuat.');
    }


}
