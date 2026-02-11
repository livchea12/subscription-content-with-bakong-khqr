<?php

use App\Models\Content;
use App\Models\User;
use App\Enums\ContentTier;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RolePermissionSeeder;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->user = User::factory()->admin()->create();
});

it('can list all contents', function () {
    Content::factory()->count(3)->create();

    $response = actingAs($this->user, 'api')->getJson('/api/v1/contents');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'data' => [
                'data',
                'meta' => [
                    'current_page',
                    'per_page'
                ]
            ]
        ])
        ->assertJsonCount(3, 'data.data');
});

it('can show a specific content', function () {
    $content = Content::factory()->create();

    $response = actingAs($this->user, 'api')->getJson("/api/v1/contents/{$content->id}");

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $content->id,
                'title' => $content->title,
            ]
        ]);
});

it('can create content when authenticated', function () {
    $data = [
        'title' => 'Test Content',
        'body' => 'This is a test body',
        'tier' => ContentTier::FREE->value,
    ];

    $response = actingAs($this->user, 'api')->postJson('/api/v1/contents', $data);

    $response->assertStatus(201)
        ->assertJson([
            'status' => 'success',
            'message' => 'Content created successfully',
            'data' => [
                'id' => $response->json('data.id'),
                'title' => 'Test Content',
                'body' => 'This is a test body',
                'tier' => ContentTier::FREE->value,
                'created_at' => $response->json('data.created_at'),
                'creator' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ],
            ]
        ]);

    $this->assertDatabaseHas('contents', ['title' => 'Test Content']);
});

it('cannot create content when unauthenticated', function () {
    $data = [
        'title' => 'Test Content',
        'body' => 'This is a test body',
        'tier' => ContentTier::FREE->value,
    ];

    $response = postJson('/api/v1/contents', $data);

    $response->assertStatus(401);
});

it('can update content when authenticated', function () {
    $content = Content::factory()->create(['created_by' => $this->user->id]);

    $data = [
        'title' => 'Updated Title',
        'tier' => ContentTier::PREMIUM->value,
    ];

    $response = actingAs($this->user, 'api')->putJson("/api/v1/contents/{$content->id}", $data);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'message' => 'Content updated successfully',
            'data' => [
                'title' => 'Updated Title',
                'tier' => ContentTier::PREMIUM->value,
            ]
        ]);

    $this->assertDatabaseHas('contents', ['title' => 'Updated Title']);
});

it('can delete content when authenticated', function () {
    $content = Content::factory()->create(['created_by' => $this->user->id]);

    $response = actingAs($this->user, 'api')->deleteJson("/api/v1/contents/{$content->id}");

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'message' => 'Content deleted successfully'
        ]);

    $this->assertDatabaseMissing('contents', ['id' => $content->id]);
});

it('fails validation when creating content with missing fields', function () {
    $response = actingAs($this->user, 'api')->postJson('/api/v1/contents', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'body', 'tier']);
});
