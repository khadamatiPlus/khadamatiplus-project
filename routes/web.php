<?php

use App\Http\Controllers\LocaleController;

/*
 * Global Routes
 *
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LocaleController::class, 'change'])->name('locale.change');

/*
 * Frontend Routes
 */
Route::group(['as' => 'frontend.'], function () {
    includeRouteFiles(__DIR__.'/frontend/');
});

/*
 * Backend Routes
 *
 * These routes can only be accessed by users with type `admin`
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    includeRouteFiles(__DIR__.'/backend/');
});
Route::delete('/service-images/{id}', [\App\Domains\Service\Http\Controllers\Backend\ServiceController::class, 'destroyImage'])->name('service-images.destroy');
Route::delete('/products/{product}', [\App\Domains\Service\Http\Controllers\Backend\ServiceController::class, 'destroyProduct'])->name('products.destroy');
