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
            Schema::create('booking_room_services', function (Blueprint $table) {
                $table->id();
                $table->string('booking_room');
                $table->bigInteger('service_id');
                $table->timestamps();
                $table->softDeletes();//dekete_at xóa mềm
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('booking_room_services');
        }
    };
