<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_room_type', function (Blueprint $table) {
            $table->bigInteger('promotion_id')->unsigned();
            $table->bigInteger('room_type_id')->unsigned();
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('cascade');
            $table->primary(['promotion_id', 'room_type_id']); // Khóa chính composite
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_room_type');
    }
};
