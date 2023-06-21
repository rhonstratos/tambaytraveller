<?php

use App\Http\Controllers\Api\V1\TourController;
use App\Http\Controllers\Api\V1\TravelController;
use Illuminate\Support\Facades\Route;

Route::resource('travels', TravelController::class)
    ->only(['index']);
Route::resource('travels.tours', TourController::class)
    ->only(['index'])
    ->parameters(['travels' => 'travel:slug']);
