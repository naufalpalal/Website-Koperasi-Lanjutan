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
        $tabungan->update([
            'status' => 'diterima',
            'pengurus_id' => Auth::guard('pengurus')->id()
        ]);

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
        $tabungan->update([
            'status' => 'ditolak',
            'pengurus_id' => Auth::guard('pengurus')->id()
        ]);

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
            'tanggal'  => 'required|date|after_or_equal:2000-01-01|before_or_equal:' . now()->format('Y-m-d'),
            'nilai'    => 'required|numeric|min:1000',
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
            'users_id'        => $request->users_id,
            'pengurus_id'     => Auth::guard('pengurus')->id(),
            'tanggal'         => $request->tanggal,
            'nilai'           => $request->nilai,
            'status'          => 'diterima',
            'bukti_transfer'  => null,
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
            'tanggal'  => 'required|date|before_or_equal:today',
            'debit'    => 'required|numeric|min:100',
        ], [
            'users_id.required' => 'User tidak ditemukan.',
            'users_id.exists'   => 'Data user tidak valid.',
            'tanggal.required'  => 'Tanggal harus diisi.',
            'tanggal.date'      => 'Tanggal tidak sesuai.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'debit.required'    => 'Nominal harus diisi.',
            'debit.numeric'     => 'Nominal harus berupa angka.',
            'debit.min'         => 'Nominal minimal 100.',
        ]);

        $user = User::findOrFail($request->users_id);
        $saldoSekarang = $user->totalSaldo();

        if ($saldoSekarang < $request->debit) {
            return back()->withInput()->with('error', 'Saldo tidak mencukupi untuk melakukan debit.');
        }

        Tabungan::create([
            'users_id'   => $user->id,
            'pengurus_id'=> Auth::guard('pengurus')->id(),
            'tanggal'    => $request->tanggal,
            'nilai'      => 0,
            'debit'      => $request->debit,
            'status'     => 'dipotong',
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
            'tanggal'  => 'required|date|before_or_equal:today',
            'nilai'    => 'required|numeric|min:100',
        ], [
            'users_id.required' => 'User tidak ditemukan.',
            'users_id.exists'   => 'Data user tidak valid.',
            'tanggal.required'  => 'Tanggal harus diisi.',
            'tanggal.date'      => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
            'nilai.required'    => 'Nominal harus diisi.',
            'nilai.numeric'     => 'Nominal harus berupa angka.',
            'nilai.min'         => 'Nominal minimal Rp 100.',
        ]);

        Tabungan::create([
            'users_id'       => $request->users_id,
            'pengurus_id'    => Auth::guard('pengurus')->id(),
            'tanggal'        => $request->tanggal,
            'nilai'          => $request->nilai,
            'status'         => 'diterima',
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
        $status  = $request->input('status');

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
            'user', 'tabungans', 'totalSaldo', 'tanggal', 'status'
        ));
    }
}