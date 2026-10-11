<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // Production is served over HTTPS only; this stops assets and links being generated as http://
        // (which browsers block as mixed content, leaving the page unstyled).
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Laravel's default (min 8 characters) everywhere except production, where passwords must
        // also be longer, mixed-case, contain numbers and not appear in known data breaches.
        Password::defaults(fn () => $this->app->isProduction()
            ? Password::min(10)->mixedCase()->numbers()->uncompromised()
            : null);
    }
}
