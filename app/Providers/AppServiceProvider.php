<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // We need to import the Gate facade

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
        // The "God Mode" Bypass
        // This intercepts every single permission check in the system.
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true; // Instantly grants authorization for everything
            }
        });
    }
}
