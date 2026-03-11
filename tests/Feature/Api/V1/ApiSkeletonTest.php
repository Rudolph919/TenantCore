<?php

test('api v1 root returns version info', function () {
    $response = $this->getJson('/api/v1');

    $response->assertOk()
        ->assertJsonStructure(['name', 'version'])
        ->assertJson(['name' => 'TenantCore API', 'version' => 'v1']);
});

test('health endpoint returns ok', function () {
    $response = $this->get('/up');

    $response->assertOk();
});
