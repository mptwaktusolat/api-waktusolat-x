<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrayerTimeContoller extends Controller
{
    public function index() {
        return response()->json("Hello World");
    }
}
