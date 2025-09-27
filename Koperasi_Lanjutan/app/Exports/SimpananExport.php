<?php

namespace App\Exports;

use App\Models\Pengurus\SimpananWajib;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SimpananExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $simpanan = SimpananWajib::with('user')->get();

        return $simpanan->map(function ($s) {
            return [
                'Nama' => $s->user->nama,
                'Nominal' => $s->nilai,
                'Bulan' => $s->bulan,
                'Tahun' => $s->tahun,
                'Status' => $s->status,
            ];
        });
    }
    public function headings(): array
    {
        return ['Nama', 'Nominal', 'Bulan', 'Tahun', 'Status'];
    }

}