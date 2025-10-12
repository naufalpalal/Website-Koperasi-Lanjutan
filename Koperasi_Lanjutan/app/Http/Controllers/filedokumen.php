<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Dokumen;
use App\Models\Pengurus\MasterSimpananWajib;
use Illuminate\Support\Facades\Auth;

class filedokumen extends Controller
{
    // 🔹 Generate PDF Pendaftaran Anggota
    public function dokumenVerifikasi()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil data Master Simpanan Wajib terakhir dari pengurus
        $master = MasterSimpananWajib::latest()->first();

        // Data yang dikirim ke view
        $data = [
            'user' => $user,
            'simpanan_wajib' => $master,
            'tanggal_permohonan' => now(),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('dokumen.PendaftaranAnggotain', $data);

        $filename = 'PendaftaranAnggota_' . $user->nama . '.pdf';

        return $pdf->download($filename);
    }

public function uploadDokumen(Request $request)
{
    // Validasi file
    $request->validate([
        'dokumen_pendaftaran' => 'required|file|mimes:pdf|max:2048',
        'sk_tenaga_kerja' => 'required|file|mimes:pdf|max:2048',
    ]);

    $user = auth()->user();

    // Cek apakah user sudah punya dokumen sebelumnya
    $dokumen = Dokumen::where('user_id', $user->id)->first();

    // Hapus file lama jika ada
    if ($dokumen) {
        if ($dokumen->dokumen_pendaftaran && file_exists(storage_path('app/private/' . $dokumen->dokumen_pendaftaran))) {
            unlink(storage_path('app/private/' . $dokumen->dokumen_pendaftaran));
        }
        if ($dokumen->sk_tenaga_kerja && file_exists(storage_path('app/private/' . $dokumen->sk_tenaga_kerja))) {
            unlink(storage_path('app/private/' . $dokumen->sk_tenaga_kerja));
        }
    }

    // Simpan file baru
    $file1 = $request->file('dokumen_pendaftaran');
    $file1Name = 'dokumen_pendaftaran_' . Str::random(10) . '.' . $file1->getClientOriginalExtension();
    $path1 = $file1->storeAs('private', $file1Name);

    $file2 = $request->file('sk_tenaga_kerja');
    $file2Name = 'sk_tenaga_kerja_' . Str::random(10) . '.' . $file2->getClientOriginalExtension();
    $path2 = $file2->storeAs('private', $file2Name);

    // Jika sudah ada -> update, jika belum -> create
    if ($dokumen) {
        $dokumen->update([
            'dokumen_pendaftaran' => $file1Name,
            'sk_tenaga_kerja' => $file2Name,
        ]);
    } else {
        Dokumen::create([
            'user_id' => $user->id,
            'dokumen_pendaftaran' => $file1Name,
            'sk_tenaga_kerja' => $file2Name,
        ]);
    }

    return redirect()->back()->with('success', 'Dokumen berhasil diupload.');
}

    public function lihatDokumen($userId, $jenis)
{
    // Ambil user yang login
    $currentUser = auth()->user();

    if (!$currentUser) {
        abort(403, 'Silakan login terlebih dahulu.');
    }

    // Ambil user target
    $targetUser = User::findOrFail($userId);

    // Hanya user sendiri atau pengurus bisa akses
    $allowedRoles = ['pengurus']; // bisa ditambahkan role lain jika perlu
    if ($currentUser->id !== $targetUser->id && !in_array($currentUser->role, $allowedRoles)) {
        abort(403, 'Akses ditolak.');
    }

    // Ambil dokumen
    $dokumen = Dokumen::where('user_id', $targetUser->id)->first();
    if (!$dokumen) {
        abort(404, 'Dokumen tidak ditemukan.');
    }

    // Tentukan jenis file
    switch ($jenis) {
        case 'pendaftaran':
            $fileName = $dokumen->dokumen_pendaftaran;
            break;
        case 'sk':
            $fileName = $dokumen->sk_tenaga_kerja;
            break;
        default:
            abort(400, 'Jenis dokumen tidak valid.');
    }

    $filePath = storage_path('app/private/private/' . $fileName);

    if (!file_exists($filePath)) {
        abort(404, 'File dokumen tidak ditemukan di server.');
    }

    return response()->file($filePath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $fileName . '"'
    ]);
}



    public function dashboardUpload()
    {
        return view('guest.upload');
    }
}
