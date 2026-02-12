<?php

use App\Http\Controllers\api\v1\JadualSolatController;
use App\Http\Controllers\api\v1\PrayerTimeV1Contoller;
use App\Http\Controllers\api\v1\ZonesController;
use App\Http\Controllers\api\v2\PrayerTimeController;
use Illuminate\Support\Facades\Route;

Route::middleware('cache.headers:public;max_age=3600;etag')->group(function () {
    Route::get('/v2/solat/{zone}', [PrayerTimeController::class, 'fetchMonth'])->name('v2.solat.month');
    Route::get('/v2/solat/{lat}/{long}', [PrayerTimeController::class, 'fetchMonthLocationByGpsDeprecated'])->name('v2.solat.month_with_gps_deprecated');
    Route::get('/v2/solat/gps/{lat}/{long}', [PrayerTimeController::class, 'fetchMonthLocationByGps'])->name('v2.solat.month_with_gps');

    Route::get('/solat/{zone}', [PrayerTimeV1Contoller::class, 'fetchMonth'])->name('v1.solat.month');
    Route::get('/solat/{zone}/{day}', [PrayerTimeV1Contoller::class, 'fetchDay'])
        ->name('v1.solat.day')
        ->whereNumber('day');

    Route::prefix('zones')->group(function () {
        Route::get('/', [ZonesController::class, 'index'])->name('zones.index');
        Route::get('/{state}', [ZonesController::class, 'getByState'])->name('zones.state');
        Route::get('/{lat}/{long}', [ZonesController::class, 'getZoneFromCoordinate'])->name('zones.gps');
    });

    Route::get('/jadual_solat/{zone}', [JadualSolatController::class, 'fetchMonth'])->name('jadual_solat.index');
});

Route::fallback(function () {
    return response()->json(['message' => 'No route matched. Please see the API documentation at '.url('/docs')], 404);
});
