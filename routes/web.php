<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DataHealthController;

Route::get('/', function () {
    return Inertia::render('home');
})->name('home');

Route::get('/health', [DataHealthController::class, 'index'])->name('data-health');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
