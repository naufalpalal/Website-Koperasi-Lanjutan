<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Tabungan2Controller extends Controller
{
    /**
     * Menampilkan daftar anggota dengan total saldo tabungan
     */
    public function index(Request $request)
    {
<<<<<<< HEAD
        $search = $request->search;

        $tabungans = User::with('tabungans')
            ->where('role', 'anggota')
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
=======
        // Ambil semua tabungan dengan relasi user
            $tabungans = Tabungan::with('user')->whereHas('user', function ($query) {
            $query = User::query(); // hanya user yang role anggota
        })
        ->latest()
        ->get();

        // Ambil semua anggota untuk ditampilkan di form
        $users = User::get();
        // Sesuaikan dengan path view kamu
        return view('pengurus.simpanan.tabungan.index', compact('tabungans', 'users'));
>>>>>>> e3c6bd7312397e601a245f0c9071b96c70dcc81d
    }

    /**
     * Setujui tabungan (ubah status menjadi diterima)
     */
    public function approve($id)
    {
        $tabungan = Tabungan::findOrFail($id);
        $tabungan->update(['status' => 'diterima']);

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
        $tabungan->update(['status' => 'ditolak']);

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
            'tanggal'  => ['required', 'date', 'after_or_equal:today'],
            'nilai'    => 'required|numeric|min:1000',
        ]);

        Tabungan::create([
            'users_id'        => $request->users_id,
            'tanggal'         => $request->tanggal,
            'nilai'           => $request->nilai,
            'status'          => 'diterima', // langsung diterima karena pengurus yang input
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
            'tanggal'  => 'required|date',
            'debit'    => 'required|numeric|min:1',
        ]);

        $user = User::findOrFail($request->users_id);
        $saldoSekarang = $user->totalSaldo();

        if ($saldoSekarang < $request->debit) {
            return back()->with('error', 'Saldo tidak mencukupi untuk melakukan debit.');
        }

        Tabungan::create([
            'users_id' => $request->users_id,
            'tanggal'  => $request->tanggal,
            'nilai'    => 0,
            'debit'    => $request->debit,
            'status'   => 'dipotong',
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
            'tanggal'  => 'required|date',
            'nilai'    => 'required|numeric|min:1',
        ]);

        Tabungan::create([
            'users_id'       => $request->users_id,
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