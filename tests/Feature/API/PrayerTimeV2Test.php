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
                    'imsak',
                    'fajr',
                    'syuruk',
                    'dhuha',
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

    test('returns null dhuha for SWK01 in 2025', function () {
        $response = $this->getJson('/v2/solat/swk01?year=2025&month=1');

        $response->assertStatus(200);
        $prayers = $response->json('prayers');

        expect($prayers)->toBeArray();
        expect($prayers[0]['dhuha'])->toBeNull();
        expect($prayers[0]['imsak'])->not->toBeNull();
    });

    test('prayer times data are correct', function () {
        $response = $this->getJson('/v2/solat/sgr01?year=2026&month=7');

        $response->assertStatus(200);
        $prayers = $response->json('prayers');

        // Verify all prayer times data for first day
        expect($prayers[0]['day'])->toEqual(1);
        expect($prayers[0]['hijri'])->toEqual('1448-01-15');
        expect($prayers[0]['imsak'])->toEqual(1782855840);
        expect($prayers[0]['fajr'])->toEqual(1782856440);
        expect($prayers[0]['syuruk'])->toEqual(1782860820);
        expect($prayers[0]['dhuha'])->toEqual(1782862320);
        expect($prayers[0]['dhuhr'])->toEqual(1782883200);
        expect($prayers[0]['asr'])->toEqual(1782895500);
        expect($prayers[0]['maghrib'])->toEqual(1782905340);
        expect($prayers[0]['isha'])->toEqual(1782909840);
    });

    test('prayer times have day numbers', function () {
        $response = $this->getJson('/v2/solat/sgr01?year=2026&month=7');

        $response->assertStatus(200);
        $prayers = $response->json('prayers');

        // First day should be 1
        expect($prayers[0]['day'])->toBe(1);

        // Last day should be 31 (July has 31 days)
        expect($prayers[count($prayers) - 1]['day'])->toBe(31);
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
                    'imsak',
                    'fajr',
                    'syuruk',
                    'dhuha',
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

        $response = $this->getJson("/v2/solat/gps/{$lat}/{$long}?year=2026&month=7");

        $response->assertStatus(200);
        $response->assertJsonPath('zone', 'WLY01');
        $response->assertJsonPath('year', 2026);
        $response->assertJsonPath('month', 'JUL');
        $response->assertJsonPath('month_number', 7);
        $response->assertJsonPath('last_updated', null);

        // Verify first prayer time data
        $response->assertJsonPath('prayers.0.day', 1);
        $response->assertJsonPath('prayers.0.hijri', '1448-01-15');
        $response->assertJsonPath('prayers.0.imsak', 1782855840);
        $response->assertJsonPath('prayers.0.fajr', 1782856440);
        $response->assertJsonPath('prayers.0.syuruk', 1782860820);
        $response->assertJsonPath('prayers.0.dhuha', 1782862320);
        $response->assertJsonPath('prayers.0.dhuhr', 1782883200);
        $response->assertJsonPath('prayers.0.asr', 1782895500);
        $response->assertJsonPath('prayers.0.maghrib', 1782905340);
        $response->assertJsonPath('prayers.0.isha', 1782909840);
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
                    'imsak',
                    'fajr',
                    'syuruk',
                    'dhuha',
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

        $response = $this->getJson("/v2/solat/{$lat}/{$long}?year=2026&month=7");

        $response->assertStatus(200);
        $response->assertJsonPath('zone', 'WLY01');
        $response->assertJsonPath('year', 2026);
        $response->assertJsonPath('month', 'JUL');
        $response->assertJsonPath('month_number', 7);
        $response->assertJsonPath('last_updated', null);

        // Verify first prayer time data
        $response->assertJsonPath('prayers.0.day', 1);
        $response->assertJsonPath('prayers.0.hijri', '1448-01-15');
        $response->assertJsonPath('prayers.0.imsak', 1782855840);
        $response->assertJsonPath('prayers.0.fajr', 1782856440);
        $response->assertJsonPath('prayers.0.syuruk', 1782860820);
        $response->assertJsonPath('prayers.0.dhuha', 1782862320);
        $response->assertJsonPath('prayers.0.dhuhr', 1782883200);
        $response->assertJsonPath('prayers.0.asr', 1782895500);
        $response->assertJsonPath('prayers.0.maghrib', 1782905340);
        $response->assertJsonPath('prayers.0.isha', 1782909840);
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
