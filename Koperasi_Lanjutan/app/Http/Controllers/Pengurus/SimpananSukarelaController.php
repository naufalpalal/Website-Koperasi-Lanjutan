<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\User\MasterSimpananSukarela;
use App\Models\User;

class SimpananSukarelaController extends Controller
{
    public function index()
    {


        $simpanan = SimpananSukarela::with('user')->latest()->paginate(10);
        return view('pengurus.simpanan.sukarela.index', compact('simpanan'));
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
            'bulan' => 'required|date_format:Y-m',
        ]);

        [$tahun, $bulan] = explode('-', $request->bulan);

        $anggota = User::where('role', 'anggota')->get();

        foreach ($anggota as $a) {
            // Cari nominal terbaru dari master simpanan sukarela milik anggota ini
            $master = MasterSimpananSukarela::where('users_id', $a->id)
                ->where('status', 'Disetujui')
                ->latest()
                ->first();

            $nominal = $master->nilai ?? 0;

            // Cek apakah sudah ada simpanan di periode ini
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


}
