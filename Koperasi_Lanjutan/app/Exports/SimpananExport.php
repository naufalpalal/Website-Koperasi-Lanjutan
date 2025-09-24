<?php

namespace App\Exports;

use App\Models\Pengurus\SimpananWajib;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SimpananExport implements FromCollection, WithHeadings, WithEvents
{
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
        return [
            ['Logo Universitas Anda'], // ini akan diganti dengan image di event
            ['Nama', 'Nominal', 'Bulan', 'Tahun', 'Status'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // menambahkan logo universitas
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo Universitas');
                $drawing->setPath(public_path('assets/poliwangi_icon.png')); // letakkan logo di public/assets/images/logo.png
                $drawing->setHeight(50);
                $drawing->setCoordinates('A1'); // posisi logo
                $drawing->setWorksheet($event->sheet->getDelegate());
            },
        ];
    }
}
