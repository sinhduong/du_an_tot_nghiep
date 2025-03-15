<?php

namespace App\Helpers;

use Carbon\Carbon;

class FormatHelper
{
    public static function formatPrice($price)
    {
        return number_format($price, 0, ',', '.') . ' VND';
    }

    public static function formatDate($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }
    public static function formatDateTime($date)
    {
        return $date ? Carbon::parse($date)->format('d-m-Y H:i') : 'Không có';
    }
}
