<?php

use Illuminate\Testing\Fluent\AssertableJson;

test('get prayer time by month (no param)', function () {
    $response = $this->getJson('/api/solat/sgr01');

    $response->assertStatus(200);

    $response->assertJsonStructure([
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
            ]
        ]
    ]);

    // TODO: Check if the month and year of the prayer times match the current month and year

    // $currentMonth = now()->format('m');
    // $currentYear = now()->format('Y');

    // foreach ($prayerTimes as $prayerTime) {
    //     $date = \Carbon\Carbon::createFromFormat('d-M-Y', $prayerTime['date']);
    //     $this->assertEquals($currentMonth, $date->format('m'), 'Month should match the current month.');
    //     $this->assertEquals($currentYear, $date->format('Y'), 'Year should match the current year.');
    // }

    // $response->assertJson(function (AssertableJson $json) {
    //     $json->has('prayerTime')
    //         ->where('status', 'OK!')
    //         ->where('periodType', 'month')
    //         ->where('zone', 'SGR01')
    //         ->whereType('prayerTime', 'array')
    //         ->etc();
    // });
});
