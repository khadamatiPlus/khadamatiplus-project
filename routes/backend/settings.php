<?php

use App\Domains\Setting\Http\Controllers\Backend\SettingController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'settings',
    'as' => 'settings.'
], function () {
    Route::get('/', [SettingController::class, 'edit'])->name('edit');
    Route::post('/', [SettingController::class, 'update'])->name('update');
});
