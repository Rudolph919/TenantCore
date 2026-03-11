<?php

use App\Models\Item;
use App\Models\Tenant;
use App\Models\User;

function authHeaders(Tenant $tenant, User $user): array
{
    $token = $user->createToken('api')->plainTextToken;

    return [
        'X-Tenant-ID' => $tenant->slug,
        'Authorization' => 'Bearer ' . $token,
    ];
}

test('index returns items for tenant', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $items = Item::factory()->count(3)->create(['tenant_id' => $tenant->id]);

    $response = $this->getJson('/api/v1/items', authHeaders($tenant, $user));

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('data.0.id', $items[0]->id);
});

test('index excludes items from other tenants', function () {
    $tenant = Tenant::factory()->create();
    $otherTenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    Item::factory()->create(['tenant_id' => $tenant->id]);
    Item::factory()->create(['tenant_id' => $otherTenant->id]);

    $response = $this->getJson('/api/v1/items', authHeaders($tenant, $user));

    $response->assertOk()
        ->assertJsonCount(1, 'data');
});

test('store validates required name', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $response = $this->postJson('/api/v1/items', [
        'description' => 'No name provided',
    ], authHeaders($tenant, $user));

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('store creates item for tenant', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $response = $this->postJson('/api/v1/items', [
        'name' => 'Test Item',
        'description' => 'A test description',
    ], authHeaders($tenant, $user));

    $response->assertStatus(201)
        ->assertJson([
            'data' => [
                'name' => 'Test Item',
                'description' => 'A test description',
            ],
        ]);

    $this->assertDatabaseHas('items', [
        'tenant_id' => $tenant->id,
        'name' => 'Test Item',
    ]);
});

test('show returns item for tenant', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $item = Item::factory()->create(['tenant_id' => $tenant->id]);

    $response = $this->getJson("/api/v1/items/{$item->id}", authHeaders($tenant, $user));

    $response->assertOk()
        ->assertJson(['data' => ['id' => $item->id, 'name' => $item->name]]);
});

test('show returns 404 when item belongs to other tenant', function () {
    $tenant = Tenant::factory()->create();
    $otherTenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $item = Item::factory()->create(['tenant_id' => $otherTenant->id]);

    $response = $this->getJson("/api/v1/items/{$item->id}", authHeaders($tenant, $user));

    $response->assertStatus(404);
});

test('update modifies item', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $item = Item::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Original']);

    $response = $this->putJson("/api/v1/items/{$item->id}", [
        'name' => 'Updated',
        'description' => 'Updated description',
    ], authHeaders($tenant, $user));

    $response->assertOk()
        ->assertJson(['data' => ['name' => 'Updated', 'description' => 'Updated description']]);

    $this->assertDatabaseHas('items', ['id' => $item->id, 'name' => 'Updated']);
});

test('update returns 404 when item belongs to other tenant', function () {
    $tenant = Tenant::factory()->create();
    $otherTenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $item = Item::factory()->create(['tenant_id' => $otherTenant->id]);

    $response = $this->putJson("/api/v1/items/{$item->id}", [
        'name' => 'Updated',
    ], authHeaders($tenant, $user));

    $response->assertStatus(404);
});

test('destroy deletes item', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $item = Item::factory()->create(['tenant_id' => $tenant->id]);

    $response = $this->deleteJson("/api/v1/items/{$item->id}", [], authHeaders($tenant, $user));

    $response->assertStatus(204);
    $this->assertDatabaseMissing('items', ['id' => $item->id]);
});

test('destroy returns 404 when item belongs to other tenant', function () {
    $tenant = Tenant::factory()->create();
    $otherTenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $item = Item::factory()->create(['tenant_id' => $otherTenant->id]);

    $response = $this->deleteJson("/api/v1/items/{$item->id}", [], authHeaders($tenant, $user));

    $response->assertStatus(404);
    $this->assertDatabaseHas('items', ['id' => $item->id]);
});

test('items require authentication', function () {
    $tenant = Tenant::factory()->create();

    $response = $this->getJson('/api/v1/items', [
        'X-Tenant-ID' => $tenant->slug,
    ]);

    $response->assertStatus(401);
});
