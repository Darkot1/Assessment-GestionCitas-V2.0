<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Availability;
use App\Observers\AvailabilityObserver;

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
        Availability::observe(AvailabilityObserver::class);
    }
}
