<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{

    public function index()
    {
        return view('admin.layouts.laporan.index');
    }

    // Laporan Simpanan
    public function simpanan()
    {
        // Logika ambil data simpanan dari database
        // Contoh: $data = Simpanan::all();

        return view('laporan.simpanan', [
            // 'data' => $data
        ]);
    }

    // Laporan Pinjaman
    public function pinjaman()
    {
        // Logika ambil data pinjaman dari database
        // Contoh: $data = Pinjaman::all();

        return view('laporan.pinjaman', [
            // 'data' => $data
        ]);
    }

    // Laporan Keuangan
    public function keuangan()
    {
        // Logika ambil data keuangan dari database
        // Contoh: $data = Keuangan::all();

        return view('laporan.keuangan', [
            // 'data' => $data
        ]);
    }
}
