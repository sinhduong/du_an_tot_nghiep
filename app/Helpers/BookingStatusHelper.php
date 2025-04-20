<?php

namespace App\Helpers;

class BookingStatusHelper
{
    public static function getStatusLabel($status)
    {
        $statusLabels = [
            'unpaid' => 'Chưa thanh toán',
            'partial' => 'Đã cọc',
            'paid' => 'Đã thanh toán',
            'check_in' => 'Đã check in',
            'check_out' => 'Đã checkout',
            'cancelled' => 'Đã hủy',
            'cancelled_without_refund' => 'Đã hủy không hoàn tiền',
            'refunded' => 'Đã hoàn tiền'
        ];

        return $statusLabels[$status] ?? $status;
    }

    public static function getStatusClass($status)
    {
        $statusClasses = [
            'unpaid' => 'badge bg-warning',
            'partial' => 'badge bg-info',
            'paid' => 'badge bg-success',
            'check_in' => 'badge bg-primary',
            'check_out' => 'badge bg-secondary',
            'cancelled' => 'badge bg-danger',
            'cancelled_without_refund' => 'badge bg-danger',
            'refunded' => 'badge bg-dark'
        ];

        return $statusClasses[$status] ?? 'badge bg-secondary';
    }
} 