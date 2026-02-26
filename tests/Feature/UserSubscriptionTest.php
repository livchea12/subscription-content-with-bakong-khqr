<?php

use App\Models\Content;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;
use function Pest\Laravel\actingAs;
use App\Enums\SystemRole;
use App\Models\SubscriptionPlan;
use Database\Seeders\RolePermissionSeeder;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->user = User::factory()->withRole(SystemRole::USER)->create();

});

it('can subscribe plan', function () {

    $plan = SubscriptionPlan::factory()->create([
        'name' => 'Monthly Plus',
        'interval' => 'monthly',
        'price' => 9.99
    ]);
    $respone = actingAs($this->user, 'api')->postJson("/api/v1/user-subscription/subscribe/{$plan->id}");

    $respone->assertStatus(200)
        ->assertJson([
            "status" => "success"
        ]);
})->only();