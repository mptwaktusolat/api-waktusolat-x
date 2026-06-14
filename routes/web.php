<?php

use App\Http\Controllers\DataHealthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/locations', function () {
    return redirect('https://peta.waktusolat.app/');
})->name('locations');

Route::get('/health', [DataHealthController::class, 'index'])->name('data-health');

Route::get('/feedback', function () {
    return view('feedback');
})->name('feedback');

Route::get('/feedback/success', function () {
    return view('feedback-success');
})->name('feedback.success');

// Use to debug if reverse proxy setup is working
// More info: https://github.com/mptwaktusolat/api-waktusolat-x/tree/main/docs/deployments/docker-compose.md#reverse-proxy
Route::get('/_debug/proxy-headers', function (Request $request) {
    return [
        'is_secure' => $request->isSecure(),
        'scheme' => $request->getScheme(),
        'forwarded_proto' => $request->header('X-Forwarded-Proto'),
        'forwarded_for' => $request->header('X-Forwarded-For'),
        'url' => $request->url(),
    ];
});
