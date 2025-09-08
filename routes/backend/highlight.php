<?php
use Tabuna\Breadcrumbs\Trail;
use App\Domains\Highlight\Models\Highlight;
use App\Domains\Highlight\Http\Controllers\Backend\HighlightController;
/**
 * Highlight Routes
 */
Route::group([
    'prefix' => 'highlight',
    'as' => 'highlight.'
], function () {
    Route::get('create', [HighlightController::class, 'create'])
        ->name('create')
        ->middleware('permission:admin.highlight.store')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.highlight.index')
                ->push(__('Create Highlight'), route('admin.highlight.create'));
        });

    Route::post('store', [HighlightController::class, 'store'])
        ->name('store')
        ->middleware('permission:admin.highlight.store');

    Route::group(['prefix' => '{highlight}'], function () {
        Route::get('edit', [HighlightController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.highlight.update')
            ->breadcrumbs(function (Trail $trail, Highlight $highlight) {
                $trail->parent('admin.highlight.index', $highlight)
                    ->push(__('Editing :entity', ['entity' => __('Highlight')]), route('admin.highlight.edit', $highlight));
            });

        Route::patch('/', [HighlightController::class, 'update'])
            ->name('update')
            ->middleware('permission:admin.highlight.update');

        Route::delete('delete', [HighlightController::class, 'destroy'])
            ->name('delete')
            ->middleware('permission:admin.highlight.delete');
    });

    Route::get('/', [HighlightController::class, 'index'])
        ->name('index')
        ->middleware('permission:admin.highlight.list|admin.highlight.store|admin.highlight.update|admin.highlight.delete')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Highlight Management'), route('admin.highlight.index'));
        });
});
/**
 * End Highlight Routes
 */
