<?php

test('get prayer zones list', function () {
    $response = $this->get('/zones');

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

test('get zones by state', function () {
    $response = $this->getJson('/zones/sgr');

    $response->assertStatus(200);
    $response->assertJsonIsArray();

    // All zones should start with SGR
    $zones = $response->json();
    foreach ($zones as $zone) {
        expect($zone['jakimCode'])->toStartWith('SGR');
    }
});

test('get zones by state returns empty array for invalid state', function () {
    $response = $this->getJson('/zones/xyz');

    $response->assertStatus(200);
    $response->assertJsonIsArray();
    expect($response->json())->toBeEmpty();
});

test('get zone from GPS coordinates - Kuala Lumpur', function () {
    // Coordinates for Kuala Lumpur city center (should be WLY01)
    $response = $this->getJson('/zones/3.1390/101.6869');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'zone',
        'state',
        'district',
    ]);

    $response->assertJsonPath('zone', 'WLY01');
});

test('get zone from GPS coordinates - Johor Bahru', function () {
    // Coordinates for Johor Bahru
    $response = $this->getJson('/zones/1.4927/103.7414');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'zone',
        'state',
        'district',
    ]);

    // Should be a JHR zone
    $zone = $response->json('zone');
    expect($zone)->toStartWith('JHR');
});

test('get zone from GPS coordinates - invalid coordinates outside Malaysia', function () {
    // Coordinates for Singapore (outside Malaysia)
    $response = $this->getJson('/zones/1.2897/103.8501');

    $response->assertStatus(500);
    $response->assertJsonStructure(['error']);
    expect($response->json('error'))->toBe('No zone found for the given coordinates.');
});

test('get zone from GPS coordinates - invalid coordinates out of range', function () {
    // Coordinates for Singapore (outside Malaysia)
    $response = $this->getJson('/zones/91/181');

    $response->assertStatus(500);
    $response->assertJsonStructure(['error']);
    expect($response->json('error'))->toBe('Longitude 181.000000 is out of range in function st_geomfromtext. It must be within (-180.000000, 180.000000].');
});

test('has CORS header allowing all origins', function () {
    $response = $this->getJson('/zones');

    $response->assertStatus(200);
    $response->assertHeader('Access-Control-Allow-Origin', '*');
});
