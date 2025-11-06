<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tabungan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TabunganController extends Controller
{
    // Menampilkan daftar tabungan user
    public function index(Request $request)
    {
        $tabungans = Tabungan::where('users_id', auth()->id())
                    ->orderBy('id', 'desc')
                    ->take(5)
                    ->get();

        $showQr = $request->has('show_qr'); // jika ada parameter show_qr, tampilkan QR
        $tanggal = $request->input('tanggal');
        $nilai = $request->input('nilai');

        return view('user.simpanan.tabungan.index', compact('tabungans', 'showQr', 'tanggal', 'nilai'));
    }

    public function historyFull(Request $request)
    {
        $user = Auth::user();

        $tanggal = $request->input('tanggal');
        $status  = $request->input('status');

        $query = Tabungan::where('users_id', $user->id);

        if (!empty($tanggal)) {
            $query->whereDate('tanggal', $tanggal);
        }
        if (!empty($status)) {
            $query->where('status', $status);
        }

        $tabungans = $query->orderBy('tanggal', 'desc')->paginate(10);
        return view('user.simpanan.tabungan.history', compact('tabungans', 'tanggal', 'status'));
    }

    // Form tambah tabungan
    public function create(Request $request)
    {
        $showQr = $request->has('show_qr'); // jika ada parameter show_qr, tampilkan QR
        $tabungans = Tabungan::where('users_id', auth()->id())->latest()->get();

        return view('user.simpanan.tabungan.index', compact('tabungans', 'showQr'));
    }

    // Simpan tabungan baru
    public function store(Request $request)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:1000',
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'bukti_transfer' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maks 2MB
        ]);

        // Siapkan nama file bukti transfer (jika ada)
        $namaFile = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_transfer'), $namaFile);
        }

        // Simpan ke database
        Tabungan::create([
            'users_id' => auth()->id(),
            'tanggal' => $request->tanggal,
            'nilai' => $request->nilai,
            'status' => 'pending',
            'pengurus_id' => null,
            'bukti_transfer' => $namaFile,
        ]);

        return redirect()->route('user.simpanan.tabungan.index')
                         ->with('success', 'Tabungan berhasil diajukan.');
    }

    // Update status tabungan (digunakan oleh pengurus)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $tabungan = Tabungan::findOrFail($id);
        $tabungan->status = strtolower($request->status);
        $tabungan->save();

        return redirect()->back()->with('success', 'Status tabungan berhasil diperbarui.');
    }

    // Dashboard pengguna
    public function dashboard()
    {

    $userId = auth()->id();
    // Total tabungan yang diterima
    $totalDiterima = Tabungan::where('users_id', $userId)
                             ->where('status', 'diterima')
                             ->sum('nilai');

    // Total saldo yang sudah dipotong / diambil
    $totalDipakai = Tabungan::where('users_id', $userId)
                             ->where('status', 'dipotong')
                             ->sum('debit');

    // Hitung saldo akhir
    $totalTabungan = $totalDiterima - $totalDipakai;

        return view('user.dashboard.index', compact('totalTabungan'));
    }
}