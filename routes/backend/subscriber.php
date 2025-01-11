<?php
use App\Domains\Subscriber\Http\Controllers\Backend\SubscriberController;
use Tabuna\Breadcrumbs\Trail;

/**
 * subscriber Routes
 */
Route::group([
    'prefix' => 'subscriber',
    'as' => 'subscriber.'
], function (){
    Route::get('/', [SubscriberController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.subscriber.list|admin.subscriber.store|admin.subscriber.update|admin.subscriber.delete')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('subscriber Management'), route('admin.subscriber.index'));
        });

});
/**
 * End subscriber Routes
 */
