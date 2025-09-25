<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pengurus\SimpananWajib;
use App\Models\Pengurus\MasterSimpananWajib;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\SimpananExport;
use Maatwebsite\Excel\Facades\Excel;

class PengurusSimpananWajibController extends Controller
{
    // Halaman kelola simpanan wajib
    public function dashboard(Request $request)
    {
        $anggota = User::where('role', 'anggota')->get();
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
        $anggota = User::where('role', 'anggota')->get();
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

        $anggota = User::where('role', 'anggota')->get();

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
                    'status'  => 'Diajukan',
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
        $anggota = User::where('role', 'anggota')->get();

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

$riwayat = SimpananWajib::orderBy('tahun', 'desc')
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

        public function downloadExcel()
        {
            $fileName = 'Simpanan_Wajib_' . now()->format('Y_m_d') . '.xlsx';
            return Excel::download(new SimpananExport, $fileName);
        }


}