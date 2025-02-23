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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('manager_id')->nullable();
            $table->string('room_number')->unique(); // Số phòng riêng biệt
            $table->decimal('price', 20, 2)->nullable();
            $table->integer('max_capacity'); // Tổng số người tối đa
            $table->enum('bed_type', ['single', 'double', 'queen', 'king', 'bunk', 'sofa'])->default('double');
            $table->integer('children_free_limit')->default(0); // Số trẻ em miễn phí
            $table->string('description')->nullable();
            $table->enum('status', ['available', 'booked', 'maintenance'])->default('available');
            $table->bigInteger('room_type_id');
            $table->timestamps();
            $table->softDeletes();//dekete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }

};
