<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết với bảng users
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // Thay room_id bằng booking_id
            $table->integer('rating'); // Điểm đánh giá (ví dụ: 1-5)
            $table->text('comment')->nullable(); // Bình luận của người dùng
            $table->text('response')->nullable(); // Phản hồi từ admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
