<?php

namespace Database\Seeders;

use App\Models\RefundPolicy;
use Illuminate\Database\Seeder;

class RefundPolicySeeder extends Seeder
{
    public function run(): void
    {
        RefundPolicy::create([
            'name' => 'Hủy trước 7 ngày',
            'days_before_checkin' => 7,
            'refund_percentage' => 100,
            'cancellation_fee_percentage' => 0,
            'description' => 'Hoàn trả 100% số tiền đã thanh toán'
        ]);

        RefundPolicy::create([
            'name' => 'Hủy từ 3-7 ngày',
            'days_before_checkin' => 3,
            'refund_percentage' => 70,
            'cancellation_fee_percentage' => 30,
            'description' => 'Hoàn trả 70% số tiền đã thanh toán'
        ]);

        RefundPolicy::create([
            'name' => 'Hủy từ 1-3 ngày',
            'days_before_checkin' => 1,
            'refund_percentage' => 50,
            'cancellation_fee_percentage' => 50,
            'description' => 'Hoàn trả 50% số tiền đã thanh toán'
        ]);

        RefundPolicy::create([
            'name' => 'Hủy trong 24 giờ',
            'days_before_checkin' => 0,
            'refund_percentage' => 0,
            'cancellation_fee_percentage' => 100,
            'description' => 'Không hoàn trả số tiền đã thanh toán'
        ]);
    }
}