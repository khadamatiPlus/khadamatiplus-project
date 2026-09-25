<?php

use App\Domains\SmsCampaign\Http\Controllers\Backend\SmsCampaignController;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

Route::group([
    'prefix' => 'sms-campaign',
    'as' => 'sms-campaign.'
], function () {
    Route::get('/', [SmsCampaignController::class, 'index'])->name('index')->middleware('permission:admin.sms-campaign.list')->breadcrumbs(function (Trail $trail) {
        $trail->parent('admin.dashboard')->push(__('SMS Campaigns'), route('admin.sms-campaign.index'));
    });

    Route::get('create', [SmsCampaignController::class, 'create'])->name('create')->middleware('permission:admin.sms-campaign.store');
    Route::post('store', [SmsCampaignController::class, 'store'])->name('store')->middleware('permission:admin.sms-campaign.store');
    Route::post('{campaign}/send', [SmsCampaignController::class, 'send'])->name('send')->middleware('permission:admin.sms-campaign.store');
});
