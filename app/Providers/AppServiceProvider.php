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
        // Detect shared hosting setups where Laravel's public/ folder contents
        // (index.php, .htaccess, etc.) are served directly from public_html root,
        // meaning there is no public/ subdirectory below the project root.
        $publicDir = $this->app->basePath('public');
        if (! is_dir($publicDir) || ! file_exists($publicDir . '/index.php')) {
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
