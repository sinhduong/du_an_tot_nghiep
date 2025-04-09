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
            $table->decimal('discount_amount', 15, 2)->nullable(); // Thêm trường giảm giá
            $table->decimal('base_price', 15, 2)->nullable(); // Thêm trường giá gốc
            $table->decimal('service_total', 15, 2)->nullable(); // Thêm trường tổng chi phí dịch vụ
            $table->decimal('tax_fee', 15, 2)->nullable(); // Trường thuế đã có, giữ nguyên
            $table->integer('total_guests'); // Tổng số khách đặt phòng
            $table->integer('children_count')->default(0); // Số trẻ em
            $table->integer('room_quantity')->default(1); // Số lượng phòng được đặt
            $table->enum('status', [
                'confirmed', //đã xác nhận
                'paid', //đã thanh toán
                'check_in', //đã vào
                'check_out', //đã ra
                'cancelled', //dã hủy
                'refunded' //được hoàn tiền
            ])->default('confirmed');
            $table->bigInteger('user_id');
            $table->text('special_request')->nullable(); // Thêm trường yêu cầu đặc biệt
            $table->timestamps();
            $table->softDeletes(); //dekete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
        // Sau đó xóa bảng bookings
        Schema::dropIfExists('bookings');
    }
};
