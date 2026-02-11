<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Enums\UserSubscriptionStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSubscription>
 */
class UserSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'subscription_plan_id' => SubscriptionPlan::inRandomOrder()->first()?->id ?? SubscriptionPlan::factory(),
            'status' => fake()->randomElement(UserSubscriptionStatus::cases())->value,
            'expired_at' => fake()->dateTimeBetween('+100 day', '+1 year'),
        ];
    }
}
