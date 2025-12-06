<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class Tabungan2Controller extends Controller
{
    /**
     * Menampilkan daftar anggota dengan total saldo tabungan
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $tabungans = User::with('tabungans')
            // ->where('role', 'anggota')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->paginate(10);

        // Hitung total saldo setiap anggota
        $tabungans->getCollection()->transform(function ($user) {
            $totalDiterima = $user->tabungans->where('status', 'diterima')->sum('nilai');
            $totalDipakai = $user->tabungans->where('status', 'dipotong')->sum('debit');
            $user->total_saldo = $totalDiterima - $totalDipakai;
            return $user;
        });

        return view('pengurus.simpanan.tabungan.index', compact('tabungans', 'search'));
    }

    /**
     * Setujui tabungan (ubah status menjadi diterima)
     */
    public function approve($id)
    {
        $tabungan = Tabungan::findOrFail($id);

        $pengurus = Auth::guard('pengurus')->user();
        $anggota = $tabungan->user;

        $tabungan->update([
            'status' => 'diterima',
            'pengurus_id' => Auth::guard('pengurus')->id()
        ]);

        $this->writeLog(
            "Tanggal: {$tabungan->tanggal} | Nominal: {$tabungan->nilai} |" .
            "NIP Anggota: {$anggota->nip} | Diterima Oleh: {$pengurus->nama} (ID: {$pengurus->id})"
        );
        
        return redirect()
            ->route('pengurus.tabungan.detail', $tabungan->users_id)
            ->with('success', 'Tabungan berhasil disetujui.');
    }

    /**
     * Tolak tabungan
     */
    public function reject($id)
    {
        $tabungan = Tabungan::findOrFail($id);
        $pengurus = Auth::guard('pengurus')->user();
        $anggota = $tabungan->user;
        
        $tabungan->update([
            'status' => 'ditolak',
            'pengurus_id' => Auth::guard('pengurus')->id()
        ]);

        // CATAT LOG
        $this->writeLog(
            "Tanggal: {$tabungan->tanggal} | Nominal: {$tabungan->nilai} |" .
            "NIP Anggota: {$anggota->nip} | Ditolak Oleh: {$pengurus->nama} (ID: {$pengurus->id})"
        );

        return redirect()
            ->route('pengurus.tabungan.detail', $tabungan->users_id)
            ->with('error', 'Tabungan ditolak.');
    }

    /**
     * Tambahkan tabungan baru (oleh pengurus)
     */
    public function store(Request $request)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|after_or_equal:2000-01-01|before_or_equal:' . now()->format('Y-m-d'),
            'nilai' => 'required|numeric|min:1000',
        ], [
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum tahun 2000.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
        ]);

        // Tambahan validasi logis: pastikan tahun tidak aneh seperti 11111111
        $tanggal = Carbon::parse($request->tanggal);
        if ($tanggal->year < 2000 || $tanggal->year > 2100) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tahun tidak valid (harus antara 2000 dan 2100).',
            ]);
        }

        Tabungan::create([
            'users_id' => $request->users_id,
            'pengurus_id' => Auth::guard('pengurus')->id(),
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'status' => 'diterima',
            'bukti_transfer' => null,
        ]);

        return redirect()
            ->route('pengurus.tabungan.detail', $request->users_id)
            ->with('success', 'Tabungan berhasil ditambahkan.');
    }

    /**
     * Tampilkan daftar transaksi debit anggota
     */
    public function debit($id)
    {
        $user = User::findOrFail($id);

        $debits = Tabungan::where('users_id', $id)
            ->where('status', 'dipotong')
            ->orderByDesc('id')
            ->paginate(5);

        $totalSaldo = $user->totalSaldo();

        return view('pengurus.simpanan.tabungan.debit', compact('user', 'totalSaldo', 'debits'));
    }

    /**
     * Simpan transaksi debit (pengambilan saldo)
     */
    public function storeDebit(Request $request)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'debit' => 'required|numeric|min:100',
        ], [
            'users_id.required' => 'User tidak ditemukan.',
            'users_id.exists' => 'Data user tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Tanggal tidak sesuai.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'debit.required' => 'Nominal harus diisi.',
            'debit.numeric' => 'Nominal harus berupa angka.',
            'debit.min' => 'Nominal minimal 100.',
        ]);

        $user = User::findOrFail($request->users_id);
        $saldoSekarang = $user->totalSaldo();

        if ($saldoSekarang < $request->debit) {
            return back()->withInput()->with('error', 'Saldo tidak mencukupi untuk melakukan debit.');
        }

        Tabungan::create([
            'users_id' => $user->id,
            'pengurus_id' => Auth::guard('pengurus')->id(),
            'tanggal' => $request->tanggal,
            'nilai' => 0,
            'debit' => $request->debit,
            'status' => 'dipotong',
        ]);

        return back()->with('success', 'Debit berhasil dilakukan dan saldo telah diperbarui.');
    }

    /**
     * Tampilkan form kredit (penambahan saldo)
     */
    public function kredit($id)
    {
        $user = User::findOrFail($id);

        $tabungans = Tabungan::where('users_id', $id)
            ->where('status', 'diterima')
            ->orderByDesc('id')
            ->paginate(5);

        $totalSaldo = $user->totalSaldo();

        return view('pengurus.simpanan.tabungan.kredit', compact('user', 'tabungans', 'totalSaldo'));
    }

    /**
     * Simpan transaksi kredit (penambahan saldo)
     */
    public function storeKredit(Request $request)
    {
        $request->validate([
            'users_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'nilai' => 'required|numeric|min:100',
        ], [
            'users_id.required' => 'User tidak ditemukan.',
            'users_id.exists' => 'Data user tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'nilai.required' => 'Nominal harus diisi.',
            'nilai.numeric' => 'Nominal harus berupa angka.',
            'nilai.min' => 'Nominal minimal Rp 100.',
        ]);

        Tabungan::create([
            'users_id' => $request->users_id,
            'pengurus_id' => Auth::guard('pengurus')->id(),
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'status' => 'diterima',
            'bukti_transfer' => null,
        ]);

        return redirect()
            ->route('pengurus.tabungan.kredit', $request->users_id)
            ->with('success', 'Saldo tabungan berhasil ditambahkan.');
    }

    /**
     * Detail tabungan per anggota dengan filter tanggal & status
     */
    public function detail(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $tanggal = $request->input('tanggal');
        $status = $request->input('status');

        $query = Tabungan::where('users_id', $id);

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $tabungans = $query->orderByDesc('id')->paginate(10);

        $totalKredit = Tabungan::where('users_id', $id)
            ->where('status', 'diterima')
            ->sum('nilai');

        $totalDebit = Tabungan::where('users_id', $id)
            ->where('status', 'dipotong')
            ->sum('debit');

        $totalSaldo = $totalKredit - $totalDebit;

        return view('pengurus.simpanan.tabungan.detail', compact(
            'user',
            'tabungans',
            'totalSaldo',
            'tanggal',
            'status'
        ));
    }
    private function writeLog($message)
    {
        // Folder log: storage/logs/tabungan/
        $folderPath = storage_path('logs/tabungan');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Nama file per hari: YYYY-MM-DD.log
        $filePath = $folderPath . '/' . date('Y-m-d') . '.log';

        // Format log
        $timestamp = date('[Y-m-d H:i:s]');
        $logMessage = $timestamp . ' ' . $message . PHP_EOL;

        // Append ke file
        file_put_contents($filePath, $logMessage, FILE_APPEND);
    }
    public function downloadExcel()
    {
        // Ambil semua tabungan
        $tabungan = Tabungan::with('user')->get();

        // Kelompokkan berdasarkan user_id
        $grouped = $tabungan->groupBy('users_id')->map(function ($items) {
            return [
                'nama' => $items->first()->user->nama ?? '-',
                'total_nilai' => $items->sum('nilai'),
                'total_debit' => $items->sum('debit'),
                'first_tanggal' => $items->first()->tanggal,
                'last_tanggal' => $items->last()->tanggal,
                'created_at' => $items->first()->created_at,
            ];
        });

        $filename = 'rekap_tabungan_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($grouped) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'Nama Anggota',
                'Total Nilai',
                'Total Debit',
                'Tanggal Pertama',
                'Tanggal Terakhir',
                'Tanggal Input Pertama'
            ]);

            // Isi CSV per user
            foreach ($grouped as $row) {
                fputcsv($handle, [
                    $row['nama'],
                    $row['total_nilai'],
                    $row['total_debit'],
                    $row['first_tanggal'],
                    $row['last_tanggal'],
                    $row['created_at'],
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function potongSemua()
    {
        $users = User::with('tabungans')->get();
        $pengurus = Auth::guard('pengurus')->user();

        $rekap = []; // untuk keperluan export nanti

        foreach ($users as $user) {

            $totalSaldo = $user->totalSaldo();

            if ($totalSaldo <= 0) {
                continue; // skip jika saldo kosong
            }

            // Buat transaksi pemotongan
            Tabungan::create([
                'users_id' => $user->id,
                'pengurus_id' => $pengurus->id,
                'tanggal' => now()->format('Y-m-d'),
                'nilai' => 0,
                'debit' => $totalSaldo,
                'status' => 'dipotong',
            ]);

            // Simpan untuk rekap download
            $rekap[] = [
                'nama' => $user->nama,
                'saldo_dipotong' => $totalSaldo,
                'tanggal' => now()->format('Y-m-d H:i:s'),
                'dipotong_oleh' => $pengurus->nama
            ];
        }

        // Simpan session untuk file Excel
        session(['rekap_potongan' => $rekap]);

        return redirect()
            ->route('pengurus.tabungan.potong_semua.download')
            ->with('success', 'Semua tabungan berhasil dipotong.');
    }

    public function downloadPotonganExcel()
    {
        $rekap = session('rekap_potongan');

        if (!$rekap) {
            return back()->with('error', 'Tidak ada data pemotongan.');
        }

        $filename = 'rekap_pemotongan_massal_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($rekap) {

            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'Nama Anggota',
                'Saldo Dipotong',
                'Tanggal',
                'Diproses Oleh'
            ]);

            foreach ($rekap as $row) {
                fputcsv($handle, [
                    $row['nama'],
                    $row['saldo_dipotong'],
                    $row['tanggal'],
                    $row['dipotong_oleh']
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

}