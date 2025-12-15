<?php

namespace App\Helpers;

class Bulan
{
    public static function indoToEnglish(string $bulan): ?string
    {
        $map = [
            'januari'   => 'January',
            'februari'  => 'February',
            'maret'     => 'March',
            'april'     => 'April',
            'mei'       => 'May',
            'juni'      => 'June',
            'juli'      => 'July',
            'agustus'   => 'August',
            'september' => 'September',
            'oktober'   => 'October',
            'november'  => 'November',
            'desember'  => 'December',
        ];

        return $map[strtolower($bulan)] ?? null;
    }
}
