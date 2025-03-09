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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->enum('method', ['momo', 'vnpay', 'cash']);
            $table->decimal('amount', 20, 2)->nullable();
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->string('transaction_id')->nullable(); // Có thể nullable nếu chưa có giao dịch
            $table->bigInteger('booking_id');
            $table->timestamps();
            $table->softDeletes();//dekete_at xóa mềm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
