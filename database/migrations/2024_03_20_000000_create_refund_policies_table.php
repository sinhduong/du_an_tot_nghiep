<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refund_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('days_before_checkin');
            $table->decimal('refund_percentage', 5, 2);
            $table->decimal('cancellation_fee_percentage', 5, 2);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_policies');
    }
};