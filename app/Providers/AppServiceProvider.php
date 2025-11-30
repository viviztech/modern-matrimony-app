<?php

namespace App\Providers;

use App\Models\Photo;
use App\Observers\PhotoObserver;
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
        // Register observers
        Photo::observe(PhotoObserver::class);
    }
}
