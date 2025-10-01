<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetRequestController extends Controller
{
    // Anggota ajukan reset password
    public function requestReset(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string',
            'nip'      => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cari user berdasarkan nama & nip
        $user = User::where('name', $request->nama)
            ->where('nip', $request->nip)
            ->firstOrFail();

        // Simpan permintaan reset password
        PasswordResetRequest::create([
            'user_id'  => $user->id,
            'password' => Hash::make($request->password), // simpan hash
            'status'   => 'pending',
        ]);

        return back()->with('success', 'Permintaan reset password dikirim, menunggu persetujuan pengurus.');
    }

    // Pengurus setujui reset
    public function approve($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);

        $user = $resetRequest->user;
        $user->password = $resetRequest->password; // password sudah hash
        $user->save();

        $resetRequest->update(['status' => 'approved']);

        return back()->with('success', 'Password berhasil direset.');
    }

    // Pengurus tolak reset
    public function reject($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);
        $resetRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan reset password ditolak.');
    }
}
