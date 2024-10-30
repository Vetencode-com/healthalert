<?php

namespace App\Libraries;

use DateTime;

class Formatter
{
    // d F Y
    public static function commonDate($date): string
    {
        $dateObject = new DateTime($date);

        $months = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $day = $dateObject->format('d');
        $month = $months[(int)$dateObject->format('m')];
        $year = $dateObject->format('Y');

        return "{$day} {$month} {$year}";
    }

    public static function shortTime($time)
    {
        return date('H:i', strtotime($time));
    }
}
