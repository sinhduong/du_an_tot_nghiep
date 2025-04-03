<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên chương trình khuyến mãi
            $table->decimal('value', 10, 2); // Giá trị giảm giá
            $table->enum('type', ['percent', 'fixed']); // Loại giảm giá: phần trăm hoặc cố định
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết với room_types
            $table->dateTime('start_date')->nullable(); // Thời gian bắt đầu chương trình khuyến mãi
            $table->dateTime('end_date')->nullable(); // Thời gian kết thúc chương trình khuyến mãi
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái: active hoặc inactive
            $table->timestamps(); // Thêm created_at và updated_at
        });
    }

    public function down(): void
    {
        Schema::table('sale_room_types', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']); // Xóa khóa ngoại trước khi xóa bảng
        });

        Schema::dropIfExists('sale_room_types');
    }
};
