<?php

test('get prayer time by month (no param)', function () {
    $response = $this->getJson('/api/solat/sgr01');

    $response->assertStatus(200);

    $response->assertExactJsonStructure([
        'prayerTime' => [
            '*' => [
                'hijri',
                'date',
                'day',
                'fajr',
                'syuruk',
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
