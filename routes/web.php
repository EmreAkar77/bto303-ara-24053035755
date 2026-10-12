<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('vehicles.index');
})->name('home');

// Müsait araç arama sayfası (Özel operasyon rotası)
Route::get('availability', [AvailabilityController::class, 'index'])->name('availability.index');

// Filo istatistik sayfası (Özel analitik rotası)
Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');

// Tam 7 rotalı CRUD kaynakları (Rubric C1)
Route::resource('vehicles', VehicleController::class);
Route::resource('rentals', RentalController::class);

// Müşteri ve Şube yardımcı kaynakları
Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store']);
Route::resource('branches', BranchController::class)->only(['index', 'create', 'store']);
