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
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('email')->unique();
            $table->enum('status', ['active', 'inactive', 'on_leave']);
            $table->enum('role', ['admin', 'manager', 'employee']);
            $table->decimal('salary', 10, 2);
            $table->date('date_hired');
            $table->string('insurance_number')->unique()->nullable();
            $table->string('contract_type');
            $table->date('contract_start');
            $table->date('contract_end')->nullable();
            $table->text('notes')->nullable();
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
