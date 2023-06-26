<?php

use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\TourController;
use App\Http\Controllers\Api\V1\TravelController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class);
});

Route::middleware(['auth:sanctum'])
    ->prefix('admin')
    ->group(function () {
        Route::middleware(['role:admin'])->group(function () {
            Route::post('travels', [Admin\TravelController::class, 'store']);
            Route::post('travels/{travel}/tours', [Admin\TourController::class, 'store']);
        });
        Route::put('travels/{travel}', [Admin\TravelController::class, 'update']);
    });


Route::resource('travels', TravelController::class)
    ->only(['index']);
Route::resource('travels.tours', TourController::class)
    ->only(['index'])
    ->parameters(['travels' => 'travel:slug']);
