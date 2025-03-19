<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->string('table_number');
            $table->string('location'); // indoor, outdoor, bar, private room
            $table->integer('min_capacity');
            $table->integer('max_capacity');
            $table->string('table_type'); // standard, booth, high-top, private
            $table->boolean('is_accessible')->default(false);
            $table->boolean('is_smoking')->default(false);
            $table->integer('default_reservation_duration')->default(120); // in minutes
            $table->json('seating_options')->nullable(); // chair types, cushions, etc.
            $table->text('notes')->nullable(); // special features or restrictions
            $table->boolean('requires_deposit')->default(false);
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->integer('minimum_spend')->nullable(); // minimum required spend for reservation
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['venue_id', 'table_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
