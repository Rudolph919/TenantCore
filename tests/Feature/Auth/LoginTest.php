<?php

use App\Models\Tenant;
use App\Models\User;

test('login returns token with valid credentials', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
        'email' => 'user@example.com',
        'password' => 'password',
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'user@example.com',
        'password' => 'password',
    ], [
        'X-Tenant-ID' => $tenant->slug,
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token', 'user'])
        ->assertJson([
            'user' => [
                'id' => $user->id,
                'email' => 'user@example.com',
            ],
        ]);
});

test('login fails without X-Tenant-ID header', function () {
    $response = $this->postJson('/api/v1/login', [
        'email' => 'user@example.com',
        'password' => 'password',
    ]);

    $response->assertStatus(400);
});

test('login fails with invalid credentials', function () {
    $tenant = Tenant::factory()->create();
    User::factory()->create([
        'tenant_id' => $tenant->id,
        'email' => 'user@example.com',
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'user@example.com',
        'password' => 'wrong-password',
    ], [
        'X-Tenant-ID' => $tenant->slug,
    ]);

    $response->assertStatus(422);
});

test('login fails when user belongs to different tenant', function () {
    $tenantA = Tenant::factory()->create(['slug' => 'tenant-a']);
    $tenantB = Tenant::factory()->create(['slug' => 'tenant-b']);
    User::factory()->create([
        'tenant_id' => $tenantA->id,
        'email' => 'user@example.com',
    ]);

    $response = $this->postJson('/api/v1/login', [
        'email' => 'user@example.com',
        'password' => 'password',
    ], [
        'X-Tenant-ID' => $tenantB->slug,
    ]);

    $response->assertStatus(422);
});

test('logout revokes token', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $token = $user->createToken('api')->plainTextToken;

    $response = $this->postJson('/api/v1/logout', [], [
        'X-Tenant-ID' => $tenant->slug,
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'Logged out']);

    $this->assertCount(0, $user->fresh()->tokens);
});
