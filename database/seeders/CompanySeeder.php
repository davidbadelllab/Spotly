<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Downtown Sports Complex',
                'type' => 'sports',
                'address' => '123 Main Street',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'phone' => '(415) 555-0123',
                'website' => 'https://downtownsports.example.com',
                'description' => 'Premier sports facility offering tennis courts, basketball courts, and more.',
                'status' => 'active',
                'onboarding_completed' => true,
            ],
            [
                'name' => 'Luxury Bay Hotel',
                'type' => 'hotel',
                'address' => '456 Bay Street',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'phone' => '(415) 555-0124',
                'website' => 'https://luxurybayhotel.example.com',
                'description' => 'Luxury hotel with stunning bay views and world-class amenities.',
                'status' => 'active',
                'onboarding_completed' => true,
            ],
            [
                'name' => 'Gourmet Heights Restaurant',
                'type' => 'restaurant',
                'address' => '789 Food Avenue',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'phone' => '(415) 555-0125',
                'website' => 'https://gourmetheights.example.com',
                'description' => 'Fine dining restaurant with panoramic city views.',
                'status' => 'active',
                'onboarding_completed' => true,
            ],
            [
                'name' => 'Innovation Hub Coworking',
                'type' => 'coworking',
                'address' => '321 Tech Boulevard',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'phone' => '(415) 555-0126',
                'website' => 'https://innovationhub.example.com',
                'description' => 'Modern coworking space for startups and entrepreneurs.',
                'status' => 'active',
                'onboarding_completed' => true,
            ],
        ];

        // Get the Professional monthly plan for subscriptions
        $plan = SubscriptionPlan::where('slug', 'professional')->first();

        foreach ($companies as $companyData) {
            // Create a user for each company
            $user = User::create([
                'name' => $companyData['name'] . ' Admin',
                'email' => strtolower(str_replace(' ', '', $companyData['name'])) . '@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            // Create the company
            $company = Company::create([
                'user_id' => $user->id,
                ...$companyData,
            ]);

            // Create a subscription for the company
            Subscription::create([
                'company_id' => $company->id,
                'subscription_plan_id' => $plan->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
                'stripe_id' => 'sub_' . Str::random(10),
                'stripe_status' => 'active',
                'stripe_price' => $plan->price,
            ]);
        }
    }
}
