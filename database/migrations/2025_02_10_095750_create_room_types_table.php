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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // Mô tả dài hơn
            $table->decimal('price', 10, 2); // Giá của loại phòng
            $table->integer('max_capacity'); // Số người tối đa
            $table->float('size')->nullable(); // Kích thước phòng (m²)
            $table->enum('bed_type', ['single', 'double', 'queen', 'king', 'bunk', 'sofa'])->default('double'); // Loại giường
            $table->integer('children_free_limit')->default(0); // Số trẻ em miễn phí
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); //delete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
