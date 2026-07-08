<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
        // 1. Force HTTPS for ngrok tunneling when in local environment
        if (config('app.env') === 'local') {
            URL::forceScheme('https');
        }

        // 2. The "God Mode" Bypass
        // This intercepts every single permission check in the system.
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true; // Instantly grants authorization for everything
            }
        });
    }
}
