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
        // Schema::create('blog_catalogs', function (Blueprint $table) {
        //     $table->id();
        //     $table->bigInteger('blog_id')->onDelete('cascade');
        //     $table->bigInteger('catalog_id')->onDelete('cascade');
        //     $table->timestamps();
        //     $table->softDeletes();//delete_at xóa mềm
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_catalogs');
    }
};
