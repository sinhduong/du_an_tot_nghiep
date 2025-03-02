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
<<<<<<<< HEAD:database/migrations/2025_02_06_173442_create_staff_roles_table.php
        Schema::create('staff_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Tên vai trò (Admin, Nhân viên...)
            $table->json('permissions')->nullable(); // Quyền hạn (dạng JSON)
========
        Schema::create('booking_guest', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booking_id');
            $table->bigInteger('guest_id');
            $table->softDeletes();
>>>>>>>> origin/main:database/migrations/2025_03_02_172555_create_booking_guest_table.php
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2025_02_06_173442_create_staff_roles_table.php
        Schema::dropIfExists('staff_roles');
========
        Schema::dropIfExists('booking_guest');
>>>>>>>> origin/main:database/migrations/2025_03_02_172555_create_booking_guest_table.php
    }
};
