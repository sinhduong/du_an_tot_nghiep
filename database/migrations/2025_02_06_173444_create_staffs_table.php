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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar');
            $table->date('birthday')->nullable();
            $table->string('phone');
            $table->string('role');
            $table->string('email')->unique();
            $table->enum('status', ['active', 'inactive', 'on_leave']);
            $table->decimal('salary', 10, 2);
            $table->bigInteger('room_id');
            $table->timestamps();
            $table->softDeletes();//dekete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
