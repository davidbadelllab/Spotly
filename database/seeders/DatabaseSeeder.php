<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SubscriptionPlanSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            VenueSeeder::class,
            SportsFacilitySeeder::class,
            HotelRoomSeeder::class,
            RestaurantTableSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
