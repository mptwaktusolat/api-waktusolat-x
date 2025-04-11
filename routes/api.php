<?php

use App\Http\Controllers\api\v1\PrayerTimeV1Contoller;
use App\Http\Controllers\api\v1\ZonesController;
use App\Http\Controllers\api\v2\PrayerTimeContoller;
use Illuminate\Support\Facades\Route;

Route::get('/v2/solat/{zone}', [PrayerTimeContoller::class, 'fetchMonth'])->name('v2.solat.month');
Route::get('/v2/solat/{lat}/{long}', [PrayerTimeContoller::class, 'fetchMonthLocationByGps'])->name('v2.solat.month_with_gps');

Route::get('/solat/{zone}', [PrayerTimeV1Contoller::class, 'fetchMonth'])->name('v1.solat.month');
Route::get('/solat/{zone}/{day}', [PrayerTimeV1Contoller::class, 'fetchDay'])->name('v1.solat.day');

Route::prefix('zones')->group(function () {
    Route::get('/', [ZonesController::class, 'index'])->name('zones.index');
    Route::get('/{state}', [ZonesController::class, 'getByState'])->name('zones.state');
    Route::get('/{lat}/{long}', [ZonesController::class, 'getZoneFromCoordinate'])->name('zones.gps');
});
