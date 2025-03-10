<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EconomicGroupController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UnitController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('economic-group', [EconomicGroupController::class, 'index'])->name('economic.group.list');
    Route::post('economic-group', [EconomicGroupController::class, 'store'])->name('economic.group.create');
    Route::get('economic-group/edit/{economicGroup}', [EconomicGroupController::class, 'edit'])->name('economic.group.edit');
    Route::patch('economic-group/{economicGroup}', [EconomicGroupController::class, 'update'])->name('economic.group.update');
    Route::delete('economic-group/{economicGroup}', [EconomicGroupController::class, 'destroy'])->name('economic.group.delete');

    Route::get('brand', [BrandController::class, 'index'])->name('brand.list');
    Route::post('brand', [BrandController::class, 'store'])->name('brand.create');
    Route::get('brand/edit/{brand}', [BrandController::class, 'edit'])->name('brand.edit');
    Route::patch('brand/{brand}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('brand/{brand}', [BrandController::class, 'destroy'])->name('brand.delete');

    Route::get('unit', [UnitController::class, 'index'])->name('unit.list');
    Route::post('unit', [UnitController::class, 'store'])->name('unit.create');
    Route::get('unit/edit/{unit}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::patch('unit/{unit}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('unit/{unit}', [UnitController::class, 'destroy'])->name('unit.delete');

    Route::resource('employees', EmployeeController::class);
    Route::post('employees-export', [DashboardController::class, 'exportToExcel']);
    // Route::get('employees-report', [EmployeeController::class, 'report']);
    // Route::post('employees-import', [EmployeeController::class, 'import']);
});

require __DIR__ . '/auth.php';
