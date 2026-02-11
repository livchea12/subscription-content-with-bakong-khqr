<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Enums\SystemRole;
use App\Enums\SystemPermisson;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign a specific role to the user.
     */
    public function withRole(SystemRole $role): static
    {
        return $this->afterCreating(function (\App\Models\User $user) use ($role) {
            $user->assignRole($role->value);
        });
    }


    public function withPermission(SystemPermisson $permission): static
    {
        return $this->afterCreating(function (User $user) use ($permission) {
            $user->givePermissionTo($permission->value);
        });
    }

    /**
     * Assign the Admin role.
     */
    public function admin(): static
    {
        return $this->withRole(\App\Enums\SystemRole::ADMIN);
    }
}
