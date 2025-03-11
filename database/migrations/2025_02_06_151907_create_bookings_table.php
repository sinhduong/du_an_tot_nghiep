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
            $table->dateTime('check_in'); 
            $table->dateTime('check_out'); 
            $table->dateTime('actual_check_in')->nullable();
            $table->dateTime('actual_check_out')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->integer('total_guests'); // Tổng số khách đặt phòng
            $table->integer('children_count')->default(0); // Số trẻ em
            $table->integer('room_quantity')->default(1); // Số lượng phòng được đặt
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
