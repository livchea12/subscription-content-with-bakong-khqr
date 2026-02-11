<?php

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\postJson;

uses(TestCase::class, RefreshDatabase::class);

it('can login with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'token',
                'refresh_token',
            ]
        ]);
});

it('cannot login with incorrect password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'status' => false,
            'message' => 'Invalid email or password',
        ]);
});

it('cannot login with non-existent email', function () {
    $response = postJson('/api/v1/auth/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'status' => false,
            'message' => 'Invalid email or password',
        ]);
});

it('has validation errors for login', function () {
    $response = postJson('/api/v1/auth/login', [
        'email' => '',
        'password' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});


it('can register new user', function () {
    $response = postJson('/api/v1/auth/register', [
        'email' => 'test@example.com',
        'password' => 'Password123+-*/',
        'name' => 'Test User',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'token',
                'refresh_token',
            ]
        ]);
});

it('has validation errors for register', function () {
    $response = postJson('/api/v1/auth/register', [
        'email' => '',
        'password' => '',
        'name' => '',
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors(['email', 'password', 'name']);
});

it('can logout successfully', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $token = $response->json('data.token');

    $response = $this->withToken($token)->postJson('/api/v1/auth/logout');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message'
        ]);
});

it('can reset password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $token = $response->json('data.token');
 
    $response = $this->withToken($token)->putJson('/api/v1/auth/reset-password', [
        'old_password' => 'password123',
        'new_password' => 'new-Password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message'
        ]); 
});

it('can not reset password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $token = $response->json('data.token');
 
    $response = $this->withToken($token)->putJson('/api/v1/auth/reset-password', [
        'old_password' => 'passworad123',
        'new_password' => 'new-Password123',
    ]);

    $response->assertStatus(400)
        ->assertJsonStructure([
            'status',
            'message'
        ]); 
});

it('has validation errors for reset password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $token = $response->json('data.token');
 
    $response = $this->withToken($token)->putJson('/api/v1/auth/reset-password', [
        'old_password' => 'password123',
        'new_password' => 'newPassword',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['new_password']);
});