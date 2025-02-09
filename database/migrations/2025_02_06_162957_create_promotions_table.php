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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); 
            $table->string('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount']); // Giới hạn loại khuyến mãi
            $table->decimal('value', 10, 2);
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->integer('usage_limit')->default(1); // Có thể thêm default
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
