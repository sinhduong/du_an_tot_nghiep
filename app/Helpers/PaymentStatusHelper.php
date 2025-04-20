<?php

namespace App\Helpers;

class PaymentStatusHelper
{
    public static function getStatusLabel($status)
    {
        $statusLabels = [
            'pending' => 'Đang chờ',
            'completed' => 'Đã hoàn thành',
            'failed' => 'Không hoàn thành'
        ];

        return $statusLabels[$status] ?? $status;
    }

    public static function getStatusClass($status)
    {
        $statusClasses = [
            'pending' => 'badge bg-warning',
            'completed' => 'badge bg-success',
            'failed' => 'badge bg-danger'
        ];

        return $statusClasses[$status] ?? 'badge bg-secondary';
    }
} 