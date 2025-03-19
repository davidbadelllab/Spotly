<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->string('room_type'); // single, double, suite, etc.
            $table->integer('capacity'); // max number of guests
            $table->integer('floor_number');
            $table->decimal('price_per_night', 10, 2);
            $table->integer('bed_count');
            $table->string('bed_type'); // king, queen, twin, etc.
            $table->integer('bathroom_count')->default(1);
            $table->boolean('has_balcony')->default(false);
            $table->boolean('has_kitchen')->default(false);
            $table->boolean('has_wifi')->default(true);
            $table->json('amenities')->nullable(); // TV, minibar, safe, etc.
            $table->text('description')->nullable();
            $table->text('policies')->nullable(); // cancellation policy, check-in/out times
            $table->boolean('is_smoking')->default(false);
            $table->boolean('is_accessible')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['venue_id', 'room_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
