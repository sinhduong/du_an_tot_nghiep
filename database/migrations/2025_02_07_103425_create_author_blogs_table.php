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
    public function up()
{
    // Schema::create('author_blogs', function (Blueprint $table) {
    //     $table->id();
    //     $table->unsignedBigInteger('blog_id');
    //     $table->unsignedBigInteger('staff_id'); // ID nhân viên
    //     $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
    //     $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
    //     $table->timestamps();
    // });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_blogs');
    }
};
