<?php

use App\Models\Tenant;
use App\Models\User;

test('register creates user and returns token', function () {
    $tenant = Tenant::factory()->create();

    $response = $this->postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], [
        'X-Tenant-ID' => $tenant->slug,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['token', 'user'])
        ->assertJson([
            'user' => [
                'name' => 'Test User',
                'email' => 'new@example.com',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'tenant_id' => $tenant->id,
        'email' => 'new@example.com',
    ]);
});

test('register fails without X-Tenant-ID header', function () {
    $response = $this->postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'new@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(400);
});

test('register fails when email already exists for tenant', function () {
    $tenant = Tenant::factory()->create();
    User::factory()->create([
        'tenant_id' => $tenant->id,
        'email' => 'existing@example.com',
    ]);

    $response = $this->postJson('/api/v1/register', [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], [
        'X-Tenant-ID' => $tenant->slug,
    ]);

    $response->assertStatus(422)
        ->assertJson(['message' => 'Email already registered for this tenant']);
});
