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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề bài viết
            $table->string('short_title')->nullable(); // Tiêu đề rút gọn (có thể null)
            $table->text('content'); // nội dung dài
            $table->boolean('is_active')->default(true); // Trạng thái kích hoạt
            $table->string('thumbnail')->nullable(); // Ảnh thu nhỏ, có thể null
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
