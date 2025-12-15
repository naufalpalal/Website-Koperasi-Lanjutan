<?php

namespace App\Http\Controllers\User;

use App\Helpers\Bulan;
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
    public function create()
    {
        $user = Auth::user();

        // Ambil semua paket pinjaman
        $paketPinjaman = PaketPinjaman::where('status', 1)->get();

        return view('user.pinjaman.create', compact('paketPinjaman'));
    }

    /* ============================================================
       SIMPAN PENGAJUAN PINJAMAN
    ============================================================ */
    public function store(Request $request, $paketId)
    {
        $user = Auth::user();

        // ===============================
        // VALIDASI BULAN MASUK
        // ===============================
        $bulanMasuk = strtolower(trim($user->bulan_masuk));
        $bulanMasuk = preg_replace('/\s+/', ' ', $bulanMasuk);
        $parts = explode(' ', $bulanMasuk);

       $bulan = Bulan::indoToEnglish($parts[0]);

        if (count($parts) !== 2 || !isset($map[$parts[0]])) {
            return back()->with('modal', [
                'title' => 'Data Tidak Valid',
                'message' => 'Data bulan masuk anggota bermasalah. Hubungi pengurus.',
                'type' => 'error',
            ]);
        }

        [$bulanIndo, $tahun] = $parts;
        $tanggalGabung = Carbon::parse("1 {$bulan[$bulanIndo]} {$tahun}");
        $lamaBulan = $tanggalGabung->diffInMonths(now());

        // ===============================
        // BLOKIR JIKA < 6 BULAN
        // ===============================
        if ($lamaBulan < 6) {
            return back()->with('modal', [
                'title' => 'Belum Bisa Mengajukan Pinjaman',
                'message' => "Masa keanggotaan Anda baru {$lamaBulan} bulan.\nMinimal 6 bulan.",
                'type' => 'warning',
            ]);
        }

        // ===============================
        // VALIDASI PAKET
        // ===============================
        $paket = PaketPinjaman::where('id', $paketId)
            ->where('status', 1)
            ->first();

        if (!$paket) {
            return back()->with('modal', [
                'title' => 'Paket Tidak Ditemukan',
                'message' => 'Paket pinjaman tidak valid.',
                'type' => 'error',
            ]);
        }

        // ===============================
        // SIMPAN PINJAMAN
        // ===============================
        $pinjaman = Pinjaman::create([
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'nominal' => $paket->nominal,
            'tenor' => $paket->tenor,
            'bunga' => $paket->bunga,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('user.pinjaman.dokumen', ['id' => $pinjaman->id])
            ->with('modal', [
                'title' => 'Berhasil',
                'message' => 'Pengajuan pinjaman berhasil diajukan.',
                'type' => 'success',
            ]);
    }


    /* ============================================================
       TAMPIL DOKUMEN PINJAMAN
    ============================================================ */
    public function showDokumen($id)
    {
        $pinjaman = Pinjaman::with('paket')->findOrFail($id);

        return view('user.pinjaman.dokumen', [
            'pinjaman' => $pinjaman
        ]);
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
