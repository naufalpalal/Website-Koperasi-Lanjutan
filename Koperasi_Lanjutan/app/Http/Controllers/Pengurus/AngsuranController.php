<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;
use Illuminate\Http\Request;

class AngsuranController extends Controller
{
    // Tampilkan semua angsuran untuk pengurus
     public function index($pinjaman_id)
    {
        $pinjaman = Pinjaman::findOrFail($pinjaman_id);
        $angsuran = Angsuran::where('pinjaman_id', $pinjaman_id)->get();

        return view('pengurus.pinjaman.angsuran.index', compact('pinjaman', 'angsuran'));
    }

}

