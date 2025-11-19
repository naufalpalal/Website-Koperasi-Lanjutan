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

        // VALIDASI DINAMIS
        $rules = [
            'nama' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'alamat_rumah' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Validasi nip hanya jika DIUBAH oleh user
        if ($request->nip !== $user->nip) {
            $rules['nip'] = 'required|string|max:20|unique:users,nip';
        } else {
            $rules['nip'] = 'required|string|max:20';
        }

        // Validasi no telepon hanya jika DIUBAH oleh user
        if ($request->no_telepon !== $user->no_telepon) {
            $rules['no_telepon'] = 'required|string|max:15|unique:users,no_telepon';
        } else {
            $rules['no_telepon'] = 'required|string|max:15';
        }

        // Jalankan validasi
        $validated = $request->validate($rules);

        // === Upload Foto Baru ===
        if ($request->hasFile('photo')) {
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->photo_path = $path;
        }

        // === Update Password Jika Ada ===
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // === Update Data Profil ===
        $user->nama = $validated['nama'];
        $user->nip = $validated['nip'];
        $user->no_telepon = $validated['no_telepon'];
        $user->alamat_rumah = $validated['alamat_rumah'] ?? $user->alamat_rumah;

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Profil atau Password Berhasil Diperbarui');
    }


}
