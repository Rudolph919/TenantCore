<?php

use App\Models\Tenant;

test('tenant route returns 400 when X-Tenant-ID header is missing', function () {
    $response = $this->getJson('/api/v1/tenant');

    $response->assertStatus(400)
        ->assertJson(['message' => 'Missing X-Tenant-ID header']);
});

test('tenant route returns 404 when tenant does not exist', function () {
    $response = $this->getJson('/api/v1/tenant', [
        'X-Tenant-ID' => 'non-existent',
    ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Tenant not found']);
});

test('tenant route returns tenant when resolved by id', function () {
    $tenant = Tenant::factory()->create(['name' => 'Acme Corp', 'slug' => 'acme']);

    $response = $this->getJson('/api/v1/tenant', [
        'X-Tenant-ID' => (string) $tenant->id,
    ]);

    $response->assertOk()
        ->assertJson([
            'id' => $tenant->id,
            'name' => 'Acme Corp',
            'slug' => 'acme',
        ]);
});

test('tenant route returns tenant when resolved by slug', function () {
    $tenant = Tenant::factory()->create(['name' => 'Acme Corp', 'slug' => 'acme']);

    $response = $this->getJson('/api/v1/tenant', [
        'X-Tenant-ID' => 'acme',
    ]);

    $response->assertOk()
        ->assertJson([
            'id' => $tenant->id,
            'name' => 'Acme Corp',
            'slug' => 'acme',
        ]);
});
