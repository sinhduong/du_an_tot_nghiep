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
        Schema::create('staff_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên ca (Ca sáng, Ca chiều)
            $table->time('start_time'); // Giờ bắt đầu
            $table->time('end_time'); // Giờ kết thúc
            $table->timestamps();
            $table->softDeletes();//dekete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_shifts');
    }
};
