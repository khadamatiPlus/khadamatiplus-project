<?php
use Tabuna\Breadcrumbs\Trail;
use App\Domains\Offer\Models\Offer;
use App\Domains\Offer\Http\Controllers\Backend\OfferController;

/**
 * Offer Routes
 */
Route::group([
    'prefix' => 'offer',
    'as' => 'offer.'
], function () {
    Route::get('create', [OfferController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.offer.index')
                ->push(__('Create Offer'), route('admin.offer.create'));
        });

    Route::post('store', [OfferController::class, 'store'])
        ->name('store');

    Route::group(['prefix' => '{offer}'], function () {
        Route::get('edit', [OfferController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Offer $offer) {
                $trail->parent('admin.offer.index', $offer)
                    ->push(__('Editing :entity', ['entity' => __('Offer')]), route('admin.offer.edit', $offer));
            });
        
        Route::get('show', [OfferController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, Offer $offer) {
                $trail->parent('admin.offer.index', $offer)
                    ->push(__('View :entity', ['entity' => __('Offer')]), route('admin.offer.show', $offer));
            });
        
        Route::patch('/', [OfferController::class, 'update'])
            ->name('update');

        Route::delete('delete', [OfferController::class, 'destroy'])
            ->name('delete');
    });

    Route::get('/', [OfferController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Offers Management'), route('admin.offer.index'));
        });
});
/**
 * End Offer Routes
 */
