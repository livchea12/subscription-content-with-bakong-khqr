<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ContentTire;
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
        $tire = fake()->randomElement([ContentTire::FREE, ContentTire::PREMIUM]);
        return [
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
            'tire' => $tire,
        ];
    }
}
