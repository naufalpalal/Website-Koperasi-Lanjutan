<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\User\MasterSimpananSukarela;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SimpananSukarelaController extends Controller
{
    public function index(Request $request)
    {
        // ===== DEFAULT PERIODE =====
        $periode = $request->get('periode', now()->format('Y-m'));
        [$tahun, $bulan] = explode('-', $periode);

        // ===== QUERY DASAR =====
        $query = SimpananSukarela::with('user')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan);

        // ===== FILTER NAMA =====
        if ($request->filled('nama')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        // ===== DATA TABEL =====
        $simpanan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString(); // biar pagination gak ilang filter

        // ===== LIST PERIODE DROPDOWN =====
        $periodeList = SimpananSukarela::select('tahun', 'bulan')
            ->selectRaw("CONCAT(tahun,'-',LPAD(bulan,2,'0')) as periode")
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->pluck('periode');

        return view(
            'pengurus.simpanan.sukarela.index',
            compact('simpanan', 'periode', 'periodeList')
        );
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
        // ambil semua data yang tampil di halaman
        $allIds = SimpananSukarela::where('status', 'diajukan')
            ->pluck('id')
            ->toArray();

        // yang dicentang
        $checkedIds = $request->input('ids', []);

        // 1️⃣ yang dicentang → BERHASIL
        if (!empty($checkedIds)) {
            SimpananSukarela::whereIn('id', $checkedIds)
                ->update(['status' => 'dibayar']);
        }

        // 2️⃣ yang TIDAK dicentang → GAGAL
        $failedIds = array_diff($allIds, $checkedIds);

        if (!empty($failedIds)) {
            SimpananSukarela::whereIn('id', $failedIds)
                ->update(['status' => 'gagal']);
        }

        return back()->with('success', 'Proses persetujuan berhasil disimpan');
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

    public function generatePage()
    {
        $simpanan = SimpananSukarela::with('user')
            ->where('status', 'Diajukan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('pengurus.simpanan.sukarela.generate', compact('simpanan'));
    }



    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        $periode = Carbon::create($tahun, $bulan, 1)->startOfMonth();

        if ($periode->lessThan(now()->startOfMonth())) {
            return back()->with('error', 'Tidak boleh generate periode lampau.');
        }


        return DB::transaction(function () use ($bulan, $tahun) {

            // 🔒 Lock data biar tidak double generate
            $sudahAda = SimpananSukarela::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->exists();

            if ($sudahAda) {
                return back()->with(
                    'error',
                    "Simpanan sukarela bulan $bulan tahun $tahun sudah pernah digenerate."
                );
            }

            // Ambil anggota AKTIF saja
            $anggota = User::where('status', 'aktif')->get();

            // Ambil master yang disetujui untuk periode ini
            $masterMap = MasterSimpananSukarela::where('status', 'Disetujui')
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->get()
                ->keyBy('users_id');

            $dataInsert = [];

            foreach ($anggota as $a) {
                $master = $masterMap->get($a->id);

                if (!$master || $master->nilai <= 0) {
                    continue;
                }

                $dataInsert[] = [
                    'users_id' => $a->id,
                    'nilai' => $master->nilai,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'status' => 'Diajukan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($dataInsert) === 0) {
                return back()->with(
                    'error',
                    "Tidak ada simpanan yang bisa digenerate untuk bulan $bulan tahun $tahun."
                );
            }

            // 🚀 Insert massal (lebih cepat & bersih)
            SimpananSukarela::insert($dataInsert);

            return back()->with(
                'success',
                "Simpanan sukarela bulan $bulan tahun $tahun berhasil digenerate untuk "
                . count($dataInsert) . " anggota."
            );
        });
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
