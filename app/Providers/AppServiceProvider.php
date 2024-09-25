<?php

namespace App\Providers;

use App\Interfaces\AdRequestServiceInterface;
use App\Models\Subscription;
use App\Observers\SubscriptionObserver;
use App\Services\OlxService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(
            AdRequestServiceInterface::class,
            OlxService::class,
        );

        Subscription::observe(SubscriptionObserver::class);
    }
}
