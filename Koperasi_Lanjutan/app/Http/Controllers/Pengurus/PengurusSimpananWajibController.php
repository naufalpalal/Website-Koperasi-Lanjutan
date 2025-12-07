<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pengurus\SimpananWajib;
use App\Models\Pengurus\MasterSimpananWajib;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\SimpananExport;
use Maatwebsite\Excel\Facades\Excel;

class PengurusSimpananWajibController extends Controller
{
    // Halaman kelola simpanan wajib
    public function dashboard(Request $request)
    {
        $anggota = User::where('status', 'aktif')->get();
        $master = MasterSimpananWajib::latest()->first();

        // Ambil bulan yang dipilih di filter, default bulan ini
        $periodeFilter = $request->get('bulan', now()->format('Y-m'));
        [$tahunFilter, $bulanFilter] = explode('-', $periodeFilter);

        // Ambil simpanan untuk periode filter
        $simpananBulanIni = SimpananWajib::where('tahun', $tahunFilter)
            ->where('bulan', $bulanFilter)
            ->get()
            ->keyBy('users_id');

        // Ambil semua periode unik untuk history dropdown
        $bulan = SimpananWajib::selectRaw("CONCAT(tahun, '-', LPAD(bulan, 2, '0')) as periode")
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->pluck('periode');



        return view('pengurus.simpanan.wajib_2.dashboard', compact('anggota', 'master', 'simpananBulanIni', 'bulan', 'periodeFilter'));
    }
    public function index(Request $request)
    {
        $anggota = User::where('status', 'aktif')->get();
        $master = MasterSimpananWajib::latest()->first();

        // Ambil bulan yang dipilih di filter, default bulan ini
        $periodeFilter = $request->get('bulan', now()->format('Y-m'));
        [$tahunFilter, $bulanFilter] = explode('-', $periodeFilter);

        // Ambil simpanan untuk periode filter
        $simpananBulanIni = SimpananWajib::where('tahun', $tahunFilter)
            ->where('bulan', $bulanFilter)
            ->get()
            ->keyBy('users_id');

        // Ambil semua periode unik untuk history dropdown
        $bulan = SimpananWajib::selectRaw("CONCAT(tahun, '-', LPAD(bulan, 2, '0')) as periode")
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->pluck('periode');



        return view('pengurus.simpanan.wajib_2.index', compact('anggota', 'master', 'simpananBulanIni', 'bulan', 'periodeFilter'));
    }

    // // Update nominal simpanan wajib (Master)
    // public function updateNominal(Request $request)
    // {
    //     $request->validate([
    //         'nilai' => 'required|integer|min:0',
    //     ]);

    //     MasterSimpananWajib::create([
    //         'nilai' => $request->nilai,
    //         'tahun' => now()->year,
    //         'bulan' => now()->month,
    //         'users_id' => auth()->id()
    //     ]);

    //     return back()->with('success', 'Nominal simpanan wajib berhasil diperbarui.');
    // }

    // Generate simpanan wajib otomatis
    public function generate(Request $request)
    {
        $request->validate([
            //'inputNominal' => 'required|integer|min:0',
            'bulan' => 'required|date_format:Y-m',
        ]);

        //$nominal = $request->input('inputNominal');
        $master = MasterSimpananWajib::latest()->first();
        $nominal = $master->nilai ?? 0;

        // Split periode menjadi tahun dan bulan
        [$tahun, $bulan] = explode('-', $request->bulan);

        $anggota = User::where('status', 'aktif')->get();

        foreach ($anggota as $a) {
            $cek = SimpananWajib::where('users_id', $a->id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->first();

            if (!$cek) {
                SimpananWajib::create([
                    'nilai' => $nominal,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'status' => 'Diajukan',
                    'pengurus_id' => Auth::guard('pengurus')->id(),
                    'users_id' => $a->id,
                ]);
            }
        }

        return back()->with('success', 'Simpanan wajib berhasil digenerate.');
    }

    // Update status berdasarkan checklist
    public function updateStatus(Request $request)
    {
        $anggotaDicentang = $request->anggota ?? [];
        $anggota = User::where('status', 'aktif')->get();

        // Ambil bulan yang dipilih di filter, default bulan ini
        $periodeFilter = $request->get('bulan', now()->format('Y-m'));
        [$tahunFilter, $bulanFilter] = explode('-', $periodeFilter);

        foreach ($anggota as $a) {
            $simpanan = SimpananWajib::where('users_id', $a->id)
                ->where('tahun', $tahunFilter)
                ->where('bulan', $bulanFilter)
                ->first();

            if ($simpanan) {
                $simpanan->status = in_array($a->id, $anggotaDicentang) ? 'Dibayar' : 'Gagal';
                $simpanan->save();
            }
        }

        return back()->with('success', 'Status simpanan wajib berhasil diperbarui.');
    }

    public function riwayat($id)
    {
        $anggota = User::findOrFail($id);

        $riwayat = SimpananWajib::where('users_id', $anggota->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('pengurus.simpanan.wajib_2.riwayat', compact('anggota', 'riwayat'));
    }

    public function destroy($id)
    {
        $simpanan = SimpananWajib::findOrFail($id);
        $simpanan->delete();

        return back()->with('success', 'Data simpanan wajib berhasil dihapus.');
    }

    public function downloadExcel(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        [$tahunFilter, $bulanFilter] = explode('-', $request->bulan);

        // Ambil hanya simpanan untuk bulan & tahun yang dipilih
        $simpanan = SimpananWajib::with('user')
            ->where('tahun', $tahunFilter)
            ->where('bulan', $bulanFilter)
            ->get();

        $filename = 'laporan_simpanan_wajib_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($simpanan) {
            $handle = fopen('php://output', 'w');

            // Header kolom CSV
            fputcsv($handle, ['Nama Anggota', 'Nama Pengurus', 'Nominal', 'Bulan', 'Tahun', 'Status', 'Tanggal Dibuat', 'Terakhir Diupdate']);

            // Data isi CSV
            foreach ($simpanan as $s) {
                fputcsv($handle, [
                    $s->user->nama ?? '-',
                    $s->pengurus->nama ?? '-',
                    $s->nilai,
                    $s->bulan,
                    $s->tahun,
                    ucfirst($s->status),
                    $s->created_at ? $s->created_at->translatedFormat('j F Y H:i') : '-',
                    $s->updated_at ? $s->updated_at->translatedFormat('j F Y H:i') : '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    // Laporan Simpanan Wajib Tahunan
    public function laporanTahunan(Request $request)
    {
        // Ambil tahun filter, default tahun ini
        $tahunFilter = $request->get('tahun', now()->year);

        // Ambil data simpanan wajib per anggota per tahun
        $laporanTahunan = SimpananWajib::select('users_id', 'tahun')
            ->selectRaw('SUM(nilai) as total_simpanan')
            ->where('tahun', $tahunFilter)
            ->groupBy('users_id', 'tahun')
            ->with('user')
            ->get();

        return view('pengurus.simpanan.wajib_2.laporan_tahunan', compact('laporanTahunan', 'tahunFilter'));
    }

    // Download Excel Simpanan Wajib Tahunan
    public function downloadTahunan(Request $request)
    {
        // Validasi input tahun
        $tahunFilter = $request->get('tahun', now()->year);

        // Ambil total simpanan per anggota untuk tahun tersebut
        $simpanan = SimpananWajib::select('users_id', 'tahun')
            ->selectRaw('SUM(nilai) as total_simpanan')
            ->where('tahun', $tahunFilter)
            ->groupBy('users_id', 'tahun')
            ->with('user')
            ->get();

        $filename = 'laporan_simpanan_wajib_tahunan_' . $tahunFilter . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($simpanan) {
            $handle = fopen('php://output', 'w');

            // Header kolom CSV
            fputcsv($handle, ['Nama Anggota', 'Tahun', 'Total Simpanan']);

            // Data isi CSV
            foreach ($simpanan as $s) {
                fputcsv($handle, [
                    $s->user->nama ?? '-',
                    $s->tahun,
                    $s->total_simpanan,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }


    public function lockPeriode(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        [$tahun, $bulan] = explode('-', $request->bulan);

        // Update semua simpanan bulan tersebut jadi terkunci
        SimpananWajib::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->update(['is_locked' => true]);

        return back()->with('success', 'Periode berhasil dikunci. Data tidak bisa diubah lagi.');
    }

    // Lihat bukti pembayaran anggota yang gagal
    public function lihatBukti()
    {
        $data = SimpananWajib::where('status', 'Diajukan')->get();

        return view('pengurus.simpanan.wajib_2.lihat_bukti', compact('data'));
    }

    // Hapus bukti pembayaran
    public function hapusBukti($id)
    {
        $item = SimpananWajib::findOrFail($id);

        // Hapus file bukti jika ada di public/storage/bukti_transfer
        if ($item->bukti_transfer && file_exists(public_path('storage/bukti_transfer/' . $item->bukti_transfer))) {
            unlink(public_path('storage/bukti_transfer/' . $item->bukti_transfer));
        }

        $item->delete();

        return redirect()->route('pengurus.simpanan.wajib_2.lihat_bukti')
            ->with('success', 'Data bukti pembayaran berhasil dihapus.');
    }

}