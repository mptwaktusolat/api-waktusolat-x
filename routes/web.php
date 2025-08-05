<?php

use App\Http\Controllers\DataHealthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/locations', function () {
    return redirect('https://peta.waktusolat.app/');
})->name('locations');

Route::get('/health', [DataHealthController::class, 'index'])->name('data-health');

require __DIR__ . '/auth.php';
