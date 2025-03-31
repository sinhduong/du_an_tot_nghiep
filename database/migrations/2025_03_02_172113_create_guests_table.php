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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('id_number')->nullable(); // Số CMND/CCCD
            $table->string('id_photo')->nullable(); // Đường dẫn ảnh căn cước
            $table->date('birth_date')->nullable(); // Ngày sinh
            $table->string('gender')->nullable(); // Giới tính
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('email')->nullable(); // Email
            $table->string('country')->nullable(); // quốc gia
            $table->string('relationship')->nullable(); // Quan hệ với người đặt (ví dụ: bạn, gia đình, v.v.)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
