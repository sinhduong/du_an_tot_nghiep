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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255); // Giới hạn độ dài tiêu đề
            $table->string('phone', 20); // Giới hạn số ký tự của số điện thoại
            $table->string('email')->unique(); // Email không trùng lặp
            $table->text('content'); // Cho phép nội dung dài
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Trạng thái liên hệ
            $table->timestamps();
            $table->softDeletes();//delete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
