<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TermsController;
use Tabuna\Breadcrumbs\Trail;
use App\Domains\ContactUsSubmission\Http\Controllers\Frontend\ContactUsSubmissionController;
use App\Domains\Subscriber\Http\Controllers\Frontend\SubscriberController;
/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', [HomeController::class, 'index'])
    ->name('index')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('frontend.index'));
    });
Route::get('provider', [HomeController::class, 'providerIndex'])->name('provider.index');
Route::get('terms', [TermsController::class, 'index'])
    ->name('pages.terms')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('frontend.index')
            ->push(__('Terms & Conditions'), route('frontend.pages.terms'));
    });
Route::get('privacy-policy', [TermsController::class, 'privacy'])
    ->name('pages.terms')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('frontend.privacy')
            ->push(__('Privacy Policy'), route('frontend.pages.privacy'));
    });
Route::prefix('contactUsSubmission')->group(function () {
    Route::post('store', [ContactUsSubmissionController::class, 'store'])
        ->name('contactUsSubmission');
});
Route::post('storeSubscriber', [SubscriberController::class, 'store'])
    ->name('storeSubscriber');
