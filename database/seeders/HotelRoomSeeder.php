<?php

namespace Database\Seeders;

use App\Models\Venue;
use App\Models\HotelRoom;
use Illuminate\Database\Seeder;

class HotelRoomSeeder extends Seeder
{
    public function run(): void
    {
        $venues = Venue::where('type', 'hotel')->get();

        foreach ($venues as $venue) {
            if ($venue->name === 'Grand Plaza Hotel') {
                // Standard Rooms (Floors 3-5)
                for ($floor = 3; $floor <= 5; $floor++) {
                    for ($room = 1; $room <= 10; $room++) {
                        $venue->hotelRooms()->create([
                            'room_number' => "{$floor}" . str_pad($room, 2, '0', STR_PAD_LEFT),
                            'room_type' => 'standard',
                            'capacity' => 2,
                            'floor_number' => $floor,
                            'price_per_night' => 150.00,
                            'bed_count' => 1,
                            'bed_type' => 'queen',
                            'bathroom_count' => 1,
                            'has_balcony' => false,
                            'has_kitchen' => false,
                            'has_wifi' => true,
                            'amenities' => ['tv', 'air_conditioning', 'mini_fridge', 'safe'],
                            'description' => 'Comfortable standard room with modern amenities',
                            'policies' => 'Check-in: 3 PM, Check-out: 11 AM. No smoking.',
                            'is_smoking' => false,
                            'is_accessible' => $room === 1,
                            'is_active' => true,
                        ]);
                    }
                }

                // Deluxe Rooms (Floors 6-8)
                for ($floor = 6; $floor <= 8; $floor++) {
                    for ($room = 1; $room <= 8; $room++) {
                        $venue->hotelRooms()->create([
                            'room_number' => "{$floor}" . str_pad($room, 2, '0', STR_PAD_LEFT),
                            'room_type' => 'deluxe',
                            'capacity' => 2,
                            'floor_number' => $floor,
                            'price_per_night' => 250.00,
                            'bed_count' => 1,
                            'bed_type' => 'king',
                            'bathroom_count' => 1,
                            'has_balcony' => true,
                            'has_kitchen' => false,
                            'has_wifi' => true,
                            'amenities' => ['tv', 'air_conditioning', 'mini_bar', 'safe', 'coffee_maker', 'bathrobe'],
                            'description' => 'Spacious deluxe room with city views',
                            'policies' => 'Check-in: 3 PM, Check-out: 11 AM. No smoking.',
                            'is_smoking' => false,
                            'is_accessible' => $room === 1,
                            'is_active' => true,
                        ]);
                    }
                }

                // Suites (Floors 9-10)
                for ($floor = 9; $floor <= 10; $floor++) {
                    for ($room = 1; $room <= 4; $room++) {
                        $venue->hotelRooms()->create([
                            'room_number' => "{$floor}" . str_pad($room, 2, '0', STR_PAD_LEFT),
                            'room_type' => 'suite',
                            'capacity' => 4,
                            'floor_number' => $floor,
                            'price_per_night' => 450.00,
                            'bed_count' => 2,
                            'bed_type' => 'king',
                            'bathroom_count' => 2,
                            'has_balcony' => true,
                            'has_kitchen' => true,
                            'has_wifi' => true,
                            'amenities' => [
                                'tv', 'air_conditioning', 'full_mini_bar', 'safe', 'coffee_maker',
                                'bathrobe', 'living_room', 'dining_area', 'premium_toiletries'
                            ],
                            'description' => 'Luxury suite with separate living area and stunning views',
                            'policies' => 'Check-in: 2 PM, Check-out: 12 PM. No smoking. Butler service available.',
                            'is_smoking' => false,
                            'is_accessible' => $room === 1,
                            'is_active' => true,
                        ]);
                    }
                }
            }

            if ($venue->name === 'Seaside Resort') {
                // Ocean View Rooms (Floors 1-3)
                for ($floor = 1; $floor <= 3; $floor++) {
                    for ($room = 1; $room <= 8; $room++) {
                        $venue->hotelRooms()->create([
                            'room_number' => "{$floor}" . str_pad($room, 2, '0', STR_PAD_LEFT),
                            'room_type' => 'ocean_view',
                            'capacity' => 2,
                            'floor_number' => $floor,
                            'price_per_night' => 300.00,
                            'bed_count' => 1,
                            'bed_type' => 'king',
                            'bathroom_count' => 1,
                            'has_balcony' => true,
                            'has_kitchen' => false,
                            'has_wifi' => true,
                            'amenities' => ['tv', 'air_conditioning', 'mini_bar', 'safe', 'beach_access'],
                            'description' => 'Beautiful room with direct ocean views',
                            'policies' => 'Check-in: 4 PM, Check-out: 11 AM. No smoking.',
                            'is_smoking' => false,
                            'is_accessible' => $room === 1,
                            'is_active' => true,
                        ]);
                    }
                }

                // Beach Villas (Ground Floor)
                for ($villa = 1; $villa <= 5; $villa++) {
                    $venue->hotelRooms()->create([
                        'room_number' => "V" . str_pad($villa, 2, '0', STR_PAD_LEFT),
                        'room_type' => 'villa',
                        'capacity' => 6,
                        'floor_number' => 0,
                        'price_per_night' => 800.00,
                        'bed_count' => 3,
                        'bed_type' => 'king',
                        'bathroom_count' => 3,
                        'has_balcony' => true,
                        'has_kitchen' => true,
                        'has_wifi' => true,
                        'amenities' => [
                            'tv', 'air_conditioning', 'full_kitchen', 'private_pool',
                            'beach_access', 'bbq_area', 'private_garden', 'premium_toiletries'
                        ],
                        'description' => 'Luxurious beachfront villa with private pool',
                        'policies' => 'Check-in: 3 PM, Check-out: 12 PM. No smoking. Private chef available.',
                        'is_smoking' => false,
                        'is_accessible' => $villa === 1,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
