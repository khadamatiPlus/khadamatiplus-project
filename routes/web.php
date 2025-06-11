<?php

use App\Http\Controllers\LocaleController;
use App\Services\FirebaseNotificationService;
use Illuminate\Support\Facades\Route;
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
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/version', [App\Http\Controllers\Admin\AppVersionController::class, 'edit'])->name('version.edit');
    Route::put('/version', [App\Http\Controllers\Admin\AppVersionController::class, 'update'])->name('version.update');
});


Route::get('/test-notification', function () {
    // Replace with a valid device token from your Firebase app
    $deviceToken = 'dU9sO9pXRk6JCIB-6uNk1F:APA91bHk4v9J7Z...Dk3mWrqoQwYd7E2TxVfXq';

    $service = new FirebaseNotificationService();
    $result = $service->sendPushNotification(
        $deviceToken,
        'Test Notification',
        'This is a test message from Laravel!'
    );

    if ($result) {
        return response()->json([
            'status' => 'success',
            'message' => 'Notification sent successfully!'
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Failed to send notification'
    ], 500);
});
