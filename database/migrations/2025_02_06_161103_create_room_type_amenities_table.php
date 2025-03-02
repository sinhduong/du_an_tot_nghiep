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
<<<<<<<< HEAD:database/migrations/2025_02_06_173441_create_staff_shifts_table.php
        Schema::create('staff_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên ca (Ca sáng, Ca chiều)
            $table->time('start_time'); // Giờ bắt đầu
            $table->time('end_time'); // Giờ kết thúc
========
        Schema::create('room_type_amenities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('room_type_id');
            $table->bigInteger('amenity_id');
>>>>>>>> origin/main:database/migrations/2025_02_06_161103_create_room_type_amenities_table.php
            $table->timestamps();
            $table->softDeletes();//dekete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2025_02_06_173441_create_staff_shifts_table.php
        Schema::dropIfExists('staff_shifts');
========
        Schema::dropIfExists('room_amenities');
>>>>>>>> origin/main:database/migrations/2025_02_06_161103_create_room_type_amenities_table.php
    }
};
