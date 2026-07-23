<?php

test('the application serves the landing page at root', function () {
    $response = $this->get('/');

    $response->assertOk();
});
