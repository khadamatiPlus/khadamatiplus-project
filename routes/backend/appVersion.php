<?php
use Tabuna\Breadcrumbs\Trail;

use App\Domains\AppVersion\Models\AppVersion;
use App\Domains\AppVersion\Http\Controllers\Backend\AppVersionController;

Route::group([
    'prefix' => 'appVersion',
    'as' => 'appVersion.'
], function (){
    Route::group(['prefix' => '{appVersion}'], function () {
        Route::get('edit', [AppVersionController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:admin.appVersion.update')
            ->breadcrumbs(function (Trail $trail, AppVersion $appVersion) {
                $trail->parent('admin.dashboard', $appVersion)
                    ->push(__('Update App Version Media'), route('admin.appVersion.edit', $appVersion));
            });

        Route::patch('/', [AppVersionController::class, 'update'])
            ->name('update')
            ->middleware('permission:admin.appVersion.update');
    });
});
/**
 * End appVersion Routes
 */
