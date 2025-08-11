<?php
namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        Gate::define('create-transfer', function ($user) {
            return $user->isAuthenticated();
        });

        Gate::define('update-stock', function ($user) {
            return $user->isAuthenticated();
        });
    }
}
