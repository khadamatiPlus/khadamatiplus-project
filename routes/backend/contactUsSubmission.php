<?php
use Tabuna\Breadcrumbs\Trail;
use App\Domains\ContactUsSubmission\Http\Controllers\Backend\ContactUsSubmissionController;
//CMS ContactUsSubmission group
Route::group([
    'prefix' => 'contactUsSubmission',
    'as' => 'contactUsSubmission.',
], function () {
    Route::get('/', [ContactUsSubmissionController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.contactUsSubmission.list')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Contact Us Submission Management'), route('admin.contactUsSubmission.index'));
        });
    Route::group(['prefix' => '{contactUsSubmission}'], function () {

        Route::delete('delete', [ContactUsSubmissionController::class, 'destroy'])
            ->name('delete')
            ->middleware('permission:admin.contactUsSubmission.delete');
    });
});
