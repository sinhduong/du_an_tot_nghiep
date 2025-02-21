<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique()->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', [
                'pending_confirmation', //chờ xác nhận
                'confirmed', //đã xác nhận
                'paid', //đã thanh toán
                'check_in', //đã vào
                'check_out', //đã ra
                'cancelled', //dã hủy
                'refunded' //được hoàn tiền
            ])->default('pending_confirmation');
            $table->bigInteger('user_id');
            $table->bigInteger('room_id');
            $table->timestamps();
            $table->softDeletes(); //dekete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
