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
        // Sửa đổi bảng refunds
        DB::statement("ALTER TABLE refunds MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");

        // Sửa đổi bảng refund_transactions
        DB::statement("ALTER TABLE refund_transactions MODIFY COLUMN transaction_type ENUM('refund_request', 'refund', 'refund_reject') NOT NULL");
        DB::statement("ALTER TABLE refund_transactions MODIFY COLUMN status ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // refunds
        DB::statement("ALTER TABLE refunds MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending'");

        // refund_transactions
        DB::statement("ALTER TABLE refund_transactions MODIFY COLUMN transaction_type VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE refund_transactions MODIFY COLUMN status VARCHAR(255) NOT NULL DEFAULT 'pending'");
    }
};
