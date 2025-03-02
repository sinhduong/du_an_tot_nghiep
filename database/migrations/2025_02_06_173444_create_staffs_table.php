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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Liên kết tai khoan
            $table->foreignId('role_id')->constrained('staff_roles')->onDelete('cascade'); // Liên kết vai trò
            $table->foreignId('shift_id')->nullable()->constrained('staff_shifts')->onDelete('set null'); // Ca làm việc
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái làm việc
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
