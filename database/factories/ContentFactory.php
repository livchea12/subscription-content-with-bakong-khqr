<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ContentTier;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tier = fake()->randomElement([ContentTier::FREE, ContentTier::PREMIUM]);
        return [
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'tier' => $tier,
        ];
    }
}
