<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        // Register authentication components
        Blade::component('components.guest-layout', 'guest-layout');
        Blade::component('components.auth-card', 'auth-card');
        Blade::component('components.auth-session-status', 'auth-session-status');
        Blade::component('components.auth-validation-errors', 'auth-validation-errors');
        Blade::component('components.application-logo', 'application-logo');
        Blade::component('components.input', 'input');
        Blade::component('components.label', 'label');
        Blade::component('components.button', 'button');
    }
}
