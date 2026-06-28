<?php

namespace App\Providers;

use App\Domains\Setting\Services\SettingService;
use App\Domains\Wallet\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Services\StorageManagerService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Storage manager singleton registration
        $this->app->singleton(StorageManagerService::class,function (){
            return new StorageManagerService();
        });

        $this->app->singleton(SettingService::class, function ($app) {
            return new SettingService(new \App\Domains\Setting\Models\Setting());
        });

        $this->app->singleton(WalletService::class, function ($app) {
            return new WalletService(new \App\Domains\Wallet\Models\Wallet(), new \App\Domains\Wallet\Models\WalletTransaction());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share the authenticated user globally in views
        View::composer('*', function ($view) {
            $view->with('logged_in_user', Auth::user());
        });
        Paginator::useBootstrap();
    }
}
