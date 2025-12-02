<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\DokumenPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pengurus\PinjamanSetting;
use App\Models\Pengurus\Angsuran;
use Carbon\Carbon;

class PinjamanAnggotaController extends Controller
{
    // // === BAGIAN PENGURUS ===
    // public function index()
    // {
    //     $pinjamans = Pinjaman::with('user')
    //                     ->where('status', 'pending')
    //                     ->get();

    //     return view('pengurus.pinjaman.index', compact('pinjamans'));
    // }

    // public function show($id)
    // {
    //     $pinjaman = Pinjaman::with('user')->findOrFail($id);
    //     return view('pengurus.pinjaman.show', compact('pinjaman'));
    // }

    // public function verify(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:disetujui,ditolak',
    //         'bunga' => 'required_if:status,disetujui|numeric|min:0',
    //         'tenor' => 'required_if:status,disetujui|integer|min:1',
    //     ]);

    //     $pinjaman = Pinjaman::findOrFail($id);

    //     if ($request->status === 'disetujui') {
    //         $pinjaman->update([
    //             'status' => 'disetujui',
    //             'bunga' => $request->bunga,
    //             'tenor' => $request->tenor,
    //             'angsuran' => ($pinjaman->nominal + ($pinjaman->nominal * $request->bunga / 100)) / $request->tenor,
    //         ]);
    //     } else {
    //         $pinjaman->update(['status' => 'ditolak']);
    //     }

    //     return redirect()->route('pengurus.pinjaman.index')
    //         ->with('success', 'Pinjaman telah diverifikasi.');
    // }

    // public function downloadFinal($id)
    // {
    //     $pinjaman = Pinjaman::with('user')->findOrFail($id);

    //     if ($pinjaman->status !== 'disetujui') {
    //         abort(403, "Pinjaman belum disetujui");
    //     }

    //     $pdf = Pdf::loadView('dokumen.SuratPinjamanFinal', [
    //         'pinjaman' => $pinjaman,
    //         'tanggal' => now()->translatedFormat('d F Y'),
    //     ])->setPaper('a4', 'portrait');

    //     return $pdf->download('Surat_Pinjaman_Final_'.$pinjaman->user->nama.'.pdf');
    // }

    // === BAGIAN ANGGOTA ===

    public function create()
    {
        $user = auth()->user();
        $userId = $user->id;

        // === Cek keanggotaan minimal 6 bulan ===
        // Ambil bulan masuk dari user
        $bulanMasuk = $user->bulan_masuk;


        // Helper: konversi nama bulan Indonesia → Inggris
        $convertBulanIndonesia = function ($bulanTahun) {
            $bulan = [
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

            // Pisahkan nama bulan dan tahun
            [$namaBulan, $tahun] = explode(' ', $bulanTahun);

            // Ganti ke bahasa Inggris
            $bulanInggris = $bulan[$namaBulan] ?? null;

            // Kembalikan string yang bisa diparse Carbon
            return "$bulanInggris $tahun";
        };

        // Jika bulan_masuk kosong, anggap belum memenuhi syarat
        if (!$bulanMasuk) {
            $masaKeanggotaan = 0;
        } else {
            // Parse ke Carbon (auto: YYYY-MM, YYYY-MM-DD, dll)
            $tanggalMasuk = Carbon::parse($convertBulanIndonesia($bulanMasuk));

            // Hitung selisih bulan sampai sekarang
            $masaKeanggotaan = $tanggalMasuk->diffInMonths(now());
        }

        $bolehPinjam = $masaKeanggotaan >= 6;
        $sisaBulan = max(0, 6 - $masaKeanggotaan);


        // Ambil pinjaman terbaru user
        $pinjaman = Pinjaman::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        // Ambil pengaturan tenor
        $settings = PinjamanSetting::orderBy('tenor', 'asc')->get();

        // Jika belum pernah pinjam → langsung tampilkan halaman kosong
        if (!$pinjaman) {
            return view('user.pinjaman.create', [
                'pinjaman' => null,
                'settings' => $settings,
                'angsuran' => collect(),
                'bolehPinjam' => $bolehPinjam,
                'sisaBulan' => $sisaBulan
            ]);
        }

        // Jika status pending → tinggal tunggu persetujuan
        if ($pinjaman->status === 'pending') {
            return view('user.pinjaman.create', [
                'pinjaman' => $pinjaman,
                'settings' => $settings,
                'angsuran' => collect(),
                'bolehPinjam' => $bolehPinjam,
                'sisaBulan' => $sisaBulan
            ]);
        }

        // Jika sudah disetujui → cek angsuran
        if ($pinjaman->status === 'disetujui') {

            $angsuran = Angsuran::where('pinjaman_id', $pinjaman->id)
                ->orderBy('bulan_ke', 'asc')
                ->get();

            $masihBelumLunas = $angsuran->contains('status', 'belum_lunas');

            // Jika masih ada angsuran yang belum lunas → tidak bisa ajukan lagi
            if ($masihBelumLunas) {
                return view('user.pinjaman.create', [
                    'pinjaman' => $pinjaman,
                    'settings' => $settings,
                    'angsuran' => $angsuran,
                    'bolehPinjam' => $bolehPinjam,
                    'sisaBulan' => $sisaBulan
                ]);
            }

            // Jika semua lunas → boleh ajukan lagi
            return view('user.pinjaman.create', [
                'pinjaman' => null,
                'settings' => $settings,
                'angsuran' => collect(),
                'bolehPinjam' => $bolehPinjam,
                'sisaBulan' => $sisaBulan
            ]);
        }

        // Jika status lain (ditolak, dsb.)
        return view('user.pinjaman.create', [
            'pinjaman' => $pinjaman,
            'settings' => $settings,
            'bolehPinjam' => $bolehPinjam,
            'sisaBulan' => $sisaBulan
        ]);
    }


    private function formPengajuanBaru($settings)
    {
        return view('user.pinjaman.create', [
            'pinjaman' => null,
            'settings' => $settings,
            'angsuran' => collect(),
            'bolehAjukan' => true,
        ]);
    }

// Ganti dengan path model Angsuran Anda

    public function store(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;

        // --- HELPER: Konversi Bulan (Diperlukan untuk konsistensi dengan create()) ---
        $convertBulanIndonesia = function ($bulanTahun) {
            $bulan = [
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

            // Jika format input tidak sesuai "NamaBulan Tahun" (misal: "Januari 2023"), tangani
            if (!str_contains($bulanTahun, ' ')) {
                return null; // Tidak bisa diproses
            }

            [$namaBulan, $tahun] = explode(' ', $bulanTahun);
            $bulanInggris = $bulan[$namaBulan] ?? null;

            return "$bulanInggris $tahun";
        };

        // ===========================================
        // 1. CEK MASA KEANGGOTAAN MINIMAL 6 BULAN
        //    (Menggunakan logika 'bulan_masuk' dari method create)
        // ===========================================
        $bulanMasuk = $user->bulan_masuk;
        $masaKeanggotaan = 0;

        if ($bulanMasuk) {
            $tanggalMasuk = Carbon::parse($convertBulanIndonesia($bulanMasuk));
            $masaKeanggotaan = $tanggalMasuk->diffInMonths(now());
        }

        if ($masaKeanggotaan < 6) {
            $sisaBulan = 6 - $masaKeanggotaan;
            return back()->with('error', "Anda belum mencapai masa keanggotaan minimal 6 bulan. Sisa $sisaBulan bulan lagi.");
        }

        // ===========================================
        // 2. CEK PINJAMAN AKTIF/PENDING
        // ===========================================
        $pinjamanTerbaru = Pinjaman::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($pinjamanTerbaru) {

            // A. Cek Pinjaman Status 'pending'
            if ($pinjamanTerbaru->status === 'pending') {
                return back()->with('error', 'Anda sudah memiliki pengajuan pinjaman yang sedang menunggu persetujuan (status: pending).');
            }

            // B. Cek Pinjaman Status 'disetujui' (apakah angsuran sudah lunas semua)
            if ($pinjamanTerbaru->status === 'disetujui') {

                $angsuran = Angsuran::where('pinjaman_id', $pinjamanTerbaru->id)->get();

                // Cek apakah ada angsuran yang statusnya BUKAN 'lunas' (case insensitive)
                $masihBelumLunas = $angsuran->contains(function ($a) {
                    return trim(strtolower($a->status)) !== 'lunas';
                });

                if ($masihBelumLunas) {
                    return back()->with('error', 'Anda masih memiliki pinjaman yang angsurannya belum lunas.');
                }
            }
        }

        // ===========================================
        // 3. VALIDASI INPUT (Hanya menambahkan penanganan error)
        // ===========================================
        $validatedData = $request->validate([
            'nominal' => 'required|numeric|min:100000',
            'tenor' => 'required|numeric|integer|min:1', // Menambahkan validasi integer dan min:1
            'bunga' => 'required|numeric|min:0',         // Menambahkan validasi min:0
        ]);

        // ===========================================
        // 4. SIMPAN PENGAJUAN PINJAMAN
        // ===========================================
        Pinjaman::create([
            'user_id' => $userId,
            'nominal' => $validatedData['nominal'],
            'tenor' => $validatedData['tenor'],
            'bunga' => $validatedData['bunga'],
            'status' => 'pending'
        ]);

        return redirect()->route('user.pinjaman.create')
            ->with('success', 'Pengajuan pinjaman berhasil dikirim dan sedang menunggu persetujuan!');
    }



    public function upload(Request $request, $id)
    {
        $request->validate([
            'dokumen_pinjaman.*' => 'required|mimes:pdf|max:2048',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);
        $user = auth()->user();

        if (!$request->hasFile('dokumen_pinjaman')) {
            return back()->with('error', 'Tidak ada dokumen yang di-upload.');
        }

        $files = $request->file('dokumen_pinjaman');

        if (count($files) < 2) {
            return back()->with('error', 'Harap upload 2 dokumen PDF (Dokumen Pinjaman & Dokumen Verifikasi).');
        }

        // FIX: gunakan timestamp berbeda agar tidak bentrok
        $timestamp = now()->timestamp;

        // =======================
        // Simpan Dokumen 1 (dokumen_pinjaman)
        // =======================
        $dokPinName = "dokumen_pinjaman_{$pinjaman->id}_{$timestamp}.pdf";
        $dokumenPinjaman = $files[0]->storeAs(
            "dokumen_pinjaman/{$user->id}",
            $dokPinName,
            'public'
        );

        // =======================
        // Simpan Dokumen 2 (dokumen_verifikasi)
        // =======================
        // timestamp ditambah sedikit agar beda nama file
        $timestamp2 = $timestamp + 1;

        $dokVerName = "dokumen_verifikasi_{$pinjaman->id}_{$timestamp2}.pdf";
        $dokumenVerifikasi = $files[1]->storeAs(
            "dokumen_pinjaman/{$user->id}",
            $dokVerName,
            'public'
        );

        // SIMPAN KE DATABASE
        $pinjaman->dokumen_pinjaman = $dokumenPinjaman;
        $pinjaman->dokumen_verifikasi = $dokumenVerifikasi;
        $pinjaman->status = 'pending';
        $pinjaman->save();

        return back()->with('success', '2 dokumen berhasil di-upload. Menunggu verifikasi pengurus.');
    }




    // public function download($id)
    // {
    //     $pinjaman = Pinjaman::with('user')->findOrFail($id);
    //     $user = Auth::user();

    //     $pdf = Pdf::loadView('dokumen.SuratPinjaman', [
    //         'user' => $user,
    //         'pinjaman' => $pinjaman,
    //         'tanggal' => now()->translatedFormat('d F Y'),
    //     ])->setPaper('a4', 'portrait');

    //     return $pdf->download('Surat_Pinjaman_' . $pinjaman->user->nama . '.pdf');
    // }

    // public function uploadForm($id)
    // {
    //     $pinjaman = Pinjaman::findOrFail($id);
    //     return view('user.pinjaman.upload', compact('pinjaman'));
    // }

    // public function upload(Request $request, $id)
    // {
    //     $request->validate([
    //         'dokumen_pinjaman.*' => 'required|mimes:pdf|max:2048',
    //     ]);

    //     $pinjaman = Pinjaman::findOrFail($id);

    //     // Proses upload file
    //     if ($request->hasFile('dokumen_pinjaman')) {
    //         $files = [];
    //         foreach ($request->file('dokumen_pinjaman') as $file) {
    //             $path = $file->store('dokumen_pinjaman', 'public');
    //             $files[] = $path;
    //         }

    //         $pinjaman->update([
    //             'dokumen_pinjaman' => json_encode($files),
    //             'status' => 'pending', // ✅ ubah status agar muncul di pengurus
    //         ]);
    //     }

    //     return back()->with('success', 'Surat pinjaman berhasil diupload.');
    // }


    // public function hapusDokumen($id)
    // {
    //     $dokumen = DokumenPinjaman::findOrFail($id);
    //     Storage::disk('public')->delete($dokumen->file_path);
    //     $dokumen->delete();

    //     return back()->with('success', 'Dokumen berhasil dihapus.');
    // }
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

    public function bayarAngsuran(Request $request, $id)
    {
        $request->validate([
            'angsuran_ids' => 'required|array',
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $user = auth()->user();
        $pinjaman = Pinjaman::findOrFail($id);

        // Upload Bukti
        $path = $request->file('bukti_transfer')->store('bukti_transfer_angsuran', 'public');

        // Simpan Pengajuan
        \App\Models\PengajuanAngsuran::create([
            'user_id' => $user->id,
            'pinjaman_id' => $pinjaman->id,
            'angsuran_ids' => $request->angsuran_ids, // Casts array otomatis
            'bukti_transfer' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('user.pinjaman.create')
            ->with('success', 'Bukti transfer berhasil dikirim. Menunggu verifikasi admin.');
    }
}
