<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm trạng thái cancelled_without_refund vào enum của cột status
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid', 'check_in', 'check_out', 'cancelled', 'cancelled_without_refund', 'refunded') NOT NULL DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid', 'check_in', 'check_out', 'cancelled', 'refunded') NOT NULL DEFAULT 'unpaid'");
    }
};
