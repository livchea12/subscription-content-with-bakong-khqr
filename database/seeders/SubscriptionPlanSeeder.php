<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Interval;
use App\Enums\Currency;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            ['name' => 'Free', 'price' => 0, 'interval' => Interval::MONTHLY, 'currency' => Currency::DOLLAR],
            ['name' => 'Plus', 'price' => 20, 'interval' => Interval::YEARLY, 'currency' => Currency::DOLLAR],
            ['name' => 'Premium', 'price' => 30, 'interval' => Interval::MONTHLY, 'currency' => Currency::DOLLAR],
        ];

        foreach ($plans as $plan) {
            \App\Models\SubscriptionPlan::create($plan);
        }
    }
}
