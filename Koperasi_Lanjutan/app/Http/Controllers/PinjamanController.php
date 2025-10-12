<?php

namespace App\Http\Controllers;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DokumenPinjaman;
use Illuminate\Support\Facades\Storage;


class PinjamanController extends Controller
{
    // Menampilkan semua pinjaman anggota di pengurus
public function index()
{
    $pinjamans = Pinjaman::with('user')
                    ->where('status', 'pending')
                    ->get();
    return view('pengurus.pinjaman.index', compact('pinjamans'));
}

// Melihat detail pinjaman dan dokumen
public function show($id)
{
    $pinjaman = Pinjaman::with('user')->findOrFail($id);
    return view('pengurus.pinjaman.show', compact('pinjaman'));
}

// Verifikasi pinjaman (setuju/ditolak + bunga, tenor, angsuran)
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
            'angsuran' => ($pinjaman->jumlah + ($pinjaman->jumlah * $request->bunga / 100)) / $request->tenor,
        ]);
    } else {
        $pinjaman->update([
            'status' => 'ditolak'
        ]);
    }

    return redirect()->route('pengurus.pinjaman.index')
        ->with('success', 'Pinjaman telah diverifikasi.');
}

// Download surat tipe 2 (final) untuk anggota
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

    // Form pengajuan pinjaman anggota
    public function create()
    {
        return view('user.pinjaman.create');
    }

    // Simpan data pinjaman
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

        return redirect()->route('user.pinjaman.uploadForm', $pinjaman->id)
                     ->with('success', 'Pengajuan pinjaman berhasil dibuat. Silakan unduh surat pinjaman dan upload kembali setelah ditandatangani.');
    }

    // Generate surat pinjaman
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

    // Upload surat pinjaman yang sudah ditandatangani
    public function upload(Request $request, $id)
    {
        $request->validate([
            'dokumen_pinjaman' => 'required|file|mimes:pdf|max:2048',
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
        $pinjaman->update([
            'dokumen_pinjaman' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Surat pinjaman berhasil diupload.');
    }

    public function uploadForm($id)
{
    $pinjaman = Pinjaman::findOrFail($id);
    return view('user.pinjaman.upload', compact('pinjaman'));
}

//     public function hapusDokumen($id)
// {
//     $dokumen = DokumenPinjaman::findOrFail($id);
//     Storage::disk('public')->delete($dokumen->file_path);
//     $dokumen->delete();

//     return back()->with('success', 'Dokumen berhasil dihapus.');
// }
}
