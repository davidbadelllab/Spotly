<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sports_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('sport_type'); // padel, football, basketball, baseball
            $table->integer('capacity');
            $table->text('equipment_provided')->nullable();
            $table->text('rules')->nullable();
            $table->integer('duration_minutes')->default(60); // Default booking duration
            $table->decimal('price_per_hour', 10, 2);
            $table->json('amenities')->nullable(); // showers, lockers, etc.
            $table->boolean('is_indoor')->default(false);
            $table->boolean('has_lighting')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sports_facilities');
    }
};
