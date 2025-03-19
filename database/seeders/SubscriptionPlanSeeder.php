<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'slug' => 'basic',
                'price' => 29.99,
                'billing_period' => 'monthly',
                'features' => [
                    'Single venue management',
                    'Basic analytics',
                    'Email support',
                    'Up to 2 staff accounts',
                    'Basic booking system',
                ],
                'max_venues' => 1,
                'max_users' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Professional Plan',
                'slug' => 'professional',
                'price' => 79.99,
                'billing_period' => 'monthly',
                'features' => [
                    'Up to 3 venues',
                    'Advanced analytics',
                    'Priority support',
                    'Up to 5 staff accounts',
                    'Advanced booking system',
                    'Custom branding',
                    'API access',
                ],
                'max_venues' => 3,
                'max_users' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise Plan',
                'slug' => 'enterprise',
                'price' => 199.99,
                'billing_period' => 'monthly',
                'features' => [
                    'Unlimited venues',
                    'Enterprise analytics',
                    '24/7 Priority support',
                    'Unlimited staff accounts',
                    'Advanced booking system',
                    'Custom branding',
                    'API access',
                    'Dedicated account manager',
                    'Custom integrations',
                ],
                'max_venues' => -1, // unlimited
                'max_users' => -1, // unlimited
                'is_active' => true,
            ],
            // Yearly plans with discount
            [
                'name' => 'Basic Plan (Yearly)',
                'slug' => 'basic-yearly',
                'price' => 299.99, // ~20% discount
                'billing_period' => 'yearly',
                'features' => [
                    'Single venue management',
                    'Basic analytics',
                    'Email support',
                    'Up to 2 staff accounts',
                    'Basic booking system',
                    '20% yearly discount',
                ],
                'max_venues' => 1,
                'max_users' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Professional Plan (Yearly)',
                'slug' => 'professional-yearly',
                'price' => 799.99, // ~20% discount
                'billing_period' => 'yearly',
                'features' => [
                    'Up to 3 venues',
                    'Advanced analytics',
                    'Priority support',
                    'Up to 5 staff accounts',
                    'Advanced booking system',
                    'Custom branding',
                    'API access',
                    '20% yearly discount',
                ],
                'max_venues' => 3,
                'max_users' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise Plan (Yearly)',
                'slug' => 'enterprise-yearly',
                'price' => 1999.99, // ~20% discount
                'billing_period' => 'yearly',
                'features' => [
                    'Unlimited venues',
                    'Enterprise analytics',
                    '24/7 Priority support',
                    'Unlimited staff accounts',
                    'Advanced booking system',
                    'Custom branding',
                    'API access',
                    'Dedicated account manager',
                    'Custom integrations',
                    '20% yearly discount',
                ],
                'max_venues' => -1, // unlimited
                'max_users' => -1, // unlimited
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
