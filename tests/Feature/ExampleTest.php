<?php

test('the application redirects root to api v1', function () {
    $response = $this->get('/');

    $response->assertRedirect('/api/v1');
});
