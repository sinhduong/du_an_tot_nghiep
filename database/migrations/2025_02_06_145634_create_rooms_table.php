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
            $table->string('room_number')->unique(); // Số phòng riêng biệt
            $table->decimal('price', 20, 2)->nullable();
            $table->tinyInteger('capacity'); // Số người tối đa
            $table->integer('adults')->default(1); // Số người lớn
            $table->integer('children')->default(0); // Số trẻ em
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
