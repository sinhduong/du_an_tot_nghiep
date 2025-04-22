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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('paid_amount', 15, 2)->default(0)->after('total_price');
            $table->enum('status', [
                'unpaid',    // chưa thanh toán
                'partial',   // thanh toán một phần
                'paid',      // đã thanh toán
                'check_in',  // đã vào
                'check_out', // đã ra
                'cancelled', // đã hủy
                'refunded'   // được hoàn tiền
            ])->default('unpaid')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });

        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('confirmed', 'paid', 'check_in', 'check_out', 'cancelled', 'refunded') NOT NULL DEFAULT 'confirmed'");
    }
}; 