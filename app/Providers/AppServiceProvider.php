<?php

namespace App\Providers;

use App\Models\TransactionDetail;
use App\Observers\TransactionDetailObserver;
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
        TransactionDetail::observe(TransactionDetailObserver::class);
    }
}
