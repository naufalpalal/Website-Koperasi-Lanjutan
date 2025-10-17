<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\DokumenPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PinjamanController extends Controller
{
    // === BAGIAN PENGURUS ===
    public function index()
    {
        $pinjamans = Pinjaman::with('user')
                        ->where('status', 'pending')
                        ->get();

        return view('pengurus.pinjaman.index', compact('pinjamans'));
    }

    public function show($id)
    {
        $pinjaman = Pinjaman::with('user')->findOrFail($id);
        return view('pengurus.pinjaman.show', compact('pinjaman'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'bunga' => 'required_if:status,disetujui|numeric|min:0',
            'tenor' => 'required_if:status,disetujui|integer|min:1',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);

        if ($request->status === 'disetujui') {
            $pinjaman->update([
                'status' => 'disetujui',
                'bunga' => $request->bunga,
                'tenor' => $request->tenor,
                'angsuran' => ($pinjaman->nominal + ($pinjaman->nominal * $request->bunga / 100)) / $request->tenor,
            ]);
        } else {
            $pinjaman->update(['status' => 'ditolak']);
        }

        return redirect()->route('pengurus.pinjaman.index')
            ->with('success', 'Pinjaman telah diverifikasi.');
    }

    public function downloadFinal($id)
    {
        $pinjaman = Pinjaman::with('user')->findOrFail($id);

        if ($pinjaman->status !== 'disetujui') {
            abort(403, "Pinjaman belum disetujui");
        }

        $pdf = Pdf::loadView('dokumen.SuratPinjamanFinal', [
            'pinjaman' => $pinjaman,
            'tanggal' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Surat_Pinjaman_Final_'.$pinjaman->user->nama.'.pdf');
    }

    // === BAGIAN ANGGOTA ===

    public function create()
    {
        return view('user.pinjaman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:100000',
        ]);

        $pinjaman = Pinjaman::create([
            'user_id' => Auth::id(),
            'nominal' => $request->nominal,
            'status' => 'draft',
        ]);

        // ✅ Redirect ke halaman upload setelah pengajuan
        return redirect()
            ->route('user.pinjaman.uploadForm', $pinjaman->id)
            ->with('success', 'Pengajuan pinjaman berhasil dibuat. Silakan unduh surat pinjaman dan upload kembali setelah ditandatangani.');
    }

    public function download($id)
    {
        $pinjaman = Pinjaman::with('user')->findOrFail($id);
        $user = Auth::user();

        $pdf = Pdf::loadView('dokumen.SuratPinjaman', [
            'user' => $user,
            'pinjaman' => $pinjaman,
            'tanggal' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('Surat_Pinjaman_'.$pinjaman->user->nama.'.pdf');
    }

    public function uploadForm($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        return view('user.pinjaman.upload', compact('pinjaman'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'dokumen_pinjaman.*' => 'required|mimes:pdf|max:2048',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);

        if ($request->hasFile('dokumen_pinjaman')) {
            foreach ($request->file('dokumen_pinjaman') as $file) {
                $path = $file->store('dokumen_pinjaman', 'public');

                DokumenPinjaman::create([
                    'pinjaman_id' => $pinjaman->id,
                    'file_path' => $path,
                ]);
            }
        }

        $pinjaman->update(['status' => 'pending']);

        return back()->with('success', 'Surat pinjaman berhasil diupload.');
    }

    public function hapusDokumen($id)
    {
        $dokumen = DokumenPinjaman::findOrFail($id);
        Storage::disk('public')->delete($dokumen->file_path);
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
