<?php

// app/Http/Controllers/AnggotaController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaAnggotController extends Controller
{
    public function index()
    {
        return view('admin.anggota.index');
    }
}
//     public function create()
//     {
//         return view('anggota.create');
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'name'     => 'required|string|max:255',
//             'email'    => 'required|email|unique:users',
//             'password' => 'required|min:6',
//         ]);

//         User::create([
//             'name'     => $request->name,
//             'email'    => $request->email,
//             'password' => Hash::make($request->password),
//             'role'     => 'anggota'
//         ]);

//         return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan');
//     }

//     public function edit($id)
//     {
//         $anggota = User::findOrFail($id);
//         return view('anggota.edit', compact('anggota'));
//     }

//     public function update(Request $request, $id)
//     {
//         $anggota = User::findOrFail($id);
//         $request->validate([
//             'name'  => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email,' . $id,
//         ]);

//         $anggota->update([
//             'name'  => $request->name,
//             'email' => $request->email,
//         ]);

//         return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui');
//     }

//     public function destroy($id)
//     {
//         $anggota = User::findOrFail($id);
//         $anggota->delete();
//         return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus');
//     }
// }
