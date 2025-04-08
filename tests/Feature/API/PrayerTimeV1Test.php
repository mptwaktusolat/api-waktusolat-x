<?php

use Illuminate\Testing\Fluent\AssertableJson;

test('Get Prayer Time By Month (Default)', function () {
    $response = $this->get('/api/solat/sgr01');

    $response->assertStatus(200);

    $prayerTimes =  $response->json('prayerTime');

    $this->assertNotEmpty($prayerTimes, 'Prayer times array should not be empty.');

    $currentMonth = now()->format('m');
    $currentYear = now()->format('Y');

    foreach ($prayerTimes as $prayerTime) {
        $date = \Carbon\Carbon::createFromFormat('d-M-Y', $prayerTime['date']);
            $this->assertEquals($currentMonth, $date->format('m'), 'Month should match the current month.');
            $this->assertEquals($currentYear, $date->format('Y'), 'Year should match the current year.');
    }

    $response->assertJson(function (AssertableJson $json) {
        $json->has('prayerTime')
             ->where('status', 'OK!')
             ->where('periodType', 'month')
             ->where('zone', 'SGR01')
             ->whereType('prayerTime', 'array')
             ->etc();
    });

});
