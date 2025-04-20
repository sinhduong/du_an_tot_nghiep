<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm trạng thái cancelled_without_refund vào enum của cột status
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid', 'check_in', 'check_out', 'cancelled', 'cancelled_without_refund', 'refunded') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại enum cũ
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid', 'check_in', 'check_out', 'cancelled', 'refunded') NOT NULL");
    }
}; 