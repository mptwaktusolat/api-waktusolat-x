<?php

describe('Prayer Time V1 - Month Endpoint', function () {
    test('get prayer time by month (no param)', function () {
        $response = $this->getJson('/solat/sgr01');

        $response->assertStatus(200);

        $response->assertExactJsonStructure([
            'prayerTime' => [
                '*' => [
                    'hijri',
                    'date',
                    'day',
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
            'status',
            'serverTime',
            'periodType',
            'lang',
            'zone',
            'bearing',
        ]);

        $response
            ->assertJsonPath('status', 'OK!')
            ->assertJsonPath('periodType', 'month')
            ->assertJsonPath('lang', '')
            ->assertJsonPath('bearing', '')
            ->assertJsonPath('zone', 'SGR01');
    });

    test('returns correct prayer times for specific month', function () {
        $response = $this->getJson('/solat/ngs03?year=2026&month=7');

        $response->assertStatus(200);
        $response->assertJsonPath('zone', 'NGS03');
        $response->assertJsonPath('periodType', 'month');

        // Asserts the prayerTime array data
        $response->assertJsonIsArray('prayerTime');
        $response->assertJsonPath('prayerTime.0.hijri', '1448-01-15');
        $response->assertJsonPath('prayerTime.0.date', '01-Jul-2026');
        $response->assertJsonPath('prayerTime.0.day', 'Wednesday');
        $response->assertJsonPath('prayerTime.0.imsak', '05:44:00');
        $response->assertJsonPath('prayerTime.0.fajr', '05:54:00');
        $response->assertJsonPath('prayerTime.0.syuruk', '07:07:00');
        $response->assertJsonPath('prayerTime.0.dhuha', '07:35:00');
        $response->assertJsonPath('prayerTime.0.dhuhr', '13:19:00');
        $response->assertJsonPath('prayerTime.0.asr', '16:44:00');
        $response->assertJsonPath('prayerTime.0.maghrib', '19:26:00');
        $response->assertJsonPath('prayerTime.0.isha', '20:41:00');
    });

    test('get prayer time by month returns null dhuha for SWK01 in 2025', function () {
        $response = $this->getJson('/solat/swk01?year=2025&month=1');

        $response->assertStatus(200);

        $prayerTimes = $response->json('prayerTime');
        expect($prayerTimes)->toBeArray();
        expect($prayerTimes[0]['imsak'])->not->toBeEmpty();
        expect($prayerTimes[0]['fajr'])->not->toBeEmpty();
        expect($prayerTimes[0]['syuruk'])->not->toBeEmpty();
        expect($prayerTimes[0]['dhuha'])->toBeNull(); // only dhuha we expect to be null
        expect($prayerTimes[0]['dhuhr'])->not->toBeEmpty();
        expect($prayerTimes[0]['asr'])->not->toBeEmpty();
        expect($prayerTimes[0]['maghrib'])->not->toBeEmpty();
        expect($prayerTimes[0]['isha'])->not->toBeEmpty();
    });

    test('get prayer time by month with lowercase zone code', function () {
        $response = $this->getJson('/solat/sgr01');

        $response->assertStatus(200);
        $response->assertJsonPath('zone', 'SGR01'); // Should be uppercase
    });

    test('get prayer time by month validates year format', function () {
        $response = $this->getJson('/solat/sgr01?year=24');

        $response->assertStatus(422); // Validation error
    });

    test('get prayer time by month validates month minimum', function () {
        $response = $this->getJson('/solat/sgr01?month=0');

        $response->assertStatus(422); // Validation error
    });

    test('get prayer time returns proper time format', function () {
        $response = $this->getJson('/solat/sgr01');

        $response->assertStatus(200);
        $prayerTimes = $response->json('prayerTime');

        // Check first day has proper time format (HH:MM:SS)
        expect($prayerTimes[0]['imsak'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect($prayerTimes[0]['fajr'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect($prayerTimes[0]['syuruk'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect($prayerTimes[0]['dhuha'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect($prayerTimes[0]['dhuhr'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect($prayerTimes[0]['asr'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect($prayerTimes[0]['maghrib'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect($prayerTimes[0]['isha'])->toMatch('/^\d{2}:\d{2}:\d{2}$/');
    });

    test('get prayer time returns proper date format', function () {
        $response = $this->getJson('/solat/sgr01?year=2024&month=6');

        $response->assertStatus(200);
        $prayerTimes = $response->json('prayerTime');

        // Check first day has proper date format (dd-MMM-yyyy)
        expect($prayerTimes[0]['date'])->toMatch('/^\d{2}-[A-Za-z]{3}-\d{4}$/');
    });
});

describe('Prayer Time V1 - Day Endpoint', function () {
    test('get prayer time for specific day', function () {
        $response = $this->getJson('/solat/sgr01/15');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'prayerTime' => [
                'hijri',
                'date',
                'day',
                'imsak',
                'fajr',
                'syuruk',
                'dhuha',
                'dhuhr',
                'asr',
                'maghrib',
                'isha',
            ],
            'status',
            'serverTime',
            'periodType',
            'lang',
            'zone',
            'bearing',
        ]);

        $response
            ->assertJsonPath('status', 'OK!')
            ->assertJsonPath('periodType', 'day')
            ->assertJsonPath('zone', 'SGR01');
    });

    test('get prayer time for specific day with year and month', function () {
        $response = $this->getJson('/solat/sgr01/1?year=2026&month=7');

        $response->assertStatus(200);
        $response->assertJsonPath('zone', 'SGR01');
        $response->assertJsonPath('periodType', 'day');

        // Asserts prayer time data
        $response->assertJsonPath('prayerTime.hijri', '1448-01-15');
        $response->assertJsonPath('prayerTime.date', '01-Jul-2026');
        $response->assertJsonPath('prayerTime.day', 'Wednesday');
        $response->assertJsonPath('prayerTime.imsak', '05:44:00');
        $response->assertJsonPath('prayerTime.fajr', '05:54:00');
        $response->assertJsonPath('prayerTime.syuruk', '07:07:00');
        $response->assertJsonPath('prayerTime.dhuha', '07:32:00');
        $response->assertJsonPath('prayerTime.dhuhr', '13:20:00');
        $response->assertJsonPath('prayerTime.asr', '16:45:00');
        $response->assertJsonPath('prayerTime.maghrib', '19:29:00');
        $response->assertJsonPath('prayerTime.isha', '20:44:00');
    });

    test('get prayer time for last day of month', function () {
        $response = $this->getJson('/solat/sgr01/31?year=2024&month=1'); // January has 31 days

        $response->assertStatus(200);
        $response->assertJsonPath('periodType', 'day');
    });

    test('get prayer time for invalid day returns error', function () {
        $response = $this->getJson('/solat/sgr01/32'); // Day 32 doesn't exist

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid day provided.']);
    });

    test('get prayer time for day 0 returns error', function () {
        $response = $this->getJson('/solat/sgr01/0');

        $response->assertStatus(400);
    });

    test('get prayer time for February 30 returns error', function () {
        $response = $this->getJson('/solat/sgr01/30?year=2024&month=2'); // Feb only has 29 days in 2024

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid day provided.']);
    });

    test('rejects non-numeric day parameter', function () {
        $response = $this->getJson('/solat/sgr01/abc');

        $response->assertStatus(404); // Route not found due to whereNumber constraint
    });

    test('accepts numeric string day parameter', function () {
        $response = $this->getJson('/solat/sgr01/15');

        $response->assertStatus(200);
        $response->assertJsonPath('periodType', 'day');
    });
});

describe('Prayer Time V1 - Error Handling', function () {
    test('returns 500 for invalid zone code', function () {
        $response = $this->getJson('/solat/INVALID123');

        $response->assertStatus(500);
    });

    test('handles different zone codes correctly', function () {
        // Test a few different valid zone codes
        $zones = ['SGR01', 'WLY01', 'JHR01', 'KDH01'];

        foreach ($zones as $zone) {
            $response = $this->getJson("/solat/{$zone}");
            $response->assertStatus(200);
            $response->assertJsonPath('zone', strtoupper($zone));
        }
    });

    test('has CORS header allowing all origins', function () {
        $response = $this->getJson('/solat/sgr01');

        $response->assertStatus(200);
        $response->assertHeader('Access-Control-Allow-Origin', '*');
    });
});
