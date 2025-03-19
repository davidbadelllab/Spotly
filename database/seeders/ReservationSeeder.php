<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Venue;
use App\Models\Reservation;
use App\Models\SportsFacility;
use App\Models\HotelRoom;
use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereNotIn('email', [
            'admin@example.com',
            'sports@example.com',
            'hotel@example.com',
            'restaurant@example.com'
        ])->get();

        // Sports Facility Reservations
        $sportsFacilities = SportsFacility::with('venue')->get();
        foreach ($sportsFacilities as $facility) {
            // Create some upcoming reservations
            for ($i = 0; $i < 3; $i++) {
                $startTime = now()->addDays(rand(1, 14))->setHour(rand(9, 20))->setMinute(0);
                $endTime = (clone $startTime)->addMinutes($facility->duration_minutes);

                if ($facility->isAvailable($startTime, $endTime)) {
                    $customer = $customers->random();
                    $totalPrice = $facility->calculatePrice($startTime, $endTime);

                    $facility->reservations()->create([
                        'user_id' => $customer->id,
                        'venue_id' => $facility->venue_id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'number_of_people' => rand(2, $facility->capacity),
                        'status' => rand(0, 1) ? 'confirmed' : 'pending',
                        'total_price' => $totalPrice,
                        'special_requests' => rand(0, 1) ? 'Equipment rental needed' : null,
                        'customer_details' => [
                            'name' => $customer->name,
                            'email' => $customer->email,
                            'phone' => '(555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                        ],
                        'confirmation_code' => strtoupper(Str::random(8)),
                        'payment_status' => 'pending',
                    ]);
                }
            }
        }

        // Hotel Room Reservations
        $hotelRooms = HotelRoom::with('venue')->get();
        foreach ($hotelRooms as $room) {
            // Create some upcoming reservations
            for ($i = 0; $i < 2; $i++) {
                $checkIn = now()->addDays(rand(1, 30))->setHour(15)->setMinute(0);
                $checkOut = (clone $checkIn)->addDays(rand(1, 5))->setHour(11)->setMinute(0);

                if ($room->isAvailable($checkIn, $checkOut)) {
                    $customer = $customers->random();
                    $totalPrice = $room->calculatePrice($checkIn, $checkOut);

                    $room->reservations()->create([
                        'user_id' => $customer->id,
                        'venue_id' => $room->venue_id,
                        'start_time' => $checkIn,
                        'end_time' => $checkOut,
                        'number_of_people' => rand(1, $room->capacity),
                        'status' => rand(0, 1) ? 'confirmed' : 'pending',
                        'total_price' => $totalPrice,
                        'special_requests' => rand(0, 1) ? 'Late check-out requested' : null,
                        'customer_details' => [
                            'name' => $customer->name,
                            'email' => $customer->email,
                            'phone' => '(555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                        ],
                        'confirmation_code' => strtoupper(Str::random(8)),
                        'payment_status' => 'pending',
                    ]);
                }
            }
        }

        // Restaurant Table Reservations
        $restaurantTables = RestaurantTable::with('venue')->get();
        foreach ($restaurantTables as $table) {
            // Create some upcoming reservations
            for ($i = 0; $i < 2; $i++) {
                $startTime = now()->addDays(rand(1, 14))
                    ->setHour(rand(17, 21))
                    ->setMinute(rand(0, 1) ? 0 : 30);
                $endTime = (clone $startTime)->addMinutes($table->default_reservation_duration);

                if ($table->isAvailable($startTime, $endTime)) {
                    $customer = $customers->random();
                    $partySize = rand($table->min_capacity, $table->max_capacity);

                    $reservation = $table->reservations()->create([
                        'user_id' => $customer->id,
                        'venue_id' => $table->venue_id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'number_of_people' => $partySize,
                        'status' => rand(0, 1) ? 'confirmed' : 'pending',
                        'total_price' => $table->minimum_spend ?: 0,
                        'special_requests' => rand(0, 1) ? 'Birthday celebration' : null,
                        'customer_details' => [
                            'name' => $customer->name,
                            'email' => $customer->email,
                            'phone' => '(555) ' . rand(100, 999) . '-' . rand(1000, 9999),
                        ],
                        'confirmation_code' => strtoupper(Str::random(8)),
                        'payment_status' => $table->requires_deposit ? 'partially_paid' : 'pending',
                        'deposit_paid' => $table->requires_deposit ? $table->deposit_amount : 0,
                    ]);

                    if ($table->requires_deposit && $reservation->status === 'confirmed') {
                        $reservation->update(['payment_status' => 'partially_paid']);
                    }
                }
            }
        }
    }
}
