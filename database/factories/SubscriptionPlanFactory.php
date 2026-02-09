<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use App\Enums\Interval;
use App\Enums\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlanFactory extends Factory
{
    protected $model = SubscriptionPlan::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'price' => fake()->numberBetween(0, 100),
            'interval' => fake()->randomElement(Interval::cases())->value,
            'currency' => fake()->randomElement(Currency::cases())->value,
        ];
    }
}
