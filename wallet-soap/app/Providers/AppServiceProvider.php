<?php

namespace App\Providers;

use App\Services\ApiResponse;
use App\Services\CustomerService;
use App\Services\WalletService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiResponse::class);
        $this->app->singleton(CustomerService::class);
        $this->app->singleton(WalletService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
