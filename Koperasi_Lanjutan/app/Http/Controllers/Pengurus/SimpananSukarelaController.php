<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\User\MasterSimpananSukarela;
use App\Models\User;
use Carbon\Carbon;

class SimpananSukarelaController extends Controller
{
    public function index()
    {
        // ambil bulan dan tahun sekarang
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        // ambil data simpanan berdasarkan bulan & tahun saat ini
        $simpanan = SimpananSukarela::with('user')
            ->where('bulan', $bulanSekarang)
            ->where('tahun', $tahunSekarang)
            ->latest()
            ->paginate(10);

        return view('pengurus.simpanan.sukarela.index', compact('simpanan', 'bulanSekarang', 'tahunSekarang'));
    }

    public function create()
    {
        // Menampilkan form untuk membuat simpanan sukarela baru
        $pengajuan = MasterSimpananSukarela::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengurus.simpanan.sukarela.pengajuan', compact('pengajuan'));
    }

    public function update(Request $request)
    {
        $ids = $request->input('ids', []); // id yang dicentang

        // Semua pengajuan yang masih diajukan bulan ini
        $pengajuan = SimpananSukarela::where('status', 'Diajukan')->get();

        foreach ($pengajuan as $item) {
            if (in_array($item->id, $ids)) {
                // Kalau dicentang → Dibayar
                $item->update(['status' => 'Dibayar']);
            } else {
                // Kalau tidak dicentang → tetap Diajukan (atau bisa diberi status khusus "Gagal/Libur")
                $item->update(['status' => 'Diajukan']);
            }
        }

        return redirect()->back()->with('success', 'Proses persetujuan simpanan berhasil diperbarui.');
    }

    public function approve($id)
    {
        $pengajuan = MasterSimpananSukarela::findOrFail($id);
        $pengajuan->status = 'Disetujui';
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan simpanan berhasil disetujui.');
    }

    // Tolak pengajuan
    public function reject($id)
    {
        $pengajuan = MasterSimpananSukarela::findOrFail($id);
        $pengajuan->status = 'Ditolak';
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan simpanan ditolak.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric|min:1|max:12',
            'tahun' => 'required|numeric|min:2000',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $anggota = User::get();

        foreach ($anggota as $a) {
            $master = MasterSimpananSukarela::where('users_id', $a->id)
                ->where('status', 'Disetujui')
                ->latest()
                ->first();

            $nominal = $master->nilai ?? 0;

            $cek = SimpananSukarela::where('users_id', $a->id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->first();

            if (!$cek) {
                SimpananSukarela::create([
                    'nilai' => $nominal,
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'status' => 'Diajukan',
                    'users_id' => $a->id,
                ]);
            }
        }

        return back()->with('success', 'Simpanan sukarela berhasil digenerate.');
    }


    public function riwayat(Request $request)
    {
        // Ambil parameter dari request (jika ada)
        $id = $request->input('id');     // ID anggota (optional)
        $bulan = $request->input('bulan');  // Bulan filter (optional)
        $tahun = $request->input('tahun');  // Tahun filter (optional)
        $nilai = $request->input('nilai');  // Nilai filter (optional)

        // Buat query dasar
        $query = SimpananSukarela::with('user');

        // Filter per anggota jika ID ada
        $anggota = null;
        if ($id) {
            $query->where('users_id', $id);
        }

        // Filter bulan
        if ($bulan) {
            $query->where('bulan', $bulan);
        }

        // Filter tahun
        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        // Filter berdasarkan nilai (jika user ingin mencari nominal tertentu)
        if ($nilai) {
            $query->where('nilai', $nilai);
        }

        // Ambil data riwayat dengan urutan terbaru
        $riwayat = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);


        $totalSimpanan = $riwayat->sum('nilai');


        // Kirim ke view
        return view('pengurus.simpanan.sukarela.riwayat', compact('riwayat', 'anggota', 'bulan', 'tahun', 'nilai', 'id', 'totalSimpanan'));
    }


    public function downloadExcel()
    {
        $sukarela = SimpananSukarela::with('user')->get();

        $filename = 'laporan_simpanan_sukarela' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($sukarela) {

            // Bersihkan output buffer agar tidak merusak CSV
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $handle = fopen('php://output', 'w');

            // Tambahkan UTF-8 BOM agar excel tidak berantakan
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            // Header CSV
            fputcsv($handle, [
                'Nama Anggota',
                'Bulan',
                'Tahun',
                'Nominal',
                'Status',
                'Tanggal Pengajuan',
                'Tanggal Disetujui'
            ]);

            // Isi data CSV
            foreach ($sukarela as $p) {
                fputcsv($handle, [
                    $p->user->nama ?? '-',
                    $p->bulan,
                    $p->tahun,
                    $p->nominal,
                    ucfirst($p->status ?? '-'),
                    $p->created_at?->format('d-m-Y H:i') ?? '-',
                    $p->tanggal_disetujui?->format('d-m-Y H:i') ?? '-',
                ]);
            }


            fclose($handle);
        }, 200, $headers);
    }





}
