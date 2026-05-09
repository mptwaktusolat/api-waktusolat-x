<?php

describe('Prayer Time V2 - Month Endpoint', function () {
    test('get prayer time by zone', function () {
        $response = $this->getJson('/v2/solat/sgr01');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'zone',
            'year',
            'month',
            'month_number',
            'last_updated',
            'prayers' => [
                '*' => [
                    'day',
                    'hijri',
                    'fajr',
                    'syuruk',
                    'dhuhr',
                    'asr',
                    'maghrib',
                    'isha',
                ],
            ],
        ]);

        $response->assertJsonPath('zone', 'SGR01');
        expect($response->json('prayers'))->toBeArray();
    });

    test('get prayer time with year and month params', function () {
        $response = $this->getJson('/v2/solat/sgr01?year=2024&month=6');

        $response->assertStatus(200);
        $response->assertJsonPath('zone', 'SGR01');
        $response->assertJsonPath('year', 2024);
        $response->assertJsonPath('month', 'JUN');
        $response->assertJsonPath('month_number', 6);

        $prayers = $response->json('prayers');
        expect($prayers)->toBeArray();
        expect(count($prayers))->toBeGreaterThan(25);
    });

    test('prayer times are returned as timestamps', function () {
        $response = $this->getJson('/v2/solat/sgr01');

        $response->assertStatus(200);
        $prayers = $response->json('prayers');

        // Verify all prayer times are integers (timestamps)
        expect($prayers[0]['fajr'])->toBeInt();
        expect($prayers[0]['syuruk'])->toBeInt();
        expect($prayers[0]['dhuhr'])->toBeInt();
        expect($prayers[0]['asr'])->toBeInt();
        expect($prayers[0]['maghrib'])->toBeInt();
        expect($prayers[0]['isha'])->toBeInt();

        // Verify timestamps are reasonable (after 2020)
        expect($prayers[0]['fajr'])->toBeGreaterThan(1577836800); // 2020-01-01
    });

    test('prayer times have day numbers', function () {
        $response = $this->getJson('/v2/solat/sgr01?year=2024&month=6');

        $response->assertStatus(200);
        $prayers = $response->json('prayers');

        // First day should be 1
        expect($prayers[0]['day'])->toBe(1);

        // Last day should be 30 (June has 30 days)
        expect($prayers[count($prayers) - 1]['day'])->toBe(30);
    });

    test('validates year format', function () {
        $response = $this->getJson('/v2/solat/sgr01?year=24');

        $response->assertStatus(422);
    });

    test('validates month minimum', function () {
        $response = $this->getJson('/v2/solat/sgr01?month=0');

        $response->assertStatus(422);
    });

    test('returns 404 for invalid zone', function () {
        $response = $this->getJson('/v2/solat/INVALID999');

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    });

    test('handles different valid zones', function () {
        $zones = ['SGR01', 'WLY01', 'JHR01'];

        foreach ($zones as $zone) {
            $response = $this->getJson("/v2/solat/{$zone}");
            $response->assertStatus(200);
            $response->assertJsonPath('zone', strtoupper($zone));
        }
    });
});

describe('Prayer Time V2 - GPS Endpoint', function () {
    test('get prayer time by GPS coordinates', function () {
        // Coordinates for Kuala Lumpur (WLY01 zone)
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/gps/{$lat}/{$long}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'zone',
            'year',
            'month',
            'month_number',
            'last_updated',
            'prayers' => [
                '*' => [
                    'day',
                    'hijri',
                    'fajr',
                    'syuruk',
                    'dhuhr',
                    'asr',
                    'maghrib',
                    'isha',
                ],
            ],
        ]);

        // Should detect WLY01 zone for KL coordinates
        $response->assertJsonPath('zone', 'WLY01');
    });

    test('get prayer time by GPS with year and month', function () {
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/gps/{$lat}/{$long}?year=2024&month=6");

        $response->assertStatus(200);
        $response->assertJsonPath('year', 2024);
        $response->assertJsonPath('month', 'JUN');
        $response->assertJsonPath('month_number', 6);
    });

    test('GPS endpoint validates year', function () {
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/gps/{$lat}/{$long}?year=2019");

        $response->assertStatus(422);
    });

    test('GPS endpoint validates month', function () {
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/gps/{$lat}/{$long}?month=0");

        $response->assertStatus(422);
    });

    test('returns error for coordinates outside Malaysia', function () {
        // Coordinates in Singapore
        $lat = 1.3521;
        $long = 103.8198;

        $response = $this->getJson("/v2/solat/gps/{$lat}/{$long}");

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    });

    test('returns 422 for non-numeric coordinates', function () {
        $response = $this->getJson('/v2/solat/gps/abc/xyz');

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    });

    test('handles various Malaysian coordinates', function () {
        // Array of coordinates and expected zones
        $testCases = [
            ['lat' => 3.1390, 'long' => 101.6869, 'zone' => 'WLY01'], // Kuala Lumpur
            ['lat' => 5.4164, 'long' => 100.3327, 'zone' => 'PNG01'], // Penang
            ['lat' => 1.4927, 'long' => 103.3952, 'zone' => 'JHR03'], // Johor Bahru
        ];

        foreach ($testCases as $case) {
            $response = $this->getJson("/v2/solat/gps/{$case['lat']}/{$case['long']}");
            $response->assertStatus(200);
            $response->assertJsonPath('zone', $case['zone']);
        }
    });
});

// Testing deprecated endpoint, the result should be the same as the non-deprecated one.
describe('Prayer Time V2 - Deprecated GPS Endpoint', function () {
    test('get prayer time by GPS coordinates', function () {
        // Coordinates for Kuala Lumpur (WLY01 zone)
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/{$lat}/{$long}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'zone',
            'year',
            'month',
            'month_number',
            'last_updated',
            'prayers' => [
                '*' => [
                    'day',
                    'hijri',
                    'fajr',
                    'syuruk',
                    'dhuhr',
                    'asr',
                    'maghrib',
                    'isha',
                ],
            ],
        ]);

        // Should detect WLY01 zone for KL coordinates
        $response->assertJsonPath('zone', 'WLY01');
    });

    test('get prayer time by GPS with year and month', function () {
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/{$lat}/{$long}?year=2024&month=6");

        $response->assertStatus(200);
        $response->assertJsonPath('year', 2024);
        $response->assertJsonPath('month', 'JUN');
        $response->assertJsonPath('month_number', 6);
    });

    test('GPS endpoint validates year', function () {
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/{$lat}/{$long}?year=2019");

        $response->assertStatus(422);
    });

    test('GPS endpoint validates month', function () {
        $lat = 3.1390;
        $long = 101.6869;

        $response = $this->getJson("/v2/solat/{$lat}/{$long}?month=0");

        $response->assertStatus(422);
    });

    test('returns error for coordinates outside Malaysia', function () {
        // Coordinates in Singapore
        $lat = 1.3521;
        $long = 103.8198;

        $response = $this->getJson("/v2/solat/{$lat}/{$long}");

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    });

    test('returns 422 for non-numeric coordinates', function () {
        $response = $this->getJson('/v2/solat/abc/xyz');

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    });

    test('handles various Malaysian coordinates', function () {
        // Array of coordinates and expected zones
        $testCases = [
            ['lat' => 3.1390, 'long' => 101.6869, 'zone' => 'WLY01'], // Kuala Lumpur
            ['lat' => 5.4164, 'long' => 100.3327, 'zone' => 'PNG01'], // Penang
            ['lat' => 1.4927, 'long' => 103.3952, 'zone' => 'JHR03'], // Johor Bahru
        ];

        foreach ($testCases as $case) {
            $response = $this->getJson("/v2/solat/{$case['lat']}/{$case['long']}");
            $response->assertStatus(200);
            $response->assertJsonPath('zone', $case['zone']);
        }
    });
});
