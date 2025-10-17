<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tabungan;
use Carbon\Carbon;

class TabunganController extends Controller
{
    // Menampilkan daftar tabungan user
    public function index(Request $request)
    {
        $tabungans = Tabungan::where('users_id', auth()->id())
                    ->orderBy('tanggal', 'desc')
                    ->paginate(10);

        $showQr = $request->has('show_qr'); // jika ada parameter show_qr, tampilkan QR
        $tanggal = $request->input('tanggal');
        $nilai = $request->input('nilai');

        return view('user.simpanan.tabungan.index', compact('tabungans', 'showQr', 'tanggal', 'nilai'));
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
            'tanggal' => 'required|date',
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
        $totalTabungan = Tabungan::where('users_id', auth()->id())
                                  ->where('status', 'diterima')
                                  ->sum('nilai');

        return view('user.dashboard.index', compact('totalTabungan'));
    }
}