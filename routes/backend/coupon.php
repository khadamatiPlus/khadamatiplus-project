<?php
use Tabuna\Breadcrumbs\Trail;
use App\Domains\Coupon\Models\Coupon;
use App\Domains\Coupon\Http\Controllers\Backend\CouponController;

/**
 * Coupon Routes
 */
Route::group([
    'prefix' => 'coupon',
    'as' => 'coupon.'
], function () {
    Route::get('create', [CouponController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.coupon.index')
                ->push(__('Create Coupon'), route('admin.coupon.create'));
        });

    Route::post('store', [CouponController::class, 'store'])
        ->name('store');

    Route::group(['prefix' => '{coupon}'], function () {
        Route::get('edit', [CouponController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Coupon $coupon) {
                $trail->parent('admin.coupon.index', $coupon)
                    ->push(__('Editing :entity', ['entity' => __('Coupon')]), route('admin.coupon.edit', $coupon));
            });
        
        Route::get('show', [CouponController::class, 'show'])
            ->name('show')
            ->breadcrumbs(function (Trail $trail, Coupon $coupon) {
                $trail->parent('admin.coupon.index', $coupon)
                    ->push(__('View :entity', ['entity' => __('Coupon')]), route('admin.coupon.show', $coupon));
            });
        
        Route::patch('/', [CouponController::class, 'update'])
            ->name('update');

        Route::delete('delete', [CouponController::class, 'destroy'])
            ->name('delete');
    });

    Route::get('/', [CouponController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Coupons Management'), route('admin.coupon.index'));
        });
});
/**
 * End Coupon Routes
 */
