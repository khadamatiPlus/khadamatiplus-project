<?php

use App\Domains\Wallet\Http\Controllers\Backend\WalletController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'wallet',
    'as' => 'wallet.'
], function () {
    Route::get('/', [WalletController::class, 'index'])->name('index');
});
