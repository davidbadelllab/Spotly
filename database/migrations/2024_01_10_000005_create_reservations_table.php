<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->morphs('reservable'); // Polymorphic relationship to sports_facilities, hotel_rooms, or restaurant_tables
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('number_of_people');
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed
            $table->decimal('total_price', 10, 2);
            $table->decimal('deposit_paid', 10, 2)->default(0);
            $table->text('special_requests')->nullable();
            $table->json('customer_details')->nullable(); // Additional customer information
            $table->string('confirmation_code')->unique();
            $table->text('cancellation_reason')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, refunded
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Prevent double bookings
            $table->unique(['reservable_type', 'reservable_id', 'start_time', 'end_time'], 'prevent_double_booking');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
