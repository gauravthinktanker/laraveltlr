<?php

namespace Laraveltlr\Tlr;

use Illuminate\Support\ServiceProvider;

class TlrServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        include __DIR__.'/routes/web.php';
        $this->app->make('Laraveltlr\Tlr\TlrController');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/views', 'tlr');
    }
}
