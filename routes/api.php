<?php

use App\Http\Controllers\api\v1\PrayerTimeV1Contoller;
use App\Http\Controllers\api\v2\PrayerTimeContoller;
use Illuminate\Support\Facades\Route;

Route::get("/v2/solat/{zone}", [PrayerTimeContoller::class,"fetchMonth"])->name("v2.solat.month");

Route::get("/solat/{zone}", [PrayerTimeV1Contoller::class,'fetchMonth'])->name("v1.solat.month");
