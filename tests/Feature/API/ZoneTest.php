<?php

test('get prayer zones list', function () {
    $response = $this->get('/api/zones');

    $response->assertStatus(200);

    // Assert response is JSON
    $response->assertHeader('Content-Type', 'application/json');

    $response->assertJsonIsArray();
    $response->assertJsonStructure([
        '*' => [
            'jakimCode',
            'negeri',
            'daerah',
        ],
    ]);

});
