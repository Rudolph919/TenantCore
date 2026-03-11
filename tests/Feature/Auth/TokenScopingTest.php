<?php

use App\Models\Tenant;
use App\Models\User;

test('authenticated user can access protected route with valid token and tenant', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $token = $user->createToken('api')->plainTextToken;

    $response = $this->getJson('/api/v1/me', [
        'X-Tenant-ID' => $tenant->slug,
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertOk()
        ->assertJson(['id' => $user->id, 'email' => $user->email]);
});

test('protected route without tenant header returns 400', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $token = $user->createToken('api')->plainTextToken;

    $response = $this->getJson('/api/v1/me', [
        'Authorization' => 'Bearer ' . $token,
    ]);

    $response->assertStatus(400);
});

test('protected route without token returns 401', function () {
    $tenant = Tenant::factory()->create();

    $response = $this->getJson('/api/v1/me', [
        'X-Tenant-ID' => $tenant->slug,
    ]);

    $response->assertStatus(401);
});

test('logout requires authentication', function () {
    $tenant = Tenant::factory()->create();

    $response = $this->postJson('/api/v1/logout', [], [
        'X-Tenant-ID' => $tenant->slug,
    ]);

    $response->assertStatus(401);
});
