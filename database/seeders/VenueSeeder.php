<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        // Get our venue owners
        $sportsOwner = User::where('email', 'sports@example.com')->first();
        $hotelManager = User::where('email', 'hotel@example.com')->first();
        $restaurantOwner = User::where('email', 'restaurant@example.com')->first();

        // Create sports venues
        $sportsVenues = [
            [
                'name' => 'Downtown Sports Complex',
                'type' => 'sports',
                'description' => 'Modern sports facility with multiple courts and fields',
                'address' => '123 Sports Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90001',
                'phone' => '(555) 123-4567',
                'email' => 'info@downtownsports.com',
                'website' => 'https://downtownsports.com',
                'business_hours' => [
                    'monday' => ['open' => '06:00', 'close' => '22:00'],
                    'tuesday' => ['open' => '06:00', 'close' => '22:00'],
                    'wednesday' => ['open' => '06:00', 'close' => '22:00'],
                    'thursday' => ['open' => '06:00', 'close' => '22:00'],
                    'friday' => ['open' => '06:00', 'close' => '23:00'],
                    'saturday' => ['open' => '08:00', 'close' => '23:00'],
                    'sunday' => ['open' => '08:00', 'close' => '20:00'],
                ],
            ],
            [
                'name' => 'Elite Sports Center',
                'type' => 'sports',
                'description' => 'Premium sports facility with professional equipment',
                'address' => '456 Elite Way',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '94105',
                'phone' => '(555) 987-6543',
                'email' => 'info@elitesports.com',
                'website' => 'https://elitesports.com',
                'business_hours' => [
                    'monday' => ['open' => '07:00', 'close' => '21:00'],
                    'tuesday' => ['open' => '07:00', 'close' => '21:00'],
                    'wednesday' => ['open' => '07:00', 'close' => '21:00'],
                    'thursday' => ['open' => '07:00', 'close' => '21:00'],
                    'friday' => ['open' => '07:00', 'close' => '22:00'],
                    'saturday' => ['open' => '09:00', 'close' => '20:00'],
                    'sunday' => ['open' => '09:00', 'close' => '19:00'],
                ],
            ],
        ];

        foreach ($sportsVenues as $venue) {
            $sportsOwner->venues()->create($venue);
        }

        // Create hotel venues
        $hotelVenues = [
            [
                'name' => 'Grand Plaza Hotel',
                'type' => 'hotel',
                'description' => 'Luxury hotel in the heart of downtown',
                'address' => '789 Grand Blvd',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'phone' => '(555) 234-5678',
                'email' => 'info@grandplaza.com',
                'website' => 'https://grandplaza.com',
                'business_hours' => [
                    'monday' => ['open' => '00:00', 'close' => '23:59'],
                    'tuesday' => ['open' => '00:00', 'close' => '23:59'],
                    'wednesday' => ['open' => '00:00', 'close' => '23:59'],
                    'thursday' => ['open' => '00:00', 'close' => '23:59'],
                    'friday' => ['open' => '00:00', 'close' => '23:59'],
                    'saturday' => ['open' => '00:00', 'close' => '23:59'],
                    'sunday' => ['open' => '00:00', 'close' => '23:59'],
                ],
            ],
            [
                'name' => 'Seaside Resort',
                'type' => 'hotel',
                'description' => 'Beautiful beachfront resort with stunning views',
                'address' => '321 Beach Road',
                'city' => 'Miami',
                'state' => 'FL',
                'country' => 'USA',
                'postal_code' => '33139',
                'phone' => '(555) 876-5432',
                'email' => 'info@seasideresort.com',
                'website' => 'https://seasideresort.com',
                'business_hours' => [
                    'monday' => ['open' => '00:00', 'close' => '23:59'],
                    'tuesday' => ['open' => '00:00', 'close' => '23:59'],
                    'wednesday' => ['open' => '00:00', 'close' => '23:59'],
                    'thursday' => ['open' => '00:00', 'close' => '23:59'],
                    'friday' => ['open' => '00:00', 'close' => '23:59'],
                    'saturday' => ['open' => '00:00', 'close' => '23:59'],
                    'sunday' => ['open' => '00:00', 'close' => '23:59'],
                ],
            ],
        ];

        foreach ($hotelVenues as $venue) {
            $hotelManager->venues()->create($venue);
        }

        // Create restaurant venues
        $restaurantVenues = [
            [
                'name' => 'The Fine Dining Experience',
                'type' => 'restaurant',
                'description' => 'Upscale dining with international cuisine',
                'address' => '567 Gourmet Street',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'postal_code' => '60601',
                'phone' => '(555) 345-6789',
                'email' => 'info@finedining.com',
                'website' => 'https://finedining.com',
                'business_hours' => [
                    'monday' => ['open' => '17:00', 'close' => '23:00'],
                    'tuesday' => ['open' => '17:00', 'close' => '23:00'],
                    'wednesday' => ['open' => '17:00', 'close' => '23:00'],
                    'thursday' => ['open' => '17:00', 'close' => '23:00'],
                    'friday' => ['open' => '17:00', 'close' => '00:00'],
                    'saturday' => ['open' => '17:00', 'close' => '00:00'],
                    'sunday' => ['open' => '17:00', 'close' => '22:00'],
                ],
            ],
            [
                'name' => 'Casual Bites',
                'type' => 'restaurant',
                'description' => 'Family-friendly restaurant with diverse menu',
                'address' => '890 Casual Lane',
                'city' => 'Houston',
                'state' => 'TX',
                'country' => 'USA',
                'postal_code' => '77001',
                'phone' => '(555) 765-4321',
                'email' => 'info@casualbites.com',
                'website' => 'https://casualbites.com',
                'business_hours' => [
                    'monday' => ['open' => '11:00', 'close' => '22:00'],
                    'tuesday' => ['open' => '11:00', 'close' => '22:00'],
                    'wednesday' => ['open' => '11:00', 'close' => '22:00'],
                    'thursday' => ['open' => '11:00', 'close' => '22:00'],
                    'friday' => ['open' => '11:00', 'close' => '23:00'],
                    'saturday' => ['open' => '11:00', 'close' => '23:00'],
                    'sunday' => ['open' => '11:00', 'close' => '21:00'],
                ],
            ],
        ];

        foreach ($restaurantVenues as $venue) {
            $restaurantOwner->venues()->create($venue);
        }
    }
}
