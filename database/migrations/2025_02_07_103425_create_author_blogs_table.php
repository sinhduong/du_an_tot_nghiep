<?php

use App\Models\Staff;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('author_blogs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('staff_id');
            $table->bigInteger('blog_id');
            $table->timestamps();
            $table->softDeletes();//delete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_blogs');
    }
};
