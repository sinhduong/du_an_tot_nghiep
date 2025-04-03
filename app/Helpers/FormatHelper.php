<?php

namespace App\Helpers;

use Carbon\Carbon;

class FormatHelper
{
    public static function formatPrice($price)
    {
        return number_format($price, 0, ',', '.') . 'VND';
    }

    public static function formatDate($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }
    public static function formatDateVI($date)
    {
        $parsedDate = Carbon::parse($date);
        $day = $parsedDate->format('d'); // Lấy ngày (18)
        $month = $parsedDate->format('m'); // Lấy tháng (03)
        $year = $parsedDate->format('Y'); // Lấy tháng (03)

        // Loại bỏ số 0 ở đầu nếu có (ví dụ: 03 -> 3)
        $day = ltrim($day, '0');
        $month = ltrim($month, '0');
        $year = ltrim($year, '0');

        return "$day tháng $month năm $year";
    }
    public static function formatDateTime($date)
    {
        return $date ? Carbon::parse($date)->format('d-m-Y H:i') : 'Không có';
    }
}
