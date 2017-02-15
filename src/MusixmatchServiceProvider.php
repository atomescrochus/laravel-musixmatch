<?php

namespace Atomescrochus\Musixmatch;

use Illuminate\Support\ServiceProvider;

class MusixmatchServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/laravel-musixmatch.php' => config_path('laravel-musixmatch.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/config/laravel-musixmatch.php', 'laravel-musixmatch');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        config([
                'config/laravel-musixmatch.php',
        ]);
    }
}
