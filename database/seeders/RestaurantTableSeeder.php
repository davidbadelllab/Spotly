<?php

namespace Database\Seeders;

use App\Models\Venue;
use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    public function run(): void
    {
        $venues = Venue::where('type', 'restaurant')->get();

        foreach ($venues as $venue) {
            if ($venue->name === 'The Fine Dining Experience') {
                // Main Dining Room Tables
                for ($i = 1; $i <= 15; $i++) {
                    $venue->restaurantTables()->create([
                        'table_number' => "M{$i}",
                        'location' => 'main_dining',
                        'min_capacity' => 2,
                        'max_capacity' => 4,
                        'table_type' => 'standard',
                        'is_accessible' => in_array($i, [1, 2]),
                        'is_smoking' => false,
                        'default_reservation_duration' => 120,
                        'seating_options' => ['chairs'],
                        'notes' => 'Elegant main dining room setting',
                        'requires_deposit' => false,
                        'minimum_spend' => null,
                        'is_active' => true,
                    ]);
                }

                // Window Tables (Premium)
                for ($i = 1; $i <= 6; $i++) {
                    $venue->restaurantTables()->create([
                        'table_number' => "W{$i}",
                        'location' => 'window',
                        'min_capacity' => 2,
                        'max_capacity' => 4,
                        'table_type' => 'premium',
                        'is_accessible' => $i === 1,
                        'is_smoking' => false,
                        'default_reservation_duration' => 120,
                        'seating_options' => ['chairs'],
                        'notes' => 'Premium window seating with city views',
                        'requires_deposit' => true,
                        'deposit_amount' => 50.00,
                        'minimum_spend' => 200,
                        'is_active' => true,
                    ]);
                }

                // Private Dining Rooms
                for ($i = 1; $i <= 3; $i++) {
                    $venue->restaurantTables()->create([
                        'table_number' => "P{$i}",
                        'location' => 'private_room',
                        'min_capacity' => 6,
                        'max_capacity' => 12,
                        'table_type' => 'private',
                        'is_accessible' => true,
                        'is_smoking' => false,
                        'default_reservation_duration' => 180,
                        'seating_options' => ['chairs', 'custom_setup'],
                        'notes' => 'Private dining room with dedicated service',
                        'requires_deposit' => true,
                        'deposit_amount' => 200.00,
                        'minimum_spend' => 500,
                        'is_active' => true,
                    ]);
                }
            }

            if ($venue->name === 'Casual Bites') {
                // Indoor Tables
                for ($i = 1; $i <= 20; $i++) {
                    $venue->restaurantTables()->create([
                        'table_number' => "T{$i}",
                        'location' => 'indoor',
                        'min_capacity' => 2,
                        'max_capacity' => 4,
                        'table_type' => 'standard',
                        'is_accessible' => in_array($i, [1, 2, 3]),
                        'is_smoking' => false,
                        'default_reservation_duration' => 90,
                        'seating_options' => ['chairs'],
                        'notes' => 'Comfortable indoor seating',
                        'requires_deposit' => false,
                        'minimum_spend' => null,
                        'is_active' => true,
                    ]);
                }

                // Outdoor Patio Tables
                for ($i = 1; $i <= 8; $i++) {
                    $venue->restaurantTables()->create([
                        'table_number' => "P{$i}",
                        'location' => 'patio',
                        'min_capacity' => 2,
                        'max_capacity' => 6,
                        'table_type' => 'outdoor',
                        'is_accessible' => $i === 1,
                        'is_smoking' => true,
                        'default_reservation_duration' => 90,
                        'seating_options' => ['chairs', 'umbrellas'],
                        'notes' => 'Pleasant outdoor patio seating',
                        'requires_deposit' => false,
                        'minimum_spend' => null,
                        'is_active' => true,
                    ]);
                }

                // Family Booths
                for ($i = 1; $i <= 6; $i++) {
                    $venue->restaurantTables()->create([
                        'table_number' => "B{$i}",
                        'location' => 'indoor',
                        'min_capacity' => 4,
                        'max_capacity' => 8,
                        'table_type' => 'booth',
                        'is_accessible' => false,
                        'is_smoking' => false,
                        'default_reservation_duration' => 90,
                        'seating_options' => ['booth_seating'],
                        'notes' => 'Comfortable family-style booth seating',
                        'requires_deposit' => false,
                        'minimum_spend' => null,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
