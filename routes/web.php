<?php

use App\Http\Controllers\DataHealthController;
use Illuminate\Http\Request;
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

Route::get('/debug-headers', function (Request $request) {
    return [
        'is_secure' => $request->isSecure(),
        'scheme' => $request->getScheme(),
        'forwarded_proto' => $request->header('X-Forwarded-Proto'),
        'forwarded_for' => $request->header('X-Forwarded-For'),
        'url' => $request->url(),
    ];
});

require __DIR__.'/auth.php';
