<?php

use App\Models\Tenant;
use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('tenant has users relationship', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    expect($tenant->users)->toHaveCount(1)
        ->and($tenant->users->first()->id)->toBe($user->id);
});

test('tenant slug is unique', function () {
    Tenant::factory()->create(['slug' => 'acme']);

    expect(fn () => Tenant::factory()->create(['slug' => 'acme']))
        ->toThrow(\Illuminate\Database\QueryException::class);
});
