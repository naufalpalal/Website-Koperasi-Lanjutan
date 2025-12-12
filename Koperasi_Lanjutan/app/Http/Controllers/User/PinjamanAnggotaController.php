<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\PengajuanAngsuran;
use App\Models\Pengurus\Angsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Pengurus\PinjamanSetting as PaketPinjaman;

class PinjamanAnggotaController extends Controller
{
    /* ============================================================
       HELPER: Konversi nama bulan Indonesia → format Carbon
    ============================================================ */
    private function convertBulan($bulanTahun)
    {
        if (!$bulanTahun || !str_contains($bulanTahun, ' '))
            return null;

        $map = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        [$nama, $tahun] = explode(' ', $bulanTahun);
        return ($map[$nama] ?? null) . " " . $tahun;
    }


    /* ============================================================
       HELPER: Hitung masa keanggotaan user (bulan)
    ============================================================ */
    private function hitungMasaKeanggotaan($user)
    {
        if (!$user->bulan_masuk)
            return 0;

        $converted = $this->convertBulan($user->bulan_masuk);
        if (!$converted)
            return 0;

        $masuk = Carbon::parse($converted);
        return $masuk->diffInMonths(now());
    }


    /* ============================================================
       HALAMAN INDEX / CREATE
    ============================================================ */
    public function index()
    {
        $user = Auth::user();

        // ============================================
        // VALIDASI & PARSING KOLOM 'bulan_masuk'
        // ============================================
        $bulanMasuk = strtolower(trim($user->bulan_masuk));
        // contoh data: "Mei 2023"

        // Normalisasi spasi ganda
        $bulanMasuk = preg_replace('/\s+/', ' ', $bulanMasuk);

        // Pecah menjadi dua bagian
        $parts = explode(' ', $bulanMasuk);

        if (count($parts) !== 2) {
            throw new \Exception("Format bulan_masuk tidak valid. Gunakan format: 'Mei 2023'. Nilai sekarang: {$bulanMasuk}");
        }

        [$bulanIndo, $tahun] = $parts;

        // Map bulan
        $monthMap = [
            'januari' => 'January',
            'februari' => 'February',
            'maret' => 'March',
            'april' => 'April',
            'mei' => 'May',
            'juni' => 'June',
            'juli' => 'July',
            'agustus' => 'August',
            'september' => 'September',
            'oktober' => 'October',
            'november' => 'November',
            'desember' => 'December',
        ];

        if (!array_key_exists($bulanIndo, $monthMap)) {
            throw new \Exception("Nama bulan tidak dikenali: {$bulanIndo}");
        }

        // Buat tanggal Carbon valid
        $tanggalGabung = Carbon::parse("1 {$monthMap[$bulanIndo]} {$tahun}");

        // ============================================
        // HITUNG LAMA KEANGGOTAAN
        // ============================================
        $lamaBulan = (int) $tanggalGabung->diffInMonths(now());
        $bolehPinjam = $lamaBulan >= 6;
        $sisaBulan = max(0, 6 - $lamaBulan);

        // ============================================
        // JIKA BELUM 6 BULAN → KOSONGKAN PAKET
        // ============================================
        $paketPinjaman = $bolehPinjam
            ? PaketPinjaman::where('status', 1)->orderBy('tenor', 'ASC')->get()
            : collect([]); // kirim array kosong supaya tidak muncul paket

        return view('user.pinjaman.index', compact(
            'paketPinjaman',
            'bolehPinjam',
            'sisaBulan'
        ));
    }





    /* ============================================================
       STORE PENGAJUAN PINJAMAN
    ============================================================ */
    public function store(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // Cek masa keanggotaan 6 bulan
        $masa = $this->hitungMasaKeanggotaan($user);
        if ($masa < 6) {
            $sisa = 6 - $masa;
            return back()->with('error', "Belum 6 bulan jadi anggota. Sisa $sisa bulan.");
        }

        // Cek pinjaman sebelumnya
        $lama = Pinjaman::where('user_id', $userId)->latest()->first();

        if ($lama) {

            if ($lama->status === 'pending')
                return back()->with('error', 'Masih ada pinjaman yang pending.');

            if ($lama->status === 'disetujui') {
                $angsuran = Angsuran::where('pinjaman_id', $lama->id)->get();

                $belum = $angsuran->contains(fn($a) => strtolower($a->status) !== 'lunas');

                if ($belum)
                    return back()->with('error', 'Masih punya angsuran yang belum lunas.');
            }
        }

        // Validasi input
        $request->validate([
            'nominal' => 'required|numeric|min:100000',
            'tenor' => 'required|integer|min:1',
            'bunga' => 'required|numeric|min:0',
        ]);

        // Simpan pengajuan
        Pinjaman::create([
            'user_id' => $userId,
            'nominal' => $request->nominal,
            'tenor' => $request->tenor,
            'bunga' => $request->bunga,
            'status' => 'pending'
        ]);

        return redirect()->route('user.pinjaman.create')
            ->with('success', 'Pengajuan berhasil dikirim!');
    }


    /* ============================================================
       UPLOAD DOKUMEN (2 file PDF)
    ============================================================ */
    public function upload(Request $request, $id)
    {
        $request->validate([
            'dokumen_pinjaman.*' => 'required|mimes:pdf|max:2048',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);
        $user = auth()->user();

        if (!$request->hasFile('dokumen_pinjaman'))
            return back()->with('error', 'Tidak ada file diupload.');

        $files = $request->file('dokumen_pinjaman');

        if (count($files) < 2)
            return back()->with('error', 'Wajib upload 2 dokumen PDF.');

        $time = time();

        // Dokumen 1
        $dok1 = $files[0]->storeAs(
            "dokumen_pinjaman/$user->id",
            "dokumen_pinjaman_{$id}_{$time}.pdf",
            'public'
        );

        // Dokumen 2
        $dok2 = $files[1]->storeAs(
            "dokumen_pinjaman/$user->id",
            "dokumen_verifikasi_{$id}_" . ($time + 1) . ".pdf",
            'public'
        );

        $pinjaman->update([
            'dokumen_pinjaman' => $dok1,
            'dokumen_verifikasi' => $dok2,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Dokumen berhasil diupload.');
    }


    /* ============================================================
       TAMPIL PEMILIHAN ANGSURAN
    ============================================================ */
    public function pilihAngsuran(Request $request, $id)
    {
        $request->validate([
            'angsuran_ids' => 'required|array',
            'angsuran_ids.*' => 'exists:angsuran_pinjaman,id',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);
        $angsuran = Angsuran::whereIn('id', $request->angsuran_ids)->get();

        return view('user.pinjaman.transfer', compact('pinjaman', 'angsuran'));
    }


    /* ============================================================
       BAYAR ANGSURAN
    ============================================================ */
    public function bayarAngsuran(Request $request, $id)
    {
        $request->validate([
            'angsuran_ids' => 'required|array',
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        PengajuanAngsuran::create([
            'user_id' => auth()->id(),
            'pinjaman_id' => $id,
            'angsuran_ids' => $request->angsuran_ids,
            'bukti_transfer' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('user.pinjaman.create')
            ->with('success', 'Bukti transfer dikirim. Menunggu verifikasi.');
    }
}
