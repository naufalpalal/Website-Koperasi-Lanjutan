<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Tampilkan form edit profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update informasi profil dasar pengguna.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update password pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Update gabungan: profil + password + foto.
     */
   public function updateCombined(Request $request): RedirectResponse
{
    $user = auth()->user();

    // Validasi data dasar
    $request->validate([
        'nama'         => 'required|string|max:255',
        'nip'          => 'required|string|max:20|unique:users,nip,' . $user->id,
        'no_telepon'   => 'required|string|max:15|unique:users,no_telepon,' . $user->id,
        'photo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'alamat_rumah' => 'nullable|string|max:500',
        'password'     => 'nullable|string|min:8|confirmed',
    ]);

    // === Upload Foto Baru (jika ada) ===
    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada dan file eksis
        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        // Simpan foto baru ke storage/app/public/profile-photos
        $path = $request->file('photo')->store('profile-photos', 'public');

        // Simpan path baru ke kolom photo_path
        $user->photo_path = $path;
    }

    // === Update Password (jika diisi) ===
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // === Update Data Profil Lain ===
    $user->nama         = $request->nama;
    $user->nip          = $request->nip;
    $user->no_telepon   = $request->no_telepon;
    $user->alamat_rumah = $request->alamat_rumah;

    // Simpan semua perubahan
    $user->save();

    return redirect()
        ->route('profile.edit')
        ->with('status', 'Profil atau Password Berhasil Diperbarui');
}

}
