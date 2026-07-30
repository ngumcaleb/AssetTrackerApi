<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Support shared hosting environments where public folder contents are placed directly in public_html / root
        if (! is_dir($this->app->basePath('public')) || (! file_exists($this->app->basePath('public/build/manifest.json')) && file_exists($this->app->basePath('build/manifest.json')))) {
            $this->app->usePublicPath($this->app->basePath());
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
