<?php

namespace Database\Seeders;

use App\Models\Venue;
use App\Models\SportsFacility;
use Illuminate\Database\Seeder;

class SportsFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $venues = Venue::where('type', 'sports')->get();

        foreach ($venues as $venue) {
            if ($venue->name === 'Downtown Sports Complex') {
                // Create padel courts
                for ($i = 1; $i <= 4; $i++) {
                    $venue->sportsFacilities()->create([
                        'name' => "Padel Court {$i}",
                        'sport_type' => 'padel',
                        'capacity' => 4,
                        'equipment_provided' => 'Rackets available for rent, balls included',
                        'rules' => 'Proper sports attire required. No food or drinks on court.',
                        'duration_minutes' => 60,
                        'price_per_hour' => 40.00,
                        'amenities' => ['showers', 'lockers', 'equipment_rental'],
                        'is_indoor' => true,
                        'has_lighting' => true,
                        'is_active' => true,
                    ]);
                }

                // Create soccer fields
                for ($i = 1; $i <= 2; $i++) {
                    $venue->sportsFacilities()->create([
                        'name' => "Soccer Field {$i}",
                        'sport_type' => 'soccer',
                        'capacity' => 22,
                        'equipment_provided' => 'Goals and corner flags provided',
                        'rules' => 'No metal cleats allowed. Teams responsible for their own balls.',
                        'duration_minutes' => 90,
                        'price_per_hour' => 120.00,
                        'amenities' => ['changing_rooms', 'showers', 'bleachers'],
                        'is_indoor' => false,
                        'has_lighting' => true,
                        'is_active' => true,
                    ]);
                }

                // Create basketball courts
                for ($i = 1; $i <= 2; $i++) {
                    $venue->sportsFacilities()->create([
                        'name' => "Basketball Court {$i}",
                        'sport_type' => 'basketball',
                        'capacity' => 10,
                        'equipment_provided' => 'Balls available for rent',
                        'rules' => 'Indoor shoes required. No dunking on practice hoops.',
                        'duration_minutes' => 60,
                        'price_per_hour' => 60.00,
                        'amenities' => ['water_fountains', 'scoreboard', 'equipment_rental'],
                        'is_indoor' => true,
                        'has_lighting' => true,
                        'is_active' => true,
                    ]);
                }
            }

            if ($venue->name === 'Elite Sports Center') {
                // Create premium padel courts
                for ($i = 1; $i <= 6; $i++) {
                    $venue->sportsFacilities()->create([
                        'name' => "Premium Padel Court {$i}",
                        'sport_type' => 'padel',
                        'capacity' => 4,
                        'equipment_provided' => 'Premium rackets and balls included',
                        'rules' => 'Professional coaching available. Court shoes required.',
                        'duration_minutes' => 60,
                        'price_per_hour' => 60.00,
                        'amenities' => ['premium_equipment', 'towel_service', 'video_analysis'],
                        'is_indoor' => true,
                        'has_lighting' => true,
                        'is_active' => true,
                    ]);
                }

                // Create indoor soccer field
                $venue->sportsFacilities()->create([
                    'name' => 'Indoor Soccer Arena',
                    'sport_type' => 'soccer',
                    'capacity' => 12,
                    'equipment_provided' => 'Professional goals and balls provided',
                    'rules' => 'Indoor soccer shoes only. No sliding tackles.',
                    'duration_minutes' => 60,
                    'price_per_hour' => 150.00,
                    'amenities' => ['climate_control', 'professional_surface', 'video_recording'],
                    'is_indoor' => true,
                    'has_lighting' => true,
                    'is_active' => true,
                ]);

                // Create premium basketball court
                $venue->sportsFacilities()->create([
                    'name' => 'Professional Basketball Court',
                    'sport_type' => 'basketball',
                    'capacity' => 10,
                    'equipment_provided' => 'Professional equipment available',
                    'rules' => 'Professional or supervised amateur use only.',
                    'duration_minutes' => 60,
                    'price_per_hour' => 100.00,
                    'amenities' => ['professional_flooring', 'shot_clocks', 'replay_system'],
                    'is_indoor' => true,
                    'has_lighting' => true,
                    'is_active' => true,
                ]);
            }
        }
    }
}
