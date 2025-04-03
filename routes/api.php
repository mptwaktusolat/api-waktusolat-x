<?php

use App\Http\Controllers\api\v1\PrayerTimeV1Contoller;
use App\Http\Controllers\api\v2\PrayerTimeContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/v2/solat/{zone}", [PrayerTimeContoller::class,"index"])->name("v2.solat.index");

Route::get("/solat/{zone}", [PrayerTimeV1Contoller::class,"index"])->name("v1.solat.index");

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

