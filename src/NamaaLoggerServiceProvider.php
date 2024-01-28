<?php

namespace Namaa\NamaaLogger;

use Illuminate\Support\ServiceProvider;

class NamaaLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * Used to initialize some routes or add an event listener.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/namaa-logger.php',
            'namaa-logger'
        );
    }

    /**
     * Bootstrap services.
     *
     * Used to bind our package to the classes inside the app container
     * @return void
     */
    public function boot()
    {
        if (config('namaa-logger.enabled')) {
            NamaaLogger::start($this->app);
        }
    }
}
