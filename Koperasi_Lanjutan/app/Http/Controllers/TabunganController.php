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
        $user = Auth::user();
        $tabungans = Tabungan::where('users_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $showQr = $request->has('show_qr');
        $tanggal = $request->input('tanggal');
        $nilai = $request->input('nilai');

        // Hitung total tabungan juga di halaman index (optional)
        $totalTabungan = $user->totalSaldo();

        return view('user.simpanan.tabungan.index', compact('tabungans', 'showQr', 'tanggal', 'nilai', 'totalTabungan'));
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
        $showQr = $request->has('show_qr');
        $user = Auth::user();
        $tabungans = Tabungan::where('users_id', $user->id)->latest()->get();

        $totalTabungan = $user->totalSaldo();

        return view('user.simpanan.tabungan.index', compact('tabungans', 'showQr', 'totalTabungan'));
    }

    // Simpan tabungan baru
    public function store(Request $request)
    {
        $request->validate([
            'nilai' => ['required', 'numeric', 'min:100'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'bukti_transfer' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'nilai.min' => 'Nominal tabungan minimal terdiri dari 3 digit angka.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'bukti_transfer.required' => 'Bukti transfer wajib diupload.',
            'bukti_transfer.image' => 'Bukti transfer harus berupa file gambar (jpg/png).',
        ]);

        $user = Auth::user();

        // Upload bukti transfer
        $namaFile = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_transfer'), $namaFile);
        }

        Tabungan::create([
            'users_id' => $user->id,
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
            'status' => 'required|in:pending,diterima,ditolak,dipotong',
        ]);

        $tabungan = Tabungan::findOrFail($id);
        $tabungan->status = strtolower($request->status);
        $tabungan->save();

        return redirect()->back()->with('success', 'Status tabungan berhasil diperbarui.');
    }
}