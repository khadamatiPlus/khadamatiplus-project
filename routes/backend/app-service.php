<?php
use Tabuna\Breadcrumbs\Trail;
use App\Domains\AppService\Models\AppService;
use App\Domains\AppService\Http\Controllers\Backend\AppServiceController;

/**
 * App Service Routes
 */
Route::group([
    'prefix' => 'app-service',
    'as' => 'app-service.'
], function () {
    Route::get('create', [AppServiceController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.app-service.index')
                ->push(__('Create App Service'), route('admin.app-service.create'));
        });

    Route::post('store', [AppServiceController::class, 'store'])
        ->name('store');

    Route::group(['prefix' => '{appService}'], function () {
        Route::get('edit', [AppServiceController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, AppService $appService) {
                $trail->parent('admin.app-service.index', $appService)
                    ->push(__('Editing :entity', ['entity' => __('App Service')]), route('admin.app-service.edit', $appService));
            });
        
        Route::get('show', [AppServiceController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, AppService $appService) {
                $trail->parent('admin.app-service.index', $appService)
                    ->push(__('View :entity', ['entity' => __('App Service')]), route('admin.app-service.show', $appService));
            });
        
        Route::patch('/', [AppServiceController::class, 'update'])
            ->name('update');

        Route::delete('delete', [AppServiceController::class, 'destroy'])
            ->name('delete');
    });

    Route::get('/', [AppServiceController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('App Services Management'), route('admin.app-service.index'));
        });
});
/**
 * End App Service Routes
 */
