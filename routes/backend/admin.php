<?php

use App\Http\Controllers\Backend\DashboardController;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });

// Test route for real-time updates
Route::get('test-real-time', function() {
    return response()->json(['message' => 'Real-time test endpoint']);
})->name('test.real-time');

// Test page for real-time dashboard
Route::get('test-real-time-page', function() {
    return view('backend.test-real-time');
})->name('test.real-time-page');

// Route for logging call attempts (optional)
Route::post('log-call-attempt', function(\Illuminate\Http\Request $request) {
    // Log the call attempt to database or file
    \Log::info('Admin call attempt', [
        'phone_number' => $request->phone_number,
        'contact_type' => $request->contact_type,
        'admin_user' => auth()->user()->name,
        'timestamp' => $request->timestamp
    ]);
    
    return response()->json(['success' => true]);
})->name('log-call-attempt');
