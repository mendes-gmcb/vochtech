<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EconomicGroupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('economic-group', [EconomicGroupController::class, 'index'])->name('economic.group.list');
    Route::post('economic-group', [EconomicGroupController::class, 'store'])->name('economic.group.create');
    Route::get('economic-group/edit/{economicGroup}', [EconomicGroupController::class, 'edit'])->name('economic.group.edit');
    Route::patch('economic-group/{economicGroup}', [EconomicGroupController::class, 'update'])->name('economic.group.update');
    Route::delete('economic-group/{economicGroup}', [EconomicGroupController::class, 'destroy'])->name('economic.group.delete');
});

require __DIR__.'/auth.php';
